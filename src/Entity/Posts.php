<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts implements \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity=Threads::class, inversedBy="posts")
     */
    private $thread;

    /**
     * @ORM\ManyToMany(targetEntity=Posts::class, inversedBy="posts")
     */
    private $replies;

    /**
     * @ORM\ManyToMany(targetEntity=Posts::class, mappedBy="replies")
     */
    private $posts;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    private $formatedCreatedAt;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFormatedCreatedAt()
    {
        return $this->formatedCreatedAt;
    }

    /**
     * @param mixed $formatedCreatedAt
     */
    public function setFormatedCreatedAt($formatedCreatedAt): void
    {
        $this->formatedCreatedAt = $formatedCreatedAt;
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

    public function getThread(): ?Threads
    {
        return $this->thread;
    }

    public function setThread(?Threads $thread): self
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        $this->replies->removeElement($reply);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(self $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addReply($this);
        }

        return $this;
    }

    public function removePost(self $post): self
    {
        if ($this->posts->removeElement($post)) {
            $post->removeReply($this);
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'theme' => $this->theme,
            'text' => $this->text
        ];
    }
}
