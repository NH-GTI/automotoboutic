<?php
if (!defined('STORE_COMMANDER')) { exit; }
echo '<script>'; ?>
    window.scWindowCoreSettingsMainLayout = scWindowCoreSettings.attachLayout('3T');
    scWindowCoreSettingsMainLayout._selectionParams = null;
    <?php
        if(Tools::isSubmit('urlparams')) {
            $params = Tools::getValue('urlparams');
            if(is_array($params)) {
                foreach($params as &$param) {
                    $param = strip_tags($param);
                }
    ?>
    scWindowCoreSettingsMainLayout._selectionParams = <?php echo json_encode($params); ?>;
    <?php
            }
        }
    ?>
    scWindowCoreSettingsMainLayout.cont.id="scCoreSettings"
    scWindowCoreSettingsMainLayout.setSeparatorSize(0, 0);
    scWindowCoreSettingsMainLayout.setSeparatorSize(1, 0);
    scWindowCoreSettingsMainLayout.cells('a').hideHeader()
    scWindowCoreSettingsMainLayout.cells('a').setHeight(30)
    scWindowCoreSettingsMainLayout.cells('a').fixSize(false,true)
    scWindowCoreSettingsMainLayout.cells('a').attachHTMLString(`<h1 class="title">${lang_settings}</h1>`)


    scWindowCoreSettingsMainLayout.cells('b').hideHeader()
    scWindowCoreSettingsMainLayout.cells('b').setWidth(290)
    scWindowCoreSettingsMainLayout.cells('b').fixSize(true,true)
    scWindowCoreSettingsMainLayout.cells('b').cell.id = 'toolList'
    scWindowCoreSettingsMainLayout._toolsList = scWindowCoreSettingsMainLayout.cells('b').attachGrid()
    scWindowCoreSettingsMainLayout._toolsList.setHeader('')
    scWindowCoreSettingsMainLayout._toolsList.setInitWidths('*')
    scWindowCoreSettingsMainLayout._toolsList.setColAlign('left')
    scWindowCoreSettingsMainLayout._toolsList.setColTypes('tree')
    scWindowCoreSettingsMainLayout._toolsList.attachHeader('#text_filter')
    scWindowCoreSettingsMainLayout._toolsList.setColSorting('')
    scWindowCoreSettingsMainLayout._toolsList.enableAutoWidth(true)
    scWindowCoreSettingsMainLayout._toolsList._searchActive = false;

    scWindowCoreSettingsMainLayout._toolsList.init()
    scWindowCoreSettingsMainLayout._iButtonClearSearch = document.createElement('i')
    scWindowCoreSettingsMainLayout._iButtonClearSearch.classList.add('fal','fa-times','clearSearch')
    scWindowCoreSettingsMainLayout._toolsList.filters[0][0].parentNode.append(scWindowCoreSettingsMainLayout._iButtonClearSearch)

    scWindowCoreSettingsMainLayout._toolsList._load = function(query = null) {

        scWindowCoreSettingsMainLayout._toolsList.clearAll();
        let params = {
            action: 'getTools'
        }
        if(Boolean(query)) {
            params['querySearch'] = query
        }

        scWindowCoreSettingsMainLayout.cells('b').progressOn()

        $.post('index.php?ajax=1&act=core_settings_get',
            params,
        function (response) {
            if(response.length === 0) {
                response = {
                    rows: [
                        {
                            id: 'empty',
                            data: [
                                '<?php echo _l('No result'); ?>'
                            ]
                        }
                    ]
                }
            }

            scWindowCoreSettingsMainLayout._toolsList.parse(response, 'json')
            scWindowCoreSettingsMainLayout._toolsList.sortRows(0, 'str', 'asc')

            if(Boolean(query)) {
                scWindowCoreSettingsMainLayout._toolsList.expandAll()
                scWindowCoreSettingsMainLayout._toolSettings._loadBySearchResult(scWindowCoreSettingsMainLayout._toolsList.getAllRowIds())

            } else if(Boolean(scWindowCoreSettingsMainLayout._selectionParams)){
                let selection = []
                if(Boolean(scWindowCoreSettingsMainLayout._selectionParams.id)) {
                    selection = [
                        scWindowCoreSettingsMainLayout._selectionParams.section1,
                        scWindowCoreSettingsMainLayout._selectionParams.section2,
                        scWindowCoreSettingsMainLayout._selectionParams.id
                    ]
                } else if(Boolean(scWindowCoreSettingsMainLayout._selectionParams.section2)){
                    selection = [
                        scWindowCoreSettingsMainLayout._selectionParams.section1,
                        scWindowCoreSettingsMainLayout._selectionParams.section2
                    ]
                } else {
                    selection = [
                        scWindowCoreSettingsMainLayout._selectionParams.section1
                    ]
                }
                scWindowCoreSettingsMainLayout._toolsList.openItem(selection.join('-'))
                scWindowCoreSettingsMainLayout._toolsList.selectRowById(selection.join('-'),true,true,true);
                scWindowCoreSettingsMainLayout._selectionParams = null;
            }

            // replace blank image by padding left on parent div
            for(const cell of document.querySelectorAll('.treegrid_cell')) {
                let spaceWidth = cell.querySelectorAll('img.space').length;
                if(Boolean(spaceWidth)) {
                    cell.dataset.countspace = spaceWidth
                }
            }
        }).always(function(){
            scWindowCoreSettingsMainLayout.cells('b').progressOff()
        })
    }


    document.querySelector('#scWindowCoreSettings #toolList i.clearSearch').addEventListener('click',function(){
        scWindowCoreSettingsMainLayout._toolsList._searchActive = false;
        scWindowCoreSettingsMainLayout._toolsList.getFilterElement(0).value= ''
        scWindowCoreSettingsMainLayout._toolsList.objBox.classList.remove('searchOn')
        scWindowCoreSettingsMainLayout._toolsList._load()
    })

    scWindowCoreSettingsMainLayout._toolsList._load()

    scWindowCoreSettingsMainLayout._toolsList.attachEvent('onMouseOver', function(id,ind) {
        let item = this.cellById(id,ind).cell
        let text = this.getUserData(id,'title')
        if(Boolean(text)) {
            item.setAttribute('title', text)
        }
        return false;
    })

    scWindowCoreSettingsMainLayout._toolsList.attachEvent('onEditCell', function(){
        return false;
    })

    scWindowCoreSettingsMainLayout._toolsList.attachEvent("onBeforeSorting", function(){
        return false;
    });

    scWindowCoreSettingsMainLayout._toolsList.attachEvent('onFilterStart', function(item,query){
        scWindowCoreSettingsMainLayout._toolsList._searchActive = true;
        scWindowCoreSettingsMainLayout._toolsList.objBox.classList.add('searchOn')
        scWindowCoreSettingsMainLayout._toolsList._load(query[0])
        return false;
    })

    scWindowCoreSettingsMainLayout._toolsList.attachEvent('onRowSelect', function(fullId){
        const splited = fullId.split('-')
        let toolId = splited[0]
        if(scWindowCoreSettingsMainLayout._toolSettings._currentToolId === toolId) {
            scWindowCoreSettingsMainLayout._toolSettings._scrollTo(fullId)
            scWindowCoreSettingsMainLayout._toolSettings._currentToolId = toolId
            return true
        }

        scWindowCoreSettingsMainLayout._toolSettings._currentToolId = toolId

        if(scWindowCoreSettingsMainLayout._toolsList._searchActive) {
            scWindowCoreSettingsMainLayout._toolSettings._scrollTo(fullId)
            return true;
        }
        scWindowCoreSettingsMainLayout._toolSettings._load(fullId)
    });

    scWindowCoreSettingsMainLayout.cells('c').hideHeader()
    scWindowCoreSettingsMainLayout.cells('c').cell.id = 'toolForm'

    scWindowCoreSettingsMainLayout._toolSettings = scWindowCoreSettingsMainLayout.cells('c')

    scWindowCoreSettingsMainLayout._toolSettings._currentToolId = null

    scWindowCoreSettingsMainLayout._toolSettings._load = function(fullId) {
        const splited = fullId.split('-')
        let toolId = splited[0]

        $.post('index.php?ajax=1&act=core_settings_get', {
            action: 'getSettings',
            tool: toolId
        }, function(response){
            scWindowCoreSettingsMainLayout._toolSettings._buildFromResponse(response, fullId)
        })
    }

    scWindowCoreSettingsMainLayout._toolSettings._loadBySearchResult = function(searchResult) {
        $.post('index.php?ajax=1&act=core_settings_get', {
            action: 'getSettings',
            searchResult: searchResult
        }, function(response){
            scWindowCoreSettingsMainLayout._toolSettings._buildFromResponse(response)
        })
    }

    scWindowCoreSettingsMainLayout._toolSettings._buildFromResponse = function (response, scrollTo = null) {
        if(Boolean(scWindowCoreSettingsMainLayout._settingsFormByTool)) {
            scWindowCoreSettingsMainLayout._settingsFormByTool.unload();
        }
        scWindowCoreSettingsMainLayout._settingsFormByTool = scWindowCoreSettingsMainLayout.cells('c').attachForm()
        scWindowCoreSettingsMainLayout._settingsFormByTool.cont.classList.add('scModalForm','scSettings')

        scWindowCoreSettingsMainLayout._settingsFormByTool.loadStruct(response, function(){
           // desactivation clic sur label
            let selectorTwoStateToDisableLabel = scWindowCoreSettingsMainLayout._settingsFormByTool.cont.querySelectorAll('.scModalForm .twoState > .dhxform_label > .dhxform_label_nav_link');
            if(Boolean(selectorTwoStateToDisableLabel)) {
                selectorTwoStateToDisableLabel.forEach(function (itemTwoState) {
                    let h2 = itemTwoState.querySelector('h2');
                    itemTwoState.parentNode.replaceChild(h2, itemTwoState);
                });
            }

            if(scrollTo) {
                scWindowCoreSettingsMainLayout._toolSettings._scrollTo(scrollTo)
            }
        });

        scWindowCoreSettingsMainLayout._settingsFormByTool.attachEvent('onChange', function (name, value, state){
            switch(this.getItemType(name)) {
                case 'btn2state':
                    scWindowCoreSettingsMainLayout._toolSettings._updateSettings(name, Number(state))
                    break;
                default:
                    scWindowCoreSettingsMainLayout._toolSettings._updateSettings(name, value)
            }
            return true;
        })


    }

    scWindowCoreSettingsMainLayout._toolSettings._scrollTo = function (fullId)
    {
        let selectorTitleForScroll = document.querySelector(`.scModalForm #${fullId}`)
        if(Boolean(selectorTitleForScroll)) {
            selectorTitleForScroll.classList.add('active')
            selectorTitleForScroll.scrollIntoView({
                behavior: 'smooth'
            });
            setTimeout(function() {
                selectorTitleForScroll.classList.remove('active')
            }, 3000);
        }
    }

    scWindowCoreSettingsMainLayout._toolSettings._updateSettings = function (inputName, inputValue) {
        $.post('index.php?ajax=1&act=core_settings_update', {
            id: inputName,
            value: inputValue
        }, function(response){
            dhtmlx.message({text:response.title, type:response.type, expire:5000})
        })
    }
<?php echo '</script>'; ?>