# React/Whois

Whois client based on React.

[![Build Status](https://secure.travis-ci.org/reactphp/whois.png?branch=master)](http://travis-ci.org/reactphp/whois)

## Install

The recommended way to install react/whois is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "react/whois": "dev-master"
    }
}
```

## Example

```php
<?php

$client = new React\Whois\Client($resolver, $connFactory);
$client->query('igor.io', function ($result) {
    echo $result;
});
```

## Todo

* Provide convenience connection factory (maybe in react core)
* Use react/dns dependency once it is merged
* Streaming whois body
* Timeout

## Tests

To run the test suite, you need PHPUnit.

    $ phpunit

## License

MIT, see LICENSE.

## Resources

* [WHOIS Protocol Specification](http://tools.ietf.org/html/rfc3912)
* [Whois - Wikipedia](http://en.wikipedia.org/wiki/Whois)
