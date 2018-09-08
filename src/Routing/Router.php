<?php
/**
 * Created by PhpStorm.
 * User: hiho
 * Date: 18-9-8
 * Time: 上午10:32
 */

namespace HihoZhou\Hertz\Routing;


class Router
{

    /**
     * The application instance.
     *
     * @var \HihoZhou\Hertz\Application
     */
    public $app;

    /**
     * All of the routes waiting to be registered.
     * 添加待解析路由
     *
     * @var array
     */
    protected $routes = [];


    /**
     * Router constructor.
     *
     * @param  \HihoZhou\Hertz\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * Add a route to the collection.
     * 添加路由
     *
     * @param  array|string $method
     * @param  string       $uri
     * @param  mixed        $action
     *
     * @return void
     */
    public function addRoute($method, $uri, $action)
    {

        $routeOption = [
//            'middleware' => [],
//            'prefix' => '',
            'method' => $method,
            'url' => $uri,
            'namespace' => "\\App\\Http\\Controllers\\",
        ];
        list($routeOption['controller'], $routeOption['action']) = explode('@', $action);
//        $route['method'], $route['uri'], $route['action']
        $this->routes[] = $routeOption;
    }


    /**
     * Get the raw routes for the application.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}