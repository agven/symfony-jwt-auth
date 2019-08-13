<?php

namespace Agven\JWTAuthBundle\Core\ValueObject;

use Agven\JWTAuthBundle\Core\ValueObject\JWT\Header;
use Agven\JWTAuthBundle\Core\ValueObject\JWT\Payload;

class AccessToken
{
    private $payload;
    private $header;
    private $token;

    public function __construct(Header $header, Payload $payload, string $token)
    {
        $this->header = $header;
        $this->payload = $payload;
        $this->token = $token;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function geToken(): string
    {
        return $this->token;
    }
}