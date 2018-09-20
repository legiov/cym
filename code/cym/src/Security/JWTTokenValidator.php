<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 21:57
 */

namespace App\Security;


use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTTokenValidator
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var SecretKeyProvider
     */
    private $secretKeyProvider;

    /**
     * JWTTokenValidator constructor.
     *
     * @param RequestStack      $requestStack
     * @param SecretKeyProvider $secretKeyProvider
     */
    public function __construct(RequestStack $requestStack, SecretKeyProvider $secretKeyProvider)
    {
        $this->requestStack = $requestStack;
        $this->secretKeyProvider = $secretKeyProvider;
    }

    public function validateToken($checkYourself = null, $id = null)
    {

        if(true === $checkYourself && null === $id) {
            return false;
        }

        $request = $this->requestStack->getCurrentRequest();

        if(null === $request) {
            return false;
        }

        $token = $request->headers->get('token', null);

        if(null === $token) {
            return false;
        }
        try {
            $payload = JWT::decode($token, $this->secretKeyProvider->getKey());
        } catch (\Throwable $exception) {
            return false;
        }

        if($checkYourself) {
            $id = (string)$id;
            return $payload->id === $id;
        }

        return true;

    }
}