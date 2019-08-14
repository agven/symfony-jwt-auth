<?php

namespace Agven\JWTAuthBundle\Core\ValueObject\Token;

class Refresh
{
    private $token;

    public function __construct(int $lenght)
    {
        $this->token = bin2hex(openssl_random_pseudo_bytes($lenght));;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}