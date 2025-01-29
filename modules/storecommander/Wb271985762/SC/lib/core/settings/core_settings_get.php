<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$data = [];
$action = Tools::getValue('action');
$default_settings_temp = $default_settings;

unset($default_settings_temp['CAT_ADVANCEDSTOCKS_WAREHOUSESHARE_DEFAULT_TYPE']);

if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
{
    // PS ADD A CONFIGURATION FOR THIS ACTION
    // SO CUSTOMER PARAMS IT IN PS BACKOFFICE
    unset($default_settings_temp['CAT_SEO_NAME_TO_URL']);
}
if (!_r('MEN_TOO_CUSTOM_LINKS'))
{
    unset($default_settings_temp['TOOLS_LINK_1']);
    unset($default_settings_temp['TOOLS_LINK_2']);
    unset($default_settings_temp['TOOLS_LINK_3']);
    unset($default_settings_temp['TOOLS_LINK_4']);
    unset($default_settings_temp['TOOLS_LINK_5']);
}
$tiny = _s('CAT_PROD_IMG_TINYPNG');
if (empty($tiny))
{
    unset($default_settings_temp['CAT_PROD_IMG_TINYPNG']);
}

$allSettings = [];
foreach ($default_settings_temp as $k => $setting)
{
    $value = $local_settings[$k]['value'];
    if (in_array($k, ['CAT_PROD_GRID_DEFAULT', 'CAT_PRODPROP_GRID_DEFAULT', 'CMS_PAGEPROP_GRID_DEFAULT', 'CMS_PAGE_GRID_DEFAULT', 'MAN_MANUF_PROP_GRID_DEFAULT', 'MAN_MANUF_GRID_DEFAULT', 'ORD_ORDER_GRID_DEFAULT', 'ORD_ORDPROP_GRID_DEFAULT', 'CUS_CUSTOMER_GRID_DEFAULT', 'CUS_CUSPROP_GRID_DEFAULT']))
    {
        $uiset = UISettings::getSetting($k);
        if (!empty($uiset))
        {
            $value = $uiset;
        }
    }
    $setting['value'] = $value;
    $allSettings[$setting['section1']][$setting['section2']][$setting['id']] = $setting;
}

switch ($action) {
    case 'getTools':
        $querySearch = Tools::getValue('querySearch');
        $searchFounding = [];
        $searchFoundingWithValue = [];
        if (!empty($querySearch))
        {
            foreach ($default_settings_temp as $setting)
            {
                $key = $setting['section1'].'-'.$setting['section2'].'-'.$setting['id'];

                ## recherche query dans le nom
                if (stripos(_l($setting['name']), $querySearch) !== false)
                {
                    $searchFoundingWithValue[$key] = _l($setting['name']);
                    $searchFounding[] = $key;
                    continue;
                }

                ## recherche query dans la description
                if (stripos(_l($setting['description']), $querySearch) !== false)
                {
                    $searchFoundingWithValue[$key] = [
                        _l($setting['name'])
                        ,_l($setting['description'])
                    ];
                    $searchFounding[] = $key;
                    continue;
                }

                ## recherche query dans la seconde section
                if (strtolower(_l($setting['section2'])) === strtolower($querySearch))
                {
                    $key = $setting['section1'].'-'.$setting['section2'];
                    $searchFoundingWithValue[$key] = _l($setting['section2']);
                    $searchFounding[] = $key;
                }
            }
            if (!$searchFounding)
            {
                break;
            }
            $searchFounding = array_unique($searchFounding);
        }

        $data['rows'] = [];
        foreach ($allSettings as $tool => $sections)
        {
            $toolContent = [
                'id' => $tool,
                'data' => [
                    _l($tool),
                ],
            ];
            foreach ($sections as $section => $settings)
            {
                $sectionContent = [
                    'id' => $tool.'-'.$section,
                    'data' => [
                        _l($section),
                    ],
                    'rows' => (!empty($searchFounding) && in_array($tool.'-'.$section, $searchFounding) ? true : []), ## si recherche correspond seconde section alors afficher menu
                ];
                foreach ($settings as $setting)
                {
                    if (!empty($searchFounding) && !in_array($tool.'-'.$section.'-'.$setting['id'], $searchFounding))
                    {
                        continue;
                    }

                    if (is_array($sectionContent['rows']))
                    {
                        $sectionContent['rows'][] = [
                            'id' => $tool.'-'.$section.'-'.$setting['id'],
                            'data' => [
                                retrieveNodeName($setting['name'], $tool.'-'.$section.'-'.$setting['id'], $searchFoundingWithValue, $querySearch),
                            ],
                            'userdata' => [
                                'title' => ucfirst(_l($setting['name'])),
                            ],
                        ];
                    }
                }
                if (!empty($searchFounding) && empty($sectionContent['rows']))
                {
                    continue;
                }
                $toolContent['rows'][] = $sectionContent;
            }
            if (!empty($searchFounding) && empty($toolContent['rows']))
            {
                continue;
            }
            $data['rows'][] = $toolContent;
        }
        break;
    case 'getSettings':
        $settingList = [];
        if(Tools::isSubmit('tool')) {
            $tool = Tools::getValue('tool');
            if (!isset($allSettings[$tool])) {
                break;
            }
            $settingList = $allSettings[$tool];
        }

        if(Tools::isSubmit('searchResult')) {
            $items = Tools::getValue('searchResult');
            if(empty($items)) {
                break;
            }

            $items = explode(',',$items);

            foreach($items as $item) {
                $exploded = explode('-', $item);
                if (empty($exploded[2])) {
                    continue;
                }
                list($section1, $section2, $id) = $exploded;
                $settingList[$section2][$id] = $allSettings[$section1][$section2][$id];
            }
        }

        if(empty($settingList)) {
            break;
        }
        $data = buildFormStructureForSettings($settingList);
        break;
    default:
}

