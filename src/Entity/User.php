<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $password_code;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $password_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $renouv_time;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $renouv_code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_new;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->setCreateAt(new DateTime());
        $this->setRenouvTime(new DateTime());
        $this->setIsNew(true);
        $this->setAvatar('profil.jpg');
        try {
            $this->setToken(bin2hex(random_bytes(32)));
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getHighRole(){
        $rolesSortedByImportance = ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'];
        $rolesLabel = ['Super admin', 'Administrateur', 'Utilisateur'];
        $i = 0;
        foreach ($rolesSortedByImportance as $role)
        {
            if (in_array($role, $this->roles))
            {
                return $rolesLabel[$i];
            }
            $i++;
        }
        
        return "Utilisateur";
    }

    public function getHighRoleCode(){
        switch($this->getHighRole()){
            case 'Super admin':
                return 1;
            case 'Administrateur':
                return 2;
            default:
                return 0;
        }
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getPasswordCode(): ?string
    {
        return $this->password_code;
    }

    public function setPasswordCode(?string $password_code): self
    {
        $this->password_code = $password_code;

        return $this;
    }

    public function getPasswordTime(): ?\DateTimeInterface
    {
        return $this->password_time;
    }

    public function setPasswordTime(?\DateTimeInterface $password_time): self
    {
        $this->password_time = $password_time;

        return $this;
    }

    public function getRenouvTime(): ?\DateTimeInterface
    {
        return $this->renouv_time;
    }

    public function setRenouvTime(\DateTimeInterface $renouv_time): self
    {
        $this->renouv_time = $renouv_time;

        return $this;
    }

    public function getRenouvCode(): ?string
    {
        return $this->renouv_code;
    }

    public function setRenouvCode(?string $renouv_code): self
    {
        $this->renouv_code = $renouv_code;

        return $this;
    }

    public function getIsNew(): ?bool
    {
        return $this->is_new;
    }

    public function setIsNew(bool $is_new): self
    {
        $this->is_new = $is_new;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
