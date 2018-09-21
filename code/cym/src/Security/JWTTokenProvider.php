<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:20
 */

namespace App\Security;


use App\Security\Interfaces\JWTTokenProviderInterface;
use App\Security\Interfaces\UserInterface;
use Firebase\JWT\JWT;

class JWTTokenProvider implements JWTTokenProviderInterface
{
//region SECTION: Fields
    /**
     * @var SecretKeyProvider
     */
    private $secretKeyProvider;
//endregion Fields


//region SECTION: Constructor
    /**
     * JWTTokenProvider constructor.
     *
     * @param SecretKeyProvider $secretKeyProvider
     */
    public function __construct(SecretKeyProvider $secretKeyProvider)
    {
        $this->secretKeyProvider = $secretKeyProvider;
    }
//endregion Constructor

//region SECTION: Getters/Setters
    public function getTokens(UserInterface $user):array
    {

        $accessExpire = new \DateTime();
        $accessExpire->modify('+30 min');
        $secretKey     = $this->secretKeyProvider->getKey();
        $refreshExpire = new \DateTime();
        $refreshExpire->modify('+1 day');

        return array(
            self::ACCESS_TOKEN  => JWT::encode(
                [
                    'name'    => $user->getName(),
                    'user_id' => (string)$user->getId(),
                    'exp'     => $accessExpire->getTimestamp(),
                ],
                $secretKey
            ),
            self::REFRESH_TOKEN => JWT::encode(
                [
                    'user_id' => (string)$user->getId(),
                    'exp'     => $refreshExpire->getTimestamp(),
                ],
                $secretKey
            ),
            self::EXPIRES_IN    => $accessExpire->getTimestamp(),
        );
    }
//endregion Getters/Setters

}