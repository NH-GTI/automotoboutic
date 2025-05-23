<?php
if (!defined('STORE_COMMANDER')) { exit; }
echo '<script type="text/javascript">'; ?>
// INSTALLATION DE LA VIEW

// IMPORT
    lastCSVFile='';
    mapping='';
    arrayFieldLang=Array('name','description','meta_title','meta_description','meta_keywords','link_rewrite'<?php echo sc_ext::readCatImportCSVConfigXML('definitionForLangField'); ?>);
    arrayFieldOption=Array();
    var comboArray = null;
    var comboValuesArray = null;
    var optionLabelArray = null;
    var progress_interval = null;
    dhxlCatImport=wCatImport.attachLayout("3T");
    wCatImport._sb=dhxlCatImport.attachStatusBar();
    dhxlCatImport.cells('a').hideHeader();
    dhxlCatImport.cells('a').setHeight(200);
    wCatImport.tbOptions=dhxlCatImport.cells('a').attachToolbar();
    wCatImport.tbOptions.setIconset('awesome');
    wCatImport.tbOptions.addButton("help", 0, "", "fad fa-question-circle blue", "fad fa-question-circle blue");
    wCatImport.tbOptions.setItemToolTip('help','<?php echo _l('Help', 1); ?>');
    wCatImport.tbOptions.addButton("download", 0, "", "fad fa-external-link green", "fad fa-external-link green");
    wCatImport.tbOptions.setItemToolTip('download','<?php echo _l('Download selected file', 1); ?>');
    wCatImport.tbOptions.addButton("delete", 0, "", "fa fa-minus-circle red", "fa fa-minus-circle red");
    wCatImport.tbOptions.setItemToolTip('delete','<?php echo _l('Delete marked files', 1); ?>');
    wCatImport.tbOptions.addButton("upload", 0, "", "fa fa-plus-circle green", "fa fa-plus-circle green");
    wCatImport.tbOptions.setItemToolTip('upload','<?php echo _l('Upload CSV file', 1); ?>');
    wCatImport.tbOptions.addInput("filter_name", 0,"",100);
    wCatImport.tbOptions.setItemToolTip('filter_name','<?php echo _l('Filter by name'); ?>');
    wCatImport.tbOptions.addText('txt_filter_name', 0, '<?php echo _l('Filter by name'); ?>');
    wCatImport.tbOptions.addButton("refresh", 0, "", "fa fa-sync green", "fa fa-sync green");
    wCatImport.tbOptions.setItemToolTip('refresh','<?php echo _l('Refresh', 1); ?>');
    wCatImport.tbOptions.attachEvent("onClick",
        function(id){
            if (id=='help')
            {
                <?php echo "window.open('".getScExternalLink('support_csv_import_categories')."');"; ?>
            }
            if (id=='refresh')
            {
                displayCatOptions();
            }
            if (id=='download')
            {
                window.open(wCatImport.gridFiles.getUserData(wCatImport.gridFiles.getSelectedRowId(),'fileUrl'));
            }
            if (id=='delete')
            {
                idxMarkedFile=wCatImport.gridFiles.getColIndexById('markedfile');
                filesList='';
                wCatImport.gridFiles.forEachRow(function(id){
                    if (wCatImport.gridFiles.cells(id,idxMarkedFile).getValue()==true)
                    {
                        idxFilename=wCatImport.gridFiles.getColIndexById('filename');
                        filesList+=wCatImport.gridFiles.cells(id,idxFilename).getValue()+';';
                    }
                    });
                $.post('index.php?ajax=1&act=cat_win-catimport_process&action=conf_delete',{'imp_opt_files':filesList},function(data){
                        dhtmlx.message({text:data,type:'info'});
                        displayCatOptions();
                    });
            }
            if (id=='upload')
            {
                if (!dhxWins.isWindow("wCatImportUpload"))
                {
                    wCatImport._uploadWindow = dhxWins.createWindow("wCatImportUpload", 50, 50, 585, 400);
                    wCatImport._uploadWindow.setText('<?php echo _l('Upload CSV files', 1); ?>');
                    ll = new dhtmlXLayoutObject(wCatImport._uploadWindow, "1C");
                    ll.cells('a').hideHeader();
                    ll.cells('a').attachURL('index.php?ajax=1&act=cat_win-catimport_upload'+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(data){});
                    wCatImport._uploadWindow.attachEvent("onClose", function(win){
                            win.hide();
                            return false;
                        });
                }else{
                    ll.cells('a').attachURL('index.php?ajax=1&act=cat_win-catimport_upload'+"&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(data){});
                    wCatImport._uploadWindow.show();
                    wCatImport._uploadWindow.bringToTop();
                }
            }
        });
        
    wCatImport.gridFiles=dhxlCatImport.cells('a').attachGrid();
    wCatImport.gridFiles.setImagePath("lib/js/imgs/");
    function filterCatScriptName()
    {
        let inputFilterName=wCatImport.tbOptions.getInput('filter_name');
        inputFilterName.onkeyup=function(){
            let search = $(this).val();
            wCatImport.gridFiles.filterBy(1,search);
        };
    }
    function sort_dateFR(a,b,order){
    var a_array=a.split('/');
    var b_array=b.split('/');
    var new_a=a_array[2]*10000+a_array[1]*100+a_array[0];
    var new_b=b_array[2]*10000+b_array[1]*100+b_array[0];
        if(order=="asc")
            return new_a>new_b?1:-1;
        else
            return new_a<new_b?1:-1;
  }
    wCatImport.gridFiles.attachEvent("onRowSelect", function(id,ind){
            if (id!=lastCSVFile)
            {
                idxFilename=wCatImport.gridFiles.getColIndexById('filename');
                idxMapping=wCatImport.gridFiles.getColIndexById('mapping');
                idxLimit=wCatImport.gridFiles.getColIndexById('importlimit');
                wCatImport.tbProcess.setValue('importlimit',wCatImport.gridFiles.cells(id,idxLimit).getValue());
                filename=wCatImport.gridFiles.cells(id,idxFilename).getValue();
                mapping=wCatImport.gridFiles.cells(id,idxMapping).getValue();
                dhxlCatImport.cells('b').setText("<?php echo _l('Mapping'); ?> "+filename);
                displayCatMapping(filename,mapping);
                lastCSVFile=id;
                setProgressBar();
            }
        });
    wCatImport.gridFiles.attachEvent('onEditCell',function (stage,rId,cInd,nValue,oValue){
            idxfieldsep=wCatImport.gridFiles.getColIndexById('fieldsep');
            idxvaluesep=wCatImport.gridFiles.getColIndexById('valuesep');
            if (stage==2 && (cInd==idxfieldsep || cInd==idxvaluesep)){
                idxFilename=wCatImport.gridFiles.getColIndexById('filename');
                idxMapping=wCatImport.gridFiles.getColIndexById('mapping');
                filename=wCatImport.gridFiles.cells(rId,idxFilename).getValue();
                mapping=wCatImport.gridFiles.cells(rId,idxMapping).getValue();
                setTimeout("displayCatMapping('"+filename+"','"+mapping+"')",500);
            }
            return true;
        });
    wCatImport.gridFilesDataProcessor = new dataProcessor('index.php?ajax=1&act=cat_win-catimport_config_update');
    wCatImport.gridFilesDataProcessor.enableDataNames(true);
    wCatImport.gridFilesDataProcessor.enablePartialDataSend(true);
    wCatImport.gridFilesDataProcessor.setUpdateMode('cell',true);
    wCatImport.gridFilesDataProcessor.setDataColumns(Array(false,false,false,true,true,true,true,true,true,true,true,true,true,true,true,false));
<?php
    if (_s('CAT_NOTICE_EXPORT_SEPARATOR'))
    {
        ?>
    wCatImport.gridFilesDataProcessor.attachEvent("onBeforeUpdate",function(id,status){
            if (wCatImport.gridFiles.cells(id,6).getValue()==wCatImport.gridFiles.cells(id,7).getValue())
            {
                dhtmlx.message({text:'<?php echo _l('The field separator and the value separator could not be the same character.'); ?><br/><a href="javascript:disableThisNotice(\'CAT_NOTICE_EXPORT_SEPARATOR\');"><?php echo _l('Disable this notice', 1); ?></a>',type:'error'});
                return false;
            }
            return true;
        });
<?php
    }
?>
    wCatImport.gridFilesDataProcessor.attachEvent("onAfterUpdate",function(id,status){
        getCatCheck();
        return true;
    });
    wCatImport.gridFilesDataProcessor.init(wCatImport.gridFiles);

    displayCatOptions();

    dhxlCatImport.cells('b').setText("<?php echo _l('Mapping'); ?>");
    dhxlCatImport.cells('b').setWidth(600);
    wCatImport.tbMapping=dhxlCatImport.cells('b').attachToolbar();
    wCatImport.tbMapping.setIconset('awesome');
    wCatImport.tbMapping.addButton("load_by_name", 0, "", "fad fa-bolt green", "fad fa-bolt green");
    wCatImport.tbMapping.setItemToolTip('load_by_name','<?php echo _l('Load fields by name', 1); ?>');
    wCatImport.tbMapping.addButton("delete", 0, "", "fa fa-minus-circle red", "fa fa-minus-circle red");
    wCatImport.tbMapping.setItemToolTip('delete','<?php echo _l('Delete mapping and reset grid'); ?>');
    wCatImport.tbMapping.addButton("saveasbtn", 0, "", "fa fa-save blue", "fa fa-save blue");
    wCatImport.tbMapping.setItemToolTip('saveasbtn','<?php echo _l('Save mapping'); ?>');
    wCatImport.tbMapping.addInput("saveas", 0,"",200);
    wCatImport.tbMapping.setItemToolTip('saveas','<?php echo _l('Save mapping as'); ?>');
    wCatImport.tbMapping.addText('txt_saveas', 0, '<?php echo _l('Save mapping as'); ?>');
    var opts = [
<?php
    @$files = array_diff(scandir(SC_CSV_IMPORT_DIR.'category/'), array_merge(array('.', '..', 'index.php', '.htaccess', SC_CSV_IMPORT_CONF)));
    $content = '';
    foreach ($files as $file)
    {
        if (substr($file, strlen($file) - 8, 8) == '.map.xml')
        {
            $file = str_replace('.map.xml', '', $file);
            $content .= "['loadmapping".$file."', 'obj', '".$file."', ''],";
        }
    }
    if ($content == '')
    {
        echo "['0', 'obj', '"._l('No map available')."', ''],";
    }
    echo substr($content, 0, -1);
?>
                            ];
    wCatImport.tbMapping.addButtonSelect("loadmapping", 0, "<?php echo _l('Load'); ?>", opts, "fad fa-american-sign-language-interpreting blue", "fad fa-american-sign-language-interpreting blue",false,true);
    wCatImport.tbMapping.setItemToolTip('loadmapping','<?php echo _l('Load mapping'); ?>');
    wCatImport.tbMapping.addButton("refresh", 0, "", "fa fa-sync green", "fa fa-sync green");
    wCatImport.tbMapping.setItemToolTip('refresh','<?php echo _l('Refresh'); ?>');
    function onCatClickMapping(id){
            if (id.substr(0,11)=='loadmapping')
            {
                tmp=id.substr(11,id.length).replace('.map.xml','');
                wCatImport.tbMapping.setValue('saveas',tmp);
                $.get('index.php?ajax=1&act=cat_win-catimport_process&action=mapping_load&filename='+tmp,function(data){
                        if (data!='')
                        {
                            mapping=data.split(';');
                            wCatImport.gridMapping.forEachRow(function(id){
                                    wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),0).setValue("0");
                                    wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),2).setValue("");
                                    wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),3).setValue("");
                                
                                    if (wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),1).getValue()!='')
                                        for(var i=0; i < mapping.length; i++)
                                        {
                                            map=(mapping[i]).split(',');
                                            if (wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),1).getValue()==map[0])
                                            {
                                                wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),0).setValue("1");
                                                wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),2).setValue(map[1]);

                                                wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),3).setValue(map[2]);
                                            }
                                        }
                                });
                        }
                        getCatCheck();
                        setCatOptionsBGColor();
                    });
            }
            if (id=='refresh')
            {
                if (typeof filename=='undefined')return;
                if (typeof mapping=='undefined')
                {
                    idxMapping=wCatImport.gridFiles.getColIndexById('mapping');
                    mapping=wCatImport.gridFiles.cells(lastCSVFile,idxMapping).getValue();
                }
                displayCatMapping(filename,mapping);
            }
            if (id=='load_by_name')
            {
                comboArray = new Object();
                comboValuesArray = new Object();
                optionLabelArray = new Object();
                $.each(comboDBField.getKeys(), function(num, value){
                    var label = comboDBField.get(value);
                    if(label!=undefined && label!=null && label!="" && label!=0)
                    {
                        comboArray[label] = value;
                        comboValuesArray[value] = value;

                        if(in_array(value,arrayFieldOption))
                            optionLabelArray[value] = label;
                    }
                });
                
                idxFileField=wCatImport.gridMapping.getColIndexById('file_field');
                idxDBField=wCatImport.gridMapping.getColIndexById('db_field');
                idxOptions=wCatImport.gridMapping.getColIndexById('options');
                idxUse=wCatImport.gridMapping.getColIndexById('use');
                
                wCatImport.gridMapping.forEachRow(function(row_id){
                    var name = $.trim(wCatImport.gridMapping.cells(row_id, idxFileField).getValue());
                    var field = wCatImport.gridMapping.cells(row_id, idxDBField).getValue();
                    name = replaceAll("&amp;","&",name);

                    if(name!=undefined && name!=null && name!="" && name!=0 && field!=undefined && (field==null || field=="" || field==0))
                    {
                        // check field image
                        var patt = new RegExp("image_id");
                        var isImgId = patt.test(name);
                        if(isImgId)
                            name = "image_id";
                        // check field image
                        if(name=="id_shop")
                            name = "id_shop_list";
                            
                        var check = false;
                        var value = comboArray[name];
                        var value_bis = comboValuesArray[name];
                        
                        if(value!=undefined && value!=null && value!="" && value!=0)
                        {
                            wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value);
                            check = true;
                        }
                        else if(value_bis!=undefined && value_bis!=null && value_bis!="" && value_bis!=0)
                        {
                            wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value_bis);
                            value = value_bis;
                            check = true;
                        }
                        else
                        {
                            var original_name = name;
                            var lang = $.trim(name.slice(-2).toLowerCase());
                            name = $.trim(name.substring(0, name.length - 3));

                            var patt = new RegExp("image_link");
                            var isImg = patt.test(name);
                            if(isImg)
                            {
                                wCatImport.gridMapping.cells(row_id, idxDBField).setValue("imageURL");
                                value = "imageURL";
                                check = true;
                            }
                            else
                            {
                                var value = comboArray[name];
                                var value_bis = comboValuesArray[name];
                                if(value!=undefined && value!=null && value!="" && value!=0)
                                {
                                    wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value);
                                    check = true;
                                }
                                else if(value_bis!=undefined && value_bis!=null && value_bis!="" && value_bis!=0)
                                {
                                    wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value_bis);
                                    value = value_bis;
                                    check = true;
                                }
                                else
                                {
                                    var encoded_name = unescape(encodeURIComponent(name));
                                    var value = comboArray[encoded_name];
                                    var value_bis = comboValuesArray[encoded_name];
                                    if(value!=undefined && value!=null && value!="" && value!=0)
                                    {
                                        wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value);
                                        check = true;
                                    }
                                    else if(value_bis!=undefined && value_bis!=null && value_bis!="" && value_bis!=0)
                                    {
                                        wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value_bis);
                                        value = value_bis;
                                        check = true;
                                    }
                                    else
                                    {
                                        var decoded_name = decodeURIComponent(unescape(name));
                                        var value = comboArray[decoded_name];
                                        var value_bis = comboValuesArray[decoded_name];
                                        if(value!=undefined && value!=null && value!="" && value!=0)
                                        {
                                            wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value);
                                            check = true;
                                        }
                                        else if(value_bis!=undefined && value_bis!=null && value_bis!="" && value_bis!=0)
                                        {
                                            wCatImport.gridMapping.cells(row_id, idxDBField).setValue(value_bis);
                                            value = value_bis;
                                            check = true;
                                        }
                                    }
                                }

                                if(in_array(value,arrayFieldLang))
                                {
                                    wCatImport.gridMapping.cells(row_id, idxOptions).setValue(lang);
                                    onEditCellCatMapping(2,row_id, idxOptions,lang);
                                }
                                if(!check)
                                {
                                    $.each(optionLabelArray, function(id, label){
                                        var finded = false;
                                        var option = "";
                                        if(name.search(label)>=0)
                                        {
                                            finded = true;
                                            option = $.trim(name.replace(label+" ", ""));
                                        }
                                        else
                                        {
                                            var encoded_name = unescape(encodeURIComponent(name));
                                            if(encoded_name.search(label)>=0)
                                            {
                                                finded = true;
                                                option = $.trim(encoded_name.replace(label+" ", ""));
                                            }
                                            else
                                            {
                                                var decoded_name = decodeURIComponent(unescape(name));
                                                if(encoded_name.search(label)>=0)
                                                {
                                                    finded = true;
                                                    option = $.trim(decoded_name.replace(label+" ", ""));
                                                }
                                            }
                                        }

                                        if(finded)
                                        {
                                            wCatImport.gridMapping.cells(row_id, idxDBField).setValue(id);
                                            value = id;
                                            check = true;

                                            if(option!=undefined && option!=null && option!="")
                                            {
                                                wCatImport.gridMapping.cells(row_id, idxOptions).setValue(option);
                                            }
                                        }
                                    });
                                }
                            }
                        }

                        if(check)
                            onEditCellCatMapping(2,row_id, idxDBField,value);
                    }
                });
            }
            if (id=='saveasbtn')
            {
                if (wCatImport.tbMapping.getValue('saveas')=='')
                {
                    dhtmlx.message({text:'<?php echo _l('Mapping name should not be empty'); ?>',type:'error'});
                }else{
                    var mapping='';
                    wCatImport.gridMapping.forEachRow(function(id){
                            if (wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),0).getValue()=="1")
                            {
                                mapping+=wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),1).getValue()+','+
                                                 wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),2).getValue()+','+
                                                 wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),3).getValue()+';';
                            }
                        });
                    wCatImport.tbMapping.setValue('saveas',getLinkRewriteFromStringLightWithCase(wCatImport.tbMapping.getValue('saveas')));
                    $.post('index.php?ajax=1&act=cat_win-catimport_process&action=mapping_saveas',{'filename':wCatImport.tbMapping.getValue('saveas'),'mapping':mapping},function(data){
                                dhtmlx.message({text:data,type:'info'});
                                if (!in_array('loadmapping'+wCatImport.tbMapping.getValue('saveas'),wCatImport.tbMapping.getAllListOptions('loadmapping')))
                                {
                                    wCatImport.tbMapping.addListOption('loadmapping', 'loadmapping'+wCatImport.tbMapping.getValue('saveas'), 0, 'button', wCatImport.tbMapping.getValue('saveas'))
                                    wCatImport.tbMapping.setListOptionSelected('loadmapping', 'loadmapping'+wCatImport.tbMapping.getValue('saveas'));
                                }
                                displayCatOptions();
                                getCatCheck();
                            });
                }
            }
            if (id=='delete')
            {
                if (wCatImport.tbMapping.getValue('saveas')=='')
                {
                    dhtmlx.message({text:'<?php echo _l('Mapping name should not be empty'); ?>',type:'error'});
                }else{
                    if (confirm('<?php echo _l('Do you want to delete the current mapping?', 1); ?>'))
                        $.get('index.php?ajax=1&act=cat_win-catimport_process&action=mapping_delete&filename='+wCatImport.tbMapping.getValue('saveas'),function(data){
                                wCatImport.gridMapping.clearAll(true);
                                wCatImport.tbMapping.removeListOption('loadmapping', 'loadmapping'+wCatImport.tbMapping.getValue('saveas'));
                                wCatImport.tbMapping.setValue('saveas','');
                                getCatCheck();
                            });
                }
            }
    }
    wCatImport.tbMapping.attachEvent("onClick",onCatClickMapping);
    wCatImport.gridMapping=dhxlCatImport.cells('b').attachGrid();
    wCatImport.gridMapping.setImagePath("lib/js/imgs/");
    function setCatOptionsBGColor()
    {
        idxMark=wCatImport.gridMapping.getColIndexById('use');
        idxDBField=wCatImport.gridMapping.getColIndexById('db_field');
        idxOptions=wCatImport.gridMapping.getColIndexById('options');
        wCatImport.gridMapping.forEachRow(function(rId){
            wCatImport.gridMapping.cells(rId,idxOptions).setBgColor(wCatImport.gridMapping.cells(rId,idxDBField).getBgColor());
            var flag=false;
            if (in_array(wCatImport.gridMapping.cells(rId,idxDBField).getValue(),arrayFieldLang))
            {
                wCatImport.gridMapping.cells(rId,idxOptions).setBgColor('#CCCCEE');
                flag=true;
            }
<?php
    sc_ext::readCatImportCSVConfigXML('importMappingPrepareGrid');
?>
            if (!flag) wCatImport.gridMapping.cells(rId,idxOptions).setValue('');
        });
    }
    function checkCatOptions()
    {
        var flag=true;
        idxDBField=wCatImport.gridMapping.getColIndexById('db_field');
        idxOptions=wCatImport.gridMapping.getColIndexById('options');
        wCatImport.gridMapping.forEachRow(function(rId){
            if (wCatImport.gridMapping.cells(rId,0).getValue()=="1")
            {
                if (in_array(wCatImport.gridMapping.cells(rId,idxDBField).getValue(),arrayFieldLang)
                            && wCatImport.gridMapping.cells(rId,idxOptions).getValue()=='')
                    flag=false;
<?php
    sc_ext::readCatImportCSVConfigXML('importMappingCheckGrid');
?>
            }
        });
        return flag;
    }
    function onEditCellCatMapping(stage,rId,cInd,nValue,oValue){
        if(stage==1 && (cInd==2 || cInd==3)){ 
        var editor = this.editor; 
        var pos = this.getPosition(editor.cell);        
        var y = document.body.offsetHeight-pos[1];   
        if(y < editor.list.offsetHeight)       
            editor.list.style.top = pos[1] - editor.list.offsetHeight + 'px';   
    }
        idxMark=wCatImport.gridMapping.getColIndexById('use');
        idxDBField=wCatImport.gridMapping.getColIndexById('db_field');
        idxOptions=wCatImport.gridMapping.getColIndexById('options');
        comboDBField = wCatImport.gridMapping.getCombo(idxOptions);
        if (cInd == idxDBField && nValue != oValue){
            wCatImport.gridMapping.cells(rId,idxMark).setValue(1);
            setCatOptionsBGColor();
        }
        if (cInd == idxOptions)
        {
            comboDBField.clear();
            if (in_array(wCatImport.gridMapping.cells(rId,idxDBField).getValue(),arrayFieldLang))
            {
<?php
    foreach ($languages as $lang)
    {
        echo '                comboDBField.put("'.$lang['iso_code'].'","'.$lang['iso_code'].'");';
    }
?>
                return true;
            }
<?php
    sc_ext::readCatImportCSVConfigXML('importMappingFillCombo');
?>
            return false;
        }
        return true;
    }
    wCatImport.gridMapping.attachEvent('onEditCell',onEditCellCatMapping);
    
    dhxlCatImport.cells('c').setText("<?php echo _l('Process'); ?>");

    wCatImport.tbProcess=dhxlCatImport.cells('c').attachToolbar();
    wCatImport.tbProcess.setIconset('awesome');
    var create_categories=false;
    var start_import = 0;
    wCatImport.tbProcess.addButton("loop_tool", 0, "", "fa fa-clock", "fa fa-clock");
    wCatImport.tbProcess.setItemToolTip('loop_tool','<?php echo _l('Auto-import tool'); ?>');
    wCatImport.tbProcess.addButton("go_process", 0, "", "fad fa-sign-in", "fad fa-sign-in");
    wCatImport.tbProcess.setItemToolTip('go_process','<?php echo _l('Import data'); ?>');
    wCatImport.tbProcess.addSeparator("sep01", 0);
    wCatImport.tbProcess.addButton("check", 0, "", "fa fa-check-circle green", "fa fa-check-circle green");
    wCatImport.tbProcess.setItemToolTip('check','<?php echo _l('Votre import est-il prêt ?'); ?>');
    wCatImport.tbProcess.addSeparator("sep02", 0);
    wCatImport.tbProcess.addInput("importlimit", 0,500,30);
    wCatImport.tbProcess.setItemToolTip('importlimit','<?php echo _l('Number of the first lines to import from the CSV file'); ?>');
    $(wCatImport.tbProcess.getInput('importlimit')).change(function(){getCatCheck();});
    wCatImport.tbProcess.addText('txtimportlimit', 0, '<?php echo _l('Lines to import')._l(':'); ?>');
    wCatImport.tbProcess.attachEvent("onClick",
        function(id){
            if (id=='check')
            {
                window.open("<?php echo getScExternalLink('support_csv_import_checklist'); ?>");
            }
            if (id=='go_process')
            {
                if (!autoCatImportRunning){
                    displayCatProcess();
                }else{
                    dhtmlx.message({text:'<?php echo _l('AutoImport already running'); ?>',type:'error'});
                }
            }
            if (id=='loop_tool')
            {
                displayCatAutoImportTool();
            }
        });

    
