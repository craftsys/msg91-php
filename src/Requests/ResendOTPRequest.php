<?php

namespace Craftsys\Msg91\Requests;


class ResendOTPRequest extends Request
{
    protected $url = URLs::OTP_RESEND_URL;
}
