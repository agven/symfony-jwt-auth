<?php

namespace Agven\JWTAuthBundle\Core\ValueObject;

class RefreshToken
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getHeader()
    {
        throw new \Exception('This method is not yet implemented.');
    }

    public function gePayload()
    {
        throw new \Exception('This method is not yet implemented.');
    }

    public function geToken(): string
    {
        return $this->token;
    }
}