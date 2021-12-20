<?php

namespace App\MessageHandler;


use App\Entity\Image;
use App\Image\ImageServiceInterface;
use App\Image\Response\ImageDto;
use App\Message\ImageThumb;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ImageThumbHandler implements MessageHandlerInterface
{
    public function __construct(
        private ImageServiceInterface $imageService,
        private ContainerInterface $container,
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(ImageThumb $message)
    {
        $width = $this->container->getParameter('image.thumb.squareSize');
        /** @var Image $originImage */
        $originImage = $this->em->getRepository(Image::class)->find($message->getImageId());

        $thumbFileName = $this->imageService->squareThumb($originImage->getPath(), $width);

        // TODO refactor
        $thumbDto = new ImageDto($thumbFileName);
        $thumbDto->path = $thumbFileName;
        $thumbDto->mimeType = $originImage->getMimeType();
        $thumbDto->width = $width;
        $thumbDto->height = $width;
        $thumbDto->size = $this->imageService->getImageSize($thumbFileName);

        $this->imageService->persistFromThumbDto($thumbDto, $originImage);
    }
}
