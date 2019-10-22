<?php

namespace Craftsys\Msg91\Requests;


class VerifyOTPRequest extends Request
{
    protected $url = URLs::OTP_VERIFY_URL;
}
