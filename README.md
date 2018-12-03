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
	        "honey-comb/payments": "0.2.*"
	    }
	}
```
or
```js
    composer require honey-comb/payments
```

## Usage

To override default settings you can publish `payments.php` config file 

`php artisan vendor:publish --provider="HoneyComb\Payments\Providers\HCPaymentsServiceProvider" --tag=config`

### Payment

###### Payment uses two DTO's:

- Payment DTO
```
$paymentDto = new HCPaymentDTO('NUMBER-123', 20.21, HCPaymentStatusEnum::pending());
$paymentDto->setReason('product payment');
```
- User Payment DTO
```
$userPaymentDto = (new \HoneyComb\Payments\DTO\HCPaymentUserDTO())
    ->setEmail('john.doe@example.com')
    ->setFirstName('John')
    ->setLastName('Doe')
```
You can set additional data using setters.

###### Payment process
The main service of payment handling is `HCPaymentService`. 

- Create payment record for specific driver  
`$this->paymentService->driver('paysera')->create($paymentDto);`
- Make payment  
`$this->paymentService->driver('paysera')->pay($payment, $userPaymentDto);`
- You can do both in single method 
`$this->paymentService->driver('paysera')->createAndPay($paymentDto, $userPaymentDto);`


#### Paysera
To ovveride paysera `accept` and `cancel` blades you can change `responseClass` value in `payments.php` config file.

By default it uses `\HoneyComb\Payments\Paysera\HCPayseraResponse::class`

Your custom `PayseraReponseClass` must implement `PayseraResponseContract` interface.

###### Callback
If you want to send paysera callback within post method you must update `VerifyCsrfToken` class: 

```
protected $except = [
    'payments/paysera/callback'
];
```

## Additional managers

You can add additional PaymentManager or update existing ones by editing `payments.php` config file under `additional_drivers` section.
```
'paysera' => \HoneyComb\Payments\Managers\HCPayseraManager::class,
```
 
 Your custom manager class must extend `HCPaymentManager` class and implement `HCPaymentManagerContract` interface.
