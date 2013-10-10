<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

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
            require_once sfConfig::get('sf_root_dir') . '/psr/vendor/autoload.php';

            $container = new ContainerBuilder();
            $loader = new YamlFileLoader($container, new FileLocator(sfConfig::get('sf_config_dir')));
            $loader->load('services.yml');

            $context = $event->getSubject();
            $context->set('container', $container);
        });
    }
}
