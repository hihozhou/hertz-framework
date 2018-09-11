<?php

namespace HihoZhou\Hertz;

use HihoZhou\Hertz\Concerns\RoutesRequests;
use HihoZhou\Hertz\Routing\Router;
use \Illuminate\Container\Container;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Application extends Container
{
    use RoutesRequests;

    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;


    /**
     * The Router instance.
     * 用于存放路由
     *
     * @var \HihoZhou\Hertz\Routing\Router
     */
    public $router;

    /**
     * All of the loaded configuration files.
     * 所有已经加载的文件
     * ['app'=>true,'database'=>true]
     *
     * @var array
     */
    protected $loadedConfigurations = [];

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;
        $this->registerConfigBindings();
        $this->bootstrapContainer();
        $this->configure('app');//加载配置
        $this->bootstrapRouter();

    }


    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer()
    {
        static::setInstance($this);

        $this->instance('app', $this);

    }


    /**
     * 重写容器make方法
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        //todo
//        $abstract = $this->getAlias($abstract);
//
//        if (array_key_exists($abstract, $this->availableBindings) &&
//            ! array_key_exists($this->availableBindings[$abstract], $this->ranServiceBinders)) {
//            $this->{$method = $this->availableBindings[$abstract]}();
//
//            $this->ranServiceBinders[$method] = true;
//        }

        return parent::make($abstract, $parameters);
    }


    /**
     * Register container bindings for the application.
     *
     * @return void
     */
    protected function registerConfigBindings()
    {
        $this->singleton('config', function () {
            return new ConfigRepository;
        });
    }


    /**
     * Load a configuration file into the application.
     *
     * @param  string $name
     *
     * @return void
     */
    public function configure($name)
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;
        $path = $this->getConfigurationPath($name);
        if ($path) {
            $this->make('config')->set($name, require $path);
        }
    }

    /**
     * Get the path to the given configuration file.
     *
     * If no name is provided, then we'll return the path to the config folder.
     *
     * @param  string|null $name
     *
     * @return string
     */
    public function getConfigurationPath($name = null)
    {
        if (!$name) {
            $appConfigDir = $this->basePath('config') . '/';

            if (file_exists($appConfigDir)) {
                return $appConfigDir;
            } elseif (file_exists($path = __DIR__ . '/../config/')) {
                return $path;
            }
        } else {
            $appConfigPath = $this->basePath('config') . '/' . $name . '.php';
            if (file_exists($appConfigPath)) {
                return $appConfigPath;
            } elseif (file_exists($path = __DIR__ . '/../config/' . $name . '.php')) {
                return $path;
            }
        }
    }

    /**
     * Determine if the application is running in the console.
     * 判断应用程序是否在控制台中运行。
     *
     * @return bool
     */
    public function runningInConsole()
    {
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     * Get the base path for the application.
     * 获取应用或对应文件跟目录
     *
     * @param  string|null $path
     *
     * @return string
     */
    public function basePath($path = null)
    {
        if (isset($this->basePath)) {
            return $this->basePath . ($path ? '/' . $path : $path);
        }

        if ($this->runningInConsole()) {
            $this->basePath = getcwd();
        } else {
            $this->basePath = realpath(getcwd() . '/../');
        }

        return $this->basePath($path);
    }


    /**
     * Create a FastRoute dispatcher instance for the application.
     *
     * @return
     */
    protected function createDispatcher(Router $router)
    {
//        return $this->dispatcher ? $this->dispatcher : \FastRoute\simpleDispatcher(function ($r) {
//            foreach ($this->router->getRoutes() as $route) {
//                $r->addRoute($route['method'], $route['uri'], $route['action']);
//            }
//        });
        return $this->dispatcher ? $this->dispatcher : \FastRoute\simpleDispatcher(function ($r) use ($router) {
            foreach ($router->getRoutes() as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }


    /**
     * Bootstrap the router instance.
     *
     * @return void
     */
    public function bootstrapRouter()
    {
        $this->router = new Router($this);
    }


    /**
     * Prepare the given request instance for use with the application.
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Illuminate\Http\Request
     */
    protected function prepareRequest(SymfonyRequest $request)
    {
        if (!$request instanceof Request) {
            $request = Request::createFromBase($request);
        }

        $request->setUserResolver(function ($guard = null) {
            return $this->make('auth')->guard($guard)->user();
        })->setRouteResolver(function () {
            return $this->currentRoute;
        });

    }
}