<?php

namespace App\Image;

use App\Entity\Image;
use App\Event\ImageUploadedEvent;
use App\Image\Response\ImageDto;
use App\Image\Response\ImageUploadCollection;
use App\Image\Upload\ImageUploadStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ImageService implements ImageServiceInterface
{
    public function __construct(
        private FilesystemOperator $imageStorage,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $dispatcher,
        private Imagine $imagine = new Imagine(),
    ) {}

    /**
     * @inheritDoc
     */
    public function squareThumb(string $filename, int $size): string
    {
        $imageResource = $this->imageStorage->readStream($filename);
        $image = $this->imagine->read($imageResource);

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $thumbName = uniqid("thumb_{$size}x{$size}_") . '.' .  $extension;

        $this->imageStorage->write(
            $thumbName,
            $image->thumbnail(new Box($size, $size))->get($extension)
        );

        return $thumbName;
    }

    public function getImageDimension(string $fileName): array
    {
        $imageResource = $this->imageStorage->readStream($fileName);
        $image = $this->imagine->read($imageResource);

        $size = $image->getSize();

        return [$size->getWidth(), $size->getHeight()];
    }

    public function getImageSize(string $fileName): int
    {
        return $this->imageStorage->fileSize($fileName);
    }

    public function save(string $fileName, string $content, array $config = []): void
    {
        $this->imageStorage->write($fileName, $content, $config);
    }

    public function persistFromDto(ImageDto $dto): Image
    {
        $image = $this->createFromDto($dto);

        $this->em->persist($image);
        $this->em->flush($image);

        return $image;
    }

    public function persistFromThumbDto(ImageDto $dto, Image $origin): Image
    {
        $image = $this->createFromDto($dto);
        $image->setOrigin($origin);

        $this->em->persist($image);
        $this->em->flush($image);

        return $image;
    }

    protected function createFromDto(ImageDto $dto): Image
    {
        $image = new Image();
        $image->setPath($dto->path);
        $image->setSize($dto->size);
        $image->setMimeType($dto->mimeType);
        $image->setWidth($dto->width);
        $image->setHeight($dto->height);

        return $image;
    }

    public function upload(ImageUploadStrategyInterface $uploadStrategy, Request $request): ImageUploadCollection
    {
        $result = $uploadStrategy
            ->setRequest($request)
            ->upload()
        ;

        foreach ($result->items as $item) {
            $image = $this->persistFromDto($item);

            $this->dispatcher->dispatch(new ImageUploadedEvent($image), ImageUploadedEvent::NAME);
        }

        return $result;
    }
}
