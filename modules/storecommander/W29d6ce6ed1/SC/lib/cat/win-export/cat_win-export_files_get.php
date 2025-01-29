<?php
if (!defined('STORE_COMMANDER')) { exit; }

$id_lang = (int) Tools::getValue('id_lang');
$exportConfig = array();

function getFiles()
{
    $csvFilesFound = glob(SC_CSV_EXPORT_DIR.'*.csv');
    if($csvFilesFound)
    {
        foreach($csvFilesFound as $filePath)
        {

            $urlParams = array(
                'source' => 'export',
                'detail' => 'catalog',
                'retrieve' => generateToken($filePath)
            );
            $url = SC_ORK_EXTERNAL_URL.'cron/cron_init.php?'.http_build_query($urlParams).'&'.time();
            echo '<row id="'.basename($filePath).'">';
            echo '<cell><![CDATA[<a href="'.$url.'" target="_blank" style="color: #000000;">'.basename($filePath).'</a>]]></cell>';
            echo '<cell title="'._l('Copy to ClipBoard').'"><![CDATA[<button onclick="copyToClipBoard(\''.$url.'\',\'' . _l('Url successfully copied to clipboard', true). '\')" ><i class="fad fa-copy"></i></button>]]></cell>';
            echo '<cell><![CDATA['.number_format(filesize($filePath) / 1024, 2).']]></cell>';
            echo '<cell><![CDATA['.(date('Y-m-d H:i:s', filemtime($filePath))).']]></cell>';
            echo '</row>';
        }
    }
}

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
        <column id="filename" width="100" type="ro" align="left" sort="str"> <?php echo _l('Filename'); ?></column>
        <column id="share" width="60" type="ro" align="center" sort="str"><?php echo _l('Share'); ?></column>
        <column id="filesize" width="70" type="ro" align="right" sort="int"><?php echo _l('Filesize'); ?> (Ko)</column>
        <column id="date" width="110" type="ro" align="right" sort="str"><?php echo _l('Date'); ?></column>
    </head>
    <?php getFiles(); ?>
</rows>

