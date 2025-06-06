<?php
if (!defined('STORE_COMMANDER')) { exit; }

$product_ids = Tools::getValue('product_ids', 0);
$id_lang = (int) Tools::getValue('id_lang');

## Avant tout on supprime les doublons avant affichage
if($product_ids)
{
    $products = explode(',', $product_ids);
    foreach ($products as $id_product)
    {
        delete_duplicate($id_product);
    }
}
$sql = 'SELECT cpt.id_product, cptc.*, fl.name, cl.value, f.position
        FROM '._DB_PREFIX_.'ukoocompat_compat cpt
        INNER JOIN '._DB_PREFIX_.'ukoocompat_compat_criterion cptc 
            ON cpt.id_ukoocompat_compat = cptc.id_ukoocompat_compat
        LEFT JOIN '._DB_PREFIX_.'ukoocompat_filter f 
            ON f.id_ukoocompat_filter = cptc.id_ukoocompat_filter
        LEFT JOIN '._DB_PREFIX_.'ukoocompat_filter_lang fl 
            ON fl.id_ukoocompat_filter = f.id_ukoocompat_filter AND fl.id_lang = '.(int) $id_lang.'
        LEFT JOIN '._DB_PREFIX_.'ukoocompat_criterion_lang cl 
            ON cl.id_ukoocompat_criterion = cptc.id_ukoocompat_criterion AND cl.id_lang = '.(int) $id_lang.'
        WHERE cpt.id_product IN ('.pInSQL($product_ids).')
        ORDER BY f.position ASC';
try {
    $res = Db::getInstance()->ExecuteS($sql);
} catch (Exception $e) {
    $res = false;
}
$all_compatibilities = array();
$filter_array = array();
$first_id = 0;
if (!empty($res))
{
    foreach ($res as $data)
    {
        if ($first_id == 0)
        {
            $first_id = $data['id_ukoocompat_compat'];
        }
        $filter_array[] = $data['id_ukoocompat_filter'];
        $all_compatibilities[$data['id_ukoocompat_compat']]['id_product'] = $data['id_product'];
        $all_compatibilities[$data['id_ukoocompat_compat']]['filters'][$data['id_ukoocompat_filter']]['position'] = $data['position'];
        $all_compatibilities[$data['id_ukoocompat_compat']]['filters'][$data['id_ukoocompat_filter']]['filter_name'] = $data['name'];
        $all_compatibilities[$data['id_ukoocompat_compat']]['filters'][$data['id_ukoocompat_filter']]['criterion_id'] = $data['id_ukoocompat_criterion'];
        $all_compatibilities[$data['id_ukoocompat_compat']]['filters'][$data['id_ukoocompat_filter']]['criterion_value'] = $data['value'];
    }
}

$filter_array = array_unique($filter_array);

function attachHeader()
{
    global $all_compatibilities,$first_id;
    $return = '';
    if(!$all_compatibilities) {
        return $return;
    }
    for ($i = 1; $i <= count($all_compatibilities[$first_id]['filters']); ++$i)
    {
        $return .= ',#text_filter';
    }

    return $return;
}

function getCompatibilities()
{
    global $all_compatibilities,$filter_array;

    if (count($all_compatibilities) > 0)
    {
        foreach ($all_compatibilities as $id_compatibility => $compatibility)
        {
            $return = '';
            $return .= '<row id="'.$id_compatibility.'">';
            $return .= '<cell>'.$compatibility['id_product'].'</cell>';
            $return .= '<cell>'.$id_compatibility.'</cell>';

            $filled_filters = array();
            foreach ($compatibility['filters'] as $id_filter => $filter)
            {
                $filled_filters[] = $id_filter;
                $return .= '<cell><![CDATA['.(!empty($filter['criterion_value']) ? $filter['criterion_value'] : _l('All criteria')).']]></cell>';
            }

            ## on vérifie si des filtres n'ont pas été oubliés
            $nb_filters = count($filter_array);
            $nb_current_filters = count($compatibility['filters']);
            if ($nb_filters != $nb_current_filters)
            {
                ## si c'est le cas, on reseigne la valeur par défaut
                $to_create = array_diff($filter_array, $filled_filters);
                foreach ($to_create as $void)
                {
                    $return .= '<cell><![CDATA['._l('All criteria').']]></cell>';
                }
            }
            $return .= '</row>';
            echo $return;
        }
    }
}

// pour supprimer les doublons...
function delete_duplicate($id_product)
{
    $sql = 'SELECT ucc.*
            FROM '._DB_PREFIX_.'ukoocompat_compat_criterion ucc
            LEFT JOIN '._DB_PREFIX_.'ukoocompat_compat uc ON uc.id_ukoocompat_compat = ucc.id_ukoocompat_compat
            WHERE uc.id_product = '.(int) $id_product;
    $res = DB::getInstance()->executeS($sql);
    if (!empty($res))
    {
        $compat_cached = array();
        foreach ($res as $k => $compat)
        {
            $compat_cached[$compat['id_ukoocompat_compat']][$k] = $compat['id_ukoocompat_filter'].'_'.$compat['id_ukoocompat_criterion'];
        }
        $compat_array_to_diff = array();
        foreach ($compat_cached as $id_compat => $compat_data)
        {
            $compat_array_to_diff[$id_compat] = implode(',', $compat_data);
        }
        $diffs = array_diff_assoc($compat_array_to_diff, array_unique($compat_array_to_diff));
        if (!empty($diffs))
        {
            $id_to_delete = array_keys($diffs);
            $sql = 'DELETE FROM '._DB_PREFIX_.'ukoocompat_compat 
                        WHERE id_ukoocompat_compat IN ('.pInSQL(implode(',', $id_to_delete)).')';
            if (Db::getInstance()->Execute($sql))
            {
                $sql = 'DELETE FROM '._DB_PREFIX_.'ukoocompat_compat_criterion 
                        WHERE id_ukoocompat_compat NOT IN ((SELECT id_ukoocompat_compat FROM '._DB_PREFIX_.'ukoocompat_compat))';
                Db::getInstance()->Execute($sql);
            }
        }
    }
}

if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml') || stristr($_SERVER['HTTP_ACCEPT'], '*/*'))
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
            <call command="attachHeader"><param><![CDATA[#numeric_filter,#numeric_filter<?php echo attachHeader(); ?>]]></param></call>
        </beforeInit>
        <column id="id_product" width="70" type="ro" align="center" sort="int"><?php echo _l('Product'); ?></column>
        <column id="id" width="70" type="ro" align="center" sort="int"><?php echo _l('ID'); ?></column>

        <?php
            if (!empty($all_compatibilities))
            {
                foreach ($all_compatibilities[$first_id]['filters'] as $id_filter => $filter)
                {
                    ?>
            <column id="filter_<?php echo $id_filter; ?>" type="combo" source="index.php?ajax=1&amp;act=cat_productcompatibility_criterion_combo_get&amp;ajaxCall=1&amp;id_filter=<?php echo $id_filter; ?>&amp;id_lang=<?php echo $id_lang; ?>" auto="true" cache="false" sort="str"><?php echo $filter['filter_name']; ?></column>
        <?php
                }
            }
        ?>
    </head>
<?php
    echo '<userdata name="uisettings">'.uisettings::getSetting('cat_productcompatibility').'</userdata>'."\n";
    getCompatibilities();
?>
</rows>
