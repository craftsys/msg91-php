<?php

namespace Craftsys\Msg91\Requests;


class VerifyOTPRequest extends Request
{
    protected $method = "POST";

    protected $url = URLs::OTP_VERIFY_URL;

    protected function getPayload(): array
    {
        return $this->options->getPayload();
    }
}
