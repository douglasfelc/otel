Instale a extensão do OpenTelemetry no container:

`docker exec -it php-app pecl install opentelemetry-beta`


<!-- docker compose run php-neopack composer install -->
docker run --rm \
  -v $(pwd):/app \
  -w /app \
  composer install


docker exec -it php-app bash

php --ini


.
├── docker-compose.yml
├── grafana
├── node
│   └── app
├── otel
│   └── config
│       └── otel-config.yaml
├── php
│   ├── app
│   │   ├── composer.json
│   │   ├── src
│   │   │   └── index.php
│   │   └── vendor
│   │       ├── autoload.php
│   │       ├── composer
│   │       │   ├── autoload_classmap.php
│   │       │   ├── autoload_namespaces.php
│   │       │   ├── autoload_psr4.php
│   │       │   ├── autoload_real.php
│   │       │   ├── autoload_static.php
│   │       │   ├── ClassLoader.php
│   │       │   ├── LICENSE
│   │       │   └── semver
│   │       ├── psr
│   │       │   ├── http-message
│   │       │   └── log
│   │       ├── symfony
│   │       │   ├── polyfill-mbstring
│   │       │   └── polyfill-php82
│   │       └── tbachert
│   │           └── spi
│   ├── config
│   │   └── php.ini
│   └── Dockerfile
└── README.md