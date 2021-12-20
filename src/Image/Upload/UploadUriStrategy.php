<?php

namespace App\Image\Upload;

use App\Image\ImageServiceInterface;
use App\Image\Response\ImageUploadCollection;
use App\Image\Response\ImageDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UploadUriStrategy implements ImageUploadStrategyInterface
{
    private ?Request $request = null;

    public function __construct(
        private SerializerInterface $serializer,
        private ImageServiceInterface $imageService,
    ) {}

    public function upload(): ImageUploadCollection
    {
        if (null === $this->request) {
            throw  new \InvalidArgumentException('$request is missing');
        }

        $collection = new ImageUploadCollection();

        $collection->items[] = new ImageDto('Not implemented', 'Not implemented');

        return $collection;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}
