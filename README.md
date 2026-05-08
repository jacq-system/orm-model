[//]: # (![PHPStan]&#40;https://img.shields.io/badge/style-level%207-yellow.svg?&label=phpstan&#41;)
![PHPStan](https://github.com/jacq-system/orm-model/actions/workflows/phpstan.yml/badge.svg)
![PHPUnit](https://github.com/jacq-system/orm-model/actions/workflows/phpunit.yml/badge.svg)
![CSFixer](https://github.com/jacq-system/orm-model/actions/workflows/phpfixer.yml/badge.svg)


# orm-model
PHP Doctrine ORM and shared application model for JACQ herbarium applications based on Symfony Framework v8. Before push, please run ```./composer.sh check```.

Unit tests are AI generated to describe actual implementation that seems to be proven.

## Code quality checks
### PHPStan
```shell
./composer.sh phpstan
```
OR
```shell
clear && \
docker run --rm -it  \
 -u $(id -u):$(id -g)   \
 -v $(pwd):/app   -w /app  \
 php:8.5-cli  \
 vendor/bin/phpstan analyse --memory-limit 500M --level 7 src
```

### PHP Unit
```shell
./composer.sh test
```
OR
```shell
clear && \
docker run --rm -it  \
 -u $(id -u):$(id -g)   \
 -v $(pwd):/app   -w /app  \
 php:8.5-cli  \
vendor/bin/phpunit
```

### PHP CS fixer
```shell
./composer.sh fixer
```
OR
```shell
clear && \
docker run --rm -it  \
 -u $(id -u):$(id -g)   \
 -v $(pwd):/app   -w /app  \
 php:8.5-cli  \
vendor/bin/php-cs-fixer check
```

and to fix:
```shell
./composer.sh fixit
```
OR
```shell
clear && \
docker run --rm -it  \
 -u $(id -u):$(id -g)   \
 -v $(pwd):/app   -w /app  \
 php:8.5-cli  \
vendor/bin/php-cs-fixer fix
```