/**
 * @return array
 */
function buildFormStructureForSettings(array $settingList)
{
    $form = [];

    foreach ($settingList as $section => $settings)
    {
        $list = [];
        foreach ($settings as $setting)
        {
            $list[] = [
                'type' => 'block',
                'list' => [
                    setFormByConfiguration($setting),
                    [
                        'type' => 'template',
                        'className' => 'description',
                        'value' => _l($setting['description']).'<br><br><b>'._l('Default value')._l(':').'</b> '.$setting['default_value'],
                        'format' => 'function(n,description) { return ${description}` }',
                    ],
                ],
            ];
        }

        $form[_l($section)][] = [
            'type' => 'label',
            'label' => '<h1 id="'.$setting['section1'].'-'.$setting['section2'].'">'.ucfirst(_l($section)).'</h1>',
            'list' => $list,
        ];
    }

    uksort($form, 'strcasecmp');
    $final = [];
    foreach ($form as $settings)
    {
        foreach ($settings as $setting)
        {
            $final[] = $setting;
        }
    }

    return $final;
}

/**
 * @param array $rowSetting ligne preference
 *
 * @return array
 */
function setFormByConfiguration(array $rowSetting)
{
    $form = [];
    $form['label'] = '<h2 id="'.$rowSetting['section1'].'-'.$rowSetting['section2'].'-'.$rowSetting['id'].'">'.ucfirst(_l($rowSetting['name'])).'</h2>';
    $form['name'] = $rowSetting['id'];
    $form['position'] = 'label-top';

    if (!array_key_exists('formconfig', $rowSetting))
    {
        $form['type'] = 'input';
        $form['value'] = $rowSetting['value'];

        return $form;
    }

    switch ($rowSetting['formconfig']['type']) {
        case 'color':
            $form['type'] = 'colorpicker';
            $form['value'] = '#ffffff';
            if (!empty($rowSetting['value']))
            {
                $rgb = explode(',', $rowSetting['value']);
                $form['value'] = sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
            }
            break;
        case 'bool':
            $form['type'] = 'btn2state';
            $form['className'] = 'twoState';
            $form['position'] = 'label-left';
            $form['checked'] = (int) ($rowSetting['value']);
            break;
        case 'int':
            if (array_key_exists('input', $rowSetting['formconfig']) && $rowSetting['formconfig']['input'] === 'select')
            {
                $form['type'] = 'select';
                $form['options'] = [];
                if (!empty($rowSetting['formconfig']['max']))
                {
                    for ($i = (int) $rowSetting['formconfig']['min']; $i <= (int) $rowSetting['formconfig']['max']; ++$i)
                    {
                        $option = [
                            'text' => "$i",
                            'value' => (int) $i,
                        ];

                        if ((int) $i === (int) $rowSetting['value'])
                        {
                            $option['selected'] = true;
                        }
                        $form['options'][] = $option;
                    }
                }
            }
            else
            {
                $form['type'] = 'template';
                $form['validate'] = 'validationFormMinMax';
                $form['value'] = [
                    'name' => $rowSetting['id'],
                    'value' => $rowSetting['value'],
                    'formObjectJs' => 'scWindowCoreSettingsMainLayout._settingsFormByTool',
                ];

                if (array_key_exists('min', $rowSetting['formconfig']))
                {
                    $form['value']['min'] = $rowSetting['formconfig']['min'];
                }

                if (array_key_exists('max', $rowSetting['formconfig']))
                {
                    $form['value']['max'] = $rowSetting['formconfig']['max'];
                }

                $form['format'] = 'modalInputNumberMinMax';
            }
            break;
        case 'date':
            $form['type'] = 'calendar';
            $form['dateFormat'] = '%Y-%m-%d';
            if (!empty($rowSetting['value']))
            {
                $form['value'] = $rowSetting['value'];
            }
            break;
        default:
            if (empty($rowSetting['formconfig']['input']))
            {
                $form['type'] = 'input';
                $form['value'] = $rowSetting['value'];
            }
            else
            {
                switch ($rowSetting['formconfig']['input']) {
                    case 'select':
                        $form['type'] = $rowSetting['formconfig']['input'];
                        $form['options'] = getSettingsOptions($rowSetting['formconfig']['options'], $rowSetting['value']);
                        break;
                    case 'radio':
                    case 'checkbox':
                        ## pas possible pour le moment.
                        ## car un choix de radio ou checkbox => une entrée unique et pas une liste d'options
                        break;
                    default:
                        $form['type'] = 'input';
                        $form['value'] = $rowSetting['value'];
                }
            }
    }

    return $form;
}

