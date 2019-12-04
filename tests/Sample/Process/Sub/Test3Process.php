<?php
namespace Test\Sample\Process\Sub;

use Test\Sample\Config\Test1Config;
use Test\Sample\Config\Test2Config;

class Test3Process
{

    public function handle(Test1Config $config)
    {
        //some process
    }

    public function run(Test2Config $config)
    {
        // another process
    }
}