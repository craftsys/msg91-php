<?php

namespace Craftsys\MSG91;

class Message extends BaseMessage
{

    const MOBILES_KEY = "mobiles";

    /**
     * Payload for the message e.g. text, mobile number etc
     * @var array
     */
    protected $payload = [];

    public function __construct(string $message = "")
    {
        $this->options([
            self::SENDER_KEY => "TP-SMS",
            self::COUNTRY_KEY => 91,
            self::MESSAGE_KEY => $message,
        ]);
    }

    /**
     * Receipient's phone number
     */
    public function to($mobile_no): self
    {
        return $this->setPayloadFor(
            static::MOBILES_KEY,
            $this->formatMobileNumber($mobile_no)
        );
    }
}
