<?php

namespace Craftsys\Msg91\SMS;

use Craftsys\Msg91\Config;
use Craftsys\Msg91\Options as Msg91Options;

class Options extends Msg91Options
{
    /**
     * Receiver for SMS Flow templates
     * Configurable when create/editing flow on Msg91 panel
     * @var string
     */
    public $receiver_key = "mobiles";

    /**
     * Variable mapping for all the recipients
     * @var array
     */
    protected $variables_mapping = [];

    /**
     * Set the recipient(s) of the sms
     * @param int|array|null $mobile - recipient's mobile number
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
            return [
                ["{$this->receiver_key}" => $mobile]
            ];
        }
        return array_map(function ($mobile) {
            return [
                "{$this->receiver_key}" => $mobile
            ];
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
     * Set the flow id for sms
     * You can get/create your flow id from MSG91 Panel
     */
    public function receiverKey(string $receiver_key = "mobiles")
    {
        // remove any "#" included in key (by mistake)
        $receiver_key = str_replace('#', '', $receiver_key);
        // update the existing recipients
        $existing_recipients = $this->getPayloadForKey('recipients', []);
        if ($existing_recipients && count($existing_recipients) > 0) {
            $recipients = array_map(function ($recipient) use ($receiver_key) {
                if (isset($recipient[$this->receiver_key])) {
                    $recipient = array_merge(
                        [
                            "{$receiver_key}" => $recipient[$this->receiver_key]
                        ],
                        // remove the "$this->receiver_key" from the recipient
                        array_diff_key($recipient, ["{$this->receiver_key}" => 0])
                    );
                }
                return $recipient;
            }, $existing_recipients);
            $this->recipients($recipients);
        }
        $this->receiver_key = $receiver_key;
        return $this;
    }

    /**
     * Set value for a variable (used in the Flow message template) for all recipients
     * @param string|array $name - name of the variable in the template
     * @param string|number|null $value - value for the variable to be placed in template
     * @return $this
     */
    public function variable($name, $value = null)
    {
        $variables = $this->variables_mapping;
        if (is_array($name)) {
            $variables = array_merge($variables, $name);
        } else {
            $variables[$name] = $value;
        }
        // update the existing recipients
        $existing_recipients = $this->getPayloadForKey('recipients', []);
        if ($existing_recipients && count($existing_recipients) > 0) {
            $recipients = array_map(function ($recipient) use ($variables) {
                return array_merge($recipient, $variables);
            }, $existing_recipients);
            $this->recipients($recipients);
        }
        // also store the mapping for future recipients
        $this->variables_mapping = $variables;
        return $this;
    }

    /**
     * Get the variables mapping which was set using `variable` method
     * @return array
     */
    public function getVariableMapping()
    {
        return $this->variables_mapping;
    }

    /**
     * Set recipients with mobile numbers and any variables to pass to the template
     * @param array $recipients This should be an array of recipients with variables
     *  e.g. [['mobiles' => '919999999999', '<var>' => '<value>']]
     */
    public function recipients($recipients = [])
    {
        if (!is_array($recipients) || (count($recipients) > 0 && !is_array($recipients[0]))) {
            // these are mobile number(s)
            // transform them into recipients
            $recipients = $this->transformMobileNumbersToRecipients($recipients);
        }
        // put variables to all new recipients
        $recipients = array_map(function ($recipient) {
            return array_merge($recipient, $this->variables_mapping);
        }, $recipients);
        // set the payload
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

    public function mergeWith($options = null)
    {
        if ($options instanceof self) {
            // merge the payloads
            $existing_recipients = $this->getPayloadForKey('recipients', []);
            $this->payload = array_merge($this->toArray(), $options->toArray());

            // merge the array elements which are replaced by array_merge
            // - recipients
            $this->recipients(array_merge($options->getPayloadForKey('recipients', []), $existing_recipients));

            // set the new receiver key
            $this->receiverKey($options->receiver_key);

            // set the variables
            $this->variable($options->getVariableMapping());
            return $this;
        }
        return parent::mergeWith($options);
    }
}
