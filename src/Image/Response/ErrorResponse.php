<?php

namespace App\Image\Response;

use OpenApi\Annotations as OA;

class ErrorResponse
{
    /**
     * @var null|string
     * @OA\Property(type="string", example="Some error desctription")
     */
    public string $error;

    /**
     * @var null|string
     * @OA\Property(type="string", example="Error text")
     */
    public ?string $message = null;

    public function __construct(string $error, ?string $message = null)
    {
        $this->error = $error;
        $this->message = $message;
    }
}
