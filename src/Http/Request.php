<?php
/**
 * Created by PhpStorm.
 * User: hiho
 * Date: 18-10-20
 * Time: 下午2:03
 */

namespace HihoZhou\Hertz\Http;


class Request extends \Illuminate\Http\Request
{

    protected $swooleServer = null;
    protected $swooleFrame = null;

    /**
     * @return null
     */
    public function getSwooleServer()
    {
        return $this->swooleServer;
    }

    /**
     * @param null $swooleServer
     */
    public function setSwooleServer($swooleServer): void
    {
        $this->swooleServer = $swooleServer;
    }

    /**
     * @return null
     */
    public function getSwooleFrame()
    {
        return $this->swooleFrame;
    }

    /**
     * @param null $swooleFrame
     */
    public function setSwooleFrame($swooleFrame): void
    {
        $this->swooleFrame = $swooleFrame;
    }


}