<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

trait Timestapable 
{
    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     * @Groups({"user_read", "user_details_read",  "post_read", "post_details_read"})
     */
    private \DateTimeInterface $createdAt;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user_read", "user_details_read",  "post_read", "post_details_read"})
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * Get the value of createdAt
     *
     * @return  \DateTimeInterface
     */ 
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Get the value of updatedAt
     *
     * @return  \DateTimeInterface
     */ 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param  \DateTimeInterface  $updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}