<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 21.09.18
 * Time: 11:02
 */

namespace App\Security\Interfaces;


interface JWTTokenValidatorInterface
{
//region SECTION: Public
    /**
     *
     * @param bool   $yourSelf true - если нужно сравнить id пользователя токена и в запросе
     * @param string|null $id id пользователя из запроса
     *
     * @return bool
     */
    public function validateToken($yourSelf = false, $id = null): bool;

    public function validateRefreshToken(): bool;

//endregion Public

}