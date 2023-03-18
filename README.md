# A simple laravel package that generates CRUD operation related files


Generate CRUD related files and routes using a single command.
## Environment
This package was tested using `php 8.1` and `laravel 10.0`


## Installation

Include the repository in your `composer.json` file

```json
    "repositories": [
        {
            "url": "https://github.com/mrshoikot/scrud.git",
            "type": "git"
        }
    ],
```

You can install the package via composer:

```bash
composer require "mrshoikot/scrud @dev"
```


## Usage
If you want to create CRUD operation for a model. You can simply run

```bash
php artisan scrud YOUR_MODEL_NAME_HERE
```

Please replace `YOUR_MODEL_NAME_HERE` with your desired model name. It'll create
views, controller, migration, model, request and routes. The migration will run automatically.
All you have to do is open the route in your browser and enjoy!

For example, if you run
```bash
php artisan scrud Human
```

You'll be able to access the resource at `http://localhost/humans`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [mrshoikot](https://github.com/mrshoikot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
