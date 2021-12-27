<?php

namespace App\Entity;

use App\Repository\BoardsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BoardsRepository::class)
 */
class Boards
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Threads::class, mappedBy="board")
     */
    private $threads;

    public function __construct()
    {
        $this->threads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Threads[]
     */
    public function getImage1(): Collection
    {
        return $this->threads;
    }

    public function addImage1(Threads $image1): self
    {
        if (!$this->threads->contains($image1)) {
            $this->threads[] = $image1;
            $image1->setBoard($this);
        }

        return $this;
    }

    public function removeImage1(Threads $image1): self
    {
        if ($this->threads->removeElement($image1)) {
            // set the owning side to null (unless already changed)
            if ($image1->getBoard() === $this) {
                $image1->setBoard(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }


}
