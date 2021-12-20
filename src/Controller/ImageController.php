<?php

namespace App\Controller;

use App\Image\ImageServiceInterface;
use App\Image\Request\JsonBase64ItemDto;
use App\Image\Request\JsonUriItemDto;
use App\Image\Response\ImageUploadCollection;
use App\Image\Response\ErrorResponse;
use App\Image\Upload\ImageUploadStrategyInterface;
use App\Repository\ImageRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ImageController extends AbstractController
{
    /**
     * Возможность принимать JSON запросы с BASE64 закодированными изображениями.
     *
     * @Route("/api/image/upload-base64", methods={"POST"})
     * @OA\RequestBody (
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *          property="items",
     *          type="array",
     *          @OA\Items(ref=@Model(type=JsonBase64ItemDto::class))
     *       )
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Возможность принимать JSON запросы с BASE64 закодированными изображениями.",
     *     @OA\JsonContent(ref=@Model(type=ImageUploadCollection::class))
     * )
     * @OA\Response(
     *     response=400,
     *     description="Ошибка загрузки изображений",
     *     @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     * )
     * @OA\Tag(name="upload")
     */
    public function uploadBase64(Request $request, ImageServiceInterface $imageService, ImageUploadStrategyInterface $base64Strategy): Response
    {
        try {
            $response = $imageService->upload($base64Strategy, $request);

            return $this->json($response, 200);
        } catch (\Throwable $t) {
            $message = "{$t->getFile()}:{$t->getLine()} {$t->getMessage()}";

            return $this->json(new ErrorResponse('Unable to upload images', $message), 400);
        }
    }

    /**
     * Возможность принимать multipart/form-data запросы
     *
     * @Route("/api/image/upload", methods={"POST"})
     * @OA\RequestBody (
     *     @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Item image",
     *                     property="item_images[]",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *      )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Возможность принимать multipart/form-data запросы",
     *     @OA\JsonContent(ref=@Model(type=ImageUploadCollection::class))
     * )
     * @OA\Response(
     *     response=400,
     *     description="Ошибка загрузки изображений",
     *     @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     * )
     * @OA\Tag(name="upload")
     */
    public function uploadFormData(Request $request, ImageServiceInterface $imageService, ImageUploadStrategyInterface $formDataStrategy): Response
    {
        try {
            // TODO implement
            // $response = $imageService->upload($formDataStrategy, $request);

            return $this->json(['items' => []], 200);
        } catch (\Throwable $t) {
            $message = "{$t->getFile()}:{$t->getLine()} {$t->getMessage()}";

            return $this->json(new ErrorResponse('Unable to upload images', $message), 400);
        }
    }

    /**
     * Возможность загружать изображения по заданному URL.
     *
     * @Route("/api/image/upload-uri", methods={"POST"})
     * @OA\RequestBody (
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *          property="items",
     *          type="array",
     *          @OA\Items(ref=@Model(type=JsonUriItemDto::class))
     *       )
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Возможность загружать изображения по заданному URL",
     *     @OA\JsonContent(ref=@Model(type=ImageUploadCollection::class))
     * )
     * @OA\Response(
     *     response=400,
     *     description="Ошибка загрузки изображений",
     *     @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     * )
     * @OA\Tag(name="upload")
     */
    public function uploadUri(Request $request, ImageServiceInterface $imageService, ImageUploadStrategyInterface $uriStrategy): Response
    {
        try {
            // TODO implement
            // $response = $imageService->upload($formDataStrategy, $request);

            return $this->json(['items' => []], 200);
        } catch (\Throwable $t) {
            $message = "{$t->getFile()}:{$t->getLine()} {$t->getMessage()}";

            return $this->json(new ErrorResponse('Unable to upload images', $message), 400);
        }
    }

    /**
     * Получить список всех изображений и превью
     *
     * @Route("/api/image/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Получить список всех изображений и превью",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *          property="items",
     *          type="array",
     *          @OA\Items(ref=@Model(type=Image::class))
     *       )
     *     )
     * )
     * @OA\Response(
     *     response=500,
     *     description="Ошибка сервера",
     *     @OA\JsonContent(ref=@Model(type=ErrorResponse::class))
     * )
     * @OA\Tag(name="upload")
     */
    public function index(ImageRepository $repository): Response
    {
        try {
            // TODO add Paginator();
            $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                },
            ];

            return $this->json($repository->findAll(), 200, [], $defaultContext);
        } catch (\Throwable $t) {
            return $this->json(new ErrorResponse('Error', $t->getMessage()), 400);
        }
    }
}