//#####################################
//############ Load functions
//#####################################

function displayCatOptions(callback)
{
    wCatImport.gridFiles.clearAll(true);
    wCatImport.gridFiles.load("index.php?ajax=1&act=cat_win-catimport_config_get&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function()
    {
        filterCatScriptName();
        if (callback)
        {
            eval(callback);
        }else if(lastCSVFile!=''){
            wCatImport.gridFiles.selectRowById(lastCSVFile);
        }
    });
}

function displayCatMapping(filename,mapping)
{
    wCatImport.gridMapping.clearAll(true);
    wCatImport.gridMapping.load("index.php?ajax=1&act=cat_win-catimport_mapping_get&id_lang="+SC_ID_LANG+"&imp_opt_file="+filename+"&"+new Date().getTime(),function()
            {
                idxDBField=wCatImport.gridMapping.getColIndexById('db_field');
                comboDBField = wCatImport.gridMapping.getCombo(idxDBField);
                comboDBField.clear();
<?php
    global $array;
    $array = array();
    $array[_l('id_category', 1)] = "comboDBField.put('id_category','"._l('id_category', 1)."');";
    $array[_l('complete path', 1)] = "comboDBField.put('path','"._l('complete path', 1)."');";
    $array[_l('parents path', 1)] = "comboDBField.put('parents','"._l('parents path', 1)."');";
    $array[_l('active', 1)] = "comboDBField.put('active','"._l('active', 1)."');";
    $array[_l('name', 1)] = "comboDBField.put('name','"._l('name', 1)."');";
    $array[_l('description', 1)] = "comboDBField.put('description','"._l('description', 1)."');";
    $array[_l('meta_title', 1)] = "comboDBField.put('meta_title','"._l('meta_title', 1)."');";
    $array[_l('meta_description', 1)] = "comboDBField.put('meta_description','"._l('meta_description', 1)."');";
    $array[_l('meta_keywords', 1)] = "comboDBField.put('meta_keywords','"._l('meta_keywords', 1)."');";
    $array[_l('link_rewrite', 1)] = "comboDBField.put('link_rewrite','"._l('link_rewrite', 1)."');";
    $array[_l('imageURL', 1)] = "comboDBField.put('imageURL','"._l('imageURL', 1)."');";
    $array[_l('thumbnailURL', 1)] = "comboDBField.put('thumbnailURL','"._l('thumbnailURL', 1)."');";
    $array[_l('customer groups', 1)] = "comboDBField.put('customergroups','"._l('customer groups', 1)."');";

    $array[' '._l('Action: Delete images', 1)] = "comboDBField.put('ActionDeleteImages','"._l('Action: Delete images', 1)."');";
    $array[' '._l('Action: Dissociate groups', 1)] = "comboDBField.put('ActionCleanGroups','"._l('Action: Dissociate groups', 1)."');";

    if (SCMS)
    {
        $array['id_shop_default'] = "comboDBField.put('id_shop_default','id_shop_default');";
        $array['id_shop_list'] = "comboDBField.put('id_shop_list','id_shop_list');";
    }
    sc_ext::readCatImportCSVConfigXML('definition');

    ksort($array);
    echo join("\n", $array);
?>
                if (mapping!='')
                {
                    onCatClickMapping('loadmapping'+mapping);
                }else{
                    onCatClickMapping('loadmapping'+filename.replace('.csv','').replace('.CSV',''));
                }
            });
}

