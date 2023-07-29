<p align="center"><a href="https://github.com/bdumitrua/TaskTracker" target="_blank"><img src="https://github.com/bdumitrua/TaskTracker/blob/main/resources/js/src/images/logo.svg" width="200" alt="Fitbar Logo"></a></p>

# TaskTracker

TaskTracker - это веб-приложение для создания, редактирования и удаления списка задач, с возможностью поделиться правом на редактирование или просмотр с другими пользователями.

## Стек технологии:

-   Back-End: Laravel
-   Database: MySQL
-   Front-End: Vue3 + Vuex

## Требования

Чтобы запустить проект, вам понадобятся следующие компоненты:

-   PHP 8.1 или выше
-   Composer
-   NPM
-   MySQL

## Начало работы

Первоначальная настройка и запуск проекта осуществляется с помощью следующих команд:

Установите пакеты:

```bash
npm install
```

```bash
composer install
```

Настройте окружение:

```bash
php artisan env:create    # создать .env файл копию из .env.example
```
*Пока недоступно

```bash
php artisan jwt:secret    # создать jwt secret ключ в .env для работы JWT авторизации
```

```bash
php artisan migrate   # создать таблицы в базе данных
```

Запустите приложение:

```bash
php artisan serve    # запустить приложение на http://127.0.0.1:8000
```

*для работы загрузки превью фото (thumbnail) необходимо запускать приложение с сервера (пр: xampp, openserver), т.к. необходим модуль gd в php
