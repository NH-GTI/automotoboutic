<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $sql = 'TRUNCATE '._DB_PREFIX_.'storecom_history';
    Db::getInstance()->Execute($sql);