function displayCatProcess()
{
    var mapping='';
    if (!checkCatOptions() || lastCSVFile=='')
    {
        dhtmlx.message({text:'<?php echo _l('Some options are missing'); ?>',type:'error'});
        return false;
    }
    wCatImport.gridMapping.forEachRow(function(id){
            if (wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),0).getValue()=="1")
            {
                mapping+=wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),1).getValue()+','+
                                 wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),2).getValue()+','+
                                 wCatImport.gridMapping.cells(wCatImport.gridMapping.getRowIndex(id),3).getValue()+';';
            }
        });
    mapping=mapping.substr(0,mapping.length-1);
    autoCatImportLastState=1;
    setProgressBar();
    var needToReload = 0;
    let jqhxhr_process = $.post('index.php?ajax=1&act=cat_win-catimport_process&action=mapping_process',{'mapping':mapping,'filename':lastCSVFile,'importlimit':wCatImport.tbProcess.getValue('importlimit'),'create_categories':(1*create_categories)},function(data){
        document.onselectstart = new Function("return true;");
        dhxlCatImport.cells('c').attachHTMLString(data);
        if(start_import === 1) {
            if($('#progress_bar').length) {
                let regex = /(<b id="process_ending">)/g;
                let process_ending_good = data.match(regex);
                if(process_ending_good) {
                    $('#progress_bar > #processed').css({"width": '100%'});
                    $('#progress_bar > #processed').text(' - 100%');
                } else {
                    needToReload = 1;
                }
            }
        }
    }).fail(function(data) {
        dhxlCatImport.cells('c').attachHTMLString(data.responseText);
    });

    jqhxhr_process.always(function() {
        window.clearInterval(progress_interval);
        start_import = 0;
        if(needToReload === 1) {
            setProgressBar();
        } else {
            $.post('index.php?ajax=1&act=cat_win-catimport_progressbar', {'file': lastCSVFile}, function (data) {
                $('#progress_bar > #processed').css({"width": data + '%'});
                $('#progress_bar > #processed').text(' - ' + data + '%');
                $('#progress_bar').removeClass('in_process_awesome');
            });
        }
    });
    getProgressBar();

    setTimeout("displayCatOptions('wCatImport.gridFiles.selectRowById(getCatTODOName(lastCSVFile), false, true, false)');",500);
}

