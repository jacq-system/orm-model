![PHPStan](https://img.shields.io/badge/style-level%207-yellow.svg?&label=phpstan)

# orm-model
PHP Doctrine ORM and model for herbarium application

## PHPStan
```shell
clear && \
docker run --rm -it  \
 -u $(id -u):$(id -g)   \
 -v $(pwd):/app   -w /app  \
 php:8.5-cli  \
 vendor/bin/phpstan analyse --memory-limit 500M --level 7 src
```