<?php
/**
 * @var \HihoZhou\Hertz\Routing\Router $router
 */

$router->get('/', function () use ($router) {
    echo "GET1111111111";
});
$router->ws('/', function () use ($router) {
    echo "WS111";
});
$router->ws('/index/haha', function () use ($router) {
    echo "WS/index/haha";
});
