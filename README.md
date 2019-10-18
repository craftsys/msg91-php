# MSG91 PHP Client

This is a **PHP Client** for [MSG91 APIs](https://docs.msg91.com/collection/msg91-api-integration/5/pages/139).

**CAUTION**: This Project is still under development and apis are subjected to change before `v1.0.0` release.

## Installation

```bash
composer require creaftsys/msg91-php
```

## Usage

### Create a Client

```php
// initialize the client
$client =  new MSG91Client(string $token, GuzzleHttp\Client $httpClient);
// $token and $httpClient are optional
// e.g. $client = new MSG91Client();
// token can be provided at a later stage
// $client->setToken($token);
// $httpClient will be automatically created if none provided
```

### Managing OTPs

#### Send OTP

```php
$client->otp(12345) // set the OTP. OTP parameter is optional
	->to(912343434312) // set the mobile with country code
	->country(91) // set the country code
	->send(); // send the otp
```

### Verify OTP

```php
$client->otp(12345) // set the OTP that should be verified
	->to(912343434312) // set the mobile with country code
	->country(91) // set the country code
	->verify(); // verify this otp
```

### Resend OTP

```php
$client->otp()
	->to(912343434312) // set the mobile with country code
	->country(91) // set the country code
	->via("text") // way of retry: "text" | "voice"
	->resend(); // resend otp
```
