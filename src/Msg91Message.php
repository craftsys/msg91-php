<?php

namespace Craftsys\Msg91;

use JsonSerializable;


class Msg91Message extends Options implements JsonSerializable
{

    /**
     * Convert message to json
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
