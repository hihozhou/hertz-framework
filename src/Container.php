<?php
/**
 * Created by PhpStorm.
 * User: hiho
 * Date: 18-9-5
 * Time: 下午5:45
 */
namespace HihoZhou\Hertz;

/**
 * 一个依赖注入的容器
 * Class Container
 */
class Container
{

    use Singleton;

    /**
     * 存储的容器
     * @var array
     */
    protected $values = [];


    public function set(string $key, $value)
    {
        $this->values[$key] = $value;
    }


    public function get($key)
    {
        if (array_keys($this->values)) {
            return $this->values[$key];
        }
        return null;
    }


    /**
     * Returns all defined value names.
     *
     * @return array An array of value names
     */
    public function keys()
    {
        return array_keys($this->values);
    }
}