<?php
/**
 * @param $class
 * @return void
 */
function scAutoloader($class)
{
    $classParts = explode('\\', $class);
    if (!isset($classParts[0]) || $classParts[0] != 'Sc')
        return;

    if (isset($classParts[1]) && $classParts[1] === 'Service'){
        array_shift($classParts);
        array_shift($classParts);
        array_unshift($classParts, 'lib/php/service');
    }
    elseif (isset($classParts[1]) || in_array($classParts[1], ['ScProcess', 'ScLogger'])){
        array_shift($classParts);
        array_unshift($classParts, 'shared');
    }
    $className = implode(DIRECTORY_SEPARATOR, $classParts);

    $filePath = realpath(SC_DIR.DIRECTORY_SEPARATOR.$className.'.php');
    if (file_exists($filePath))
    {
        require $filePath;
    }
}
spl_autoload_register('scAutoloader');