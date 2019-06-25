Карта городов с количеством объявлений (abramskaia-app)
=======================================================

## Описание ##

Одностраничное приложение с картой городов. При клике на город под картой появляется график изменения количества объявлений (обновляется раз в минуту).

## Требования ##

gir, docker, docker compose, 

## Короткая инструкция ##

В терминале:

	git clone https://github.com/abramskama/abramskaia-app.git abramskaia-app &&
	cd abramskaia-app &&
	docker run --rm -v $(pwd):/app composer install &&
	cd ..  &&
	sudo chown -R $USER:$USER abramskaia-app &&
	cd abramskaia-app &&
	cp .env.example .env &&
	docker-compose up -d &&
	docker-compose exec app php artisan key:generate &&
	docker-compose exec app php artisan config:cache &&
	docker-compose exec db bash

	mysql -u root -p

Вводим пароль:

	2258796
    

	GRANT ALL ON abramskaia.* TO 'abramskaia'@'%' IDENTIFIED BY '2258796'; FLUSH PRIVILEGES; EXIT;


	exit
    

	docker-compose exec app php artisan migrate

В браузере:

	http://127.0.0.1:9999

## Подробная инструкция ##

Копируем репозиторий и переходим в папку

	git clone https://github.com/abramskama/abramskaia-app.git abramskaia-app &&
	cd abramskaia-app

Устанавливаем composer

	docker run --rm -v $(pwd):/app composer install

Устанавливаем права на папку

	cd .. &&
	sudo chown -R $USER:$USER abramskaia-app &&
	cd abramskaia-app

Копируем файл перенных среды

	cp .env.example .env

Инициализируем создание контейнеров

	docker-compose up -d

Создаем ключ laravel и создаем кэш файл переменных среды

	docker-compose exec app php artisan key:generate &&
	docker-compose exec app php artisan config:cache

Подключаемся к контейнеру с базой

	docker-compose exec db bash

Заходим от root

	mysql -u root -p

Вводим пароль:

	2258796

Даем права на базу abramskaia пользователю abramskaia

	GRANT ALL ON abramskaia.* TO 'abramskaia'@'%' IDENTIFIED BY '2258796'; FLUSH PRIVILEGES; EXIT;

Отключаемся

	exit

Запускаем миграции

	docker-compose exec app php artisan migrate

В браузере (где 127.0.0.1 - имя хоста или ip сервера)

	http://127.0.0.1:9999

