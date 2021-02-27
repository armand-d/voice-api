<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\GetMeAction;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ApiResource(
 *  collectionOperations={
 *      "get",
 *      "post"
 *  },
 *  itemOperations={
 *      "get"={
 *          "normalization_context"={"groups"={"user_details_read", "me"}},
 *          "requirements"={"id"="\d+"}
 *      },
 *      "put",
 *      "patch",
 *      "delete",
 *      "get_me"={
 *          "method"="GET",
 *          "path"="/users/me",
 *          "controller"=GetMeAction::class,
 *          "normalization_context"={"groups"={"me"}},
 *          "openapi_context"={
 *              "parameters"={}
 *          },
 *          "read"=false
 *      }
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"email": "partial"})
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 * @UniqueEntity("email", message="This email is not available")
 */
class User implements UserInterface
{
    Use ResourceId;
    Use Timestapable;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Groups({"user_read", "user_details_read", "me"})
     * @Assert\NotBlank(message="email mandatory")
     * @Assert\Email(message="email format invalid")
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank(message="password mandatory")
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author")
     * @Groups({"user_details_read"})
     */
    private Collection $posts;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"user_read", "user_details_read", "me"})
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"user_read", "user_details_read", "me"})
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Groups({"user_details_read", "me", "post_read", "post_details_read"})
     */
    private string $accountName;

    /**z
     * @ORM\Column(type="string", nullable=false)
     * @Groups({"user_details_read", "me", "post_read", "post_details_read"})
     */
    private string $lang = "en";

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Groups({"user_details_read", "me", "post_read", "post_details_read"})
     */
    private string $status = "enabled";

    public function __construct()
    {
        $this->posts = new ArrayCollection();

        $this->createdAt = new \DateTimeImmutable();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAccountName(): ?string
    {
        return $this->accountName;
    }

    public function setAccountName(string $accountName): self
    {
        $this->accountName = $accountName;

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
}
