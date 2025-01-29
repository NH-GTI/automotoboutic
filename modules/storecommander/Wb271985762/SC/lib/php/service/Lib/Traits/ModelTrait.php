<?php

namespace Sc\Service\Lib\Traits;

use Exception;

trait ModelTrait
{
    /**
     * @param $index
     *
     * @return array|bool|mixed
     */
    public static function getDefinition($index = null)
    {
        if (!isset(self::$definition))
        {
            throw new Exception('Class'.self::class.' needs public static $definition = []');
        }
        if ($index)
        {
            return isset(self::$definition[$index]) ? self::$definition[$index] : null;
        }

        return self::$definition;
    }
}
