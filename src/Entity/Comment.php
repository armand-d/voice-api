<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ApiResource()
 */
class Comment
{
    Use ResourceId;
    Use Timestapable;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $sound_path;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('fr', 'en')", nullable=false)
     */
    private string $lang;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Post $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $author;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private \DateTimeImmutable $published_at;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('published', 'deleted', 'banned')", nullable=false)
     */
    private string $status;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getSoundPath(): ?string
    {
        return $this->sound_path;
    }

    public function setSoundPath(string $sound_path): self
    {
        $this->sound_path = $sound_path;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(?\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
