# honeycomb-payments  
Payments package for HoneyComb CMS
https://github.com/honey-comb/payments

## Description

HoneyComb CMS payments functions

## Attention

This is part payments package for HoneyComb CMS package.

## Requirement

 - php: `^7.1`
 - laravel: `^5.6`
 - composer
 
 ## Installation

Begin by installing this package through Composer.


```js
	{
	    "require": {
	        "honey-comb/payments": "0.1.*"
	    }
	}
```
or
```js
    composer require honey-comb/payments
```

## Usage

Publish `payments.php` config file 

`php artisan vendor:publish --provider="HoneyComb\Payments\Providers\HCPaymentsServiceProvider" --tag=config`

For overriding paysera accept and cancel blades you can override `responseClass` setting in payments.php config file.
By default it's using  

`\HoneyComb\Payments\Paysera\HCPayseraResponse::class`

Your new `PayseraReponseClass` must implement `PayseraResponseContract` interface.
