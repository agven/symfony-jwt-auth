<?php

namespace Agven\JWTAuthBundle\Services\Factory;

use Agven\JWTAuthBundle\Core\ValueObject\Token\Access as AccessTokenValueObject;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Refresh as RefreshTokenValueObject;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Structure\Header;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Structure\Payload;

class Token
{
    public function createAccess(Header $header, Payload $payload, string $token): AccessTokenValueObject
    {
        return (new AccessTokenValueObject())
            ->setHeader($header)
            ->setPayload($payload)
            ->setToken($token);
    }

    public function createRefresh(int $length): RefreshTokenValueObject
    {
        return new RefreshTokenValueObject($length);
    }
}