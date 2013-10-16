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

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('peerj_user_security');

		// Class file namespaces.
		$this->addManagerSection($rootNode);
		
		// Configuration stuff.
        $this->addLoginShieldSection($rootNode);
        $this->addResetShieldSection($rootNode);

        return $treeBuilder;
    }

    /**
     *
     * @access private
     * @param ArrayNodeDefinition $node
     */
    private function addManagerSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('manager')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('session')
		                    ->addDefaultsIfNotSet()
		                    ->canBeUnset()
                            ->children()
								->scalarNode('class')->defaultValue('Peerj\SecurityBundle\Manager\RedisSessionManager')->end()							
							->end()
						->end()
					->end()
				->end()
			->end();
	}
	
    /**
     *
     * @access private
     * @param ArrayNodeDefinition $node
     */
    private function addLoginShieldSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('login_shield')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('enabled')->defaultValue(true)->end()
                        ->scalarNode('block_for_minutes')->defaultValue(10)->end()
                        ->scalarNode('limit_failed_login_attempts')->defaultValue(25)->end()
                        ->arrayNode('primary_login_route')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('name')->defaultValue('fos_user_security_login')->end()
                                ->arrayNode('params')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('redirect_when_denied_route')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('name')->end()
                                ->arrayNode('params')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('block_routes_when_denied')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     *
     * @access private
     * @param ArrayNodeDefinition $node
     */
    private function addResetShieldSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('reset_shield')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('enabled')->defaultValue(true)->end()
                        ->scalarNode('block_for_minutes')->defaultValue(10)->end()
                        ->scalarNode('limit_reset_attempts')->defaultValue(25)->end()
                        ->arrayNode('primary_reset_route')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('name')->defaultValue('fos_user_resetting_request')->end()
                                ->arrayNode('params')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('redirect_when_denied_route')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('name')->end()
                                ->arrayNode('params')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('block_routes_when_denied')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}