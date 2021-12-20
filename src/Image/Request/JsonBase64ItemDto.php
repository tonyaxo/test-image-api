<?php

namespace App\Image\Request;

use OpenApi\Annotations as OA;

// data:image/png;base64,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
// <img src="data:image/png;base64,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" />
class JsonBase64ItemDto
{
    /**
     * @var null|string
     * @OA\Property(type="string", example="data:image/png;base64,iVBO...Rw0=")
     */
    public ?string $data = null;

    /**
     * @var null|string
     * @OA\Property(type="string", example="avatar.png")
     */
    public ?string $originalId= null;
}
