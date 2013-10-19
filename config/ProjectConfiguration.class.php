<?php

// Composer autoload
require_once dirname(__DIR__).'/plugins/autoload.php';

// symfony1 autoload
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
        'sfDoctrinePlugin',
        'sfDependencyInjectionPlugin',
    ));
  }
}
