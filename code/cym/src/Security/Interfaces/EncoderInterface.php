<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:24
 */

namespace App\Security\Interfaces;


interface EncoderInterface
{
    /**
     * Encode string
     *
     * @param string $string
     *
     * @return string
     */
    public function encode(string $string): string;

}