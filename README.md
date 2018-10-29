# Laravel Blue Billywig

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


Laravel Blue Billywig is created by, and is maintained by Tycho, and is a Laravel/Lumen package to connect with Blue Billywig Sapi. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/tychovbh/laravel-bluebillywig/releases), [license](LICENSE.md), and [contribution guidelines](CONTRIBUTING.md)

## Install

Laravel Blue Billywig requires PHP 7.1 or 7.2. This particular version supports Laravel 5.5 - 5.7 only and Lumen.

To get the latest version, simply require the project using Composer.

``` bash
$ composer require tychovbh/laravel-bluebillywig
```

Once installed, if you are not using automatic package discovery, then you need to register the `Tychovbh\Bluebillywig\BluebillywigServiceProvider` service provider in your `config/app.php`.

In Lumen add de Service Provider in `bootstrap/app.php`:
```php
$app->register(\Tychovbh\Bluebillywig\BluebillywigServiceProvider::class);
```

## Configuration

Laravel Blue Billywig requires publication configuration.

To get started, you'll need to publish all vendor assets:

``` bash
$ php artisan vendor:publish --tag=laravel-bluebillywig
```

This will create a `config/bluebillywig.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

In lumen you have to create the configuration file manually since `vendor:publish` is not available. Create the file `config/bluebillywig.php` and copy paste the [example file](https://github.com/tychovbh/laravel-bluebillywig/blob/master/config/bluebillywig.php). Don't forget to load the configuration file in `bootstrap/app.php`:
```php
$app->configure('bluebillywig');
```  

There are two config options:

##### Default publication Name

This option (`'default'`) is where you may specify which of the publications below you wish to use as your default publication for all work. Of course, you may use many connections at once using the `$bluebillywig->publication('my_publication')` method. The default value for this setting is `'public'`.

##### Bluebillywig Publications

This option (`'publications'`) is where each of the publications are setup for your application. Example configuration has been included, but you may add as many publications as you would like.

## Usage

##### Bluebillywig
This is the class of most interest. This will send authenticated requests to Blue Billywig Sapi. Go to their [documentation](https://support.bluebillywig.com/) for all available endpoints.
 
##### Real Examples
Instantiate Bluebillywig class:
``` php
use Tychovbh\Bluebillywig\Bluebillywig;

// Use class injection
Route::get('/bluebillywig', function(Bluebillywig $bluebillywig) {
    $response = $bluebillywig->retrieve('/publication')
    return response()->json($response)
});

// Or use Laravel helper app()
Route::get('/bluebillywig', function() {
    $bluebillywig = app('bluebillywig');
    $response = $bluebillywig->retrieve('/publication')
    return response()->json($response)
});
```

Available Bluebillywig methods:
``` php
// The examples below use the default publication. 
$response = $bluebillywig->retrieve('/mediaclip')
$response = $bluebillywig->create('/mediaclip', $formData)
$response = $bluebillywig->update($id, '/mediaclip', $formData)
$response = $bluebillywig->delete($id, '/mediaclip')

// in this example we request data from my_publication. 
// my_publication key should be added to publications in the confiugration file. 
$response = $bluebillywig->publication('my_publication')->retrieve('/playlist')
```

You can send parameters with some of the requests:
```php
// Request with GET parameter 'limit=10'
$response = $bluebillywig->retrieve('/mediaclip', [
    'limit' => 10
])

// Create resource
$response = $bluebillywig->create('/mediaclip', [
    'title' => 'my fantastic new title',
])

// Update resource
$response = $bluebillywig->create($id, '/mediaclip', [
    'title' => 'my fantastic new title',
])
```  

You will need to handle Exceptions from Bluebillywig yourself:
``` php
use Tychovbh\Bluebillywig\Exceptions\ConfigurationException;

try {
    $bluebillywig = app('bluebillywig');
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
For testing `tests/feature/*` copy `tests/.env.example` to `tests/.env` and fill in your Blue Billywig testing account credentials.

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

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
