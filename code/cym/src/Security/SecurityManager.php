<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 21.09.18
 * Time: 11:47
 */

namespace App\Security;

use App\Entity\User;
use App\Security\Interfaces\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SecurityManager
 *
 * @package App\Security
 */
class SecurityManager
{
    private $user;

    private $payload;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SecurityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }


    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        if(null === $this->user && isset($this->payload->user_id)) {
            $user = $this->entityManager->getRepository(User::class)->find($this->payload->user_id);

            $this->user = $user;
        }

        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user = null): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

}