<?php
if (!defined('STORE_COMMANDER')) { exit; }

$action = Tools::getValue('action');

if (isset($action) && $action)
{
    switch ($action) {
        case 'mapping_saveas':
            $filename = str_replace('.map.xml', '', basename(Tools::getValue('filename')));
            @unlink(SC_CSV_IMPORT_DIR.$filename.'.map.xml');
            $mapping = Tools::getValue('mapping', '');
            if(empty($mapping)) {
                echo _l('Invalid mapping');
                break;
            }

            $mapping = json_decode($mapping, true);

            $content = '<mapping><id_lang>'.(int) $sc_agent->id_lang.'</id_lang>';
            foreach ($mapping as $map)
            {
                $content .= '<map>';
                $content .= '<csvname><![CDATA['.$map[0].']]></csvname>';
                $content .= '<dbname><![CDATA['.$map[1].']]></dbname>';
                $content .= '<options><![CDATA['.$map[2].']]></options>';
                $content .= '</map>';
            }
            $content .= '</mapping>';

            file_put_contents(SC_CSV_IMPORT_DIR.$filename.'.map.xml', $content);
            echo _l('Data saved!');
            break;
        case 'mapping_delete':
            $filename = str_replace('.map.xml', '', Tools::getValue('filename'));
            @unlink(SC_CSV_IMPORT_DIR.$filename.'.map.xml');
            break;
    }
}
