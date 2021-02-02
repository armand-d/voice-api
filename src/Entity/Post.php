<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *      "get"={
 *          "normalization_context"={"groups"={"post_read"}}
 *      },
 *      "post"
 *  },
 *  itemOperations={
 *      "get"={
 *          "normalization_context"={"groups"={"post_details_read"}}
 *      },
 *      "put",
 *      "patch",
 *      "delete"
 *  }
 * )
 */
class Post
{

    Use ResourceId;
    Use Timestapable;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"user_details_read", "post_read", "post_details_read"})
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"user_details_read",  "post_read", "post_details_read"})
     */
    private string $sound_path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_details_read", "post_read", "post_details_read"})
     */
    private string $img_path;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('fr', 'en')", nullable=false)
     * @Groups({"user_details_read",  "post_read", "post_details_read"})
     */
    private string $lang;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('drafted', 'published', 'deleted', 'banned')", nullable=false)
     * @Groups({"user_details_read",  "post_read", "post_details_read"})
     */
    private string $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"post_read"})
     */
    private User $author;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @ORM\JoinTable(name="post_likes")
     */
    private Collection $liked_by;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private \DateTimeImmutable $published_at;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", orphanRemoval=true)
     */
    private Collection $comments;

    public function __construct()
    {
        $this->liked_by = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->createdAt = new \DateTimeImmutable();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(?string $img_path): self
    {
        $this->img_path = $img_path;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    /**
     * @return Collection|User[]
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    public function addLikedBy(User $user): void
    {
        if ($this->likedBy->contains($user)) {
            return;
        }

        $this->likedBy->add($user);
    }

    public function removeLikedBy(User $user): void
    {
        if (!$this->likedBy->contains($user)) {
            return;
        }

        $this->likedBy->removeElement($user);
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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
