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

[![Latest Stable Version](https://poser.pugx.org/koine/strong-parameters/v/stable.svg)](https://packagist.org/packages/koine/strong-parameters)
[![Total Downloads](https://poser.pugx.org/koine/strong-parameters/downloads.svg)](https://packagist.org/packages/koine/strong-parameters)
[![Latest Unstable Version](https://poser.pugx.org/koine/strong-parameters/v/unstable.svg)](https://packagist.org/packages/koine/strong-parameters)
[![License](https://poser.pugx.org/koine/strong-parameters/license.svg)](https://packagist.org/packages/koine/strong-parameters)
[![Dependency Status](https://gemnasium.com/koinephp/StrongParameters.png)](https://gemnasium.com/koinephp/StrongParameters)


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


// nested params

$params = new Params(array(
    'book' => array(
        'title'   => 'Some Title',
        'edition' => '3',
        'authors' => array(
            array(
                'name'     => 'Jon',
                'birthday' => '1960-01-02',
            ),
            array(
                'name'     => 'Daniel',
                'birthday' => '1960-01-02',
            ),
        )
    ),
    'foo' => 'bar',
    'bar' => 'foo'
));

$params->permit(array(
    'book' => array(
        'authors' => array('name'),
        'title'
    ),
    'foo'
))->toArray();

/**
    array(
        'book' => array(
            'title'   => 'Some Title',
            'authors' => array(
                array('name' => 'Jon'),
                array('name' => 'Daniel'),
            )
        ),
        'foo' => 'bar'
  )
*/


// array params

$params = new Params(array(
    'tags' => array('php', 'ruby')
));

$params->permit(array('tags' => array()))->toArray();
// array( 'tags' => array('php', 'ruby'))

// array params with invalid data

$params = new Params(array(
    'tags' => 'invalid'
));

$params->permit(array('tags' => array()))->toArray(); // array()


// do something with the values
```

### Installing

#### Via Composer
Append the lib to your requirements key in your composer.json.

```javascript
{
    // composer.json
    // [..]
    require: {
        // append this line to your requirements
        "koine/strong-parameters": "~0.9.2"
    }
}
```

### Alternative install
- Learn [composer](https://getcomposer.org). You should not be looking for an alternative install. It is worth the time. Trust me ;-)
- Follow [this set of instructions](#installing-via-composer)

### Issues/Features proposals

[Here](https://github.com/koinephp/StrongParameters/issues) is the issue tracker.

## Contributing

Please refer to the [contribuiting guide](https://github.com/koinephp/StrongParameters/blob/master/CONTRIBUTING.md).

### Lincense
[MIT](MIT-LICENSE)

### Authors

- [Marcelo Jacobus](https://github.com/mjacobus)
