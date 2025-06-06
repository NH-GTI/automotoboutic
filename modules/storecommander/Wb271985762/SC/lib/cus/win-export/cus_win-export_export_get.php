<?php
if (!defined('STORE_COMMANDER')) { exit; }

$data = ExportCustomer::getExportList();
$filters = ExportCustomerFilter::getFilterList();
$filters[] = array(
    ExportCustomerFilter::$definition['primary'] => 0,
    'name' => '--'
);
$mappings = ExportCustomerMapping::getMappingList();
$mappings[] = array(
    ExportCustomerMapping::$definition['primary'] => 0,
    'name' => '--'
);
$languageOptions = Language::getLanguages(false);
$xml = array();
if ($data)
{
    foreach ($data as $row)
    {
        $row_xml = array();
        $row_xml[] = '<cell>'.(int) $row[ExportCustomer::$definition['primary']].'</cell>';
        $row_xml[] = '<cell>'.(int) $row[ExportCustomerFilter::$definition['primary']].'</cell>';
        $row_xml[] = '<cell>'.(int) $row[ExportCustomerMapping::$definition['primary']].'</cell>';
        $row_xml[] = '<cell>'.(int) $row['id_lang'].'</cell>';
        $row_xml[] = '<cell><![CDATA['.$row['filename'].']]></cell>';
        $row_xml[] = '<cell><![CDATA['.$row['date_last_export'].']]></cell>';
        $xml[] = '<row id="'.(int) $row[ExportCustomer::$definition['primary']].'">'.implode("\r\n\t", $row_xml).'</row>';
    }
}
$xml = implode("\r\n", $xml);

if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
{
    header('Content-type: application/xhtml+xml');
}
else
{
    header('Content-type: text/xml');
}
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<rows id="0">
    <head>
        <afterInit>
            <call command="attachHeader">
                <param><![CDATA[#numeric_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter]]></param>
            </call>
        </afterInit>
        <column id="<?php echo ExportCustomer::$definition['primary']; ?>" width="40" type="ro" align="left" sort="int"><?php echo _l('ID'); ?></column>
        <column id="<?php echo ExportCustomerFilter::$definition['primary']; ?>" width="200" type="coro" align="left" sort="str"><?php echo _l('Filters'); ?>
        <?php
            foreach ($filters as $filter)
            {
                echo '<option value="'.(int)$filter[ExportCustomerFilter::$definition['primary']].'"><![CDATA['.$filter['name'].']]></option>'."\n";
            }
        ?>
        </column>
        <column id="<?php echo ExportCustomerMapping::$definition['primary']; ?>" width="200" type="coro" align="left" sort="str"><?php echo _l('Templates'); ?>
        <?php
            foreach ($mappings as $mapping)
            {
                echo '<option value="'.(int)$mapping[ExportCustomerMapping::$definition['primary']].'"><![CDATA['.$mapping['name'].']]></option>'."\n";
            }
        ?>
        </column>
        <column id="id_lang" width="60" type="coro" align="center" sort="str"><?php echo _l('Lang'); ?>
        <?php
            foreach ($languageOptions as $lang)
            {
                echo '<option value="'.(int)$lang['id_lang'].'"><![CDATA['.strtoupper($lang['iso_code']).']]></option>'."\n";
            }
        ?>
        </column>
        <column id="filename" width="150" type="ed" align="left" sort="str"><?php echo _l('Filename'); ?> (.csv)</column>
        <column id="date_last_export" width="150" type="ro" align="left" sort="str"><?php echo _l('Date last export'); ?></column>
    </head>
    <?php
    echo $xml;
    ?>
</rows>