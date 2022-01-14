<?php

namespace App\Entity;

use App\Repository\ThreadsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThreadsRepository::class)
 */
class Threads implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $theme;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Boards::class, inversedBy="threads")
     */
    private $board;

    /**
     * @ORM\OneToMany(targetEntity=Posts::class, mappedBy="thread")
     */
    private $posts;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    private $formated_created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file1;

    /**
     * @return mixed
     */
    public function getFormatedCreatedAt()
    {
        return $this->formated_created_at;
    }

    /**
     * @param mixed $formated_created_at
     */
    public function setFormatedCreatedAt($formated_created_at): void
    {
        $this->formated_created_at = $formated_created_at;
    }

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getBoard(): ?Boards
    {
        return $this->board;
    }

    public function setBoard(?Boards $board): self
    {
        $this->board = $board;

        return $this;
    }

    /**
     * @return Collection|Posts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setThread($this);
        }

        return $this;
    }

    public function removePost(Posts $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getThread() === $this) {
                $post->setThread(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getFile1(): ?string
    {
        return $this->file1;
    }

    public function setFile1(?string $file1): self
    {
        $this->file1 = $file1;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'theme' => $this->theme,
            'text' => $this->text,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'file1' => $this->file1
            //'board' => $this->board
        ];
    }
}
