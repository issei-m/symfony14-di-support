<?php

namespace Issei;

class Tester
{
    private $name = 'Issei';

    public function sayHello()
    {
        echo sprintf("hello %s!\n", $this->name);
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
