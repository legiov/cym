<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 21:57
 */

namespace App\Security;


use App\Security\Interfaces\JWTTokenValidatorInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTTokenValidator implements JWTTokenValidatorInterface
{
//region SECTION: Fields
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var SecretKeyProvider
     */
    private $secretKeyProvider;

    /**
     * @var mixed
     */
    private $payload;

    /**
     * @var string
     */
    private $token;

    /**
     * @var SecurityManager
     */
    private $securityManager;
//endregion Fields

//region SECTION: Constructor
    /**
     * JWTTokenValidator constructor.
     *
     * @param RequestStack      $requestStack
     * @param SecretKeyProvider $secretKeyProvider
     * @param SecurityManager   $securityManager
     */
    public function __construct(
        RequestStack $requestStack,
        SecretKeyProvider $secretKeyProvider,
        SecurityManager $securityManager
    ) {
        $this->requestStack      = $requestStack;
        $this->secretKeyProvider = $secretKeyProvider;
        $this->securityManager   = $securityManager;
    }
//endregion Constructor

//region SECTION: Public
    public function validateToken($checkYourself = false, $id = null): bool
    {

        if (true === $checkYourself && null === $id) {
            return false;
        }

        $token = $this->getToken();

        if (null === $token) {
            return false;
        }

        $payload = $this->getPayLoad($token);

        if (null === $payload) {
            return false;
        }

        $this->payload = $payload;


        $this->securityManager->setPayload($payload);

        if ($checkYourself) {
            $id = (string)$id;

            return $payload->user_id === $id;
        }

        return true;

    }

    public function validateRefreshToken(): bool
    {
        if ($this->validateToken()) {

            $user = $this->securityManager->getUser();

            if (null === $user) {
                return false;
            }

            return $user->getRefreshToken() === $this->token;
        }

        return false;
    }
//endregion Public

//region SECTION: Private
    /**
     * @return null|string
     */
    private function getToken(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        $token = null;


        if (null !== $request) {
            $token = $request->headers->get('token', null);
        }

        $this->token = $token;

        return $token;
    }

    /**
     * @param $token
     *
     * @return null|object
     */
    private function getPayLoad($token)
    {
        $payload = null;

        try {
            $payload = JWT::decode($token, $this->secretKeyProvider->getKey(), ['HS256']);
        } catch (\Throwable $exception) {
        }

        return $payload;
    }
//endregion Private
}