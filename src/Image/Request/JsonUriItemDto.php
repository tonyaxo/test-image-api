<?php

namespace App\Image\Request;

use OpenApi\Annotations as OA;

class JsonUriItemDto
{
    /**
     * @var null|string
     * @OA\Property(type="string", example="https://some.host/path/to/image.jpg")
     */
    public ?string $uri = null;

    /**
     * @var null|string
     * @OA\Property(type="string", example="avatar.png")
     */
    public ?string $originalId= null;
}
