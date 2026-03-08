<div id="top-header" style="with:100%;height:auto;text-align:right;">
    <img src="../public/files/pr-banner-long.png">
</div>

# WORKTIME CONTROLLER - SYMFONY 7

- [/README.md](../README.md)
<br><br>

# API Configuration

## API REST start up

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
$ php bin/console doctrine:fixtures:load --append --dry-run # Preview fixtures seeding
$ php bin/console doctrine:fixtures:load --append --no-interaction # Avoid purging existing data
```

## Testing

Create a parallel testing database from your local machine
```bash
$ make db-test-up
```

Now you can manage testing database from API container
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
# Run all tests
/var/www $ php vendor/bin/phpunit

# Run specific test suite
/var/www $ php vendor/bin/phpunit --testsuite=Unit --testdox
/var/www $ php vendor/bin/phpunit --testsuite=Integration --testdox
/var/www $ php vendor/bin/phpunit --testsuite=Functional --testdox

# Run specific test file
/var/www $ php vendor/bin/phpunit tests/Unit/Domain/Shared/ValueObject/EmailTest.php --testdox
```

Remove testing database for new tests execution if required
```bash
/var/www $ php bin/console --env=test doctrine:database:drop --force --if-exists
/var/www $ php bin/console cache:clear --env=test
```

Or remove testing database from local
```bash
$ make db-test-down
```

Execute Static Tests
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
    <img src="../public/files/pr-banner-long.png">
</div>