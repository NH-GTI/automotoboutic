<?php
if (!defined('STORE_COMMANDER')) { exit; }

    if (!defined('SC_DIR'))
    {
        exit;
    }

    if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
    {
        dirEmpty(_PS_CACHE_DIR_.'smarty/cache/', _PS_CACHE_DIR_.'smarty/cache/', array('index.php'));
        dirEmpty(_PS_CACHE_DIR_.'smarty/compile/', _PS_CACHE_DIR_.'smarty/compile/', array('index.php'));
    }
    else
    {
        dirEmpty(_PS_SMARTY_DIR_.'cache', _PS_SMARTY_DIR_.'cache', array('index.php'));
        dirEmpty(_PS_SMARTY_DIR_.'compile', _PS_SMARTY_DIR_.'compile', array('index.php'));
    }

    echo 'Ok';
