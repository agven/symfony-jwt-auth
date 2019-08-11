<?php

namespace Agven\JWTAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('agven_jwt_auth');
        $builder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('private_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('public_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('pass_phrase')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('leeway')
                    ->defaultValue(0)
                ->end()
                ->scalarNode('token_ttl')
                    ->info('The token life time in seconds.')
                    ->defaultValue(3600)
                ->end()
                ->scalarNode('identity')
                    ->defaultValue('username')
                    ->cannotBeEmpty()
                ->end()
                ->append($this->getEncoderNode())
                ->append($this->getAuthorizationHeaderNode())
            ->end();

        return $builder;
    }

    private function getEncoderNode(): NodeDefinition
    {
        $builder = new TreeBuilder('encoder');
        $node = $builder->getRootNode();
        $node->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('signature_algorithm')
                    ->defaultValue('HS256')
                ->end()
                ->scalarNode('refresh_token_length')
                    ->info('The length of the desired string of bytes. Must be a positive integer.')
                    ->defaultValue(64)
                ->end()
            ->end();

        return $node;
    }

    private function getAuthorizationHeaderNode(): NodeDefinition
    {
        $builder = new TreeBuilder('authorization_header');
        $node = $builder->getRootNode();
        $node->addDefaultsIfNotSet()
            ->canBeDisabled()
            ->children()
                ->scalarNode('prefix')
                    ->defaultValue('Bearer')
                ->end()
                ->scalarNode('name')
                    ->defaultValue('Authorization')
                ->end()
            ->end();

        return $node;
    }
}
