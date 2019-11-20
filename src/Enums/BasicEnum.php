<?php

namespace App\Enums;

/**
 * Class AbstractBasicEnum.
 */
abstract class BasicEnum
{
    /**
     * @var array
     */
    private static $constCacheArray = [];

    /**
     * @throws \ReflectionException
     */
    public static function getConstants()
    {
        $calledClass = \get_called_class();
        if (!\array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }

    /**
     * @param $name
     * @param bool $strict
     *
     * @return bool
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if ($strict) {
            return \array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));

        return \in_array(strtolower($name), $keys, true);
    }

    /**
     * @param $value
     * @param bool $strict
     *
     * @return bool
     */
    public static function isValidValue($value, $strict = true)
    {
        $values = array_values(self::getConstants());

        return \in_array($value, $values, $strict);
    }
}
