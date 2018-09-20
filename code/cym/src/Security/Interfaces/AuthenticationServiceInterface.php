<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:22
 */

namespace App\Security\Interfaces;


interface AuthenticationServiceInterface
{
    /**
     * Authenticate Token
     * @param UserTokenInterface $userToken
     *
     * @return mixed
     */
    public function authenticate(UserTokenInterface $userToken);
}