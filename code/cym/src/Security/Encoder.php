<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 18:59
 */

namespace App\Security;

use App\Security\Interfaces\EncoderInterface;

/**
 * Class Encoder
 * Здесь будет инкапсулирована сложная логика шифрования с возможностью сметы типа шифрования
 *
 * @package App\Service
 */
class Encoder implements EncoderInterface
{
    /**
     * Здесь будет инкапсулирована сложная логика шифрования с возможностью сметы типа шифрования
     *
     * @param string $string
     *
     * @return string
     */
    public function encode(string $string): string
    {
        return md5($string);
    }
}