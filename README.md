<h1 align="center"> laravel-wx-middleware-login </h1>

<p align="center"> middleware login.</p>


## Installing

```shell
$ composer require lsshu/laravel-wx-middleware-login
```

## Usage

```shell
$ php artisan vendor:publish --tag=wx-middleware-login-migrations
```

```shell
$ php artisan migrate --path=database/migrations/wx_middleware
```

```shell
$ php artisan vendor:publish --provider="Vinkla\Hashids\HashidsServiceProvider"
```
> Modify the configuration file "hashids.php"

```php
'connections' => [
	'wx_token' => [
		'salt' => env('HASHIDS_WX_TOKEN_SALT', 'HASHIDS_WX_TOKEN_SALT'),
		'length' => env('HASHIDS_WX_TOKEN_LENGTH', 40),
	],
],
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/lsshu/laravel-wx-middleware-login/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/lsshu/laravel-wx-middleware-login/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT