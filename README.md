## Kilo Test

#### Development installation

Copy .env to .env.local and configure it
``
cp .env .env.local
``

Run composer install
``
$ composer install
``

Run migrations
``
$ bin/console doctrine:migrations:migrate
``

Start Symfony server https://symfony.com/doc/current/setup/symfony_server.html

``
$ symfony server:start
``

Request example

``
curl --request POST \
--url http://127.0.0.1:8000/webhook/apple \
--header 'Content-Type: application/json' \
--data '{
"password": "password",
"notification_type": "DID_RENEW",
"auto_renew_product_id": "subscription1"
}'
``
