<?php

use Sc\Service\Shippingbo\Shippingbo;

if (!defined('STORE_COMMANDER'))
{
    exit;
}
$pdo = Db::getInstance()->getLink();

$return = 'ERROR: Try again later';
$shippingboService = Shippingbo::getInstance();

// FUNCTIONS

// Récupération de toutes les modifications à effectuer
if (Tools::getValue('rows'))
{
    if (_PS_MAGIC_QUOTES_GPC_)
    {
        $_POST['rows'] = Tools::getValue('rows');
    }
    $rows = json_decode($_POST['rows']);

    if (count($rows) > 0)
    {
        $callbacks = [];

        // Première boucle pour remplir la table sc_queue_log
        // avec toutes ces modifications
        $log_ids = [];
        $date = date('Y-m-d H:i:s');
        foreach ($rows as $num => $row)
        {
            $id = QueueLog::add($row->name, $row->row, $row->action, !empty($row->params) ? $row->params : [], !empty($row->callback) ? $row->callback : null, $date);
            $log_ids[$num] = $id;
        }

        // Deuxième boucle pour effectuer les
        // actions les une après les autres
        foreach ($rows as $num => $row)
        {
            $params = (array) json_decode($row->params, true);
            $shippingboService->switchToShopId((int) $row->id_shop);

            $rowIdPattern = '/P#(\d+)-A#(\d+)-SBO#(\d+)/';
            preg_match($rowIdPattern, $params['rowId'], $rowIds);
            list($rowId, $id_product, $id_product_attribute, $id_sbo) = $rowIds;
            if (!empty($log_ids[$num]))
            {
                if (!empty($row->callback))
                {
                    $callbacks[] = $row->callback;
                }

                if ($params['property'] === 'is_locked')
                {
                    if($id_sbo){
                        $sboShopRelations = $shippingboService->getShopRelationRepository()->getAllByIdSboForCurrentShop($id_sbo);
                    } elseif($id_product) {
                        $sboShopRelations = $shippingboService->getShopRelationRepository()->getAllByProductId($id_product);
                    } else {
                        throw new Exception('bad request : missing parameters');
                    }
                    foreach($sboShopRelations as $sboShopRelation){
                        $sboShopRelation
                            ->setIsLocked((bool) $params['value'])
                            ->save()
                        ;
                    }
                }

                QueueLog::delete($log_ids[$num]);
            }
        }

        // RETURN
        $return = json_encode(['callback' => implode(';', $callbacks)]);
    }
}
echo $return;
