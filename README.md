# Laravel Blue Billywig

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]


Laravel Blue Billywig is created by, and is maintained by tychovbh, and is a Blue Billywig sapi bridge for Laravel 5 and Laravel Lumen. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/tychovbh/laravel-bluebillywig/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md)

## Install

Laravel Blue Billywig requires PHP 7.1 or 7.2. This particular version supports Laravel 5.5 - 5.7 only.

To get the latest version, simply require the project using Composer.

``` bash
$ composer require tychovbh/laravel-bluebillywig
```

Once installed, if you are not using automatic package discovery, then you need to register the `Tychovbh\Bluebillywig\BluebillywigServiceProvider` service provider in your `config/app.php`.

## Configuration

Laravel Blue Billywig requires publication configuration.

To get started, you'll need to publish all vendor assets:

``` bash
$ php artisan vendor:publish --tag=laravel-bluebillywig
```

This will create a `config/bluebillywig.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

There are two config options:

##### Default publication Name

This option (`'default'`) is where you may specify which of the publications below you wish to use as your default publication for all work. Of course, you may use many connections at once using the `$bluebillywig->publication()` method. The default value for this setting is `'public'`.

##### Bluebillywig Publications

This option (`'publications'`) is where each of the publications are setup for your application. Example configuration has been included, but you may add as many publications as you would like.

## Usage

##### Bluebillywig
This is the class of most interest. This will send authenticated requests to Bluebillywig sapi. Go to their [documentation](https://support.bluebillywig.com/) for all available requests.
 
##### Real Examples
Instantiate Bluebillywig class
``` php
use Tychovbh\Bluebillywig\Bluebillywig;

// Use class injection
Route::get('/bluebillywig', function(Bluebillywig $bluebillywig) {
    $bluebillywig->retrieve('/endpoint')
});

// Or use Laravel helper app()
Route::get('/bluebillywig', function() {
    $bluebillywig = app('bluebillywig');
    $bluebillywig->retrieve('/endpoint')
});
```

Available Bluebillywig methods:
``` php
// The examples below use the default publication. 
$bluebillywig->retrieve('/endpoint')
$bluebillywig->create('/endpoint', $formData)
$bluebillywig->update($id, '/endpoint', $formData)
$bluebillywig->delete($id, '/endpoint')

// in this example we request data from my_publication. 
// my_publication key should be added to the confiugration file. 
$bluebillywig->publication('my_publication')->retrieve('/playlist')
```

This package is only a Bridge to perform Bluebillywig requests. So you will need to handle Exceptions from Bluebillywig yourself.
``` php
use Tychovbh\Bluebillywig\Exceptions\ConfigurationException;

$bluebillywig = app('bluebillywig');

try {
    $bluebillywig->retrieve('/endpoint')
} catch(\ConfigurationException $exception) {
    echo $exception->getMessage();
} catch(\GuzzleException $exception) {
    echo $exception->getMessage();
} catch(\Exception $exception) {
    echo $exception->getMessage();
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email info@bespokeweb.nl instead of using the issue tracker.

## Credits

- [Tycho][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tychovbh/laravel-bluebillywig.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tychovbh/laravel-bluebillywig/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tychovbh/laravel-bluebillywig.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tychovbh/laravel-bluebillywig.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tychovbh/laravel-bluebillywig.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tychovbh/laravel-bluebillywig
[link-travis]: https://travis-ci.org/tychovbh/laravel-bluebillywig
[link-scrutinizer]: https://scrutinizer-ci.com/g/tychovbh/laravel-bluebillywig/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tychovbh/laravel-bluebillywig
[link-downloads]: https://packagist.org/packages/tychovbh/laravel-bluebillywig
[link-author]: https://github.com/tychovbh
[link-contributors]: ../../contributors
