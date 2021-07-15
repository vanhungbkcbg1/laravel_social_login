<?php


namespace App\Lib;


use Illuminate\Contracts\Container\Container;

class SimpleLib
{
    /** @var Container */
    protected $container;

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function getContainer(){
        return $this->container;
    }
}
