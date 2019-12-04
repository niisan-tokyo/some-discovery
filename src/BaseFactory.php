<?php
namespace Niisan\SomeDiscovery;

class BaseFactory
{
    protected $dir;
    protected $processClassPrefix;
    protected $methods;

    protected $classLinks = [];

    /**
     * Discovering process classes for given class
     *
     * @param string $class class name
     * @return array
     */
    public function discover(string $class = null): array
    {
        if (! $this->classLinks) {
            $this->makeLinks();
        }

        return ($class) ? $this->classLinks[$class] : $this->classLinks;
    }

    /**
     * Run corresponding methods of trigger object
     *
     * @param Object $obj trigger object
     * @return void
     */
    public function run(Object $obj)
    {
        $class = get_class($obj);
        $processers = $this->discover($class);
        $ret = [];
        foreach ($processers as $processer) {
            $arr = explode('@', $processer);
            $processObject = new $arr[0];
            $ret[] = $processObject->{$arr[1]}($obj);
        }
        return $ret;
    }

    /**
     * Make map showing trigger class and corresponding processing classes
     *
     * @return void
     */
    protected function makeLinks()
    {
        $files = $this->getDirFiles($this->dir);
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