Contributing
--------------------


1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

### How to run the tests:

- First install the dependencies
```bash
composer install
```

- Then you are ready to run the tests

```bash
vendor/bin/phpunit -c tests/phpunit.xml
```

### To check the code standard run:

The code will be checked against the the [PSR-2 code standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).
It will also be checked with [PHP-CS-Fixer](https://github.com/fabpot/PHP-CS-Fixer)

```bash
vendor/bin/phpcs --standard=PSR2 lib
vendor/bin/phpcs --standard=PSR2 tests
vendor/bin/php-cs-fixer fix -v --dry-run tests
```

You can also automatically fix code standards by running:

```bash
vendor/bin/php-cs-fixer fix lib tests
```
