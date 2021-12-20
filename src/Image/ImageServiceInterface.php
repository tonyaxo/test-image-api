<?php

namespace App\Image;

use App\Entity\Image;
use App\Image\Response\ImageDto;
use App\Image\Response\ImageUploadCollection;
use App\Image\Upload\ImageUploadStrategyInterface;
use Symfony\Component\HttpFoundation\Request;

interface ImageServiceInterface
{
    /**
     * @param string $filename
     * @param int $size
     * @return string Thumb tmp filename
     */
    public function squareThumb(string $filename, int $size): string;

    public function getImageDimension(string $fileName): array;

    public function getImageSize(string $fileName): int;

    public function persistFromDto(ImageDto $dto): Image;

    public function persistFromThumbDto(ImageDto $dto, Image $origin): Image;

    public function save(string $fileName, string $content, array $config = []): void;

    public function upload(ImageUploadStrategyInterface $uploadStrategy, Request $request): ImageUploadCollection;
}
