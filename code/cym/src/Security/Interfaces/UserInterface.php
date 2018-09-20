<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:30
 */

namespace App\Security\Interfaces;

/**
 * Interface UserInterface
 *
 * @package App\Security\Interfaces
 */
interface UserInterface
{
    public function getEmail();

    public function getId();

    public function getName();

    public function getSurname();
}