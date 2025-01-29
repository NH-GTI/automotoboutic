<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$id_lang = (int) Tools::getValue('id_lang');
$action = Tools::getValue('action');
$id_element = (int) Tools::getValue('id_element');
$value = (bool) Tools::getValue('value');
$ids = Tools::getValue('ids');

switch ($action)
{
    case 'present':
        doSegmentAction((int) Tools::getValue('id_segment'), $ids, $value);
        break;
    case 'mass_present':
        doSegmentAction(Tools::getValue('segments'), $ids, $value);
        break;
}

/**
 * @param int|string $segments    id or list id of segment
 * @param string     $elementList list of id_order
 * @param bool       $value       prensent or not
 *
 * @return void
 */
function doSegmentAction($segments, $elementList, $value)
{
    if (empty($segments) || empty($elementList))
    {
        return;
    }

    $segmentList = explode(',', (string) $segments);
    $elementList = explode(',', (string) $elementList);
    foreach ($segmentList as $id_segment)
    {
        if (!$value)
        {
            $sql = 'DELETE FROM '._DB_PREFIX_.'sc_segment_element
                    WHERE id_segment ='.(int) $id_segment.' 
                    AND type_element="order"
                    AND id_element IN ('.pInSQL($elementList).')';
            Db::getInstance()->execute($sql);

            continue;
        }

        $segment = new ScSegment((int) $id_segment);
        foreach ($elementList as $id_element)
        {
            if (!ScSegmentElement::checkInSegment($id_segment, $id_element, 'order'))
            {
                $manual_add = 0;
                if ($segment->type == 'manual')
                {
                    $manual_add = 1;
                }
                elseif ($segment->auto_file)
                {
                    $file = $segment->auto_file.'.php';
                    if (file_exists(SC_SEGMENTS_DIR.$file))
                    {
                        require_once SC_SEGMENTS_DIR.$file;
                        $instance = new $segment->auto_file();
                        if ($instance->manually_add_in == 'Y')
                        {
                            $manual_add = 1;
                        }
                    }
                }
                if ($manual_add)
                {
                    $segment_element = new ScSegmentElement();
                    $segment_element->id_segment = (int) $id_segment;
                    $segment_element->id_element = (int) $id_element;
                    $segment_element->type_element = 'order';
                    $segment_element->save();
                }
            }
        }
    }
}
