<?php


/*
|----------------------------------------------------------------------
| Configuration for Msg91 Client
|----------------------------------------------------------------------
| Set some default values for thenclient. All the defaults can be over
| written at runtime.
*/

return [
    /**
    |--------------------------------------------------------------------------
    | Authentication Token
    |--------------------------------------------------------------------------
    | Authentication token required for any communication with APIs. This can
    | be retrieved from your Msg91's dashboard under the API > configuration
    | section
     */
    'token' => '<your_token_here>',

    /**
    |--------------------------------------------------------------------------
    | OTP Message
    |--------------------------------------------------------------------------
    | This provides a template for OTP messages. ##OTP## placeholder MUST be
    | included in the message where the values of OTP will be substituted.
     */
    "otp_message" => "Your verification code is ##OTP##",

    /**
    |--------------------------------------------------------------------------
    | Retry OTP Method
    |--------------------------------------------------------------------------
    | This sets the default method of communication when attempting to resend
    | the OTP. We have two ways: text or voice.
     */
    "retry_via" => "text",

    /**
    |--------------------------------------------------------------------------
    | Country of Phone Numbers
    |--------------------------------------------------------------------------
    | Country code is required for any kind of communication with the apis. It
    | can be included in the phone number itself (preferred) or by explicitily
    | setting it's value here or at runtime.
     */
    "country" => null,

    /**
    |--------------------------------------------------------------------------
    | Default sender
    |--------------------------------------------------------------------------
    | This values sets the `sender` option in apis which is used as the "From"
    | in messaging applications.
     */
    "from" => null,

    /**
    |--------------------------------------------------------------------------
    | OTP Length
    |--------------------------------------------------------------------------
    | The default length of the OTPs. This can be used when we are not
    | generating OTP on our end and letting the Msg91 generate and
    | store otp for us. This should be between from [4,9]
     */
    "otp_length" => 4,

    /**
    |--------------------------------------------------------------------------
    | OTP Expiry
    |--------------------------------------------------------------------------
    | Expiry time (in minutes) for which the send OTP is valid. The minimum
    | values is 1 minute. Use integer values only.
     */
    "otp_expiry" => 5,
];
