<?php

// Composer autoload
require_once dirname(__DIR__).'/lib/vendor/autoload.php';

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

    $this->dispatcher->connect('service_container.build', function (sfEvent $event) {
      /** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */
      $container = $event->getSubject();
      $container->addObjectResource($this);
      $container->setParameter('extended', true);
    });
  }
}
