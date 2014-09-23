Koine Strong Parameters
-----------------

Rails-like strong parameters for php

**Work in progress**

Code information:

[![Build Status](https://travis-ci.org/koinephp/StrongParameters.png?branch=master)](https://travis-ci.org/koinephp/StrongParameters)
[![Coverage Status](https://coveralls.io/repos/koinephp/StrongParameters/badge.png?branch=master)](https://coveralls.io/r/koinephp/StrongParameters?branch=master)
[![Code Climate](https://codeclimate.com/github/koinephp/StrongParameters.png)](https://codeclimate.com/github/koinephp/StrongParameters)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koinephp/StrongParameters/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/koinephp/StrongParameters/?branch=master)

Package information:

[![Latest Stable Version](https://poser.pugx.org/koine/strong-parameters]/v/stable.svg)](https://packagist.org/packages/koine/strong-parameters])
[![Total Downloads](https://poser.pugx.org/koine/strong-parameters]/downloads.svg)](https://packagist.org/packages/koine/strong-parameters])
[![Latest Unstable Version](https://poser.pugx.org/koine/strong-parameters]/v/unstable.svg)](https://packagist.org/packages/koine/strong-parameters])
[![License](https://poser.pugx.org/koine/strong-parameters]/license.svg)](https://packagist.org/packages/koine/strong-parameters])

### Usage

```php
use Koine\Parameters;

$params = new Parameters(array(
    'user' => array(
        'name'  => 'Foo',
        'email' => 'Foo@bar.com',
        'admin' => true
     )
));

// throws exception
$userParams = $params->requireParam('user')->permit(array(
    'name',
    'email',
));

// filters value
Parameters::$throwsException = false;

$userParams = $params->requireParam('user')->permit(array(
    'name',
    'email',
))->toArray(); // array('name' => 'Foo', 'email' => 'Foo@bar.com')



// do something with the values
```

The templates:

### Installing

#### Via Composer
Append the lib to your requirements key in your composer.json.

```javascript
{
    // composer.json
    // [..]
    require: {
        // append this line to your requirements
        "koine/strong-parameters": "0.9.*"
    }
}
```

### Alternative install
- Learn [composer](https://getcomposer.org). You should not be looking for an alternative install. It is worth the time. Trust me ;-)
- Follow [this set of instructions](#installing-via-composer)

### Issues/Features proposals

[Here](https://github.com/koinephp/strong-parameters/issues) is the issue tracker.

### Contributing

Only TDD code will be accepted. Please follow the [PSR-2 code standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

### How to run the tests:

```bash
phpunit --configuration tests/phpunit.xml
```

### To check the code standard run:

```bash
phpcs --standard=PSR2 lib
phpcs --standard=PSR2 tests
```

### Lincense
[MIT](MIT-LICENSE)

### Authors

- [Marcelo Jacobus](https://github.com/mjacobus)
- [Daniel Caña](https://github.com/dbcana)
- [Elisandro Nabinger](https://github.com/nabec)
