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
    private string $soundPath;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('fr', 'en')", nullable=false)
     */
    private string $lang = "en";

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
    private \DateTimeImmutable $publishedAt;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('published', 'deleted', 'banned')", nullable=false)
     */
    private string $status = "published";

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getSoundPath(): ?string
    {
        return $this->soundPath;
    }

    public function setSoundPath(string $soundPath): self
    {
        $this->soundPath = $soundPath;

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

    public function getpublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setpublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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
