<?php

namespace App\Event;

use App\Entity\Image;
use Symfony\Contracts\EventDispatcher\Event;

class ImageUploadedEvent extends Event
{
    public const NAME = 'image.uploaded';

    protected Image $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function getImage(): Image
    {
        return $this->image;
    }
}
