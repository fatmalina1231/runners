<?php

namespace App\Entity;

use App\Repository\ListWishesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListWishesRepository::class)
 */
class ListWishes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity=Wishes::class, mappedBy="id_list")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $time_end;

    public function __construct()
    {
        $this->id_wishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdCategory(): ?int
    {
        return $this->id_category;
    }

    public function setIdCategory(?int $id_category): self
    {
        $this->id_category = $id_category;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTimeEnd(): ?string
    {
        return $this->time_end;
    }

    public function setTimeEnd(string $time_end): self
    {
        $this->time_end = $time_end;

        return $this;
    }

    /**
     * @return Collection|Wishes[]
     */
    public function getIdWishes(): Collection
    {
        return $this->id;
    }

    public function addIdWish(Wishes $idWish): self
    {
        if (!$this->id->contains($idWish)) {
            $this->id[] = $idWish;
            $idWish->setIdListWishes($this);
        }

        return $this;
    }

    public function removeIdWish(Wishes $idWish): self
    {
        if ($this->id->removeElement($idWish)) {
            // set the owning side to null (unless already changed)
            if ($idWish->getIdListWishes() === $this) {
                $idWish->setIdListWishes(null);
            }
        }

        return $this;
    }

}
