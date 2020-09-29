<?php

namespace Craftsys\Msg91\OTP;

use Craftsys\Msg91\Config;
use Craftsys\Msg91\Options as Msg91Options;

class Options extends Msg91Options
{
    /**
     * Construct a new options
     * @param mixed $options - initial payload of the message
     * @return void
     */
    public function __construct($options = null)
    {
        if (is_int($options)) {
            $this->otp($options);
        } else {
            $this->mergeWith($options);
        }
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
     * Set the receipient of the otp
     * @param int|null $mobile - receipient's mobile number
     * @return $this
     */
    public function to($mobile = null)
    {
        $this->mobile($mobile);
        return $this;
    }

    /**
     * Set the template for OTP
     * You can get/create your template id from MSG91 Panel
     */
    public function template($template_id)
    {
        $this->setPayloadFor('template_id', $template_id);
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

    public function mergeWith($options = null)
    {
        if (is_int($options)) {
            $this->otp($options);
        } else {
            parent::mergeWith($options);
        }
        return $this;
    }

    /**
     * Resolve the configuration options
     */
    public function resolveConfig(Config $config)
    {
        return $this
            ->key($config->get('key'))
            ->message($config->get('otp_message'))
            ->method($config->get('resend_otp_method'))
            ->from($config->get('from'))
            ->digits($config->get('otp_length'))
            ->expiresInMinutes($config->get('otp_expiry'));
    }
}
