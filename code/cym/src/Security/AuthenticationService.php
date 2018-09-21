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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationService implements AuthenticationServiceInterface
{
//region SECTION: Fields
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Encoder
     */
    private $encoder;
    /**
     * @var SecurityManager
     */
    private $securityManager;
//endregion Fields

//region SECTION: Constructor
    /**
     * AuthenticationService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Encoder                $encoder
     * @param SecurityManager        $securityManager
     */
    public function __construct(EntityManagerInterface $entityManager, Encoder $encoder, SecurityManager $securityManager)
    {
        $this->entityManager   = $entityManager;
        $this->encoder         = $encoder;
        $this->securityManager = $securityManager;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param UserTokenInterface $userToken
     */
    public function authenticate(UserTokenInterface $userToken)
    {
        $pass  = $userToken->getPassword();
        $email = $userToken->getEmail();

        if (null === $email || null === $pass) {
            throw new AuthenticationException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(
            [
                'email' => $email,
            ]
        );

        if (null === $user) {
            throw new AuthenticationException();
        }

        if ($user->getPassword() !== $this->encoder->encode($pass)) {
            throw new AuthenticationException();
        }

        $userToken->setIsAuthenticated(true);
        $this->securityManager->setUser($user);
        $userToken->setUser($user);
    }
//endregion Public
}