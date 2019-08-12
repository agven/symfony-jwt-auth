<?php

namespace Agven\JWTAuthBundle\Core\Services\Manager;

use Agven\JWTAuthBundle\Core\ValueObject\Token\Payload as TokenPayload;
use Symfony\Component\Security\Core\User\UserInterface;

interface TokenInterface
{
    public function createAuthToken(UserInterface $user, array $payload = []): string;
    public function createRefreshToken(): string;
    public function decodeAuthToken(string $rawToken): \stdClass;
    public function getTokenPayload(): TokenPayload;
}