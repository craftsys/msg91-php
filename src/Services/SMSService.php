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

    /**
     * Set the receipients of the message
     * @param int|null $mobile - receipient's mobile numbers
     * @return $this
     */
    public function to($mobiles = null)
    {
        $this->options->mobiles($mobiles);
        return $this;
    }

    /**
     * Set the message content (same as message method)
     * @param string|null $message
     * @return $this
     */
    public function content($message = '')
    {
        $this->options->content($message);
        return $this;
    }
}
