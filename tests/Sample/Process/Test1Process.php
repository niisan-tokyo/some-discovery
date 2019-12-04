<?php
namespace Test\Sample\Process;

use Test\Sample\Config\Test1Config;

class Test1Process
{

    public function handle(Test1Config $config)
    {
        return get_class($config);
    }
}