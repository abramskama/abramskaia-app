# abramskaia-app

git clone https://github.com/abramskama/abramskaia-app.git abramskaia-app
cd abramskaia-app
docker run --rm -v $(pwd):/app composer install
cd -
sudo chown -R $USER:$USER abramskaia-app
cp .env.example .env
docker-compose up -d
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache

http://localhost:9999
docker-compose exec db bash
mysql -u root -p
2258796
GRANT ALL ON abramskaia.* TO 'abramskaia'@'%' IDENTIFIED BY '2258796';
FLUSH PRIVILEGES;
EXIT;
exit

docker-compose exec app php artisan migrate
