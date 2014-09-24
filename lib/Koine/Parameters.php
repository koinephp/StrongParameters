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

        if ($this->valueIsEmpty($param)) {
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

        return $this->filter($params, $permittedParams);
    }

    /**
     * Filter out or throws exception according to the permitted params
     * @param  Parameter                     $params
     * @param  array                         $permittedParams
     * @return Parameter
     * @throws UnpermittedParameterException when params not permitted are passed in
     */
    protected function filter($params, array $permittedParams)
    {
        foreach ($params as $key => $value) {
            if (!is_int($key) && array_key_exists($key, $permittedParams)) {
                // handle nested params
            } elseif (!in_array($key, $permittedParams)) {
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
     *
     * @return boolean;
     */
    public function getThrowExceptions()
    {
        return static::$throwExceptions;
    }

    /**
     * Empty Hash or empty array?
     * @return boolean
     */
    protected function valueIsEmpty($value)
    {
        return (
            is_object($value) &&
            get_class($value) === 'Koine\Parameters' &&
            $value->isEmpty()
        )
        ||
        (is_array($value) && !count($value));
    }
}
