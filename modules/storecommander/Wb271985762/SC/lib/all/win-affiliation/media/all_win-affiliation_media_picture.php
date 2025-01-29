<?php
if (!defined('STORE_COMMANDER')) { exit; }
$url = (Tools::getValue('url', ''));
if (empty($url))
{
    exit();
}
$dir = _PS_IMG_.'/banner/';
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<title>SC - Affiliation</title>
</head>
<body>
<div style="text-align: center;"><img src="<?php echo $dir.$url; ?>" /></div>
</body>
</html>