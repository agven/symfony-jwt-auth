<?php

namespace Agven\JWTAuthBundle\Services\Factory;

use Agven\JWTAuthBundle\Core\ValueObject\JWT\Header as TokenHeader;
use Agven\JWTAuthBundle\Core\ValueObject\JWT\Payload as TokenPayload;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserInterface;

class JWT
{
    private $algorithm;
    private $tokenLifeTime;
    private $userIdentity;

    public function __construct(string $algorithm, int $tokenLifeTime, string $userIdentity)
    {
        $this->algorithm = $algorithm;
        $this->tokenLifeTime = $tokenLifeTime;
        $this->userIdentity = $userIdentity;
    }

    public function createHeader(): TokenHeader
    {
        return new TokenHeader($this->algorithm);
    }

    /**
     * Creates payload which contains
     * the claims about an entity the user
     *
     * @param UserInterface $user
     *
     * @return TokenPayload
     */
    public function createPayload(UserInterface $user): TokenPayload
    {
        $time = time();
        $exp = $time + $this->tokenLifeTime;
        $accessor = PropertyAccess::createPropertyAccessor();

        return (new TokenPayload())
            ->setUserIdentityValue($accessor->getValue($user, $this->userIdentity))
            ->setUserIdentityField($this->userIdentity)
            ->setNbf($time)
            ->setExp($exp);
    }
}
