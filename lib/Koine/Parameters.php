<?php

namespace Koine;

use Koine\Parameters\ParameterMissingException;
use Koine\Parameters\UnpermittedParameterException;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class Parameters extends Hash
{
    /**
     * If new created params should throw exceptions or ignore unpermitted params
     * @var boolean
     */
    public static $throwExceptions = true;

    /**
     * If should throw exceptions or ignore unpermitted params
     * @var boolean
     */
    protected $throw = true;

    /**
     * @param array $params
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
    }

    /**
     * Makes sure a parameter was passed
     *
     * @param  string                    $key the parameter key
     * @return Parameters
     * @throws ParameterMissingException when parameter is missing
     */
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

    /**
     * Filters unwanted params
     * @param  array                         $permittedParams
     * @return Parameters
     * @throws UnpermittedParameterException when parameters are set to throw
     *                                                       exception on unpermitted params
     */
    public function permit(array $permittedParams)
    {
        $params = clone $this;

        foreach ($params as $key => $value) {
            if (!in_array($key, $permittedParams)) {
                if ($this->getThrowExceptions()) {
                    throw new UnpermittedParameterException(
                        "Parameter '$key' is not allowed"
                    );
                } else {
                    $params->delete($key);
                }
            }
        }

        return $params;
    }

    /**
     * Get the flag throw
     * @return boolean
     */
    public function getThrowExceptions()
    {
        return self::$throwExceptions;
    }
}
