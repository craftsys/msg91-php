# PHP Client Library for Msg91

_This library requires a minimum PHP version of 7.1_

This is a **PHP Client** for [Msg91 APIs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139). Before using it, please make sure you have an account on [Msg91](https://msg91.com/) and have an **Authkey** (Msg91 Dashboard > API > Configure).

> **NOTE**: The project is under active development and so, some apis are subjected to change before of `v1.0.0` release.

## Table of Contents

-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Examples](#examples)
    -   [Create a Client](#create-a-client)
    -   [Managing OTPs](#managing-otps)
        -   [Send OTP](#send-otp)
        -   [Verify OTP](#verify-otp)
        -   [Resend OTP](#resend-otp)
    -   [Sending SMS](#sending-sms)
    -   [Handling Responses](#handling-responses)
-   [Related](#related)
-   [Acknowledgements](#acknowledgements)

## Installation

The packages is available on [Packagist](https://packagist.org/packages/craftsys/msg91-php) and can be installed via [Composer](https://getcomposer.org/) by executing following command in shell.

```bash
composer require craftsys/msg91-php
```

## Configuration

The module is configurable to your specific needs where you need to set default options for APIs like the default OTP message format, retry method etc.

An example configuration might look something like this:

```php
$config = [
	'key' => "123456789012345678901234",
	'otp_message' => "G: ##OTP## is your verification code".
];
```

Following configuration options are available:

| Option            | Type                   | Description                                                                                                                              | Default Value       |
| :---------------- | :--------------------- | :--------------------------------------------------------------------------------------------------------------------------------------- | :------------------ |
| key               | string                 | The authentication key for Msg91 apis (**required**)                                                                                     | null                |
| otp_message       | ?string                | Message template used when an OTP is sent. The **##OTP##** placeholder is required                                                       | Your OTP is ##OTP## |
| resend_otp_method | ?enum("text", "voice") | Default method when resending an OTP when previous attempt of OTP send/verification failed. It can take one of "text" or "voice" values. | text                |
| from              | ?string                | The default name for sender. This is used the **From** in messaging applications. It's value can only contain alphanumeric values.       | null                |
| otp_length        | ?number                | Length of the generated OTP by Msg91 api when you are not generating OTPs on our end.. This can be between [4, 9]                        | 4                   |
| otp_expiry        | ?number                | Duration (in minutes) for which the OTP is valid.                                                                                        | 5                   |
| route             | ?number                | [Route](https://help.msg91.com/article/64-what-is-the-difference-between-transactional-promotional-and-sendotp-route) for SMS            | null                |
| unicode           | ?number                | Set to this 1 if all your message contains unicode characters                                                                            | 0                   |

**NOTE**: Setting any if these values as null will override the default values to null too. And so, the default values from Msg91 APIs will be used. For example, setting the `otp_message` to `null` will let use "Your verification code is ##OTP##" which the default from [APIS](https://docs.msg91.com/collection/msg91-api-integration/5/send-otp-message/TZ6HN0YI)

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
$client = new Craftsys\Msg91\Client($config);
```

The package in distributed under `Craftsys\Msg91` namespace which can used if your are working in a namespace environment.

```php
<?php
// in your use statement sections
use Craftsys\Msg91\Client;

// somewhere in this source file where you need the client
$client = new Client();
```

Next, follow along with [examples](#examples) to learn more

## Examples

### Create a Client

The client is responsible for interacting with Msg91 apis.

```php
$client =  new Craftsys\Msg91\Client($config);
```

Client can also be initialised without a configuration which can be set by calling `setConfig($config)` method on the client instance.

```php
$client =  new Craftsys\Msg91\Client();
$client->setConfig($config);
```

**NOTE**: Configuration must be set before using any other services on the client.

You can also pass a custom `GuzzleHttp\Client` as the second argument on the Client's constructor.

```php
$client = new Craftsys\Msg91\Client($config, new GuzzleHttp\Client());
```

### Managing OTPs

OTP services like sending, verifying, and resending etc, can be accessed via `otp` method on the client instance e.g. `$client->otp()`. All the parameters which are available for the Msg91 API, have a corresponding intuitive method name e.g. to set the digits in otp for the send OTP request, you call the `digits` method on the service. You can create there via `\Craftsys\Msg91\Options` class. See following examples to learn more.

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

Instead of relying on defaults from the Msg91 or the client, you can pass all the custom options that are accepted by Msg91 APIs using the `options` helper on the service. This method accepts a close which will be called with underline `\Craftsys\Msg91\Options` instance and gives your the full flexibility to add any options that is required.

```php
$otp->to(91123123123)
    ->options(function (\Craftsys\Msg91\Options $options) {
        $options->digits(6) // set the number of digits in generated otp
        ->message("##OTP## is your verification code") // custom template
        ->from("CMPNY") // sender
        ->expiresInMinutes(60); // set the expiry
    })
	->send() // finally send
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

As the verification does not send any messages, you just need to provide the required fields to verify the OTP e.g. the sent OTP and Phone Number only.

```php
$otp = $client->otp(12345);  // OTP to be verified

$otp->to(912343434312) // phone number with country code
	->verify(); // Verify
```

### Resend OTP

To resend an OTP, access to `otp` service and call the `resend` with to resend the OTP. Method of communication can be
changed by calling `viaVoice` or `viaText` before sending the OTP. The default values can be set into the configuration

```php
$otp = $client->otp();

$otp->to(912343434312) // set the mobile with country code
	->viaText() // or ->viaVoice()
	->resend(); // resend otp
```

## Sending SMS

To send SMS, access the `SMSService` by calling `->sms()` method on the client instance

```php
$sms = $client->sms();

$sms->to(912343434312) // set the mobile with country code
	->message("You have 10 pending tasks for the end of the day") // message content
	->send(); // send the message
```

To add any more options to the message, you can call the `options` method before sending the message. The options
method accepts a call which will receive a `\Craftsys\Msg91\Options` instance. Using it, you can modify any desired
option.

```php
$client->sms()
    ->to(919999999999)
    ->options(function ($options) {
        $options->transactional() // set that it is a transactional message
            ->from('CMPNY') // set the sender
            ->unicode(); // handle unicode as the message contains unicode characters
    })
    ->message("I ❤️ this package. Thanks.")
    ->send();
```

## Handling Responses

All the services will return `\Craftsys\Msg91\Response` instance for all successfully responses and will throw
exceptions if
- \Craftsys\Msg91\Exceptions\ValidationException: request validation failed
- \Craftsys\Msg91\Exceptions\ResponseErrorException: there was an error in the response

```php
try {
    $response = $client->otp()->to(919999999999)->send();
    // response data
    // $response->getData();
    // response message
    // $response->getMessage();
    // response status code
    // $response->getStatusCode();
} catch (\Craftsys\Msg91\Exceptions\ValidationException $e) {
    // issue with the request e.g. token not provided
} catch (\Craftsys\Msg91\Exceptions\ResponseErrorException $e) {
    // error thrown by msg91 apis or by http client
} catch (\Exception $e) {
    // something else went wrong
    // please report if this happens :)
}
```

# Related

- [Msg91 Laravel Service Provider](https://github.com/craftsys/msg91-laravel)
- [Msg91 Laravel Notification Channel](https://github.com/craftsys/msg91-laravel-notification-channel)
- [Msg91 Api Docs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139)

# Acknowledgements

We are grateful to the authors of existing related projects for their ideas and collaboration:

- [Nexmo PHP](https://github.com/Nexmo/nexmo-php)
