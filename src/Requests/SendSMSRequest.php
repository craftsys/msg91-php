<?php

namespace Craftsys\Msg91\Requests;


class SendSMSRequest extends Request
{
    protected $method = "POST";

    protected $url = URLs::SEND_SMS_URL;

    protected function getPayload(): array
    {
        return $this->options->getPayload();
    }
}
