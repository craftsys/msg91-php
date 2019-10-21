<?php

namespace Craftsys\Msg91\Requests;


class SendOTPRequest extends Request
{
    protected $method = "POST";

    protected $url = URLs::OTP_URL;

    protected function getPayload(): array
    {
        return $this->options->getPayload();
    }
}