function setProgressBar()
{
    if ($('#progress_bar').length) {
        $('#progress_bar').remove();
    }
    wCatImport._sb.setText('<div id="progress_bar" data-bar="<?php echo _l('Skipped lines or lines to be processed', 1); ?>"><div id="processed" data-processed="<?php echo _l('Processed lines', 1); ?>"></div></div>');
    callEasterEgg();
}

function callEasterEgg()
{
    $('#progress_bar').click(function(){
        $('body').append('<div class="easteregg"><img src="../SC/lib/img/easteregg.gif" height="100%" width="150"></div>');
        $('.easteregg').click(function(){
           $(this).remove();
        });
        return false;
    });
}

function getProgressBar()
{
    if(start_import === 1) {
        $('#progress_bar').addClass('in_process_awesome');
        progress_interval = window.setInterval(function () {
            $.post('index.php?ajax=1&act=cat_win-catimport_progressbar', {'file': lastCSVFile}, function (data) {
                if (data <= 100) {
                    $('#progress_bar > #processed').css({"width": data + '%'});
                    $('#progress_bar > #processed').text(' - ' + data + '%');
                }
            });
        }, 3000);
    }
}

function displayCatAutoImportTool()
{
    if (!dhxWins.isWindow("wCatAutoImport"))
    {
        wCatAutoImport = dhxWins.createWindow("wCatAutoImport", 550, 350, 220, 68);
        wCatAutoImport.setMinDimension(220, 68);
        wCatAutoImport.setText("<?php echo _l('Auto-import tool'); ?>");
        wCatAutoImport.button('park').hide();
        wCatAutoImport.button('minmax').hide();
        wCatAutoImport._tb=wCatAutoImport.attachToolbar();
        wCatAutoImport._tb.setIconset('awesome');
        wCatAutoImport._tb.addButton("help", 0, "", "fad fa-question-circle blue", "fad fa-question-circle blue");
        wCatAutoImport._tb.setItemToolTip('help','<?php echo _l('Help', 1); ?>');
        wCatAutoImport._tb.addText('txtsecs', 0, '<?php echo _l('sec'); ?>');
        wCatAutoImport._tb.addInput("importinterval", 0,60,30);
        wCatAutoImport._tb.setItemToolTip('importinterval','<?php echo _l('Launch import every X seconds if possible', 1); ?>');
        wCatAutoImport._tb.addText('txtinterval', 0, '<?php echo _l('Interval:', 1); ?>');
        wCatAutoImport._tb.addButtonTwoState("play", 0, "", "fad fa-play-circle blue", "fad fa-play-circle blue");
        wCatAutoImport._tb.setItemToolTip('play','<?php echo _l('Start', 1); ?>');
        wCatAutoImport._tb.attachEvent("onClick",
            function(id){
                if (id=='help'){
                    <?php echo "window.open('".getScExternalLink('support_csv_auto_import')."');"; ?>
                }
                if (id=='stop'){
                    stopCatAutoImport();
                }
            });
        wCatAutoImport._tb.attachEvent("onStateChange", function(id, state){
                if (id=='play'){
                    if (state){
                        startCatAutoImport();
                    }else{
                        stopCatAutoImport();
                    }
                }
            });
        wCatAutoImport._tb.setListOptionSelected("alertsound", 0);
        wCatAutoImport._tb.setListOptionSelected("alertvisual", 0);
        wCatAutoImport.attachObject('alertbox');
    }else{
        wCatAutoImport.bringToTop();
    }
}

