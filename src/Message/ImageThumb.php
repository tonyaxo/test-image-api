<?php

namespace App\Message;

class ImageThumb
{
    public function __construct(private int $imageId)
    {}

    public function getImageId(): int
    {
        return $this->imageId;
    }
}
