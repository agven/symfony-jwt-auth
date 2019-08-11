<?php

namespace Agven\JWTAuthBundle\Core\Services\Manager;

use Agven\JWTAuthBundle\Core\ValueObject\Token\Payload as TokenPayload;
use Symfony\Component\Security\Core\User\UserInterface;

interface Token
{
    public function create(UserInterface $user, array $payload = []): string;
    public function decode(string $rawToken): \stdClass;
    public function getTokenPayload(): TokenPayload;
}