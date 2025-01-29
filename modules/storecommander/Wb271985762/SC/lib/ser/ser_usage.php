<?php
if (!defined('STORE_COMMANDER')) { exit; }

$snapshot = Tools::getValue('snapshot', null);
$act = Tools::getValue('act', null);

try
{
    $snapshotDecoded = json_decode($snapshot, true);
}
catch (Exception $e)
{
    exit;
}

if ($act == 'ser_usage' && !empty($snapshotDecoded))
{
    $usages = new Sc\Service\Usage();
    $usages->save($snapshotDecoded);
}