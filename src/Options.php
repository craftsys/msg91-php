<?php

namespace Craftsys\Msg91;

use Closure;

class Options
{
    /**
     * Payload for the message
     * @var array
     */
    protected $payload = [];

    /**
     * Construct a new Msg91Message
     * @param mixed $options - initial payload of the message
     * @return void
     */
    public function __construct($options = null)
    {
        $this->mergeWith($options);
    }

    /**
     * Set the authkey
     * @param string|null $key
     * @return $this
     */
    public function key($key = null)
    {
        $this->setPayloadFor('authkey', $key);
        return $this;
    }

    /**
     * Set method for the message ("text" | "voice")
     * Only usefull for otp retry
     *
     * @param string|null $via
     * @return $this
     */
    public function method($via = null)
    {
        $this->setPayloadFor('retrytype', $via);
        return $this;
    }

    /**
     * Set the sender of the message
     * @param string|null $sender
     * @return $this
     */
    public function from($sender = null)
    {
        $this->setPayloadFor('sender', $sender);
        return $this;
    }

    /**
     * Set the number of digits in otp. Must be in [4,9]
     * @param int|null $otp_digits
     * @return  $this
     */
    public function digits($otp_length = null)
    {
        $this->setPayloadFor('otp_length', $otp_length);
        return $this;
    }

    /**
     * Set the expiry time for the otps in minutes
     * @param int|null $minutes
     * @return $this
     */
    public function expiresInMinutes($minutes = null)
    {
        $this->setPayloadFor('otp_expiry', $minutes);
        return $this;
    }

    /**
     * Set if the message is of transactional type (route = 4)
     *
     * @return $this
     */
    public function transactional()
    {
        $this->route(4);
        return $this;
    }

    /**
     * Set if the message is of promotional type (route = 1)
     *
     * @return $this
     */
    public function promotional()
    {
        $this->route(1);
        return $this;
    }

    /**
     * Set the route for the sms.
     * Use `promotional` or `transactional` instead of your are not sure about route values
     *
     * @param int|null $route
     * @return $this
     */
    public function route($route = null)
    {
        $this->setPayloadFor('route', $route);
        return $this;
    }

    /**
     * Set if the message contains unicode characters
     *
     * @return $this
     */
    public function unicode()
    {
        $this->setPayloadFor('unicode', 1);
        return $this;
    }

    /**
     * Set the receipient of the message, used in OTP apis
     * @param int|null $mobile - receipient's mobile number
     * @return $this
     */
    public function mobile($mobile = null)
    {
        $this->setPayloadFor('mobile', $mobile);
        return $this;
    }

    /**
     * Set the receipients of the message, used in SMS apis
     * @param int|null $mobile - receipient's mobile number(s)
     * @return $this
     */
    public function mobiles($mobiles = null)
    {
        $this->setPayloadFor('mobiles', $mobiles);
        return $this;
    }

    /**
     * Set the receipients of the message
     * @param int|null $mobile - receipient's mobile number
     * @return $this
     */
    public function to($mobile = null)
    {
        $this->mobile($mobile);
        $this->mobiles($mobile);
        return $this;
    }

    /**
     * Set the otp for the message
     * @param int|null $otp
     * @return $this
     */
    public function otp($otp = null)
    {
        $this->setPayloadFor('otp', $otp);
        return $this;
    }

    /**
     * Set the message content (same as message method)
     * @param string|null $message
     * @return $this
     */
    public function content($message = '')
    {
        return $this->message($message);
    }

    /**
     * Set the message content (same as content method)
     * @param string|null $message
     * @return $this
     */
    public function message($message = '')
    {
        $this->setPayloadFor('message', $message);
        return $this;
    }

    /**
     * Set the payload for a given key
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setPayloadFor($key, $value)
    {
        $this->payload[$key] = $value;
        return $this;
    }

    /**
     * Get the payload of the message
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }


    /**
     * Get the options's array
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * Call the given Closure with this instance then return the instance.
     *
     * @param  callable|null  $callback
     * @return $this
     */
    public function tap(Closure $callback = null)
    {
        if ($callback) {
            $callback($this);
        }
        return $this;
    }

    /**
     * Merge this instance with payload
     * @param int|string|$this|null $payload
     * @return $this;
     */
    public function mergeWith($options = null)
    {
        // do which ever results in true
        $current_payload = $this->getPayload();
        switch (true) {
            case $options instanceof self:
            case $options instanceof Msg91Message:
                $this->payload = array_merge($current_payload, $options->getPayload());
                break;
            case is_array($options):
                // if it is an array
                $this->payload = array_merge($current_payload, $options);
                break;
            case is_string($options):
                // if it's a string
                $this->content($options);
                break;
            case $options instanceof Closure:
                // let it mutate if it's a closure
                $this->tap($options);
                break;
        }
        return $this;
    }
}
