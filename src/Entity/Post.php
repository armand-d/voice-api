<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\PostUpdatedAt;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="`post`")
 * @ApiResource(
 *  collectionOperations={
 *      "get",
 *      "post"
 *  },
 *  itemOperations={
 *      "get",
 *      "put",
 *      "patch",
 *      "delete",
 *      "put_updated_at"={
 *           "method"= "PUT",
 *           "path"= "/posts/{id}/updated-at",
 *           "controller"= PostUpdatedAt::class,
 *       },
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
    private string $soundPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_details_read", "post_read", "post_details_read"})
     */
    private string $imgPath;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default" : "fr"})
     * @Groups({"user_details_read",  "post_read", "post_details_read"})
     */
    private string $lang = "fr";

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Groups({"user_details_read",  "post_read", "post_details_read"})
     */
    private string $status = "drafted";

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"post_read", "post_details_read"})
     */
    private UserInterface $author;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @ORM\JoinTable(name="post_likes")
     * @Groups({"post_read"})
     */
    private Collection $likedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"post_read"})
     */
    private \DateTime $publishedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", orphanRemoval=true)
     */
    private Collection $comments;

    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->createdAt = new \DateTimeImmutable();
        $this->publishedAt = new \DateTime();
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
        return $this->soundPath;
    }

    public function setSoundPath(string $soundPath): self
    {
        $this->soundPath = $soundPath;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): self
    {
        $this->imgPath = $imgPath;

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

    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    public function setAuthor(UserInterface $author): self
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

    public function getpublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setpublishedAt(?\DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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
