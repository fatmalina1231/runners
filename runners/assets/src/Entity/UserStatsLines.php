<?php

namespace App\Entity;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

  /**
     * @ORM\Entity(repositoryClass: "App
\Repository\UserStatsLinesRepository")
  */
class UserStatsLines
{ 
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type: "integer")
  */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity: User::class, inversedBy: "userLines")
     * @ORM\JoinColumn(nullable: false)
    */
    private $user;

   /**
     * @ORM\Column(type: "datetime")
     *Assert\NotBlank
   */
    private $createdAt;

   /**
     * @ORM\Column(type: "text")
   */
    private $url;


    /**
     * @ORM\Column(type: "text")
    */
    private $route;


    /**
     * @ORM\Column(type: "string", nullable: true)
    */
    private $sessionId;

    /**
     * @ORM\Column(type: "string")
    */
    private $browser;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route): void
    {
        $this->route = $route;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function setBrowser($browser): void
    {
        $this->browser = $browser;
    }
}