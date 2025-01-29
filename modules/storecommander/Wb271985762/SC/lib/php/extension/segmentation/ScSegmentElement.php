<?php

/* ScSegmentation */

class ScSegmentElement extends ObjectModel
{
    public $id;
    public $id_segment;
    public $id_element;
    public $type_element;

    protected $tables = array('sc_segment_element');

    protected $table = 'sc_segment_element';
    protected $identifier = 'id_segment_element';

    public function getFields()
    {
        parent::validateFields();
        $fields['id_segment'] = (int) $this->id_segment;
        $fields['id_element'] = (int) $this->id_element;
        $fields['type_element'] = ($this->type_element);

        return $fields;
    }

    public static function checkInSegment($id_segment, $id_element, $type)
    {
        $return = false;

        $sql = 'SELECT id_segment_element 
                FROM '._DB_PREFIX_."sc_segment_element 
                WHERE id_segment='".(int)$id_segment."'
                    AND id_element='".(int)$id_element."'
                    AND type_element='".pSQL($type)."'
                LIMIT 1";
        $res = Db::getInstance()->executeS($sql);
        if (!empty($res[0]['id_segment_element']))
        {
            $return = true;
        }

        return $return;
    }

    public static function productAlreadyInSegmentForShop($id_segment, $id_element, $id_shop)
    {
        $return = false;
        $query = new DbQuery();
        $query->select('seg.id_segment_element')
            ->from('sc_segment_element', 'seg')
            ->leftJoin('product_shop', 'ps', 'ps.id_product = seg.id_segment_element AND ps.id_shop=:id_shop')
            ->where('id_segment = :id_segment')
            ->where('id_element = :id_element')
            ->where('type_element = "product"')
            ->where('ps.id_shop=:id_shop')
            ->limit(1)
        ;
        $stmt = Db::getInstance()->getLink()->prepare($query);
        $stmt->execute([
            ':id_segment' => $id_segment,
            ':id_element' => $id_element,
            ':id_shop' => $id_shop,
        ]);
        return $stmt->fetchColumn();
    }



    public static function addProduct($idSegment, $product)
    {
        $pdo = Db::getInstance()->getLink();
        $stmt = $pdo->prepare('INSERT IGNORE INTO `' . _DB_PREFIX_ . 'sc_segment_element` (`id_segment`, `id_element`, `type_element`) VALUES (:id_segment,:id_element,:type_element)');
        return $stmt->execute(array(
            ':id_segment' => (int)$idSegment,
            ':id_element' => (int)$product->id,
            ':type_element' => 'product'
        ));

    }
}
