# PHP URL
> URL resolution and parsing meant to have feature parity with PHP core.

## Composer Installation

Installation is super-easy via [Composer](https://getcomposer.org)

```bash
composer require web/url
```

or add it by hand to your `composer.json` file.

## Usage

```php
Url::parse('https://user:pass@www.example.com:5500/path/example.html?id=123&user=test#section');
// Results:
// Web\Url\Parser\Parser Object
// (
//   [hash] => #section
//   [search] => ?id=123&user=test
//   [query] => id=123&user=test
//   [slashes] => //
//   [auth] => user:pass
//   [protocol] => https:
//   [host] => www.example.com:5500
//   [href] => https://user:pass@www.example.com:5500/path/example.html?id=123&user=test#section
//   [pathname] => /path/example.html
//   [port] => 5500
//   [uri] => /path/example.html?id=123&user=test
//   [hostname] => example.com
//   [origin] => https://user:pass@www.example.com:5500
//   [username] => user
//   [password] => pass
//   [www] => www.
// )

new Url('path/example.html?id=123&user=test#section', 'https://user:pass@www.example.com:5500/');
// Results:
Web\Url\Url Object
// (
//   [hash] => #section
//   [password] => pass
//   [username] => user
//   [search] => ?id=123&user=test
//   [query] => id=123&user=test
//   [origin] => https://user:pass@www.example.com:5500
//   [slashes] => //
//   [searchParams] =>
//   [auth] => user:pass
//   [protocol] => https:
//   [www] => www.
//   [host] => www.example.com:5500
//   [href] => https://user:pass@www.example.com:5500/path/example.html?id=123&user=test#section
//   [pathname] => /path/example.html
//   [port] => 5500
//   [uri] => /path/example.html?id=123&user=test
//   [hostname] => example.com
// )
```

## Resources
- [Report issue](https://github.com/lazervel/url/issues) and [send Pull Request](https://github.com/lazervel/url/pulls) in the [main Lazervel repository](https://github.com/lazervel/url)