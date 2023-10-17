<h1 align="center"> laravel-replay-attack </h1>

<p align="center"> laravel middleware to prevent replay attacks.</p>


## Installing

```bash
$ composer require ramzeng/laravel-replay-attack -vvv
```

## Usage
### Publish config
```bash
$ php artisan vendor:publish --provider="Ramzeng\LaravelReplayAttack\ServiceProvider"
```
### Add middleware
```php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        \Ramzeng\LaravelReplayAttack\Middlewares\ReplayAttack::class,
    ];
    
    ...
    ...
}
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/ramzeng/laravel-replay-attack/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/ramzeng/laravel-replay-attack/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT