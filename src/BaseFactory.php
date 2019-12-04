<?php
namespace Niisan\SomeDiscovery;

class BaseFactory
{
    protected $dir;
    protected $processClassPrefix;
    protected $methods;

    protected static $classLinks;

    public function discover(string $class = null): array
    {
        if (! static::$classLinks) {
            $this->makeLinks();
        }

        return static::$classLinks;
    }

    protected function makeLinks()
    {
        static::$classLinks = [];
    }
}