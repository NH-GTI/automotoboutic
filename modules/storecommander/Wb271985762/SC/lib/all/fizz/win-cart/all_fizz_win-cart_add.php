<?php
if (!defined('STORE_COMMANDER')) { exit; }

$url = 'https://www.storecommander.com/eservices_encart.php?lang='.SC_ISO_LANG_FOR_EXTERNAL.'&lic='.sha1(SCI::getConfigurationValue('SC_LICENSE_KEY'));

$content = file_get_contents($url);

echo $content;
