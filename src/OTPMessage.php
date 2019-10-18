<?php

namespace Craftsys\MSG91;

class OTPMessage extends BaseMessage
{

    /**
     * Keys for payload
     */
    const MOBILE_KEY = "mobile";
    const OTP_KEY = "otp";
    const OTP_LENGTH_KEY = "otp_length";
    const OTP_EXPIRY_KEY = "otp_expiry";
    const VIA_KEY = "retrytype";

    public function __construct(?int $otp = null)
    {
        $this->options([
            self::COUNTRY_KEY => 91,
            self::MESSAGE_KEY => "Your OTP is ##OTP##",
            self::VIA_KEY => "text",
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
        // update the message payload to include the OTP if needed
        $this->formatMessage();
        return $this;
    }

    /**
     * Set the message template for the OTP
     */
    public function message(string $template): self
    {
        $this->setPayloadFor(self::MESSAGE_KEY, $template);
        $this->formatMessage();
        return $this;
    }

    /**
     * Number of digits in otp
     */
    public function digits(int $length)
    {
        return $this->setPayloadFor(self::OTP_LENGTH_KEY, $length);
    }

    /**
     * Set the expiry of otp
     */
    public function expiresInMinutes(int $minutes)
    {
        return $this->setPayloadFor(self::OTP_EXPIRY_KEY, $minutes);
    }

    protected function formatMessage()
    {
        $otp = $this->getPayloadFor(self::OTP_KEY);
        $template = $this->getPayloadFor(self::MESSAGE_KEY);
        if ($template && $otp) {
            $this->setPayloadFor(
                self::MESSAGE_KEY,
                preg_replace('/##OTP##/', "{$otp}", $this->getPayloadFor(self::MESSAGE_KEY))
            );
        }
        return $this;
    }

    /**
     * Set the resend otp method type
     */
    public function via(string $via)
    {
        return $this->setPayloadFor(self::VIA_KEY, $via);
    }


    public function send(): ?Response
    {
        return $this->sendRequest(URLs::OTP_URL);
    }

    public function verify(): ?Response
    {
        return $this->sendRequest(URLs::OTP_VERIFY_URL);
    }

    public function resend(): ?Response
    {
        return $this->sendRequest(URLs::OTP_VERIFY_URL);
    }
}
