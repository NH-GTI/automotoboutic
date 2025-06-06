<?php
if (!defined('STORE_COMMANDER')) { exit; }

if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
{
    header('Content-type: application/xhtml+xml');
}
else
{
    header('Content-type: text/xml');
}
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<rows>
<head>
<beforeInit>
<call command="attachHeader"><param><![CDATA[,#text_filter]]></param></call>
</beforeInit>
<column id="color" width="260" type="ro" align="left" sort="str"><?php echo _l('Option'); ?></column>
<column id="help" width="*" type="ro" align="left" sort="str"><?php echo _l('Help'); ?></column>
</head>
<row id="0">
    <cell><![CDATA[<?php echo _l('Associate only products using Advanced Stocks (Associate only AS)'); ?>]]></cell>
    <cell><![CDATA[<?php echo _l('Only the selected products using the Advanced Stocks Management (Advanced Stocks + Manual Management) will be associated to the selected warehouses.'); ?>]]></cell>
</row>
<row id="1">
    <cell><![CDATA[<?php echo _l('Enable Advanced Stocks and Associate (Activate AS & Associate)'); ?>]]></cell>
    <cell><![CDATA[<?php echo _l('Advanced Stocks mode (AS) only will be enabled onto all selected products where Advanced Stocks Management is disabled. They will then be associated to the selected warehouses.'); ?>]]></cell>
</row>
<row id="2">
    <cell><![CDATA[<?php echo _l('Enable Advanced Stocks + Manual Management & Associate (Activate AS + GMM & Associate)'); ?>]]></cell>
    <cell><![CDATA[<?php echo _l('Advanced Stocks + Manual Management (AS+MM) only will be enabled onto all selected products where Advanced Stocks Management is disabled. They will then be associated to the selected warehouses.'); ?>]]></cell>
</row>
</rows>