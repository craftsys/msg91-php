<?php

namespace Craftsys\MSG91Client;

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

    /**
     * Set the message to be send
     */
    public function message(string $message): self
    {
        return $this->setPayloadFor(static::MESSAGE_KEY, $message);
    }
}
