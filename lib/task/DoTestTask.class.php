<?php

use Issei\Tester;

class DoTestTask extends sfBaseTask
{
    public function configure()
    {
        $this->namespace = 'do';
        $this->name      = 'test';

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        ));
    }

    public function execute($arguments = array(), $options = array())
    {
        $containerClass = require $this->configuration->getConfigCache()->checkConfig('config/services.yml', true);
        $container = new $containerClass();

        $tester = $container->get('issei_tester');

        $tester->sayHello();
    }
}
