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
        $context = sfContext::createInstance($this->configuration);
        $container = $context->getContainer();
        $tester = $container->get('issei-tester');

        $tester->sayHello();
    }
}
