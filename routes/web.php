<?php
/**
 * @var \HihoZhou\Hertz\Routing\Router $router
 */

/**
 * @var swoole_websocket_server $server
 */

$router->get('/', function () use ($router) {
    echo "GET1111111111";
});
$router->ws('/', function () use ($router, $server) {
    \Illuminate\Http\Request::capture();
    echo "WS111";
});
$router->ws('/index/haha', function () use ($router) {
    echo "WS/index/haha";
});
