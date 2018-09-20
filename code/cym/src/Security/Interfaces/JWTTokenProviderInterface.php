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
    /**
     * @param UserInterface $user
     *
     * @return mixed
     */
    public function getTokens(UserInterface $user);
}