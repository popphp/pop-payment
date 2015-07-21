pop-payment
===========

[![Build Status](https://travis-ci.org/popphp/pop-payment.svg?branch=master)](https://travis-ci.org/popphp/pop-payment)
[![Coverage Status](http://www.popphp.org/cc/coverage.php?comp=pop-payment)](http://www.popphp.org/cc/pop-payment/)

OVERVIEW
--------
`pop-payment` is a component for processing payments via some of the known payment processing gateway 
vendors. It can also be extended to support other shipping vendors and their available APIs. Currently,
the built-in supported vendors are:

* Authorize.net
* PayLeap
* PayPal
* TrustCommerce
* USAEPay

The main idea is the "normalize" the the fields across the adapters so that the main interface has
common fields that are "translated" into the fields required for the selected adapter's API. So,
instead of having to worry that Authorize.net's credit card field is called `x_card_num` and
USAEPay's credit card field is `UMcard`, you just need to worry about the field `cardNum` and it'll
be mapped correctly to the adapter. The main common fields are:

|                 | Common Fields   |                 |
|-----------------|-----------------|-----------------|
| amount          | city            | shipToLastName  |
| cardNum         | state           | shipToCompany   |
| expDate         | zip             | shipToAddress   |
| ccv             | country         | shipToCity      |
| firstName       | phone           | shipToState     |
| lastName        | fax             | shipToZip       |
| company         | email           | shipToCountry   |
| address         | shipToFirstName |                 |   

`pop-payment` is a component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-payment` using Composer.

    composer require popphp/pop-payment

BASIC USAGE
-----------

### Creating a payment object

```php
use Pop\Payment\Payment;
use Pop\Payment\Adapter\Authorize;

$payment = new Payment(new Authorize('API_LOGIN_ID', 'TRANSACTION_KEY')); 
```

### Using the payment object to process a transaction

```php
$payment->amount    = 41.51;
$payment->cardNum   = '4111111111111111';
$payment->expDate   = '03/17';

$payment->firstName = 'Test';
$payment->lastName  = 'Person';
$payment->company   = 'Test Company';
$payment->address   = '123 Main St.';
$payment->city      = 'New Orleans';
$payment->state     = 'LA';
$payment->zip       = '70124';
$payment->country   = 'US';

$payment->shippingSameAsBilling();

$payment->send();

if ($payment->isApproved()) {
    // If approved
} else if ($payment->isDeclined()) {
    // If declined
} else if ($payment->isError()) {
    // Some other unknown error
    echo $payment->getMessage();
}
```
