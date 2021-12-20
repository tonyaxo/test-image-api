<?php

namespace App\Image\Response;

use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class ImageUploadCollection
{
    /**
     * @var ImageDto[]
     */
    public array $items = [];
}
