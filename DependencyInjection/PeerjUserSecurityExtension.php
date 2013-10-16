<?php

/*
 * This file is part of the Peerj UserSecurityBundle
 *
 * (c) Peerj <https://peerj.com/>
 *
 * Available on github <http://www.github.com/Peerj/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peerj\UserSecurityBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class PeerjUserSecurityExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'peerj_user_security';
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

		// Class file namespaces.
        $this->getManagerSection($container, $config);
		
		// Configuration stuff.
        $this->getLoginShieldSection($container, $config);
        $this->getResetShieldSection($container, $config);

		// Load Service definitions.
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

	
    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getManagerSection($container, $config)
    {
        $container->setParameter('peerj_user_security.manager.session.class', $config['manager']['session']['class']);		
	}
	
    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getLoginShieldSection($container, $config)
    {
        $container->setParameter('peerj_user_security.login_shield.enabled', $config['login_shield']['enabled']);
        $container->setParameter('peerj_user_security.login_shield.block_for_minutes', $config['login_shield']['block_for_minutes']);
        $container->setParameter('peerj_user_security.login_shield.limit_failed_login_attempts', $config['login_shield']['limit_failed_login_attempts']);
        
        $container->setParameter('peerj_user_security.login_shield.primary_login_route.name', $config['login_shield']['primary_login_route']['name']);
        $container->setParameter('peerj_user_security.login_shield.primary_login_route.params', $config['login_shield']['primary_login_route']['params']);

		$redirect_when_denied_route_name = null;
		if (array_key_exists('name', $config['login_shield']['redirect_when_denied_route'])) {
			$redirect_when_denied_route_name = $config['login_shield']['redirect_when_denied_route']['name'];
		}
		$container->setParameter('peerj_user_security.login_shield.redirect_when_denied_route.name', $redirect_when_denied_route_name);
        $container->setParameter('peerj_user_security.login_shield.redirect_when_denied_route.params', $config['login_shield']['redirect_when_denied_route']['params']);

        $blockRoutesWhenDeniedDefaults = array(
            'fos_user_security_login',
            'fos_user_security_check',
            'fos_user_security_logout',
        );

        $container->setParameter('peerj_user_security.login_shield.block_routes_when_denied', array_merge($config['login_shield']['block_routes_when_denied'], $blockRoutesWhenDeniedDefaults));
    }
	
    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getResetShieldSection($container, $config)
    {
        $container->setParameter('peerj_user_security.reset_shield.enabled', $config['reset_shield']['enabled']);
        $container->setParameter('peerj_user_security.reset_shield.block_for_minutes', $config['reset_shield']['block_for_minutes']);
        $container->setParameter('peerj_user_security.reset_shield.limit_reset_attempts', $config['reset_shield']['limit_reset_attempts']);
        
        $container->setParameter('peerj_user_security.reset_shield.primary_reset_route.name', $config['reset_shield']['primary_reset_route']['name']);
        $container->setParameter('peerj_user_security.reset_shield.primary_reset_route.params', $config['reset_shield']['primary_reset_route']['params']);

		$redirect_when_denied_route_name = null;
		if (array_key_exists('name', $config['reset_shield']['redirect_when_denied_route'])) {
			$redirect_when_denied_route_name = $config['reset_shield']['redirect_when_denied_route']['name'];
		}
        $container->setParameter('peerj_user_security.reset_shield.redirect_when_denied_route.name', $redirect_when_denied_route_name);
        $container->setParameter('peerj_user_security.reset_shield.redirect_when_denied_route.params', $config['reset_shield']['redirect_when_denied_route']['params']);

        $blockRoutesWhenDeniedDefaults = array(
            'fos_user_resetting_request',
        );

        $container->setParameter('peerj_user_security.reset_shield.block_routes_when_denied', array_merge($config['reset_shield']['block_routes_when_denied'], $blockRoutesWhenDeniedDefaults));
    }
}