<?php

namespace Craftsys\Msg91\SMS;

use Craftsys\Msg91\Config;
use Craftsys\Msg91\Options as Msg91Options;

class Options extends Msg91Options
{
    /**
     * Set the receipient(s) of the sms
     * @param int|array|null $mobile - receipient's mobile number
     * @return $this
     */
    public function to($mobile = null)
    {
        $recipients = $this->transformMobileNumbersToRecipients($mobile);
        $this->recipients($recipients);
        return $this;
    }

    protected function transformMobileNumbersToRecipients($mobile = null): array
    {
        if (!$mobile) return [];
        if (!is_array($mobile)) {
            return [[
                'mobiles' => [$mobile]
            ]];
        }
        return array_map(function ($mobile) {
            return ['mobiles' => [$mobile]];
        }, $mobile);
    }

    /**
     * Set the flow id for sms
     * You can get/create your flow id from MSG91 Panel
     */
    public function flow($flow_id)
    {
        $this->setPayloadFor('flow_id', $flow_id);
        return $this;
    }

    /**
     * Set recipients with mobile numbers and any variables to pass to the template
     */
    public function recipients($recipients = [])
    {
        $this->setPayloadFor('recipients', $recipients);
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
     * Resolve the configuration options
     */
    public function resolveConfig(Config $config): self
    {
        return (new Options)
            ->key($config->get('key'))
            ->tap(function (Options $msg) use ($config) {
                // set the sender id
                if ($config->get('from')) {
                    $msg->from($config->get('from'));
                }
                // set the message route
                if ($config->get('route')) {
                    $msg->route($config->get('route'));
                }
                // set the unicode if it's set to true
                if ($config->get('unicode')) {
                    $msg->unicode();
                }
            });
    }
}
