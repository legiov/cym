<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:38
 */

namespace App\Security;


use App\Security\Interfaces\SecretKeyProviderInterface;

/**
 * Class SecretKeyProvider
 * Здесь инкапсулируем логику получения|генерации ключа
 *
 * @package App\Security
 */
class SecretKeyProvider implements SecretKeyProviderInterface
{
    /**
     *
     * @return string
     */
    public function getKey(): string
    {
        return 'sample_key';
    }

}