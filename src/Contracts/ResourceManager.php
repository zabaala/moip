<?php

namespace Zabaala\Moip\Contracts;

use stdClass;

interface ResourceManager
{
    /**
     * Mount information of a determined object.
     *
     * @param \stdClass $response
     *
     * @return mixed
     */
    public function populate(stdClass $response);
}