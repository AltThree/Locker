# Alt Three Locker

[![Build Status](https://img.shields.io/travis/AltThree/Locker/master.svg?style=flat-square)](https://travis-ci.org/AltThree/Locker)
[![StyleCI](https://github.styleci.io/repos/47549337/shield?branch=master)](https://github.styleci.io/repos/47549337)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

**A lock manager for Laravel 5.**


## Installation

This version requires [PHP](https://php.net) 7.1 - 7.3, and supports Laravel 5.5 - 5.8 only.

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require alt-three/locker
```

Once installed, if you are not using automatic package discovery, then you need to register the `AltThree\Locker\LockerServiceProvider` service provider in your `config/app.php`.


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

Our full security policy is available to read [here](https://github.com/AltThree/Locker/security/policy).


## License

Alt Three Locker is licensed under [The MIT License (MIT)](LICENSE).
