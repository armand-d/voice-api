<?php

declare(strict_types=1);

namespace App\Entity;

trait Timestapable 
{
    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=true)
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
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): Timestapable
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}