<?php

namespace Agven\JWTAuthBundle\Services\Manager;

use Agven\JWTAuthBundle\Core\Services\Manager\Token as TokenManagerInterface;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Payload as TokenPayload;
use Agven\JWTAuthBundle\Services\Factory\Token as tokenFactory;
use Agven\JWTAuthBundle\Services\KeyReader;
use Agven\JWTAuthBundle\Services\TokenSetting;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

class Token implements TokenManagerInterface
{
    private $header;
    private $keyReader;
    private $tokenFactory;
    private $tokenPayload;

    public function __construct(KeyReader $keyReader, TokenFactory $tokenFactory)
    {
        $this->header = $tokenFactory->createHeader();
        $this->keyReader = $keyReader;
        $this->tokenFactory = $tokenFactory;
    }

    public function create(UserInterface $user, array $payload = []): string
    {
        $tokenPayload = $this->createTokenPayload($user);
        $key = $this->keyReader->getPrivateKey();
        $payload = array_merge(
            $tokenPayload->asArray(),
            $payload
        );

        return JWT::encode($payload, $key, $this->header->getAlgorithm());
    }

    public function decode(string $rawToken): \stdClass
    {
        $key = $this->keyReader->getPublicKey();

        return JWT::decode($rawToken, $key, [$this->header->getAlgorithm()]);
    }

    public function getTokenPayload(): TokenPayload
    {
        if (!$this->tokenPayload) {
            throw new \RuntimeException('Token payload does not exists.');
        }

        return $this->tokenPayload;
    }

    private function createTokenPayload(UserInterface $user): TokenPayload
    {
        if (!$this->tokenPayload) {
            $this->tokenPayload = $this->tokenFactory->createPayload($user);
        }

        return $this->tokenPayload;
    }
}
