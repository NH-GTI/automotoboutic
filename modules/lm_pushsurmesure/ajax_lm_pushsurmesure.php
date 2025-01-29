<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('lm_pushsurmesure.php');

$home_pushr = new lm_pushsurmesure();
$pushs = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $home_pushr->secure_key || !Tools::getValue('action'))
	die(1);

if (Tools::getValue('action') == 'updatePushsPosition' && Tools::getValue('pushs'))
{
	$pushs = Tools::getValue('pushs');

	foreach ($pushs as $position => $id_push)
		$res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'lm_pushsurmesure_pushs` SET `position` = '.(int)$position.'
			WHERE `id_push` = '.(int)$id_push
		);

	$home_pushr->clearCache();
}
