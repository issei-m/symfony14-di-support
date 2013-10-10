<?php

use Issei\Tester;

class DoTestTask extends sfBaseTask
{
    public function configure()
    {
        $this->namespace = 'do';
        $this->name      = 'test';
    }

    public function execute($arguments = array(), $options = array())
    {
        $tester = new Tester();
        $tester->sayHello();
    }
}
