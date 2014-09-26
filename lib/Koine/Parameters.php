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

        $this->filter($params, $permittedParams);

        return $params;
    }

    /**
     * Filter out or throws exception according to the permitted params
     * @param  Parameter                     $params
     * @param  array                         $permitted
     * @throws UnpermittedParameterException when params not permitted are passed in
     */
    public function filter(Parameters $params, array $permitted = array())
    {
        $this->cleanUnwanted($params, $permitted);
        $this->handleArrays($params, $permitted);
        $this->handleCollections($params, $permitted);
    }

    /**
     * Handle Parameters that have only integer indexes
     * @param Parameter $params
     * @param array     $permitted
     */
    private function handleCollections(Parameters $params, array $permitted = array())
    {
        // if is empty, any value is allowed
        if (empty($permitted)) {
            return;
        }

        $keys = $params->keys();
        $intKeys = $keys->select(function ($value) {
            return is_int($value);
        });

        if ($keys->count() === $intKeys->count()) {
            foreach ($keys as $key) {
                $value = $params[$key];

                if ($value instanceof Parameters) {
                    $this->filter($value, $permitted);
                }
            }
        }
    }

    /**
     * Handle permissions that are given in the hash form
     * @param Parameter $params
     * @param array     $permitted
     */
    private function handleArrays(Parameters $params, array $permitted = array())
    {
        foreach ($permitted as $key => $allowed) {
            if (is_array($allowed) && $params->hasKey($key)) {
                $value = $params[$key];

                if ($value instanceof Parameters) {
                    $this->filter($value, $allowed);
                } else {
                    $this->handleUnpermittedParam($key, $params);
                }
            }
        }
    }

    /**
     * Filters out or throws exception when parameters are neigher keys nor values
     * in the permitted array
     * @param  Parameter                 $params
     * @param  array                     $permitted
     * @throws ParameterMissingException when parameter is missing
     */
    private function cleanUnwanted(Parameters $params, $permitted)
    {
        foreach ($params->toArray() as $key => $value) {
            if (!is_int($key) && !in_array($key, $permitted) && !array_key_exists($key, $permitted)) {
                $this->handleUnpermittedParam($key, $params);
            }
        }
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

    /**
     * Handle the unpermitted param either by removing it or throwing an exception
     * @param  string                    $key
     * @param  Parameters                $params
     * @throws ParameterMissingException when parameter is missing
     */
    protected function handleUnpermittedParam($key, $params)
    {
        if ($this->getThrowExceptions()) {
            $message = "Parameter '$key' is not allowed";
            throw new UnpermittedParameterException($message);
        }

        $params->delete($key);
    }
}
