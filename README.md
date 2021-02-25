# Use PHP 8 attributes to implement request data validation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bakome/laravel-validation-attributes)](https://packagist.org/packages/bakome/laravel-validation-attributes)
![Tests](https://github.com/bakome/laravel-validation-attributes/workflows/Tests/badge.svg)
[![Type Coverage](https://shepherd.dev/github/bakome/laravel-validation-attributes/coverage.svg)](https://shepherd.dev/github/bakome/laravel-validation-attributes)

This package provides annotations to easily add validation rules on action methods in a controller. Here's a quick example:

```php
use Bakome\ValidationAttributes\Attributes\ValidationRule;

class ExampleController
{
    #[ValidationRule('title', 'required|string')]
    public function actionMethod()
    {

    }
}
```
 
## Motivation

This project exists to provide simple and cleaner way to validate request data on Laravel controllers.
Using validation rules in action methods often complicate readability of the controller and adding validation in separate requests classes imply switching trough that classes multiple time to view and change the rules (Losing focus).
With this simple attributes we can make validation more accessible and maintainable in the controllers without messing the controller code. 

## Installation

You can install the package via composer:

```bash
composer require bakome/laravel-validation-attributes
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Bakome\ValidationAttributes\ValidationAttributesServiceProvider" --tag="config"
```

## Usage

The package provides several annotations that should be put on controller action methods. 
These annotations are used to validate request data.

### Adding a validation rule with string as a parameter

```php
use Bakome\ValidationAttributes\Attributes\ValidationRule;

class ExampleController
{
    #[ValidationRule('title', 'required|string')]
    public function actionMethod()
    {

    }
}
```

This attribute will check if title parameter is present in current request and throw validation error if not present.

### Adding a validation rule with an array as a parameter

```php
use Bakome\ValidationAttributes\Attributes\ValidationRule;

class ExampleController
{
    #[ValidationRule('title', ['required', 'string'])]
    public function actionMethod()
    {

    }
}
```

This attribute will check if title parameter is present in current request and throw validation error if not present.

### Adding a multiple validation rules

```php
use Bakome\ValidationAttributes\Attributes\ValidationRule;

class ExampleController
{
    #[ValidationRule('description', 'required|string')]
    #[ValidationRule('title', ['required', 'string'])]
    public function actionMethod()
    {

    }
}
```

This attribute will check if title and description parameters are present in current request and throw validation error if not present.

### Adding a redirect for failed validation

```php
use Bakome\ValidationAttributes\Attributes\ValidationRule;
use Bakome\ValidationAttributes\Attributes\ValidationRuleRedirect;

class ExampleController
{
    #[ValidationRule('description', 'required|string')]
    #[ValidationRule('title', ['required', 'string'])]
    #[ValidationRuleRedirect('/a-redirection-route')]
    public function actionMethod()
    {

    }
}
```

This attribute will check if title and description parameters are present in current request, compile validation error messages and redirect the page to provided route.

### Adding a redirect with input for failed validation

```php
use Bakome\ValidationAttributes\Attributes\ValidationRule;
use Bakome\ValidationAttributes\Attributes\ValidationRuleRedirect;

class ExampleController
{
    #[ValidationRule('description', 'required|string')]
    #[ValidationRule('title', ['required', 'string'])]
    #[ValidationRuleRedirect('/a-redirection-route', true)]
    public function actionMethod()
    {

    }
}
```

This attribute will check if title and description parameters are present in current request, compile validation error messages and redirect the page to provided route including old input parameters.

### Ajax's requests awareness and proper json responses returned for failed validation

## Config

### Plugin enabled/disabled

```
'enabled' => true,
```

This plugin can be enabled or disabled with altering this property in configuration file. Default value is true (Enabled).

### Plugin enabled/disabled

```
'middleware' => \Bakome\ValidationAttributes\Routing\Middleware\ValidationRulesByAttributes::class,
```

This property enables developers to provide a different handler for Validation via Middleware. Please be careful with this option and don't change it if you don't need custom behaviour.

### Validation middleware 

```
'middleware' => \Bakome\ValidationAttributes\Routing\Middleware\ValidationRulesByAttributes::class,
```

This property enables developers to provide a different handler for Validation via Middleware. Please be careful with this option and don't change it if you don't need custom behaviour.

### Detect api routes

```
'api_pattern' => 'api/*',
```

This property provide value for detecting api routes. Using expects json sometimes is not enough and when that lacks this option/feature do the job.


## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Roadmap

* Provide custom validation messages
* Provide validation groups to reduce boilerplate code
