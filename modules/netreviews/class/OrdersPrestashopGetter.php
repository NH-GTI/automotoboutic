<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    NetReviews SAS <contact@avis-verifies.com>
 * @copyright 2012-2024 NetReviews SAS
 * @license   NetReviews
 *
 * @version   Release: $Revision: 9.0.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class OrdersPrestashopGetter
{
    /** @var int|string|null */
    protected $idShop;
    /** @var string|null */
    protected $groupName;
    /** @var int */
    protected $limit;
    /** @var int */
    protected $offset;
    /** @var bool */
    protected $withProducts = false;

    /**
     * @param int|string|null $idShop
     * @param string|null $groupName
     */
    public function __construct($idShop, $groupName)
    {
        $this->idShop = $idShop;
        if (is_null($groupName)) {
            $groupName = '';
        }
        $this->groupName = $groupName;
        $this->limit = 1;
        $this->offset = 0;
        $this->withProducts = false;
    }

    /**
     * @param int $limit
     *
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @param int $offset
     *
     * @return void
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;
    }

    /**
     * @param bool $withProducts
     *
     * @return void
     */
    public function setProducts(bool $withProducts = false)
    {
        $this->withProducts = $withProducts;
    }

    /**
     * @return string
     */
    private function makeSelect()
    {
        $select = 'select '
            . 'o.id_order, o.date_add, o.id_customer, o.total_paid,  o.id_lang, o.id_shop, o.current_state, s.name AS shop_name, '
            . 'c.firstname, c.lastname, c.email, lg.iso_code ';

        if ($this->withProducts) {
            $minMpnVersion = '1.7.7';

            $mpnField = 'od.product_mpn AS mpn';
            if (version_compare(_PS_VERSION_, $minMpnVersion, '<')) {
                $mpnField = 'od.product_supplier_reference AS mpn';
            }
            $select .= ', od.product_id, od.product_name, od.product_quantity, od.product_price, od.product_reference, cat.name AS category, i.id_image '
                . ", m.name AS manufacturer_name, od.product_supplier_reference, od.product_price AS price, od.product_upc AS upc, $mpnField, od.product_isbn AS isbn, od.product_ean13 AS ean13 ";
        }

        return $select;
    }

    /**
     * @return string
     */
    private function makeFrom()
    {
        $leftJoinStatment = ' LEFT JOIN ';
        $whereLimits = $this->makeWhere() . $this->makeEndQuery();
        $from = ' FROM '
            . "(SELECT id_order FROM ps_orders $whereLimits ) AS lo "
            . $leftJoinStatment . _DB_PREFIX_ . 'orders o ON o.id_order = lo.id_order'
            . $leftJoinStatment . _DB_PREFIX_ . 'customer c ON o.id_customer = c.id_customer '
            . $leftJoinStatment . _DB_PREFIX_ . 'lang lg ON o.id_lang = lg.id_lang '
            . $leftJoinStatment . _DB_PREFIX_ . 'shop s ON o.id_shop = s.id_shop ';

        if ($this->withProducts) {
            $from .= $leftJoinStatment . _DB_PREFIX_ . 'order_detail od ON o.id_order = od.id_order '
                . $leftJoinStatment . _DB_PREFIX_ . 'image i ON od.product_id = i.id_product AND i.position = 1 AND cover = 1 '
                . $leftJoinStatment . _DB_PREFIX_ . 'product p ON (p.id_product = od.product_id) '
                . $leftJoinStatment . _DB_PREFIX_ . 'manufacturer m ON p.id_manufacturer  = m.id_manufacturer '
                . $leftJoinStatment . _DB_PREFIX_ . 'category_lang cat ON cat.id_category  = s.id_category AND cat.id_lang = lg.id_lang ';
        }

        return $from;
    }

    /**
     * @return string
     */
    private function makeWhere()
    {
        $queryIdShop = '';
        $queryIsoLang = '';
        if (!empty($this->idShop)) {
            $queryIdShop = ' id_shop = ' . (int) $this->idShop;
        }
        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $this->idShop)) {
            $orderLang = new Language();

            $idLang = $orderLang->getIdByIso(InternalConfigManager::getIsoLangByGroupName($this->groupName, $this->idShop));

            if (is_int($idLang)) {
                if (!empty($queryIdShop)) {
                    $queryIsoLang = ' AND ';
                }

                $queryIsoLang .= ' id_lang = "' . (int) $idLang . '" ';
            }
        }
        if (empty($queryIdShop . $queryIsoLang)) {
            return '';
        }

        return ' WHERE ' . $queryIdShop . $queryIsoLang;
    }

    /**
     * @return string
     */
    private function makeEndQuery()
    {
        return ' ORDER BY date_add DESC'
            . ' LIMIT ' . (int) $this->offset . ', ' . (int) $this->limit . ' ';
    }

    /**
     * @return string
     */
    public function makeSqlQuery()
    {
        return $this->makeSelect() . $this->makeFrom();
    }

    /**
     * @return array<mixed>|bool|mysqli_result|PDOStatement|resource|null
     */
    public function getRawOrders()
    {
        $sql = $this->makeSqlQuery();
        $result = Db::getInstance()->executeS($sql);

        return $result;
    }
}
