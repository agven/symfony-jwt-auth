<?php

namespace Agven\JWTAuthBundle\Core\ValueObject\Token\Structure;

final class Header
{
    /**
     * Identifies which algorithm is used to generate the signature
     *
     * @var string Authentication code algorithm
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