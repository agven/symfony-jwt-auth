<?php

namespace Agven\JWTAuthBundle\Core\ValueObject\Token\Structure;

final class Payload
{
    private const TOKEN_KEY_JTI = 'jti';
    private const TOKEN_KEY_EXP = 'exp';
    private const TOKEN_KEY_IAT = 'iat';
    private const TOKEN_KEY_NBF = 'nbf';

    /**
     * Expiration Time
     * Identifies the expiration time on and after which the JWT must not be accepted for processing
     *
     * @var int timestamp
     */
    private $exp;

    /**
     * JWT ID
     * Case sensitive unique identifier of the token even among different issuers
     *
     * @var string
     */
    private $jti;

    /**
     * Issued at
     * Identifies the time at which the JWT was issued
     *
     * @var int timestamp
     */
    private $iat;

    /**
     * Not Before
     * Identifies the time on which the JWT will start to be accepted for processing
     *
     * @var int timestamp
     */
    private $nbf;

    /**
     * User identity field
     *
     * @var string
     */
    private $userIdentityField;

    /**
     * User identity value
     *
     * @var string
     */
    private $userIdentityValue;

    /**
     * @var array
     */
    private $userPayload = [];

    public function __construct()
    {
        $this->jti = bin2hex(openssl_random_pseudo_bytes(32));
        $this->iat = time();
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     *
     * @return Payload
     */
    public function setExp(int $exp): Payload
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * @return string
     */
    public function getJti(): string
    {
        return $this->jti;
    }

    /**
     * @return int
     */
    public function getIat(): int
    {
        return $this->iat;
    }

    /**
     * @param int $iat
     *
     * @return Payload
     */
    public function setIat(int $iat): Payload
    {
        $this->iat = $iat;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbf(): int
    {
        return $this->nbf;
    }

    /**
     * @param int $nbf
     *
     * @return Payload
     */
    public function setNbf(int $nbf): Payload
    {
        $this->nbf = $nbf;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserIdentityField(): string
    {
        return $this->userIdentityField;
    }

    /**
     * @param string $fieldName
     *
     * @return Payload
     */
    public function setUserIdentityField(string $fieldName): Payload
    {
        $this->userIdentityField = $fieldName;

        return $this;
    }

    /**
     * Returns non-null value
     *
     * @return string|int
     */
    public function getUserIdentityValue()
    {
        return $this->userIdentityValue;
    }

    /**
     * @param string|int (non-null) $name
     *
     * @return Payload
     */
    public function setUserIdentityValue($value): Payload
    {
        $this->userIdentityValue = $value;

        return $this;
    }

    public function addUserPayload(array $payload): Payload
    {
        $this->userPayload = array_merge(
            $this->userPayload,
            $payload
        );

        return $this;
    }

    public function asArray()
    {
        return array_merge($this->userPayload, [
            $this->getUserIdentityField() => $this->getUserIdentityValue(),
            self::TOKEN_KEY_JTI => $this->getJti(),
            self::TOKEN_KEY_EXP => $this->getExp(),
            self::TOKEN_KEY_IAT => $this->getIat(),
            self::TOKEN_KEY_NBF => $this->getNbf(),
        ]);
    }
}
