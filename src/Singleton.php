<?php
/**
 * Created by PhpStorm.
 * User: hiho
 * Date: 18-9-5
 * Time: 下午5:49
 */

namespace HihoZhou\Hertz;

/**
 * 单例模式trait
 * Trait Singleton
 * @package HihoZhou\Hertz
 */
trait Singleton
{
    private static $instance;

    private function __construct(...$args)
    {
    }

    public static function getInstance(...$args)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }
}