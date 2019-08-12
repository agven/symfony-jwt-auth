<?php

namespace Agven\JWTAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AgvenJWTAuthExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');
        $loader->load('interfaces.yaml');

        $container->setParameter('agven_jwt_auth.private_key', $config['private_key']);
        $container->setParameter('agven_jwt_auth.public_key', $config['public_key']);
        $container->setParameter('agven_jwt_auth.pass_phrase', $config['pass_phrase']);
        $container->setParameter('agven_jwt_auth.leeway', $config['leeway']);
        $container->setParameter('agven_jwt_auth.token_ttl', $config['token_ttl']);
        $container->setParameter('agven_jwt_auth.identity', $config['identity']);

        $encoder = $config['encoder'];
        $container->setParameter('agven_jwt_auth.encoder.signature_algorithm',  $encoder['signature_algorithm']);
        $container->setParameter('agven_jwt_auth.encoder.refresh_token_length', $encoder['refresh_token_length']);

        $header = $config['authorization_header'];
        $container->setParameter('agven_jwt_auth.authorization_header.prefix', $header['prefix']);
        $container->setParameter('agven_jwt_auth.authorization_header.name', $header['name']);
    }
}