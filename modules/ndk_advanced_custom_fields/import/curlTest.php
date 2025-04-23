<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');
require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/import/ndkCfImporter.php';

$base_uri = (((Configuration::get('PS_SSL_ENABLED') == 1) && (Configuration::get('PS_SSL_ENABLED_EVERYWHERE') == 1)) ? 'https://' : 'http://' )
    . Tools::getShopDomain()
    . __PS_BASE_URI__
    . str_replace(_PS_ROOT_DIR_ . '/', '', __FILE__)
    . '?ts=' . microtime(true);
    

//die($base_uri);
/*
$pid = getmypid();
exec('pkill -9 php');die();
*/
$run = Tools::getValue('run') == 1;

if(!$run)
{
	$start = 0;
	$limit = 10;
}



if((int)$start >= 0 && (int)$start < 40)
{
	$start = followLink($base_uri, $start, $limit);
	var_dump($start);
	
}
else
{
	exit('finish');
}




$datas = array();

if($run)
{
	//require_once $serviceFile;
	runDatas();
}


function runDatas()
{
	$total_size = 40;
	$n = 0;
	$start = (int)Tools::getValue('start', 0);
	$limit = (int)Tools::getValue('limit', 10);	
			
	for($i = 0; $i <= $total_size; $i++ )
	{
		if($i >= $start && $i < $start+$limit)
		{
			$n++;
		}
	}
	//die($n);
	if($n >= $total_size)
	{
		//return false;
		print(-1);
	}
	else
	{
		//return $n;
		print($n);
	}
		
			
}

function followLink($link, $timeout = 4, $start = 0, $limit = 10) {
	    $link.='?run=1'
	    .'&debug='.Tools::getValue('debug')
	    .'&start='.$start
	    .'&limit='.$limit;
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $link);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $start+$limit;

}


//$datas = ndkCfImporter::buildDatas($datas);
//var_dump($datas);