/**
 * @param string     $formConfigOption option type
 * @param string|int $fieldValue
 *
 * @return array
 */
function getSettingsOptions($formConfigOption, $fieldValue)
{
    $options = [];
    switch ($formConfigOption) {
        case 'cat-image-size':
            $imageTypes = ImageType::getImagesTypes();
            if (empty($imageTypes))
            {
                break;
            }
            foreach ($imageTypes as $type)
            {
                $option = [
                    'text' => $type['name'].' ('.$type['width'].'x'.$type['height'].')',
                    'value' => $type['name'],
                ];
                if ($type['name'] === $fieldValue)
                {
                    $option['selected'] = true;
                }
                $options[] = $option;
            }
            break;
        case 'cat-view':
        case 'cms-view':
        case 'man-view':
        case 'sup-view':
        case 'cus-view':
        case 'ord-view':
            list($interface, $none) = explode('-', $formConfigOption);

            return getGridViews($interface, $fieldValue);
        case 'cat-prop':
        case 'cat_combination-prop':
        case 'cms-prop':
        case 'man-prop':
        case 'sup-prop':
        case 'cus-prop':
        case 'ord-prop':
            list($interface, $none) = explode('-', $formConfigOption);

            return getProperties($interface, $fieldValue);
        case '1,2,languages':
            $languageList = Language::getLanguages(false, false);
            $options[] = [
                'text' => 1,
                'value' => 1,
            ];
            $options[] = [
                'text' => 2,
                'value' => 2,
            ];
            foreach ($languageList as $language)
            {
                $options[] = [
                    'text' => strtoupper($language['iso_code']).' - '.$language['name'],
                    'value' => $language['iso_code'],
                ];
            }
            break;
        default:
            $exploded = explode(',', $formConfigOption);
            if (!empty($exploded) && count($exploded) > 1)
            {
                foreach ($exploded as $item)
                {
                    $options[] = [
                        'text' => _l($item),
                        'value' => $item,
                    ];
                }
            }
    }

    return $options;
}

/**
 * @param string $interface
 * @param string $fieldValue
 *
 * @return array
 */
