Symfony JSON Web Token Authentication
=========
Lightweight JWT Authentication Bundle. The bundle also provides Refresh and Access tokens.

## Installation

    composer require agven/symfony-jwt-auth
    
or add [`agven/symfony-jwt-auth`](https://github.com/agven/symfony-jwt-auth)
to your `composer.json` file. This bundle using the [`firebase/php-jwt`](https://github.com/firebase/php-jwt) 
library for decode and encode jwt token. 

Configure your `security.yml` :

``` yaml
security:
    # ...
    firewalls:
        auth:
            pattern: ^/api/auth
            anonymous: true
            stateless: true
        api:
            pattern: ^/api
            stateless: true
            guard:
                authenticators:
                    - Agven\JWTAuthBundle\Security\TokenAuthenticator
    # ...            
    access_control:
        - { path: ^/api/auth,  roles: IS_AUTHENTICATED_ANONYMOUSLY, methods: [POST] }
        - { path: ^/api,       roles: ROLE_ADMIN }
```

You can get the token for user with service like this:

```php
// ...
use Agven\JWTAuthBundle\Core\Services\Manager\TokenInterface as TokenManagerInterface;

class AuthManager
{
    private $tokenManager;
    
    public function __construct(TokenManagerInterface $tokenManager) 
    {
        $this->tokenManager = $tokenManager;
    }
    
    public function auth(UserAuthRequest $userAuth)
    {
        $user = $this->userRepository->findOneByUsername($userAuth->username);
        if (!$user) {
            throw new EntityNotFoundException('User not found.');
        }
        // ...
        $token = $this->tokenManager->createAuthToken($user);
        //$refreshToken = $this->tokenManager->createRefreshToken(); // If you want to use refresh token
        $payload = $this->tokenManager->getTokenPayload();
        // ...
    }
```

## To Do
- Add tests.
- Improve documentation.