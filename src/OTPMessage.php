<?php

namespace Craftsys\MSG91;

class OTPMessage extends BaseMessage
{

    /**
     * Keys for payload
     */
    const MOBILE_KEY = "mobile";
    const OTP_KEY = "otp";

    public function __construct(?int $otp = null)
    {
        $this->options([
            self::COUNTRY_KEY => 91,
            self::MESSAGE_KEY => "Your OTP is ##OTP##"
        ]);
        if ($otp) {
            $this->otp($otp);
        }
    }

    /**
     * Receipient's phone number with country code
     */
    public function to($mobile_no): self
    {
        return $this->setPayloadFor(
            static::MOBILE_KEY,
            $this->formatMobileNumber($mobile_no)
        );
    }


    /**
     * Set the OTP
     */
    public function otp($otp): self
    {
        $this->setPayloadFor(self::OTP_KEY, $otp);
        // if we have an otp
        // update the message payload to include the OTP
        if ($this->getPayloadFor(self::MESSAGE_KEY) && $otp) {
            $this->setPayloadFor(
                self::MESSAGE_KEY,
                preg_replace('/##OTP##/', "{$otp}", $this->getPayloadFor(self::MESSAGE_KEY))
            );
        }
        return $this;
    }

    public function send(): ?Response
    {
        return $this->sendRequest(URLs::OTP_URL);
    }

    public function verify(): ?Response
    {
        return $this->sendRequest(URLs::OTP_VERIFY_URL);
    }
}
