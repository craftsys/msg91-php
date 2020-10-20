<?php

namespace Craftsys\Msg91\OTP;

use Craftsys\Msg91\Support\Request;
use Craftsys\Msg91\URLs;

class VerifyRequest extends Request
{
    protected $url = URLs::OTP_VERIFY_URL;

    protected $content_type = \GuzzleHttp\RequestOptions::FORM_PARAMS;
}
