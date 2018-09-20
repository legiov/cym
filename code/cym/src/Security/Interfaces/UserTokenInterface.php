<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 19:28
 */

namespace App\Security\Interfaces;

use App\Entity\User;

/**
 * Interface UserTokenInterface
 *
 * @package App\Security\Interfaces
 */
interface UserTokenInterface
{
    public function isAuthenticated():bool;

    public function setIsAuthenticated(bool $isAuth);

    public function getEmail(): ?string;

    public function getPassword(): ?string;

    public function setUser(UserInterface $user);

    public function getUser(): ?UserInterface;
}