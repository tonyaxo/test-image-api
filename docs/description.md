# Описание реализации

Тестовое задание выполненно частично, некоторые части оставлены без реализации, 
в силу того что общий подход понятен и без них. Так же есть места требующие небольшого рефакторинга.

## OpenAPI

OpenAPI доступно по ссылке [https://localhost/api/doc](https://localhost/api/doc).
Для документации используется [NelmioApiDocBundle](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html)

## Общее

- Для загргузки из разных форматов используются стратегии загрузки, реализующие [App\Image\Upload\ImageUploadStrategyInterface](../src/Image/Upload/ImageUploadStrategyInterface.php),
 например [App\Image\Upload\UploadBase64Strategy](../src/Image/Upload/UploadBase64Strategy.php)


- Для работы с изображениями используется реализация [../src/Image/ImageServiceInterface.php](src/Image/ImageServiceInterface.php),
которая позволяет работать с различнми хранилищами дынных (local, s3 и т.д.), благодяря компоненту [Filesystem](https://github.com/thephpleague/flysystem) с локальным хранилищем по умолчанию, 
но при необходимости может быть легко заменено на `s3`, `ftp` и другие. Манипуляции с изображениями выполняются через библиотеки `Imagine` и `gd`.


- Для генерации превьюшек используется система событий с последующей отправкой в Message Bus, который по умолчанию испольует
транспорт `doctrine://default`, но при необходимости может быть заменен любым другим к примеру `amqp`

## Тесты

Для примера работы с тестами написаны несколько тест-кейсов см `tests/`