function getGridViews($interface, $fieldValue = '')
{
    switch ($interface) {
        case 'cat':
            $views = [
                'grid_light' => _l('Light view'),
                'grid_large' => _l('Large view'),
                'grid_delivery' => _l('Delivery'),
                'grid_price' => _l('Prices'),
                'grid_discount' => _l('Discounts'),
                'grid_seo' => _l('SEO'),
                'grid_reference' => _l('References'),
                'grid_description' => _l('Descriptions'),
                'grid_discount_2' => _l('Discounts and margins'),
                'grid_pack' => _l('Pack'),
            ];
            if ((defined('SC_UkooParts_ACTIVE') && SC_UkooParts_ACTIVE == 1) && SCI::moduleIsInstalled('ukooparts') && _r('GRI_CAT_ENABLE_UKOO_PARTS_FEATURES')) {
                $views['grid_everyparts'] = _l('EveryParts');
            }
            if (Sc\Service\Service::exists('shippingbo', true) && _r('GRI_CAT_VIEW_SHIPPINGBO')) {
                $views['grid_shippingbo'] = _l('Shippingbo');
            }
            $customViews = SC_Ext::readCustomGridsConfigXML('getCustomGrids');
            if ($customViews) {
                $views = array_merge($views, $customViews);
            }
            break;
        case 'cms':
            $views = [
                'grid_light' => _l('Light view'),
                'grid_large' => _l('Large view'),
                'grid_seo' => _l('SEO'),
                'grid_description' => _l('Descriptions'),
            ];
            $customViews = SC_Ext::readCustomCMSGridsConfigXML('getCustomGrids');
            if ($customViews) {
                $views = array_merge($views, $customViews);
            }
            break;
        case 'man':
            $views = [
                'grid_light' => _l('Light view'),
                'grid_large' => _l('Large view'),
                'grid_seo' => _l('SEO'),
            ];
            break;
        case 'sup':
            $views = [
                'grid_light' => _l('Light view'),
                'grid_large' => _l('Large view'),
                'grid_seo' => _l('SEO'),
                'grid_address' => _l('Address'),
            ];
            break;
        case 'cus':
            $views = [
                'grid_light' => _l('Light view'),
                'grid_large' => _l('Large view'),
                'grid_address' => _l('Addresses'),
                'grid_convert' => _l('Convert'),
            ];
            $customViews = SC_Ext::readCustomCustomersGridsConfigXML('getCustomGrids');
            if ($customViews) {
                $views = array_merge($views, $customViews);
            }
            break;
        case 'ord':
            $views = [
                'grid_light' => _l('Light view'),
                'grid_large' => _l('Large view'),
                'grid_picking' => _l('Picking'),
                'grid_delivery' => _l('Delivery'),
            ];
            $customViews = SC_Ext::readCustomOrdersGridsConfigXML('getCustomGrids');
            if ($customViews) {
                $views = array_merge($views, $customViews);
            }
            break;
        default:
            return [];
    }

    $options = [];
    foreach ($views as $viewId => $viewName)
    {
        $option = [
            'text' => $viewName,
            'value' => $viewId,
        ];
        if ($viewId === $fieldValue)
        {
            $option['selected'] = true;
        }
        $options[] = $option;
    }

    return $options;
}

/**
 * @param string $interface
 * @param string $fieldValue
 *
 * @return array
 */
function getProperties($interface, $fieldValue = '')
{
    switch ($interface) {
        case 'cat':
        case 'cms':
        case 'man':
        case 'sup':
        case 'cus':
        case 'ord':
            $options = [];
            $path = implode(DIRECTORY_SEPARATOR, [SC_DIR.'lib', $interface, '*']); ## all folders without windows
            foreach (glob($path, GLOB_ONLYDIR) as $filename)
            {
                $name = basename($filename);
                if (strpos($name, 'win-') !== false)
                {
                    continue;
                }
                $option = [
                    'text' => $name,
                    'value' => $name,
                ];
                if ($fieldValue === 'images')
                {
                    $fieldValue = 'image';
                }
                if ($name === $fieldValue)
                {
                    $option['selected'] = true;
                }
                $options[] = $option;
            }

            return $options;
        case 'cat_combination':
            $options = [];
            $path = implode(DIRECTORY_SEPARATOR, [SC_DIR.'lib', str_replace('_', DIRECTORY_SEPARATOR, $interface), '*']); ## all folders without windows
            foreach (glob($path, GLOB_ONLYDIR) as $filename)
            {
                $name = basename($filename);
                if (strpos($name, 'win-') !== false)
                {
                    continue;
                }
                $option = [
                    'text' => $name,
                    'value' => $name,
                ];
                if ($fieldValue === 'images')
                {
                    $fieldValue = 'image';
                }
                if ($name === $fieldValue)
                {
                    $option['selected'] = true;
                }
                $options[] = $option;
            }

            return $options;
        default:
            return [];
    }
}

/**
 * retrieve name or the part of the text found by querysearch.
 *
 * @param string      $name
 * @param string      $key
 * @param array|null  $foundValue
 * @param string|null $querySearch
 *
 * @return string
 */
function retrieveNodeName($name, $key, $foundValue = null, $querySearch = null)
{
    $name = ucfirst(_l($name));

    if (!$querySearch || !isset($foundValue[$key]))
    {
        return $name;
    }

    if(is_array($foundValue[$key])) {
        $name = $foundValue[$key][0];
        $description = $foundValue[$key][1];
        $splitted = explode(strtolower($querySearch), strtolower($description));
    } else {
        $splitted = explode(strtolower($querySearch), strtolower($foundValue[$key]));
    }


    $prev = '';
    if (!empty($splitted[0]))
    {
        if(isset($description)) {
            $prev = $name._l(':')."\r\n...";
        } else {
            $prev = $splitted[0];
        }
    }
    $next = '';
    if (!empty($splitted[1]))
    {
        if(isset($description)) {
            $next = '...';
        } else {
            $next = $splitted[1];
        }
    }

    $name = ucfirst($prev).'<b>'.$querySearch.'</b>'.$next;

    return str_replace(['<br>', '<br/>', '<br />'], '', $name);
}

header('Content-type: application/json');
echo json_encode($data);
exit;
