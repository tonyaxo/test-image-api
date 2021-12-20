<?php

namespace App\Image\Upload;

use App\Image\ImageServiceInterface;
use App\Image\Request\JsonBase64Request;
use App\Image\Response\ImageUploadCollection;
use App\Image\Response\ImageDto;
use League\MimeTypeDetection\ExtensionMimeTypeDetector;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UploadBase64Strategy implements ImageUploadStrategyInterface
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

        /** @var JsonBase64Request $object */
        $object = $this->serializer->deserialize(
            $this->request->getContent(), JsonBase64Request::class, 'json'
        );

        $collection = new ImageUploadCollection();
        $dataUriNormalizer = new DataUriNormalizer();
        foreach ($object->items as $item) {
            /** @var File $fileObj */
            $fileObj = $dataUriNormalizer->denormalize($item->data, File::class);

            [$type, ] = explode(';', $item->data);
            [, $extension] = explode('/',$type);
            $fileName = uniqid('image_') . '.' .  $extension;

            $this->imageService->save($fileName, $fileObj->getContent());
            $collection->items[] = $this->createImageItemDto($fileName, $item->originalId);
        }

        return $collection;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    private function createImageItemDto(string $fileName, string $originId): ImageDto
    {
        $item = new ImageDto($fileName, $originId);
        $item->mimeType = (new ExtensionMimeTypeDetector())->detectMimeTypeFromPath($fileName);
        $item->size = $this->imageService->getImageSize($fileName);

        [$width, $height] = $this->imageService->getImageDimension($fileName);
        $item->width = $width;
        $item->height = $height;

        return $item;
    }
}
