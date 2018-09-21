<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 0:36
 */

namespace App\Entity;

use App\Security\Interfaces\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @ORM\Entity()
 * @ORM\Table(name="user")
 *
 * @package App\Entity
 * @UniqueEntity(fields={"email"}, message="It looks like your already have an account!")
 * @UniqueEntity(fields={"mobileNumber"}, message="This mobile number already exist")
 *
 */
class User implements EntityInterface, UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     * @Assert\NotBlank(message="Please inter name")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="surname", type="string", nullable=false)
     * @Assert\NotBlank(message="Please inter surname")
     */
    private $surname;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", nullable=false)
     * @Assert\NotBlank(message="Please inter email")
     *
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", nullable=false)
     * @Assert\NotBlank(message="Please inter password")
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="mobile_number", type="string", nullable=false)
     * @Assert\NotBlank(message="Please inter mobile phone")
     */
    private $mobileNumber;

    /**
     * @var string
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    private $avatar;

    /**
     * @var string
     * @ORM\Column(name="refresh_token", type="text", nullable=true)
     */
    private $refreshToken;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     *
     * @return User
     */
    public function setSurname(string $surname): User
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     *
     * @return User
     */
    public function setMobileNumber(string $mobileNumber): User
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar(string $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return User
     */
    public function setRefreshToken(string $refreshToken): User
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}