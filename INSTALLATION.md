# Dullahan project installation

1. composer create-project mortimer333/dullahan-project my-project
2. mv .env.example .env.local
3. Default domain is dullahan.localhost, to change it go to .docker/nginx.conf:9
4. docker compose up -d
5. docker exec -it dullahan-php-fpm php bin/console d:m:m
6. docker exec -it dullahan-php-fpm php bin/console doctrine:phpcr:node-type:register ./vendor/mortimer333/dullahan/definitions/jackrabbit/ --allow-update
7. Open http://dullahan.localhost/api/doc
