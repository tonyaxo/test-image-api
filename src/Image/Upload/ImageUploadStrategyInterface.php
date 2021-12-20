<?php

namespace App\Image\Upload;

use App\Image\Response\ImageUploadCollection;
use Symfony\Component\HttpFoundation\Request;

interface ImageUploadStrategyInterface
{
    public function upload(): ImageUploadCollection;

    public function setRequest(Request $request): self;
}
