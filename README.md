# PHP Client Library for MSG91

_This library requires a minimum PHP version of 7.1_

This is a **PHP Client** for [MSG91 APIs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139). Before using it, please make sure you have an account on [MSG91](https://msg91.com/) and have an **Authkey** (MSG91 Dashboard > API > Configure).

> **NOTE**: The project is under active development and so, some apis are subjected to change before of `v1.0.0` release.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Examples](#examples)
  - [Create a Client](#create-a-client)
  - [Managing OTPs](#managing-otps)
    - [Send OTP](#send-otp)
    - [Verify OTP](#verify-otp)
    - [Resend OTP](#resend-otp)

## Installation

The packages is available on [Packagist](https://packagist.org/packages/craftsys/msg91-php) and can be installed via [Composer](https://getcomposer.org/) by executing following command in shell.

```bash
composer require craftsys/msg91-php
```

## Configuration

The module is configurable to your specific needs where you need to set default options for APIs like the default country, OTP message format etc.

An example configuration might look something like this:

```php
$config = [
	'key' => "123456789012345678901234",
	'otp_message' => "G: ##OTP## is your verification code".
];
```

Following configuration options are available:

| Option      | Type                   | Description                                                                                                                                                                     | Default Value       |
| :---------- | :--------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | :------------------ |
| key         | string                 | The authentication key for MSG91 apis (**required**)                                                                                                                            | null                |
| otp_message | ?string                | Message template used when an OTP is sent. The **##OTP##** placeholder is required                                                                                              | Your OTP is ##OTP## |
| retry_via   | ?enum("text", "voice") | Default method when resending an OTP when previous attempt of OTP send/verification failed. It can take one of "text" or "voice" values.                                        | text                |
| country     | ?integer               | The country code when sending any requests. It's default value is set to **null** as using an internationalised phone number format (which includes country code) is preferred. | null                |
| from        | ?string                | The default name for sender. This is used the **From** in messaging applications. It's value can only contain alphanumeric values.                                              | null                |
| otp_length  | ?number                | Length of the generated OTP by MSG91 api when you are not generating OTPs on our end.. This can be between [4, 9]                                                               | 4                   |
| otp_expiry  | ?number                | Duration (in minutes) for which the OTP is valid.                                                                                                                               | 5                   |

**NOTE**: Setting any if these values as null will override the default values to null too. And so, the default values from MSG91 APIs will be used. For example, setting the `otp_message` to `null` will let use "Your verification code is ##OTP##" which the default from [APIS](https://docs.msg91.com/collection/msg91-api-integration/5/send-otp-message/TZ6HN0YI)

## Usage

If you're using Composer, make sure the autoloader is included in your project's bootstrap file:

```php
require_once "vendor/autoload.php";
```

Once you have [Configured](#configuration), client can be initialised by passing a configuration object to the constructor. The configuration in the constructor is optional.

```php
$config = [
	'key' => "123456789012345678901234",
];
$client = new Craftsys\MSG91Client\Client($config);
```

The package in distributed under `Craftsys\MSG91Client` namespace which can used if your are working in a namespace environment.

```php
<?php
// in your use statement sections
use Craftsys\MSG91Client\Client;

// somewhere in this source file where you need the client
$client = new Client();
```

Next, follow along with [examples](#examples) to learn more

## Examples

### Create a Client

The client is responsible for interacting with MSG91 apis.

```php
$client =  new Craftsys\MSG91Client\Client($config);
```

Client can also be initialised without a configuration which can be set by calling `setConfig($config)` method on the client instance.

```php
$client =  new Craftsys\MSG91Client\Client();
$client->setConfig($config);
```

**NOTE**: Configuration must be set before using any other services on the client.

You can also pass a custom `GuzzleHttp\Client` as the second argument on the Client's constructor.

```php
$client = new Craftsys\MSG91Client\Client($config, new GuzzleHttp\Client());
```

### Managing OTPs

OTP services like sending, verifying, and resending etc, can be accessed via `otp` method on the client instance e.g. `$client->otp()`. OTP service provides a fluent API for sending. All the methods except `send|verify|resend`, return
the service instance so your are free to chain methods in any order. All the parameters which are available for the MSG91 API, have a corresponding intuitive method name e.g. to set the country for the send OTP request, you call the `country` method on the service.

#### Send OTP

**Basic Usage**

```php
$otp = $client->otp();
// an OTP (optional) can be passed to service if you are generating otp on your end
// $otp = $client->otp(4657);

$otp->to(912343434312) // phone number with country code
	->send(); // send the otp
```

**Advanced Usage**

Instead of relying on defaults from the MSG91 or the client, you can pass all the custom options that are accepted by MSG91 APIs.

```php
$otp->to(91123123123)
	->digits(6) // set the number of digits in generated otp
	->message("##OTP## is your verification code") // custom template
	->from("MYSMS") // sender
	->expiresInMinutes(60) // set the expiry
	->send()
```

> Country code is required, either by including country code into the phone number or by passing it using `->country` method.

**NOTE**: If you are generating the OTP at your side, and passing it to the service, along with a custom message, you MUST include the `##OTP##` or actual value of OTP inside the message. Failing to do so will result in error

```php
// OK
$client->otp(123242)->message('Your OTP is: 123242')->send();

// NOT OK!
$client->otp(123123)->message("Use this for verification")->send();
// This will result in error with "Message doesn't contain otp"
```

### Verify OTP

As the verification does not send any messages, you just need to provide the required fields to verify the OTP e.g. the sent OTP and Phone Number only.

```php
$otp = $client->otp(12345);  // OTP to be verified

$otp->to(912343434312) // phone number with country code
	->verify(); // Verify
```

### Resend OTP

If there are any network/internal issue because of which user did not receive the OTP within a given time period (e.g. within 60 secs), you can provide a resend option which sends another OTP request to the Phone Number. While resending OTP, you can change the way user receives the OTP with `via` method. This method accepts a string from one of `text` or `voice` for the communication of this OTP. The default value is `text`

```php
$otp = $client->otp();

$otp->to(912343434312) // set the mobile with country code
	->via("text") // way of retry
	->resend(); // resend otp
```
