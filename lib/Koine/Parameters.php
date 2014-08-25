<?php

namespace Koine;

use Koine\Parameters\ParameterMissingException;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class Parameters extends Hash
{
    public function requireParam($key)
    {
        $param = $this->fetch($key, function ($key) {
            throw new ParameterMissingException("Missing param '$key'");
        });

        if (is_object($param) &&
            get_class($param) === 'Koine\Parameters' &&
            $param->isEmpty()
        ) {
            throw new ParameterMissingException("Missing param '$key'");
        }

        return $param;
    }
}
