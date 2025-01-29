<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$id_objet = (int) Tools::getValue('gr_id', 0);

$action = $newId = '';

if (isset($_POST['!nativeeditor_status']) && trim($_POST['!nativeeditor_status']) == 'updated')
{
    $fields = ['firstname', 'lastname', 'alias', 'address1', 'address2', 'postcode', 'city', 'id_state', 'id_country', 'phone', 'phone_mobile', 'other', 'company'];

    try
    {
        $address = new Address($id_objet);
        foreach ($fields as $field)
        {
            if (array_key_exists($field, $_POST))
            {
                $address->{$field} = Tools::getValue($field);
                addToHistory('address', 'modification', $field, (int) $id_objet, 0, _DB_PREFIX_.'address', pSQL(Tools::getValue($field)));
            }
        }
        $address->hydrate($address->getFields());
        $address->update();
    }
    catch (Exception $e)
    {
        exit(json_encode(['error' => $e->getMessage()]));
    }
    $newId = Tools::getValue('gr_id');
    $action = 'update';
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
echo '<data>';
echo "<action type='".$action."' sid='".Tools::getValue('gr_id')."' tid='".$newId."'/>";
echo '</data>';
