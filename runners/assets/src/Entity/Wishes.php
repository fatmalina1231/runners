<?php

namespace App\Entity;

use App\Repository\WishesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WishesRepository::class)
 */
class Wishes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity=ListWishes::class, inversedBy="id")
     * @ORM\JoinColumn(name="id_list", referencedColumnName="id", onDelete="CASCADE")
     */
    private $id_list;

    /**
     * @ORM\Column(type="integer")
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdList(): ?int
    {
        return $this->id_list;
    }

    public function setIdList(int $id_list): self
    {
        $this->id_list = $id_list;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getIdListWishes(): ?ListWishes
    {
        return $this->id_list;
    }

    public function setIdListWishes(?ListWishes $id_list): self
    {
        $this->id_list = $id_list;

        return $this;
    }

}
