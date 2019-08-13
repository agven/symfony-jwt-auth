<?php

namespace Agven\JWTAuthBundle\Services\Manager;

use Agven\JWTAuthBundle\Core\Services\Manager\TokenInterface as TokenManagerInterface;
use Agven\JWTAuthBundle\Core\ValueObject\AccessToken;
use Agven\JWTAuthBundle\Core\ValueObject\RefreshToken;
use Agven\JWTAuthBundle\Services\Factory\JWT as JWTFactory;
use Agven\JWTAuthBundle\Services\KeyReader;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

class Token implements TokenManagerInterface
{
    private $header;
    private $keyReader;
    private $tokenFactory;
    private $tokenLength;

    public function __construct(KeyReader $keyReader, JWTFactory $tokenFactory, int $tokenLength)
    {
        $this->header = $tokenFactory->createHeader();
        $this->keyReader = $keyReader;
        $this->tokenFactory = $tokenFactory;
        $this->tokenLength = $tokenLength;
    }

    /**
     * @inheritdoc
     */
    public function createAccessToken(UserInterface $user, array $payload = []): AccessToken
    {
        $tokenPayload = $this->tokenFactory->createPayload($user);
        $key = $this->keyReader->getPrivateKey();
        $payload = array_merge(
            $tokenPayload->asArray(),
            $payload
        );

        $token = JWT::encode($payload, $key, $this->header->getAlgorithm());

        return new AccessToken(
            $this->header,
            $tokenPayload,
            $token
        );
    }

    public function createRefreshToken(): RefreshToken
    {
        $token = bin2hex(openssl_random_pseudo_bytes($this->tokenLength));

        return new RefreshToken($token);
    }

    public function decodeToken(string $rawToken): \stdClass
    {
        $key = $this->keyReader->getPublicKey();

        return JWT::decode($rawToken, $key, [$this->header->getAlgorithm()]);
    }
}