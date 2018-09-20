<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 18:58
 */

namespace App\Security;


use App\Entity\User;
use App\Security\Interfaces\AuthenticationServiceInterface;
use App\Security\Interfaces\UserTokenInterface;
use App\Security\Token\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * AuthenticationService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Encoder                $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, Encoder $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * @param UserTokenInterface $userToken
     */
    public function authenticate(UserTokenInterface $userToken)
    {
        $pass = $userToken->getPassword();
        $email = $userToken->getEmail();

        if(null === $email || null === $pass) {
            throw new AuthenticationException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);

        if(null === $user) {
            throw new AuthenticationException();
        }

        if($user->getPassword() !== $this->encoder->encode($pass)) {
            throw new AuthenticationException();
        }

        $userToken->setIsAuthenticated(true);

        $userToken->setUser($user);
    }
}