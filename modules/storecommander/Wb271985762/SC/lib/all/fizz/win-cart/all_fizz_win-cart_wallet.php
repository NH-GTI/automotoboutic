<?php
if (!defined('STORE_COMMANDER')) { exit; }
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Store Commander</title>
    <link rel="stylesheet" href="lib/css/style.css">
    <style>
        body {
            line-height: 27px;
            font-weight: normal;
            font-family: Tahoma;
            font-size: 12px;
            color: #000000;
        }
    </style>
</head>
<body>
<img src="lib/img/fizz_big.png" alt="Fizz" title="Fizz" width="60px" style="float: left; margin-right: 10px;"/>
<div style="text-align: center;"><?php echo _l('Your wallet'); ?><br/>
<strong style="font-size: 20px;"><?php
    $amount = getWallet();
    if (empty($amount))
    {
        $amount = 0;
    }
    $amount = floor($amount);
    echo $amount;
?> Fizz</strong></div>
</body>
</html>