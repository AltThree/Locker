# Alt Three Locker

A lock manager for Laravel 5.


## Installation

Either [PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+ are required.

To get the latest version of Alt Three Locker, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require alt-three/locker
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "alt-three/locker": "^1.0"
    }
}
```

Once Alt Three Locker is installed, you need to register the service provider. Open up `config/app.php` and add the `AltThree\Locker\LockerServiceProvider` class to the `providers`.

## Configuration

Alt Three Locker requires configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish
```

This will create a `config/locker.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.


## Usage

Alt Three Locker provides a locker class which should be used to execute critical tasks while acquiring a lock to do so. We also have a locker middleware which will acquire a lock on a per session basis to perform "unsafe" tasks, or in other words, we're preventing more than one non-GET request from being processed at once per user.


## Security

If you discover a security vulnerability within this package, please e-mail us at support@alt-three.com. All security vulnerabilities will be promptly addressed.


## License

Alt Three Locker is licensed under [The MIT License (MIT)](LICENSE).