autoCatImportRunning=false; // check and auto import?
autoCatImportUnit=0; // counter
autoCatImportLastState=0; // 0 : nothing - 1 : waiting reply from server
autoCatImportTODOSize1=0; // Size of TODO file stored in var 1
autoCatImportTODOSize2=0; // Size of TODO file stored in var 2 to compare with autoCatImportTODOSize1


function startCatAutoImport()
{
    autoCatImportUnit=0;
    autoCatImportRunning=true;
    autoCatImportTODOSize1=0;
    autoCatImportTODOSize2=0;
    processCatAutoImport();
    displayCatProcess();
}

function stopCatAutoImport(showAlert)
{
    if (dhxWins.isWindow("wCatAutoImport"))
    {
        autoCatImportUnit=0;
        autoCatImportRunning=false;
        autoCatImportTODOSize1=0;
        autoCatImportTODOSize2=0;
        autoCatImportLastState=0;
        wCatAutoImport._tb.setItemState('play', false);
        if (showAlert){
            $('#alertbox').css('background-color','#FF0000');
            wCatAutoImport.setDimension(350, 168);
        }
    }
}

function processCatAutoImport()
{
    if (!dhxWins.isWindow("wCatAutoImport"))    stopCatAutoImport();
    if (!autoCatImportRunning) return 0;
    autoCatImportUnit++;
    if (autoCatImportUnit>=wCatAutoImport._tb.getValue('importinterval')*1){
        if(autoCatImportLastState==1 || (autoCatImportTODOSize1>0 && autoCatImportTODOSize1==autoCatImportTODOSize2)){ // still waiting reply OR TODO file didn't change
            stopCatAutoImport(true);
            return 0;
        }
        autoCatImportUnit=0;
        displayCatProcess();
    }
    setTimeout('processCatAutoImport()',1000);
}

