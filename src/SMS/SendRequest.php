<?php

namespace Craftsys\Msg91\SMS;

use Craftsys\Msg91\Support\Request;
use Craftsys\Msg91\URLs;

class SendRequest extends Request
{
    protected $url = URLs::SEND_SMS_URL;

    protected function validate(array $payload)
    {
        parent::validate($payload);
        $flow_id = $payload['flow_id'] ?? "";
        if (!$flow_id) {
            $this->validator->addError('flow_id', 'Please provide a Flow ID for the sms.');
        }
    }
}
