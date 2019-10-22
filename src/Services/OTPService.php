<?php

namespace Craftsys\Msg91\Services;

use Craftsys\Msg91\Requests\SendOTPRequest;
use Craftsys\Msg91\Requests\VerifyOTPRequest;
use Craftsys\Msg91\Requests\ResendOTPRequest;

class OTPService extends Service
{
    protected function updateOptionsWithPayload($payload = null)
    {
        if (is_int($payload)) {
            $this->options->otp($payload);
        } else {
            parent::updateOptionsWithPayload($payload);
        }
    }

    /**
     * Set the receipients of the message
     * @param int|null $mobile - receipient's mobile number
     * @return $this
     */
    public function to($mobile = null)
    {
        $this->options->mobile($mobile);
        return $this;
    }

    /**
     * Set the method of resending OTP to "text"
     * @return $this
     */
    public function viaText()
    {
        return $this->method("text");
    }

    /**
     * Set the method of resending OTP to "voice"
     * @return $this
     */
    public function viaVoice()
    {
        return $this->method("voice");
    }

    /**
     * Set the method when resending the otp
     * @param string|null $method
     * @return $this
     */
    protected function method($method = '')
    {
        $this->options->method($method);
        return $this;
    }

    /**
     * Send otp
     * @return \Craftsys\Msg91\Response
     */
    public function send()
    {
        return $this->sendRequest(SendOTPRequest::class);
    }

    public function verify()
    {
        return $this->sendRequest(VerifyOTPRequest::class);
    }

    public function resend()
    {
        return $this->sendRequest(ResendOTPRequest::class);
    }
}
