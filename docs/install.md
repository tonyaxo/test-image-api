# Инструкция по установке

1. Проект основан на [symfony-docker](https://github.com/dunglas/symfony-docker)
2. Запустить `docker-compose up -d`
3. Открыть в браузере `https://localhost` и согласиться на переход по самописному сертификату

## Настройка

1. `composer install`
2. `php bin/console doctrine:migrations:migrate`

## Запуск тестов

1. `php bin/console --env=test doctrine:database:create`
2. `php bin/console --env=test doctrine:schema:create`
3. `php ./vendor/bin/phpunit`
