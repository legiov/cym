<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:37
 */

namespace App\Security\Interfaces;


interface SecretKeyProviderInterface
{
    /**
     * @return string
     */
    public function getKey():string;
}