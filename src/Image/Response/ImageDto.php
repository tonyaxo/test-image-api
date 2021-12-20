<?php

namespace App\Image\Response;

use OpenApi\Annotations as OA;

class ImageDto
{
    /**
     * @var null|string
     * @OA\Property(type="string", example="12345")
     */
    public ?string $id = null;

    /**
     * @var string
     * @OA\Property(type="string", example="/path/to/image")
     */
    public string $path;

    /**
     * @var string
     * @OA\Property(type="string", example="image/png")
     */
    public string $mimeType;

    /**
     * @var int
     * @OA\Property(type="integer", example="2938485", description="Size in bytes")
     */
    public int $size;

    /**
     * @var int
     * @OA\Property(type="integer", example="1280")
     */
    public int $width;

    /**
     * @var int
     * @OA\Property(type="integer", example="960")
     */
    public int $height;

    public function __construct(string $path, ?string $id = null)
    {
        $this->path = $path;
        $this->id = $id;
    }
}
