<?php

namespace Agven\JWTAuthBundle\Core\Services\Manager;

use Agven\JWTAuthBundle\Core\ValueObject\Token\Payload as TokenPayload;
use Symfony\Component\Security\Core\User\UserInterface;

interface RefreshToken
{
    public function createAccess(UserInterface $user, array $payload = []): string;
    public function createRefresh(): string;
    public function decodeAccess(string $rawToken): \stdClass;
    public function getTokenPayload(): TokenPayload;
}