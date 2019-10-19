<?php

namespace Craftsys\MSG91;

use GuzzleHttp\Client as HttpClient;

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

    public function __construct(Config $config, $otp = null, HttpClient $httpClient = null)
    {
        parent::__construct($config, $httpClient);
        if ($otp) {
            $this->otp($otp);
        }
        $this->createPayload();
    }

    protected function createPayload()
    {
        $config = $this->config->getMany(["country", "message", "retry_via", "otp_length", "otp_expiry"]);
        if ($config["country"]) $this->country($config['country']);
        if ($config["message"]) $this->message($config['message']);
        if ($config["retry_via"]) $this->via($config['retry_via']);
        if ($config["otp_length"]) $this->digits($config['otp_length']);
        if ($config["otp_expiry"]) $this->expiresInMinutes($config['otp_expiry']);
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
        return $this->sendRequest(URLs::OTP_RESEND_URL);
    }
}
