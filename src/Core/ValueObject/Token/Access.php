<?php

namespace Agven\JWTAuthBundle\Core\ValueObject\Token;

use Agven\JWTAuthBundle\Core\ValueObject\Token\Structure\Header;
use Agven\JWTAuthBundle\Core\ValueObject\Token\Structure\Payload;

class Access
{
    /**
     * @var Header
     */
    private $header;

    /**
     * @var Payload
     */
    private $payload;

    /**
     * @var string
     */
    private $token;


    public function getHeader(): Header
    {
        return $this->header;
    }

    public function setHeader(Header $header): Access
    {
        $this->header = $header;

        return $this;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function setPayload(Payload $payload): Access
    {
        $this->payload = $payload;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): Access
    {
        $this->token = $token;

        return $this;
    }
}