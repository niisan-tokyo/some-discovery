<?php
namespace Test\Sample\Process;

use Test\Sample\Config\Test1Config;
use Test\Sample\Config\Test2Config;

class Test2Process
{

    public function handle(Test2Config $config)
    {
        //some process
    }

    public function handleMore(Test1Config $config)
    {
        //some process
    }
}