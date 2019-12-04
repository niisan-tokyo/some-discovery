<?php
namespace Niisan\SomeDiscovery;

class BaseFactory
{
    protected $dir;
    protected $processClassPrefix;
    protected $methods;

    protected $classLinks = [];

    public function discover(string $class = null): array
    {
        if (! $this->classLinks) {
            $this->makeLinks();
        }

        return $this->classLinks;
    }

    protected function makeLinks()
    {
        $files = $this->getDirFiles($this->dir);
        //print_r($files);
        foreach ($files as $file) {
            include_once($file);
        }
        
        $classes = array_filter(get_declared_classes(), function ($class) {
            return strpos($class, $this->processClassPrefix) === 0;
        });

        foreach ($classes as $class) {
            $this->triggerProcessMap($class);
        }
    }

    protected function triggerProcessMap($class)
    {
        $ref = new \ReflectionClass($class);
        $methodRefs = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methodRefs as $method) {
            $name = $method->getName();
            if (preg_match('/' . $this->methodFilter . '/', $name) === 1) {
                $trigger = $this->getTrigger($method);
                $this->classLinks[$trigger] ??= [];
                $this->classLinks[$trigger][] = "$class@$name";
            }
        }
        
    }

    private function getDirFiles($dir)
    {
        $ret = [];
        $list = glob($dir . '/*');
        foreach ($list as $item) {
            if (is_dir($item)) {
                $ret = array_merge($ret, $this->getDirFiles($item));
                continue;
            }

            if (substr($item, -4) === '.php') {
                $ret[] = $item;
            }
        }
        return $ret;
    }

    private function getTrigger(\ReflectionMethod $method): string
    {
        $param = $method->getParameters()[0];
        $type = $param->getType();
        if ($type and !$type->isBuiltin()) {
            return $type->getName();
        }
    }
}