<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @UniqueEntity("email", message="Cet email est déjà pris par un autre utilisateur, merci de le changer !")
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
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(message = "L'email '{{ value }}' n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 5, minMessage = "Votre nom d'utilisateur doit faire au minimum {{ limit }} caractères")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity=GroupConversation::class, inversedBy="users", cascade={"persist"})
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity=GroupConversation::class, mappedBy="admin", cascade={"persist"})
     */
    private $adminGroupConversations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * Give the full name of user
     *
     * @return string
     */
    public function getFullName()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function getRoleTitle()
    {
        if(in_array("ROLE_ADMIN", $this->roles)) return "Administrateur";
        else return "Utilisateur";
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->messages                 = new ArrayCollection();
        $this->conversations            = new ArrayCollection();
        $this->adminGroupConversations  = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getRoles() : array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles) : self
    {
        $this->roles = $roles;
        return $this;
    }

    
    public function getPassword() : string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password) : self
    {
        $this->password = $password;
        return $this;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getSalt()
    {}

    public function eraseCredentials()
    {}
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getResetToken(): string
    {
        return $this->resetToken;
    }
     
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }
    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupConversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(GroupConversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
        }

        return $this;
    }

    public function removeConversation(GroupConversation $conversation): self
    {
        $this->conversations->removeElement($conversation);

        return $this;
    }

    /**
     * @return Collection|GroupConversation[]
     */
    public function getAdminGroupConversations(): Collection
    {
        return $this->adminGroupConversations;
    }

    public function addGroupConversation(GroupConversation $groupConversation): self
    {
        if (!$this->adminGroupConversations->contains($groupConversation)) {
            $this->adminGroupConversations[] = $groupConversation;
            $groupConversation->setAdmin($this);
        }

        return $this;
    }

    public function removeGroupConversation(GroupConversation $groupConversation): self
    {
        if ($this->adminGroupConversations->removeElement($groupConversation)) {
            // set the owning side to null (unless already changed)
            if ($groupConversation->getAdmin() === $this) {
                $groupConversation->setAdmin(null);
            }
        }

        return $this;
    }
}