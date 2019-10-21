<?php

namespace Craftsys\Msg91\Services;

use Craftsys\Msg91\Requests\SendSMSRequest;

class SMSService extends Service
{
    /**
     * Send sms
     * @return \Craftsys\Msg91\Requests\Request
     */
    public function send()
    {
        return $this->sendRequest(SendSMSRequest::class);
    }
}
