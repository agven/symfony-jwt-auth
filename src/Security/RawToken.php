<?php

namespace Agven\JWTAuthBundle\Security;

class RawToken
{
    private $payload;
    private $rawToken;

    public function __construct(string $rawToken, $payload)
    {
        $this->rawToken = $rawToken;
        $this->payload = $payload;
    }

    public function validatePaylod(string $key): string
    {
        if (!isset($this->payload) || !isset($this->payload->$key)) {
            throw new \LogicException(
                sprintf('The key %s does not exists in payload.', $key)
            );
        }

        return $this->payload->$key;
    }
}
