<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="thumbs")
     */
    private $origin;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="origin")
     */
    private $thumbs;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function __construct()
    {
        $this->thumbs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getOrigin(): ?self
    {
        return $this->origin;
    }

    public function setOrigin(?self $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getThumbs(): Collection
    {
        return $this->thumbs;
    }

    public function addThumb(self $thumb): self
    {
        if (!$this->thumbs->contains($thumb)) {
            $this->thumbs[] = $thumb;
            $thumb->setOrigin($this);
        }

        return $this;
    }

    public function removeThumb(self $thumb): self
    {
        if ($this->thumbs->removeElement($thumb)) {
            // set the owning side to null (unless already changed)
            if ($thumb->getOrigin() === $this) {
                $thumb->setOrigin(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistHook()
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
    }
}
