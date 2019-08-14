<?php

namespace Agven\JWTAuthBundle\Core\Services\Manager;

use Agven\JWTAuthBundle\Core\ValueObject\Token\Access as AccessToken;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Refresh as RefreshToken;
use Symfony\Component\Security\Core\User\UserInterface;

interface TokenInterface
{
    /**
     * Returns access token
     *
     * @param UserInterface $user
     * @param array $payload
     *
     * @return AccessToken
     */
    public function createAccessToken(UserInterface $user, array $payload = []): AccessToken;

    /**
     * Returns refresh token
     *
     * @return RefreshToken
     */
    public function createRefreshToken(): RefreshToken;

    /**
     * Decodes a JWT token into a PHP object.
     *
     * @param string $rawToken
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function decodeToken(string $rawToken): \stdClass;
}