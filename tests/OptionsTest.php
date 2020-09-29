<?php

namespace Craftsys\Tests\Msg91;

use Craftsys\Msg91\Options as AbstractOptions;

class OptionsTest extends TestCase
{
    public function test_can_merge_with_a_string()
    {
        $options = (new Options)->mergeWith('string');
        $this->assertEquals('string', $options->getPayloadForKey('message'));
    }

    public function test_can_merge_with_another_options()
    {
        $options = (new Options)->mergeWith((new Options())->from('this'));
        $this->assertEquals('this', $options->getPayloadForKey('sender'));
    }

    public function test_be_merged_with_a_closure()
    {
        $options = (new Options)->mergeWith(function ($options) {
            $options->from('this');
        });
        $this->assertEquals('this', $options->getPayloadForKey('sender'));
    }

    public function test_can_merge_an_array()
    {
        $options = (new Options)->mergeWith(['template_id' => 'this']);
        $this->assertEquals('this', $options->getPayloadForKey('template_id'));
    }
}

class Options extends AbstractOptions
{
}
