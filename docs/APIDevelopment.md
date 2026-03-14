<div id="top-header" style="with:100%;height:auto;text-align:right;">
    <img src="./images/pr-banner-long.png">
</div>

# WORKTIME CONTROLLER - SYMFONY 7

- [/README.md](../README.md)
<br><br>

# API Development

If you are using the https://github.com/pabloripoll/platform-docker-nginx-php-8.5 repository, you may notice that there some config directories samples.

- `./platform/nginx-php-8.5/docker/config/nginx/conf.d-sample`
- `./platform/nginx-php-8.5/docker/config/php/conf.d-sample`
- `./platform/nginx-php-8.5/docker/config/supervisor/conf.d-sample`

All of them are required for the REST API platform:

1. NGINX
- `./platform/nginx-php-8.5/docker/config/nginx/conf.d/default.conf`

2. PHP
- `./platform/nginx-php-8.5/docker/config/php/conf.d/fpm-pool.conf`
- `./platform/nginx-php-8.5/docker/config/php/conf.d/php.ini`

3. SUPERVISOR
- `./platform/nginx-php-8.5/docker/config/supervisor/conf.d/nginx.conf`
- `./platform/nginx-php-8.5/docker/config/supervisor/conf.d/php-fpm.conf`
<br>

## API start up

Once the platform is installed and the REST API container is running, you must execute the initialization commands
```bash
$ make apirest-ssh
```

Install the application via Composer
```bash
$ composer install
```

Generate the JWT keys
```bash
# Generate JWT keys if they don't exist
/var/www $ mkdir -p config/jwt

# Generate private key
/var/www $ openssl genpkey -algorithm RSA -out config/jwt/private.pem -pkeyopt rsa_keygen_bits:4096

# Generate public key
/var/www $ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

# Set permissions
/var/www $ chmod 644 config/jwt/private.pem
/var/www $ chmod 644 config/jwt/public.pem
```

## Local Migrations

Execute the migrations
```bash
$ php bin/console doctrine:migrations:migrate
```

Execute the database seeding
```bash
$ php bin/console doctrine:fixtures:load --append --dry-run # Preview fixtures seeding before record them
$ php bin/console doctrine:fixtures:load --append # Apply fixtures
```

For more seeding, you may avoid overwrite previous fixtures
```bash
$ php bin/console doctrine:fixtures:load --append --no-interaction # Avoid purging existing data
```

## API Test

Create a parallel testing database on your local machine
```bash
$ make db-test-up
```

Now you can manage local testing database from the API container
```bash
$ make apirest-ssh

# Setup test database - Load fixtures for testing - Verify the schema
/var/www $ php bin/console --env=test doctrine:database:create --if-not-exists
/var/www $ php bin/console --env=test doctrine:schema:create
/var/www $ php bin/console --env=test doctrine:fixtures:load --no-interaction
/var/www $ php bin/console doctrine:schema:validate --env=test
```

Run Unit, Integration and Functional tests
```bash
# Run specific test suite
/var/www $ php vendor/bin/phpunit --testsuite=Unit --testdox
/var/www $ php vendor/bin/phpunit --testsuite=Integration --testdox
/var/www $ php vendor/bin/phpunit --testsuite=Functional --testdox

# Run all tests
/var/www $ php vendor/bin/phpunit

# Run specific test file
/var/www $ php vendor/bin/phpunit tests/Unit/Domain/Shared/ValueObject/EmailTest.php --testdox
```

Remove testing database for new tests execution if will require later
```bash
/var/www $ php bin/console --env=test doctrine:database:drop --force --if-exists
/var/www $ php bin/console cache:clear --env=test
```

Or remove testing database on the API container from local
```bash
$ make db-test-down
```

Execute static tests from API container
```bash
/var/www $ composer phpstan

# Clear cache
/var/www $ rm -rf var/phpstan
```

Run all tests at once
```bash
/var/www $  rm -rf var/phpstan; composer phpstan; php vendor/bin/phpunit --testdox
```

Clear all cache
```bash
/var/www $ rm -rf var/cache/* && composer dump-autoload && php bin/console cache:clear
```
<!-- FOOTER -->
<br>

---

<br>

- [GO TOP ⮙](#top-header)

<div style="with:100%;height:auto;text-align:right;">
    <img src="./images/pr-banner-long.png">
</div>