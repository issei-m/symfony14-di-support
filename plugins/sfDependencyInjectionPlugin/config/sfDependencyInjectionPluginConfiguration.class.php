<?php

/*
 * This file is part of the sfDependencyInjectionPlugin package.
 * (c) Issei Murasawa <issei.m7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once sfConfig::get('sf_root_dir') . '/psr/vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * The configuration of sfDependencyInjectionPlugin.
 *
 * @package    sfPsrSupportPlugin
 * @subpackage config
 * @author     Issei Murasawa <issei.m7@gmail.com>
 */
class sfDependencyInjectionPluginConfiguration extends sfPluginConfiguration
{
    /**
     * @see sfPluginConfiguration
     */
    public function initialize()
    {
        $this->dispatcher->connect('context.load_factories', function(sfEvent $event) {
//            $timer = sfTimerManager::getTimer('build container');

            $context = $event->getSubject();
            $context->set('container', $this->initializeContainer());

//            $timer->addTime();
        });
    }

    protected function initializeContainer()
    {
        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container, new FileLocator(sfConfig::get('sf_config_dir')));
        $loader->load('services.yml');

        return $container;
    }
}
