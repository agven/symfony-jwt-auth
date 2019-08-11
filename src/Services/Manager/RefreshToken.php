<?php

namespace Agven\JWTAuthBundle\Services\Manager;

use Agven\JWTAuthBundle\Core\Services\Manager\RefreshToken as RefreshTokenManagerInterface;
use Agven\JWTAuthBundle\Core\Services\Manager\Token as TokenManagerInterface;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Payload as TokenPayload;
use Agven\JWTAuthBundle\Services\Factory\Payload as PayloadFactory;
use Agven\JWTAuthBundle\Services\KeyReader;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

class RefreshToken implements RefreshTokenManagerInterface
{
    private $tokenLength;
    private $tokenManager;

    public function __construct(int $tokenLength, TokenManagerInterface $tokenManager)
    {
        $this->tokenLength = $tokenLength;
        $this->tokenManager = $tokenManager;
    }

    public function createAccess(UserInterface $user, array $payload = []): string
    {
        return $this->tokenManager->create($user, $payload);
    }

    public function createRefresh(): string
    {
        $bytes = openssl_random_pseudo_bytes($this->tokenLength);

        return bin2hex($bytes);
    }

    public function decodeAccess(string $rawToken): \stdClass
    {
        return $this->tokenManager->decode($rawToken);
    }

    public function getTokenPayload(): TokenPayload
    {
        return  $this->tokenManager->getTokenPayload();
    }
}
