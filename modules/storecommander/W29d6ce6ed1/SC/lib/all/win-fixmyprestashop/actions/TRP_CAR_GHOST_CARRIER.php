<?php
if (!defined('STORE_COMMANDER')) { exit; }

$post_action = Tools::getValue('action');
if (!empty($post_action) && $post_action == 'do_check')
{
    $sql = 'SELECT pl.id_carrier, pl.delay FROM '._DB_PREFIX_.'carrier_lang pl WHERE pl.id_carrier NOT IN (SELECT p.id_carrier FROM '._DB_PREFIX_.'carrier p) ORDER BY id_lang ASC LIMIT 1500';
    $res = Db::getInstance()->ExecuteS($sql);

    $content = '';
    $content_js = '';
    $results = 'OK';
    if (!empty($res) && count($res) > 0)
    {
        $results = 'KO';
        ob_start(); ?>
        <script type="text/javascript">
    
            var tbGhostCarrier = dhxlSCExtCheck.tabbar.cells("table_TRP_CAR_GHOST_CARRIER").attachToolbar();
            tbGhostCarrier.setIconset('awesome');
            tbGhostCarrier.addButton("selectall", 0, "", 'fa fa-bolt yellow', 'fa fa-bolt yellow');
            tbGhostCarrier.setItemToolTip('selectall','<?php echo _l('Select all'); ?>');
            tbGhostCarrier.addButton("delete", 0, "", 'fa fa-minus-circle red', 'fa fa-minus-circle red');
            tbGhostCarrier.setItemToolTip('delete','<?php echo _l('Delete incomplete carriers'); ?>');
            tbGhostCarrier.addButton("add", 0, "", 'fa fa-plus-circle green', 'fa fa-plus-circle green');
            tbGhostCarrier.setItemToolTip('add','<?php echo _l('Recover incomplete carriers'); ?>');
            tbGhostCarrier.attachEvent("onClick",
                function(id){
                    if (id=='selectall')
                    {
                        gridGhostCarrier.selectAll();
                        getGridStat_GhostCarrier();
                    }
                    if (id=='delete')
                    {
                        deleteGhostCarrier();
                    }
                    if (id=='add')
                    {
                        addGhostCarrier()
                    }
                });
        
            var gridGhostCarrier = dhxlSCExtCheck.tabbar.cells("table_TRP_CAR_GHOST_CARRIER").attachGrid();
            gridGhostCarrier.setImagePath("lib/js/imgs/");
            gridGhostCarrier.enableSmartRendering(true);
            gridGhostCarrier.enableMultiselect(true);
    
            gridGhostCarrier.setHeader("ID,<?php echo _l('Name'); ?>,<?php echo _l('Used ?'); ?>");
            gridGhostCarrier.setInitWidths("100, 110,50");
            gridGhostCarrier.setColAlign("left,left,left");
            gridGhostCarrier.setColTypes("ro,ro,ro");
            gridGhostCarrier.setColSorting("int,str,str");
            gridGhostCarrier.attachHeader("#numeric_filter,#text_filter,#select_filter");
            gridGhostCarrier.init();

            var xml = '<rows>';
            <?php foreach ($res as $carrier)
        {
            $sql = 'SELECT * FROM `'._DB_PREFIX_."orders` WHERE id_carrier = '".(int) $carrier['id_carrier']."' LIMIT 1";
            $is_used = Db::getInstance()->ExecuteS($sql); ?>
            xml = xml+'   <row id="<?php echo $carrier['id_carrier']; ?>">';
            xml = xml+'      <cell><![CDATA[<?php echo $carrier['id_carrier']; ?>]]></cell>';
            xml = xml+'      <cell><![CDATA[<?php echo str_replace("'", "\'", $carrier['delay']); ?>]]></cell>';
            xml = xml+'      <cell><![CDATA[<?php if (!empty($is_used) && count($is_used) > 0)
            {
                echo _l('Yes');
            }
            else
            {
                echo _l('No');
            } ?>]]></cell>';
            xml = xml+'   </row>';
            <?php
        } ?>
            xml = xml+'</rows>';
            gridGhostCarrier.parse(xml);

            sbGhostCarrier=dhxlSCExtCheck.tabbar.cells("table_TRP_CAR_GHOST_CARRIER").attachStatusBar();
            function getGridStat_GhostCarrier(){
                var filteredRows=gridGhostCarrier.getRowsNum();
                var selectedRows=(gridGhostCarrier.getSelectedRowId()?gridGhostCarrier.getSelectedRowId().split(',').length:0);
                sbGhostCarrier.setText('<?php echo count($res).' '._l('Errors'); ?>'+" - <?php echo _l('Filter')._l(':'); ?> "+filteredRows+" - <?php echo _l('Selection')._l(':'); ?> "+selectedRows);
            }
            gridGhostCarrier.attachEvent("onFilterEnd", function(elements){
                getGridStat_GhostCarrier();
            });
            gridGhostCarrier.attachEvent("onSelectStateChanged", function(id){
                getGridStat_GhostCarrier();
            });
            getGridStat_GhostCarrier();

            function deleteGhostCarrier()
            {
                var selectedGhostCarriers = gridGhostCarrier.getSelectedRowId();
                if(selectedGhostCarriers==null || selectedGhostCarriers=="")
                    selectedGhostCarriers = 0;
                if(selectedGhostCarriers!="0")
                {
                    $.post("index.php?ajax=1&act=all_win-fixmyprestashop_actions&check=TRP_CAR_GHOST_CARRIER&id_lang="+SC_ID_LANG, { "action": "delete_carriers", "ids": selectedGhostCarriers}, function(data){
                        dhxlSCExtCheck.tabbar.tabs("table_TRP_CAR_GHOST_CARRIER").close();

                         dhxlSCExtCheck.gridChecks.selectRowById('TRP_CAR_GHOST_CARRIER');
                         doCheck(false);
                    });
                }
            }

            function addGhostCarrier()
            {
                var selectedGhostCarriers = gridGhostCarrier.getSelectedRowId();
                if(selectedGhostCarriers==null || selectedGhostCarriers=="")
                    selectedGhostCarriers = 0;
                if(selectedGhostCarriers!="0")
                {
                    $.post("index.php?ajax=1&act=all_win-fixmyprestashop_actions&check=TRP_CAR_GHOST_CARRIER&id_lang="+SC_ID_LANG, { "action": "add_carriers", "ids": selectedGhostCarriers}, function(data){
                        dhxlSCExtCheck.tabbar.tabs("table_TRP_CAR_GHOST_CARRIER").close();

                         dhxlSCExtCheck.gridChecks.selectRowById('TRP_CAR_GHOST_CARRIER');
                         doCheck(false);
                    });
                }
            }
        </script>
        <?php $content_js = ob_get_clean();
    }
    echo json_encode(array(
            'results' => $results,
            'contentType' => 'grid',
            'content' => $content,
            'title' => _l('Ghost carrier'),
            'contentJs' => $content_js,
    ));
}
elseif (!empty($post_action) && $post_action == 'delete_carriers')
{
    $post_ids = Tools::getValue('ids');
    if (!empty($post_ids))
    {
        $sql = 'DELETE FROM '._DB_PREFIX_.'carrier_lang WHERE id_carrier IN ('.pInSQL($post_ids).')';
        $res = dbExecuteForeignKeyOff($sql);
    }
}
elseif (!empty($post_action) && $post_action == 'add_carriers')
{
    $post_ids = Tools::getValue('ids');
    if (!empty($post_ids))
    {
        $ids = explode(',', $post_ids);
        foreach ($ids as $id)
        {
            $sql = 'INSERT INTO '._DB_PREFIX_.'carrier (id_carrier, name, active)
                    VALUES ('.$id.",'Carrier', 0)";
            $res = dbExecuteForeignKeyOff($sql);
        }
    }
}
