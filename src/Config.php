<?php

namespace Craftsys\Msg91;

class Config
{
    /**
     * Configuration items
     * @var array
     */
    protected $items = [];

    /**
     * @param array|null $items - configuration items
     * @param string|null $base_config_path
     */
    public function __construct(array $items = null, string $base_config_path = null)
    {
        $this->items = require($base_config_path ?: __DIR__ . "./../config/msg91.php");
        $this->mergeWith($items);
    }

    /**
     * Merge the exisiting configuration with new one
     */
    protected function mergeWith(array $items = null): self
    {
        if ($items) {
            $this->items = array_merge($this->items, $items);
        }
        return $this;
    }

    /**
     * Get the specified configuration value(s)
     * @param array|string $key
     * @param mixed $default - Config value when no value is set
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }
        return $this->items[$key] ?? $default;
    }

    /**
     * Get many configuration values
     */
    public function getMany(array $keys): array
    {
        $config = [];
        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }
            $config[$key] = $this->items[$key] ?? $default;
        }
        return $config;
    }

    /**
     * Set a given configuration value
     * @param array|string $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value = null): self
    {
        $keys = is_array($key) ? $key : [$key => $value];
        foreach ($keys as $key => $value) {
            $this->items[$key] = $value;
        }
        return $this;
    }

    /**
     * Get Msg91Message instance for this config
     * @return \Craftsys\Msg91\Options
     */
    public function getOptions()
    {
        return (new Options)
            ->key($this->get('key'))
            ->message($this->get('otp_message'))
            ->method($this->get('resend_otp_method'))
            ->from($this->get('from'))
            ->digits($this->get('otp_length'))
            ->expiresInMinutes($this->get('otp_expiry'))
            ->route($this->get('route'))
            ->tap(function (Options $msg) {
                // set the unicode if it's set to true
                if ($this->get('unicode')) {
                    $msg->unicode();
                }
            });
    }

    /**
     * Get all the configuration values
     */
    public function all(): array
    {
        return $this->items;
    }
}
