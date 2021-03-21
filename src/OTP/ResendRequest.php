<?php

namespace Craftsys\Msg91\OTP;

use Craftsys\Msg91\Support\Request;
use Craftsys\Msg91\URLs;
use GuzzleHttp\RequestOptions;

class ResendRequest extends Request
{
    protected $url = URLs::OTP_RESEND_URL;

    protected $content_type = RequestOptions::FORM_PARAMS;
}
