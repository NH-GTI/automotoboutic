<?php

namespace Sc\Service\Shippingbo\Repository\Prestashop;

use Combination;
use Db;
use DbQuery;
use SCI;
use ScSegment;
use ScSegmentElement;

class SegmentRepository
{
    const TYPE_PENDING = 'pending';
    const TYPE_DATE = 'by_date';

    /**
     * @var \mysqli|\PDO|resource|null
     */
    protected $pdo;
    public $id;
    public $scAgent;

    public function __construct($sc_agent)
    {
        $this->scAgent = $sc_agent;
    }

    /**
     * get shippingbo root segment id and create it if needed.
     *
     * @throws \PrestaShopException
     * @throws \PrestaShopDatabaseException
     */
    public static function createRootSegmentIfNeeded()
    {
        if (!self::getRootSegmentId())
        {
            $SboRootSegment = new \ScSegment();
            $SboRootSegment->id_parent = 0;
            $SboRootSegment->name = 'Shippingbo';
            $SboRootSegment->type = 'manual';
            $SboRootSegment->access = '-catalog-';
            $SboRootSegment->description = _l('Automatically created from Shippingbo service')."\n\n";

            return $SboRootSegment->add();
        }

        return false;
    }

    public static function getRootSegmentId()
    {
        $pdo = \Db::getInstance()->getLink();
        $stmt = $pdo->prepare('SELECT id_segment from `'._DB_PREFIX_.'sc_segment` WHERE name = :name');
        $stmt->execute([':name' => 'Shippingbo']);
        $SboRootSegment = $stmt->fetch(\PDO::FETCH_OBJ);

        return $SboRootSegment->id_segment;
    }

    /**
     * @throws \PrestaShopException
     * @throws \PrestaShopDatabaseException
     */
    public static function getPendingSegmentId($sboRootSegmentId, $sc_agent)
    {
        $pdo = \Db::getInstance()->getLink();
        $stmt = $pdo->prepare('SELECT id_segment from `'._DB_PREFIX_.'sc_segment` WHERE name = :name AND id_parent = :sbo_root_segment_id');
        $stmt->execute([':name' => _l('Pending products'), ':sbo_root_segment_id' => $sboRootSegmentId]);
        if ($stmt->rowCount() === 0)
        {
            $SboPendingSegment = new \ScSegment();
            $SboPendingSegment->id_parent = $sboRootSegmentId;
            $SboPendingSegment->name = _l('Pending products');
            $SboPendingSegment->type = 'manual';
            $SboPendingSegment->access = '-catalog-';
            $SboPendingSegment->description = _l('Automatically created from Shippingbo import by %s %s on %s', false, [$sc_agent->firstname, $sc_agent->lastname, date('Y-m-d H:i:s')])."\n\n";
            $SboPendingSegment->add();

            return $SboPendingSegment->id;
        }
        else
        {
            return $stmt->fetch(\PDO::FETCH_COLUMN);
        }
    }

    /**
     * TODO 1 : remplacer par du générique !segmentExists() -> new Segment() -> save + getSegment(id)).
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function getInstantSboSegmentId($sboRootSegmentId, $sc_agent)
    {
        $date = date('Y-m-d H:i:s');
        $SboPendingSegment = new \ScSegment();
        $SboPendingSegment->id_parent = $sboRootSegmentId;
        $SboPendingSegment->name = _l('Products from %s', false, [$date]);
        $SboPendingSegment->type = 'manual';
        $SboPendingSegment->access = '-catalog-';
        $SboPendingSegment->description = _l('Automatically created from Shippingbo import by %s %s on %s', false, [$sc_agent->firstname, $sc_agent->lastname, $date])."\n\n";
        $SboPendingSegment->add();

        return $SboPendingSegment->id;
    }

    public static function getSegmentsIds($sc_agent)
    {
        $pdo = \Db::getInstance()->getLink();
        $stmt = $pdo->prepare('SELECT id_segment from `'._DB_PREFIX_.'sc_segment` WHERE id_parent = :sbo_root_segment_id');
        $stmt->execute([':sbo_root_segment_id' => self::getRootSegmentId()]);

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function countProductsBySegmentId($id_segment, $id_shop)
    {
        $pdo = \Db::getInstance()->getLink();
        $query = (new DbQuery())
            ->select('COUNT(seg.id_element)')
            ->from('sc_segment_element', 'seg')
            ->leftJoin('product_shop', 'ps', 'seg.id_element = ps.id_product AND ps.id_shop = :id_shop')
            ->where('seg.id_segment = :id_segment')
            ->where('seg.type_element="product"')
            ->where('ps.id_shop=:id_shop')
        ;
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_segment' => $id_segment,
            ':id_shop' => $id_shop,
        ]);

        return $stmt->fetchColumn();
    }

    /**
     * @return void
     */
    public function addProduct($product, $id_shop)
    {
        if (ScSegmentElement::productAlreadyInSegmentForShop($this->id, $product->id, $id_shop))
        {
            return;
        }

        $stmt = $this->getPdo()->prepare('INSERT INTO `'._DB_PREFIX_.'sc_segment_element` (`id_segment`, `id_element`, `type_element`) VALUES (:id_segment,:id_element,:type_element)');
        $stmt->execute([
            ':id_segment' => (int) $this->id,
            ':id_element' => (int) $product->id,
            ':type_element' => 'product',
        ]);
    }

