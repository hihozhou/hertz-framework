#!/usr/bin/env php

<?php

use HihoZhou\Hertz\Container;

//开发或开发包形式加载composer自动加载
foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

//Container::getInstance();


/**
 * 解析输入的命令
 * @return array
 */
function commandParser()
{
    global $argv;
    $command = '';
    $options = array();
    if (isset($argv[1])) {
        $command = $argv[1];
    }
    foreach ($argv as $item) {
        if (substr($item, 0, 2) === '--') {
            $temp = trim($item, "--");
            $temp = explode("-", $temp);
            $key = array_shift($temp);
            $options[$key] = array_shift($temp) ?: '';
        }
    }
    return array($command, $options);
}


/**
 * 帮助显示
 *
 * @param $options
 */
function showHelp($options)
{
    $opName = '';
    $args = array_keys($options);
    if ($args) $opName = $args[0];

    switch ($opName) {
        default:
            echo <<<DEFAULTHELP

\e[33mUsage:\e[0m
  Hertz [operate] [option]

\e[33mOperate:\e[0m
\e[32m  start \e[0m        Start Server
\e[32m  stop \e[0m         Stop Server
\e[32m  reload \e[0m       Reload Server
\e[32m  restart \e[0m      Restart Server

\e[32m  help \e[0m         Print Help (this message) and exit\n
DEFAULTHELP;
    }
}


/**
 * @author hihozhou
 * 命令执行
 */
function commandHandler()
{
    list($command, $options) = commandParser();
    switch ($command) {
        case 'start':
//            installCheck();
            serverStart($options);
            break;
        case 'stop':
        case 'reload':
        case 'install':
        case 'restart':
        case 'help':
        default:
            showHelp($options);
    }
}


/**
 *
 * @param $options
 */
function serverStart($options)
{
    $server = new swoole_websocket_server("127.0.0.1", 9501);
    $server->on('open', function (swoole_websocket_server $server, $request) {
        echo "server: handshake success with fd{$request->fd}\n";
    });
    $server->on('message', function (swoole_websocket_server $server, $frame) {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, "This message is from swoole websocket server.");
    });
    $server->on('close', function ($ser, $fd) {
        echo "client {$fd} closed\n";
    });
    $server->start();
}

commandHandler();