<?php

namespace Agven\JWTAuthBundle\Services\Manager;

use Agven\JWTAuthBundle\Core\Services\Manager\TokenInterface as TokenManagerInterface;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Access as AccessToken;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Refresh as RefreshToken;
use Agven\JWTAuthBundle\Services\Factory\Token as TokenFactory;
use Agven\JWTAuthBundle\Services\Factory\TokenStructure as TokenStructureFactory;
use Agven\JWTAuthBundle\Services\KeyReader;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

class Token implements TokenManagerInterface
{
    private $header;
    private $keyReader;
    private $refreshTokenLength;
    private $tokenFactory;
    private $tokenStructureFactory;

    public function __construct(
        KeyReader $keyReader,
        TokenFactory $tokenFactory,
        TokenStructureFactory $tokenStructureFactory,
        int $refreshTokenLength
    ) {
        $this->keyReader = $keyReader;
        $this->refreshTokenLength = $refreshTokenLength;
        $this->tokenFactory = $tokenFactory;
        $this->tokenStructureFactory = $tokenStructureFactory;
    }

    /**
     * @inheritdoc
     */
    public function createAccessToken(UserInterface $user, array $payload = []): AccessToken
    {
        $key = $this->keyReader->getPrivateKey();
        $tokenPayload = $this->tokenStructureFactory->createPayload($user)
            ->addUserPayload($payload);

        $header = $this->tokenStructureFactory->createHeader();
        $token = JWT::encode($tokenPayload->asArray(), $key, $header->getAlgorithm());

        return $this->tokenFactory->createAccess($header, $tokenPayload, $token);
    }

    /**
     * @inheritdoc
     */
    public function createRefreshToken(): RefreshToken
    {
        return $this->tokenFactory->createRefresh($this->refreshTokenLength);
    }

    /**
     * @inheritdoc
     */
    public function decodeToken(string $rawToken): \stdClass
    {
        $header = $this->tokenStructureFactory->createHeader();
        $key = $this->keyReader->getPublicKey();

        return JWT::decode($rawToken, $key, [$header->getAlgorithm()]);
    }
}