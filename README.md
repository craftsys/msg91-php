# PHP Client Library for MSG91

_This library requires a minimum PHP version of 7.1_

This is a **PHP Client** for [MSG91 APIs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139). Before using it, please make sure you have an account on [MSG91](https://msg91.com/).

**Table of Content**

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
  - [Create a Client](#create-a-client)
  - [Managing OTPs](#managing-otps)
    - [Send OTP](#send-otp)
    - [Verify OTP](#verify-otp)
    - [Resend OTP](#resend-otp)

> **CAUTION**: The project is under active development and so, some apis are subjected to change before `v1.0.0` release.

## Installation

```bash
composer require creaftsys/msg91-php
```

## Usage

If you're using Composer, make sure the autoloader is included in your project's bootstrap file:

```php
require_once "vendor/autoload.php";
```

Login into your MSG91 account and retrieve the authentication token and create a client by passing the token to the constructor.

```php
$client = new Craftsys\MSG91($TOKEN);
```

Next, follow along with [examples](#examples) to learn more

## Examples

### Create a Client

The client is responsible for interacting with MSG91 apis.

```php
$client =  new Craftsys\MSG91($TOKEN);
```

Client can also be initialised without a token which can be set by calling `setToken($token)` method on the client instance.

```php
$client =  new Craftsys\MSG91();
$client->setToken($TOKEN);
```

We can also pass a custom `GuzzleHttp\Client` as the second argument on the Client's constructor.

```php
$client = new Craftsys\MSG91($token, new GuzzleHttp\Client());
```

### Managing OTPs

OTP services like sending, verifying, and resending etc, can be accessed via `otp` method on the client instance e.g. `$client->otp()`. OTP service provides a fluent API for sending. All the methods except `send|verify|resend`, return
the service instance so your are free to chain methods in any order. All the parameters which are available for the MSG91 API, have a corresponding intuitive method name e.g. to set the country for the send OTP request, we call the `country` method on the service.

#### Send OTP

> **Phone Number** and **Country** are required fields to send an OTP.

- Basic Usage

```php
$otp = $client->otp();
// an OTP (optional) can be passed to service
// $otp = $client->otp(4657);

$otp->to(912343434312) // phone number with country code
	->country(91) // country code of the phone number
	->send(); // Finally, Send
```

- Advance Usage

Instead of relying on defaults from the MSG91 or the client, you can pass all the custom options that are accepted by MSG91 APIs.

```php
$otp->to(91123123123)
	->country(91)
	->digits(6) // set the number of digits in generated otp
	->message("##OTP## is your verification code") // custom template
	->from("MYSMS") // sender
	->expiresInMinutes(60) // set the expiry
	->send()
```

**NOTE**: If you are generating the OTP at your side, and passing it to the service, along with a custom message, you MUST include the `##OTP##` or actual value of OTP inside the message. Failing to do so will result in error

```php
// OK
$client->otp(123242)->message('Your OTP is: 123242')->send();

// NOT OK!
$client->otp(123123)->message("Use this for verification")->send();
// This will result in error with "Message doesn't contain otp"
```

### Verify OTP

As the verification does not send any messages, we just need to provide the required fields to verify the OTP e.g. the sent OTP, Phone Number and Country.

```php
$otp = $client->otp(12345);  // OTP to be verified

$otp->to(912343434312) // phone number with country code
	->country(91) // country code of the phone number
	->verify(); // Verify
```

### Resend OTP

If there are any network/internal issue because of which user did not receive the OTP within a given time period (e.g. within 60 secs), we can provide a resend option which sends another OTP request to the Phone Number. While resending OTP, we can change the way user receives the OTP with `via` method. This method accepts a string from one of `text` or `voice` for the communication of this OTP. The default value is `text`

```php
$otp = $client->otp();

$otp->to(912343434312) // set the mobile with country code
	->country(91) // set the country code
	->via("text") // way of retry
	->resend(); // resend otp
```
