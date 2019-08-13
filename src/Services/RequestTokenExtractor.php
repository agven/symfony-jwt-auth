<?php

namespace Agven\JWTAuthBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class RequestTokenExtractor
{
    /**
     * Authorization header name
     * @var string
     */
    private $name;

    /**
     * Authorization header prefix
     * @var string
     */
    private $prefix;

    public function __construct(string $name, string $prefix)
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    public function extract(Request $request): string
    {
        $token = $request->headers->get($this->name, '');
        if ($this->prefix && $token) {
            $token = trim(substr($token, strlen($this->prefix)));
        }

        return $token;
    }

    public function hasAuthHeader(Request $request): bool
    {
        return $request->headers->has($this->name);
    }
}