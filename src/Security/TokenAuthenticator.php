<?php

namespace Agven\JWTAuthBundle\Security;

use Agven\JWTAuthBundle\Core\Services\Manager\TokenInterface as TokenManagerInterface;
use Agven\JWTAuthBundle\Services\RequestTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $tokenManager;
    private $tokenExtractor;
    private $userIdentity;

    public function __construct(
        TokenManagerInterface $tokenManager,
        RequestTokenExtractor $tokenExtractor,
        string $userIdentity
    ) {
        $this->tokenManager = $tokenManager;
        $this->tokenExtractor = $tokenExtractor;
        $this->userIdentity = $userIdentity;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials(Request $request)
    {
        $token = $this->tokenExtractor->extract($request);
        if (!$token) {
            throw new \UnexpectedValueException('Authentication header does not exists or invalid.');
        }

        try {
            $payload = $this->tokenManager->decodeToken($token);
            return new RawToken($token, $payload);
        } catch (\Exception $e) {
            throw new AuthenticationException(
                sprintf("Invalid jwt token. %s", $e->getMessage())
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getUser($authToken, UserProviderInterface $userProvider): ?UserInterface
    {
        if (!($authToken instanceof RawToken)) {
            throw new AuthenticationException(sprintf(
                'The authenticated token must be an instance of RawToken (%s was given).',
                get_class($authToken)
            ));
        }

        try {
            $username = $authToken->validatePaylod($this->userIdentity);
            return $userProvider->loadUserByUsername($username);
        } catch (\Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * @inheritdoc
     *
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // return true to cause authentication success
        return true;
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => sprintf('%s %s', $exception->getMessageKey(), $exception->getMessage())
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @inheritdoc
     */
    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Authentication Required'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritdoc
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function supports(Request $request): bool
    {
        return $this->tokenExtractor->hasAuthHeader($request);
    }
}