    /**
     * @param int $id_shop
     *
     * @return void
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function clearSboSegment($id_shop = 0)
    {
        $pdo = \Db::getInstance()->getLink();
        $shopIds = $id_shop === 0 ? array_column(SCI::getAllShops(false), 'id_shop') : [$id_shop];
        $SboRootSegmentId = self::getRootSegmentId();
        // recuperation de tous les id segments à supprimer
        $stmt = $pdo->prepare('SELECT id_segment from `'._DB_PREFIX_.'sc_segment` WHERE id_parent = :id_segment_sbo_root');
        $stmt->execute([':id_segment_sbo_root' => $SboRootSegmentId]);
        $segmentIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $segmentIds = $segmentIds ?: [];
        if (!$SboRootSegmentId)
        {
            return;
        }
        array_push($segmentIds, $SboRootSegmentId);

        // suppression de tous les produits contenus dans ces segments
        // TODO 1 : a reporter dans une methode + à ajouter dans la suppression depuis interface de gestion des segments
        $stmt = $pdo->prepare('SELECT id_element from `'._DB_PREFIX_.'sc_segment_element` WHERE id_segment IN('.implode(',', $segmentIds).') AND type_element = :type_element');
        $stmt->execute([':type_element' => 'product']);
        if (!$productIds = $stmt->fetchAll(\PDO::FETCH_COLUMN))
        {
            return;
        }
        foreach ($productIds as $productId)
        {
            foreach ($shopIds as $shopId)
            {
                $product = new \Product($productId, false, null, $shopId);

                if (empty($product->getCategories()))
                {
                    SCI::removeProductFromShop($productId, $shopId);
                    // check if product is used in some shop
                    $stmt = $pdo->prepare('SELECT id_product FROM `'._DB_PREFIX_.'product_shop` WHERE `id_product` = :id_product');
                    $params = [
                        ':id_product' => $productId,
                    ];
                    // si plus utilisé, on peut supprimer les entrées dans les tables qui ne sont plus nécessaires
                    if ($stmt && $stmt->execute($params) && $stmt->rowCount() === 0)
                    {
                        // remove product attribute combinations
                        $stmt = $pdo->prepare('SELECT id_product_attribute FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product = '.(int) $productId);
                        $stmt->execute();
                        if ($id_product_attributes = $stmt->fetchAll(\PDO::FETCH_COLUMN))
                        {
                            foreach ($id_product_attributes as $id_product_attribute)
                            {
                                $combination = new Combination($id_product_attribute);
                                $combination->delete();
                            }
                        }

                        // remove product
                        $stmt = $pdo->prepare('DELETE FROM `'._DB_PREFIX_.'product` WHERE id_product = '.(int) $productId);
                        $stmt->execute();

                        // remove lang : no shop_id because once product created in product table -> translation for all available shops is inserted
                        $stmt = $pdo->prepare('DELETE FROM `'._DB_PREFIX_.'product_lang` WHERE id_product = '.(int) $productId);
                        $stmt->execute();
                        // remove pack entries
                        $stmt = $pdo->prepare('DELETE FROM `'._DB_PREFIX_.'pack` WHERE id_product_pack = '.(int) $productId);
                        $stmt->execute();
                        // suppression des segment elements
                        $stmt = $pdo->prepare('DELETE FROM `'._DB_PREFIX_.'sc_segment_element` WHERE id_segment IN('.implode(',', $segmentIds).') AND type_element = :type_element AND id_element=:id_product');
                        $stmt->execute([
                            ':type_element' => 'product',
                            ':id_product' => $productId,
                        ]);
                        // remove stock_available
                        if (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
                        {
                            $stmt = $pdo->prepare('DELETE FROM `'._DB_PREFIX_.'stock_available` WHERE id_product = '.(int) $productId);
                            $stmt->execute();
                        }

                        SCI::hookExec('actionProductDelete', ['id_product' => (int) $productId, 'deleteAllAttributes' => true]);
                    }
                }
            }
        }

        foreach ($segmentIds as $segmentId)
        {
            if (ScSegment::hasNoProducts($segmentId))
            {
                // suppression segment
                $stmt = $pdo->prepare('DELETE FROM `'._DB_PREFIX_.'sc_segment` WHERE id_segment = :id_segment');
                $stmt->execute([':id_segment' => $segmentId]);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \mysqli|\PDO|resource|null
     */
    public function getPdo()
    {
        if (!$this->pdo)
        {
            $this->pdo = Db::getInstance()->getLink();
        }

        return $this->pdo;
    }
}
