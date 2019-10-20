<?php

namespace Craftsys\Tests\Msg91;

use Craftsys\Msg91\Config;

class ConfigTest extends TestCase
{
    /**
     * Create a configuration for testing
     */
    protected function getConfig($overrides = null): Config
    {
        return new Config($overrides, __DIR__ . "/fixtures/config.php");
    }

    public function test_default_config()
    {
        $config = $this->getConfig();
        $this->assertNotNull($config);
    }


    public function test_get_configuration()
    {
        $config = $this->getConfig();
        $this->assertNotNull($config->get('otp_message'));
    }

    public function test_all_returns_array()
    {
        $config = $this->getConfig();
        $config_items = $config->all();
        $this->assertIsArray($config_items);
    }

    public function test_configuration_values()
    {
        $config = $this->getConfig();
        $config_items = $config->all();
        $this->assertArrayHasKey('otp_message', $config_items);
    }

    public function test_configuration_can_be_overwritten()
    {
        $message =  "New Message Format";
        $config = $this->getConfig([
            'otp_message' => $message
        ]);
        $this->assertEquals($message, $config->get("otp_message"));
    }
}
