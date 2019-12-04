<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Niisan\SomeDiscovery\BaseFactory;

class BaseFactoryTest extends TestCase
{

    /**
     * @test
     *
     * @return void
     */
    public function discoverProcessClass()
    {
        $object = new class extends BaseFactory 
        {
            protected $dir = __DIR__ . '/Sample/Process';
            protected $processClassPrefix = 'Test\\Sample\\Process\\';
            protected $methodFilter = '^handle.*';
        };

        $tests = $object->discover();

        $this->assertEquals([
            'Test\\Sample\\Config\\Test1Config' => [
                'Test\Sample\Process\Sub\Test3Process@handle',
                'Test\\Sample\\Process\\Test1Process@handle',
                'Test\\Sample\\Process\\Test2Process@handleMore',
            ],
            'Test\\Sample\\Config\\Test2Config' => [
                'Test\\Sample\\Process\\Test2Process@handle'
            ],
        ], $tests);
    }
}
