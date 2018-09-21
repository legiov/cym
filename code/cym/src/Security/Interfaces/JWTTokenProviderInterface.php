<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:25
 */

namespace App\Security\Interfaces;


interface JWTTokenProviderInterface
{
    public const ACCESS_TOKEN = 'accessToken';
    public const REFRESH_TOKEN = 'refreshToken';
    public const EXPIRES_IN = 'expires_in';

    /**
     * @param UserInterface $user
     *
     * @return mixed
     */
    public function getTokens(UserInterface $user):array ;
}