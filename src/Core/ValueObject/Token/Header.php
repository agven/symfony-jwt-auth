<?php

namespace Agven\JWTAuthBundle\Core\ValueObject\Token;

final class Header
{
    /**
     * Identifies which algorithm is used to generate the signature
     * @var string Message authentication code algorithm
     */
    private $algorithm;

    public function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }
}