function prepareCatNextStep(TODOFileSize)
{
    if (TODOFileSize==0)
    {
        stopCatAutoImport(true);
        return 0;
    }
    autoCatImportTODOSize2=autoCatImportTODOSize1;
    autoCatImportTODOSize1=TODOFileSize;
    autoCatImportLastState=0;
}

function stopCatAlert()
{
    $('#alertbox').css('background-color','#FFFFFF');
    wCatAutoImport.setDimension(350, 68);
}

function getCatTODOName(str)
{
    if (str.substr(0,str.length-9)=='.TODO.csv')
    {
        return str;
    }else{
        return str.substr(0,str.length-4)+'.TODO.csv';
    }
}

function getCatCheck()
{
    var selectedRow = wCatImport.gridFiles.getSelectedRowId();
    if(selectedRow!=undefined && selectedRow!="" && selectedRow!=null && selectedRow.search(",")<=0)
    {
        dhxlCatImport.cells('c').attachHTMLString('<br/><br/><center>'+loader_gif+'</center>');
        $.post('index.php?ajax=1&act=cat_win-catimport_check&id_lang='+SC_ID_LANG,{'mapping':mapping,'mappingname':wCatImport.tbMapping.getValue('saveas'),'mapppinggridlength':wCatImport.gridMapping.getRowsNum(),'filename':lastCSVFile,'importlimit':wCatImport.tbProcess.getValue('importlimit'),'create_categories':(1*create_categories)},function(data){
            dhxlCatImport.cells('c').attachHTMLString(data);
        });
    }
}

<?php echo '</script>'; ?>
<div id="alertbox" style="width:400px;height:200px;color:#FFFFFF" onclick="stopCatAlert();">Click here to close alert.</div>