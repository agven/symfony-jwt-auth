<?php

namespace Agven\JWTAuthBundle\Services;

class KeyReader
{
    /**
     * @var string The path to secret key
     */
    private $privateKeyPath;

    /**
     * @var string The path to secret key
     */
    private $publicKeyPath;

    /**
     * @var string Must be used if the specified key is encrypted (protected by a passphrase)
     */
    private $passPhrase;

    public function __construct(string $privateKeyPath, string $publicKeyPath, string $passPhrase)
    {
        $this->privateKeyPath = $privateKeyPath;
        $this->publicKeyPath = $publicKeyPath;
        $this->passPhrase = $passPhrase;
    }

    public function getPublicKey()
    {
        $key = openssl_pkey_get_public(self::getContents($this->publicKeyPath));
        if (!is_resource($key)) {
            throw new \RuntimeException('Can not return public key resource identifier.');
        }

        return $key;
    }

    public function getPrivateKey()
    {
        $key = openssl_pkey_get_private(self::getContents($this->privateKeyPath), $this->passPhrase);
        if (!is_resource($key)) {
            throw new \RuntimeException('Can not return private key resource identifier.');
        }

        return $key;
    }

    private static function getContents(string $filename): string
    {
        set_error_handler(function ($type, $msg) use (&$error) { $error = $msg; });
        $content = file_get_contents($filename);
        restore_error_handler();
        if (false === $content) {
            throw new \RuntimeException($error);
        }

        return $content;
    }
}