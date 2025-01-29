<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$data = [
    'head' => [
        [
            'id' => 'id_category',
            'width' => 60,
            'type' => 'ron',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('id_category'),
            'filter' => '#numeric_filter',
        ],
            [
            'id' => 'id_shop',
            'width' => 60,
            'type' => 'ron',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('id_shop'),
            'filter' => '#select_filter',
        ],
            [
            'id' => 'id_shop_default',
            'width' => 60,
            'type' => 'ron',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('id_shop_default'),
            'filter' => '#select_filter',
        ],
        [
            'id' => 'path',
            'width' => 200,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('complete path'),
            'filter' => '#text_filter',
        ],
        [
            'id' => 'active',
            'width' => 40,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('active'),
            'filter' => '#select_filter',
        ],
        [
            'id' => 'imageURL',
            'width' => 200,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('imageURL'),
            'filter' => '#text_filter',
        ],
    ],
    'rows' => [],
    'filterList' => [],
];

if (!SCMS)
{
    unset($data['head'][1], $data['head'][2]);
}

foreach ($languages as $lang)
{
    $data['head'] = array_merge($data['head'], [
        [
            'id' => 'customergroups',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('customer groups').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'name',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('name').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'description',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('description').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'full_url',
            'width' => 200,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('url').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'link_rewrite',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('link_rewrite').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'meta_title',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('meta_title').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'meta_description',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('meta_description').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
        [
            'id' => 'meta_keywords',
            'width' => 120,
            'type' => 'ro',
            'align' => 'left',
            'sort' => 'na',
            'value' => _l('meta_keywords').' ('.$lang['iso_code'].')',
            'filter' => '#text_filter',
        ],
    ]);
}

foreach ($data['head'] as $header)
{
    $data['filterList'][] = $header['filter'];
}

$data['filterList'] = implode(',', $data['filterList']);

// Fonction pour construire le chemin d'une catégorie
/**
 * @param $categoryId
 * @param $categories
 *
 * @return array
 */
function buildPath($categoryId, &$categories)
{
    if (empty($categories[$categoryId]['path']))
    {
        $path = [];
        $currentId = $categoryId;
        while ($currentId != 0 && isset($categories[$currentId]))
        {
            array_unshift($path, $currentId);
            $currentId = (int) $categories[$currentId]['id_parent'];
        }
        $categories[$categoryId]['path'] = $path;
    }

    return $categories[$categoryId]['path'];
}

$idDefaultLang = (int) SCI::getConfigurationValue('PS_LANG_DEFAULT');
$idRootCategory = (int) SCI::getConfigurationValue('PS_ROOT_CATEGORY', null, null, 3);
$listCategoryRecycleBin = Db::getInstance()->getValue((new DbQuery())
                                                        ->select('GROUP_CONCAT(DISTINCT(id_category)) as bin_category_list')
                                                        ->from('category_lang')
                                                        ->where('name ="SC Recycle Bin"')
                                                        );

$sql = (new DbQuery())
    ->select('c.`id_category`')
    ->select('c.`id_parent`')
    ->select('cs.`id_shop`')
    ->select('c.`id_shop_default`')
    ->select('c.`active`')
    ->from('category', 'c')
    ->leftJoin('category_shop', 'cs', 'cs.`id_category` = c.`id_category`')
    ->where('c.`id_category` <> '.(int) $idRootCategory)
    ->where('c.`id_category` NOT IN ('.pInSQL($listCategoryRecycleBin).')')
    ->orderBy('c.`level_depth`')
    ->orderBy('c.`id_category`')
    ->orderBy('cs.`id_shop`')
;

$categoryData = Db::getInstance()->executeS($sql);
if ($categoryData)
{
    $sql = (new DbQuery())
        ->select('cl.id_category')
        ->select('cl.id_shop')
        ->select('cl.id_lang')
        ->select('cl.name')
        ->select('cl.description')
        ->select('cl.link_rewrite')
        ->select('cl.meta_title')
        ->select('cl.meta_description')
        ->select('cl.meta_keywords')
        ->select('GROUP_CONCAT(DISTINCT(gl.`name`)) AS group_list')
        ->from('category_lang', 'cl')
        ->leftJoin('category_group', 'cg', 'cg.`id_category` = cl.`id_category`')
        ->leftJoin('group_shop', 'gs', 'gs.`id_group` = cg.`id_group` AND gs.`id_shop` = cl.`id_shop`')
        ->leftJoin('group_lang', 'gl', 'gl.`id_group` = gs.`id_group` AND gl.`id_lang` = cl.`id_lang`')
        ->where('cl.`id_category` <> '.(int) $idRootCategory)
        ->groupBy('cl.`id_category`')
        ->groupBy('cl.`id_shop`')
        ->groupBy('cl.`id_lang`')
        ->orderBy('cl.`id_category`')
        ->orderBy('cl.`id_shop`')
        ->orderBy('cl.`id_lang`')
        ;
    $categoryDataLang = [];
    $rawCategoryDataLang = Db::getInstance()->executeS($sql);
    if ($rawCategoryDataLang)
    {
        $link = new Link();
        foreach ($rawCategoryDataLang as $categoryLang)
        {
            $categoryDataLang[(int) $categoryLang['id_category']][(int) $categoryLang['id_shop']][(int) $categoryLang['id_lang']] = [
                'group_list' => $categoryLang['group_list'],
                'name' => $categoryLang['name'],
                'description' => $categoryLang['description'],
                'link_rewrite' => $categoryLang['link_rewrite'],
                'meta_title' => $categoryLang['meta_title'],
                'meta_description' => $categoryLang['meta_description'],
                'meta_keywords' => $categoryLang['meta_keywords'],
                'full_url' => $link->getCategoryLink($categoryLang['id_category'], $categoryLang['link_rewrite'], (int) $categoryLang['id_lang'], null, (int) $categoryLang['id_shop']),
            ];
        }
    }

    $sql = (new DbQuery())
        ->select('cs.`id_shop`')
        ->select('c.`id_category`')
        ->select('c.`id_parent`')
        ->from('category', 'c')
        ->innerJoin('category_shop', 'cs', 'c.`id_category` = cs.`id_category`')
        ->where('c.id_category <> '.(int) $idRootCategory)
        ->orderBy('cs.`id_shop`')
        ->orderBy('c.`level_depth`')
        ->orderBy('cs.`position`')
    ;
    $nestedCategory = $nestedShopCategory = [];
    $rawNestedCategory = Db::getInstance()->executeS($sql);
    if ($rawNestedCategory)
    {
        foreach ($rawNestedCategory as $row)
        {
            $nestedShopCategory[$row['id_shop']][$row['id_category']] = [
                'id_parent' => $row['id_parent'],
                'path' => [],
            ];
        }

        // Construire les chemins pour toutes les catégories
        foreach ($nestedShopCategory as $shopId => &$categories)
        {
            foreach (array_keys($categories) as $categoryId)
            {
                buildPath($categoryId, $categories);
            }
        }
    }

    foreach ($categoryData as $category)
    {
        if ((int) $category['id_category'] == (int) $category['id_parent'])
        {
            exit(_l('A category cannot be parent of itself, you must fix this error for category ID').' '.$category['id_category']);
        }

        $image_path = _PS_CAT_IMG_DIR_.(int) $category['id_category'].'.jpg';
        $image_url = '';
        if (file_exists($image_path))
        {
            $image_url = Tools::getShopDomainSsl(true).__PS_BASE_URI__.'img/c/'.(int) $category['id_category'].'.jpg';
        }

        $pathCategoryList = (isset($nestedShopCategory[$category['id_shop']][$category['id_category']]) ? $nestedShopCategory[$category['id_shop']][$category['id_category']]['path'] : null);
        if ($pathCategoryList)
        {
            foreach ($pathCategoryList as &$pathCategory)
            {
                $pathCategory = $categoryDataLang[(int) $pathCategory][(int) $category['id_shop']][(int) $idDefaultLang]['name'];
            }
        }

        $tmp = [
            'id' => $category['id_category'].'_'.$category['id_shop'],
            'data' => [
                'id_category' => (int) $category['id_category'],
                'id_shop' => (int) $category['id_shop'],
                'id_shop_default' => (int) $category['id_shop_default'],
                'path' => ($pathCategoryList ? implode(' > ', $pathCategoryList) : ''),
                'active' => (int) $category['active'],
                'imageURL' => $image_url,
            ],
        ];

        if (!SCMS)
        {
            unset($tmp['data']['id_shop'], $tmp['data']['id_shop_default']);
        }

        $tmp['data'] = array_values($tmp['data']);

        foreach ($languages as $lang)
        {
            if (isset($categoryDataLang[(int) $category['id_category']][(int) $category['id_shop']][(int) $lang['id_lang']]))
            {
                $translationItem = $categoryDataLang[(int) $category['id_category']][(int) $category['id_shop']][(int) $lang['id_lang']];
                $tmp['data'][] = $translationItem['group_list'];
                $tmp['data'][] = $translationItem['name'];
                $tmp['data'][] = str_replace(["\t", "\r", "\n"], ['', '', ''], $translationItem['description']);
                $tmp['data'][] = $translationItem['full_url'];
                $tmp['data'][] = $translationItem['link_rewrite'];
                $tmp['data'][] = $translationItem['meta_title'];
                $tmp['data'][] = $translationItem['meta_description'];
                $tmp['data'][] = $translationItem['meta_keywords'];
                $translationItem = null;
            }
            else
            {
                $tmp['data'][] = '';
                $tmp['data'][] = '';
                $tmp['data'][] = '';
                $tmp['data'][] = '';
                $tmp['data'][] = '';
                $tmp['data'][] = '';
                $tmp['data'][] = '';
                $tmp['data'][] = '';
            }
        }

        $data['rows'][] = $tmp;
        $tmp = null;
    }
}

header('Content-type:application/json');
exit(json_encode($data));
