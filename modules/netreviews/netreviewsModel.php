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

class netreviewsModel
{
    protected $table = 'av_products_reviews';
    protected $identifier = 'id_product_av';

    protected static $https = 'https://';
    protected static $http = 'http://';
    protected static $urlCache = [];

    public $orderId;
    public $shopId;
    public $isoLang;

    /**
     * @return array<mixed>|bool|object|null
     */
    public function getTotalReviews()
    {
        return Db::getInstance()->getRow('SELECT count(*) as nb_reviews FROM ' . _DB_PREFIX_ . 'av_products_reviews');
    }

    /**
     * @return array<mixed>|bool|object|null
     */
    public function getTotalReviewsAverage()
    {
        return Db::getInstance()->getRow('SELECT count(*) as nb_reviews_average '
            . 'FROM ' . _DB_PREFIX_ . 'av_products_average');
    }

    /**
     * @return array<mixed>
     */
    public function getTotalOrders()
    {
        $results = [];

        $query = "SELECT COUNT(IF(flag_get = '1', 1, NULL)) AS 'flagged', "
                    . "COUNT(IF(flag_get != '1' OR flag_get IS NULL, TRUE, NULL)) AS 'not_flagged', "
                    . "COUNT(1) AS 'all' "
                    . 'FROM ' . _DB_PREFIX_ . 'av_orders';

        $queryResult = Db::getInstance()->getRow($query);

        $results['all'] = $queryResult['all'];
        $results['flagged'] = $queryResult['flagged'];
        $results['not_flagged'] = $queryResult['not_flagged'];

        return $results;
    }

    /**
     * @param string $headerColums
     * @param int|null $idShop
     *
     * @return array<mixed>
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function export($headerColums, $idShop = null)
    {
        $filename = Configuration::get('AV_CSVFILENAME', null, null, $idShop);
        if (!empty($filename)) {
            $this->handlePreviousFileAlreadyGenerated($filename);
        }
        $filename = date('d-m-Y') . '-' . Tools::substr(md5((string) rand(0, 10000)), 1, 10) . '.csv';
        $filePath = _PS_MODULE_DIR_ . 'netreviews/Export_NetReviews_' . $filename;

        $itemList = $this->getOrdersAccordingToExportConfig($idShop);

        $allOrders = [];
        foreach ($itemList as $item) {
            $allOrders[$item['id_order']] = $this->formatOrder($item);
        }

        if (empty($allOrders)) {
            throw new DomainException('No order to export');
        }

        if ($csv = @fopen($filePath, 'w')) {
            fwrite($csv, $headerColums);
            foreach ($allOrders as $order) {
                $line = $this->getInfosForCsv($idShop, $order);
                foreach ($line as $l) {
                    fwrite($csv, self::generateCsvLine($l));
                }
            }
            fclose($csv);
            if (file_exists($filePath)) {
                Configuration::updateValue('AV_CSVFILENAME', $filename);

                return [$filename, count($allOrders), $filePath];
            } else {
                throw new DomainException('Unable to read/write export file');
            }
        } else {
            throw new DomainException('Unable to read/write export file');
        }
    }

    /**
     * @param string $headerColums
     * @param int|null $idShop
     *
     * @return array<mixed>
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function exportRetroactive($headerColums, $idShop = null)
    {
        $filename = Configuration::get('AV_CSVRETROACTIVEFILENAME', null, null, $idShop);
        if (!empty($filename)) {
            $this->handlePreviousFileAlreadyGenerated($filename, 'Retroactive_');
        }
        $filename = date('d-m-Y') . '-' . Tools::substr(md5((string) rand(0, 10000)), 1, 10) . '.csv';

        $filePath = _PS_MODULE_DIR_ . 'netreviews/Export_NetReviews_Retroactive_' . $filename;

        $itemList = $this->getRetroactiveOrdersAccordingToExportConfig($idShop);

        $allOrders = [];
        foreach ($itemList as $item) {
            $allOrders[$item['id_order']] = $this->formatToPurchaseOrder($item);
        }

        if (empty($allOrders)) {
            throw new DomainException('No order to export');
        }

        if ($csv = @fopen($filePath, 'w')) {
            fwrite($csv, $headerColums);
            foreach ($allOrders as $order) {
                $line = $this->getPurchaseInfosForCsv($idShop, $order);

                foreach ($line as $l) {
                    fwrite($csv, self::generateCsvLine($l));
                }
            }
            fclose($csv);
            if (file_exists($filePath)) {
                Configuration::updateValue('AV_CSVRETROACTIVEFILENAME', $filename);

                return [$filename, count($allOrders), $filePath];
            } else {
                throw new DomainException('Unable to read/write export file');
            }
        } else {
            throw new DomainException('Unable to read/write export file');
        }
    }

    /**
     * @return string|null
     */
    protected function getOrderStatusList()
    {
        $orderStatusList = (Tools::getValue('orderstates')) ?
            array_map('intval', Tools::getValue('orderstates')) : '';
        $orderStatusList = (!empty($orderStatusList)) ? implode(',', $orderStatusList) : null;

        return $orderStatusList;
    }

    /**
     * @param $filename
     *
     * @return void
     *
     * @throws Exception
     */
    protected function handlePreviousFileAlreadyGenerated($filename, $retroactive = '')
    {
        $filePath = _PS_MODULE_DIR_ . 'netreviews/Export_NetReviews_' . $retroactive .
            str_replace('/', '', Tools::stripslashes($filename));
        if (file_exists($filePath)) {
            if (is_writable($filePath)) {
                unlink($filePath);
            } else {
                throw new DomainException('Writing on our server is not allowed.
                     Please assign write permissions to the folder netreviews');
            }
        } else {
            foreach (glob(_PS_MODULE_DIR_ . 'netreviews/Export_NetReviews_*') as $filename_to_delete) {
                if (is_writable($filename_to_delete)) {
                    unlink($filename_to_delete);
                }
            }
        }
    }

    /**
     * @return string
     */
    protected function getDurationSql()
    {
        $duree = Tools::getValue('duree');
        $dureeSql = '';
        switch ($duree) {
            case '1w':
                $dureeSql = 'INTERVAL 1 WEEK';
                break;
            case '2w':
                $dureeSql = 'INTERVAL 2 WEEK';
                break;
            case '1m':
                $dureeSql = 'INTERVAL 1 MONTH';
                break;
            case '2m':
                $dureeSql = 'INTERVAL 2 MONTH';
                break;
            case '3m':
                $dureeSql = 'INTERVAL 3 MONTH';
                break;
            case '4m':
                $dureeSql = 'INTERVAL 4 MONTH';
                break;
            case '5m':
                $dureeSql = 'INTERVAL 5 MONTH';
                break;
            case '6m':
                $dureeSql = 'INTERVAL 6 MONTH';
                break;
            case '7m':
                $dureeSql = 'INTERVAL 7 MONTH';
                break;
            case '8m':
                $dureeSql = 'INTERVAL 8 MONTH';
                break;
            case '9m':
                $dureeSql = 'INTERVAL 9 MONTH';
                break;
            case '10m':
                $dureeSql = 'INTERVAL 10 MONTH';
                break;
            case '11m':
                $dureeSql = 'INTERVAL 11 MONTH';
                break;
            case '12m':
                $dureeSql = 'INTERVAL 12 MONTH';
                break;
            default:
                $dureeSql = 'INTERVAL 1 WEEK';
                break;
        }

        return $dureeSql;
    }

    /**
     * @param $idShop
     *
     * @return array|bool|mysqli_result|PDOStatement|resource|null
     *
     * @throws PrestaShopDatabaseException
     */
    protected function getOrdersAccordingToExportConfig($idShop)
    {
        $orderStatusList = $this->getOrderStatusList();

        $sqlDuration = $this->getDurationSql();
        $whereIdShop = (!empty($idShop)) ? 'AND o.id_shop = ' . (int) $idShop : '';
        $selectIdShop = (!empty($idShop)) ? ', o.id_shop' : '';

        $whereIdState = (!empty($orderStatusList)) ? '
        AND o.current_state IN (' . $orderStatusList . ')' : '';
        $selectIdState = (!empty($orderStatusList)) ? ', o.current_state' : '';

        $qrySql = 'SELECT o.module, lg.iso_code, o.id_order, o.total_paid, o.id_customer, o.date_add,' .
            ' c.firstname, c.lastname, c.email, o.id_lang ' . $selectIdShop . $selectIdState . '
                        FROM ' . _DB_PREFIX_ . 'orders o
                        LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON o.id_customer = c.id_customer
                        LEFT JOIN ' . _DB_PREFIX_ . 'lang lg ON o.id_lang = lg.id_lang
                        WHERE (TO_DAYS(DATE_ADD(o.date_add,' . $sqlDuration . ')) - TO_DAYS(NOW())) >= 0
                        ' . $whereIdShop . $whereIdState . ' ORDER BY o.date_add DESC';

        return Db::getInstance()->ExecuteS($qrySql);
    }

    /**
     * @param $idShop
     *
     * @return array|bool|mysqli_result|PDOStatement|resource|null
     *
     * @throws PrestaShopDatabaseException
     */
    protected function getRetroactiveOrdersAccordingToExportConfig($idShop)
    {
        $orderStatusList = $this->getOrderStatusList();
        $sqlDuration = $this->getDurationSql();
        $whereIdShop = (!empty($idShop)) ? 'AND o.id_shop = ' . (int) $idShop : '';
        $selectIdShop = (!empty($idShop)) ? ', o.id_shop' : '';

        $whereIdState = (!empty($orderStatusList)) ? '
        AND o.current_state IN (' . $orderStatusList . ')' : '';
        $selectIdState = (!empty($orderStatusList)) ? ', o.current_state' : '';
        $limit = ' LIMIT 5000';
        $orderBy = ' ORDER BY oav.horodate_now DESC';

        $qrySql = 'SELECT o.module, lg.iso_code, o.id_order, o.total_paid, o.id_customer, o.date_add,' .
            ' c.firstname, c.lastname, c.email, o.id_lang ' . $selectIdShop . $selectIdState . '
                        FROM ' . _DB_PREFIX_ . 'orders o
                        LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON o.id_customer = c.id_customer
                        RIGHT JOIN ' . _DB_PREFIX_ . 'av_orders oav ON o.id_order = oav.id_order
                        LEFT JOIN ' . _DB_PREFIX_ . 'lang lg ON o.id_lang = lg.id_lang
                        WHERE (TO_DAYS(DATE_ADD(o.date_add,' . $sqlDuration . ')) - TO_DAYS(NOW())) >= 0
                        ' . $whereIdShop . $whereIdState . $orderBy . $limit;

        return Db::getInstance()->ExecuteS($qrySql);
    }

    public function formatOrder($item)
    {
        $currentStateItem = (isset($item['current_state'])
            && !empty($item['current_state'])) ? $item['current_state'] : '';

        $orderData = [
            'TYPE_PAIEMENT' => $item['module'],
            'ID_ORDER' => $item['id_order'],
            'MONTANT_COMMANDE' => $item['total_paid'],
            'DATE_ORDER' => date('d/m/Y', strtotime($item['date_add'])),
            'ID_CUSTOMER' => [
                'ID_CUSTOMER' => $item['id_customer'],
                'FIRST_NAME' => $item['firstname'],
                'LAST_NAME' => $item['lastname'],
                'EMAIL' => $item['email'],
            ],
            'EMAIL_CLIENT' => '',
            'NOM_CLIENT' => '',
            'ORDER_STATE' => $currentStateItem,
            'ISO_LANG' => $item['iso_code'],
            'PRODUCTS' => [],
        ];

        $shopName = Configuration::get('PS_SHOP_NAME');
        $oOrder = new Order($item['id_order']);
        $orderProducts = $oOrder->getProducts();
        foreach ($orderProducts as $productElement) {
            $idShop = isset($item['id_shop']) ? $item['id_shop'] : null;
            $productData = $this->getProductData($productElement, $item['id_lang'], $idShop);

            $orderData['PRODUCTS'][] = [
                'ID_PRODUCT' => $productElement['product_id'],
                'NOM_PRODUCT' => $productElement['product_name'],
                'SKU_PRODUCT' => $productData['sku'],
                'EAN13_PRODUCT' => $productData['ean13'],
                'UPC_PRODUCT' => $productData['upc'],
                'MPN_PRODUCT' => $productData['mpn'],
                'BRAND_NAME_PRODUCT' => (isset($productData['brandName']) && !empty($productData['brandName'])) ?
                    $productData['brandName'] :
                    $shopName,
                'PRICE_PRODUCT_UNITY' => $productElement['product_price'],
                'URL_PRODUCT' => self::getUrlProduct($productElement['product_id'], (int) $item['id_lang'], null, $idShop),
                'URL_IMAGE_PRODUCT' => self::getUrlProduct(
                    $productElement['product_id'],
                    (int) $item['id_lang'],
                    $productData['titleImageId']
                ),
                'CAT_PRODUCT' => $productData['category'],
            ];
        }

        return $orderData;
    }

    public function formatToPurchaseOrder($item)
    {
        $currentStateItem = (isset($item['current_state'])
            && !empty($item['current_state'])) ? $item['current_state'] : '';

        $orderData = [
            'TYPE_PAIEMENT' => $item['module'],
            'ID_ORDER' => $item['id_order'],
            'MONTANT_COMMANDE' => $item['total_paid'],
            'DATE_ORDER' => date('d/m/Y', strtotime($item['date_add'])),
            'ID_CUSTOMER' => [
                'ID_CUSTOMER' => $item['id_customer'],
                'FIRST_NAME' => $item['firstname'],
                'LAST_NAME' => $item['lastname'],
                'EMAIL' => $item['email'],
            ],
            'EMAIL_CLIENT' => '',
            'NOM_CLIENT' => '',
            'ORDER_STATE' => $currentStateItem,
            'ISO_LANG' => $item['iso_code'],
            'PRODUCTS' => [],
        ];

        $shopName = Configuration::get('PS_SHOP_NAME');
        $oOrder = new Order($item['id_order']);
        $orderProducts = $oOrder->getProducts();
        foreach ($orderProducts as $productElement) {
            $idShop = isset($item['id_shop']) ? $item['id_shop'] : null;
            $productData = $this->getProductData($productElement, $item['id_lang'], $idShop);
            $orderData['PRODUCTS'][] = [
                'ID_PRODUCT' => $productElement['product_id'],
                'REF_PRODUCT' => $productElement['product_id'],
                'NOM_PRODUCT' => $productElement['product_name'],
                'SKU_PRODUCT' => $productData['sku'],
                'EAN13_PRODUCT' => $productData['ean13'],
                'ISBN_PRODUCT' => $productData['isbn'],
                'JAN_PRODUCT' => $productData['ean13'],
                'UPC_PRODUCT' => $productData['upc'],
                'MPN_PRODUCT' => $productData['mpn'],
                'BRAND_NAME_PRODUCT' => (isset($productData['brandName']) && !empty($productData['brandName'])) ?
                    $productData['brandName'] :
                    $shopName,
                'PRICE_PRODUCT_UNITY' => $productElement['product_price'],
                'URL_PRODUCT' => self::getUrlProduct($productElement['product_id'], (int) $item['id_lang'], null, $idShop),
                'URL_IMAGE_PRODUCT' => self::getUrlProduct(
                    $productElement['product_id'],
                    (int) $item['id_lang'],
                    $productData['titleImageId']
                ),
                'CAT_PRODUCT' => $productData['category'],
            ];
        }

        return $orderData;
    }

    protected function getProductBrandName($product)
    {
        $brandName = '';
        if (isset($product['id_manufacturer']) && !empty($product['id_manufacturer'])) {
            $orderManufacturer = new Manufacturer($product['id_manufacturer']);
            $brandName = $orderManufacturer->name;
        }

        $shopName = Configuration::get('PS_SHOP_NAME');

        $brandName = !empty($brandName) ? $brandName : $shopName;

        return $brandName;
    }

    protected function getProductUpc($product)
    {
        return (isset($product['upc'])
            && !empty($product['upc'])) ? $product['upc'] : '';
    }

    protected function getProductIsbn($product)
    {
        return (isset($product['isbn'])
            && !empty($product['isbn'])) ? $product['isbn'] : '';
    }

    protected function getProductEan13($product)
    {
        return (isset($product['ean13'])
            && !empty($product['ean13'])) ? $product['ean13'] : '';
    }

    protected function getProductSku($product)
    {
        return (isset($product['reference'])
            && !empty($product['reference'])) ? $product['reference'] : '';
    }

    protected function getProductMpn($product, $key)
    {
        return (isset($product[$key]) && !empty($product[$key])) ? $product[$key] : '';
    }

    protected function getProductCategory($product, $lang)
    {
        if (isset($product['id_category_default'])
            && !empty($product['id_category_default'])) {
            $productCategoryCreate = new Category(
                $product['id_category_default'],
                $lang
            );
            $productCategory = $productCategoryCreate->name;
        } else {
            $productCategory = '';
        }

        return $productCategory;
    }

    public function getProductTitleImageId($product)
    {
        return (isset($product['image'])
            && !empty($product['image'])) ? $product['image']->id_image : '';
    }

    public function getProductData($product, $lang, $idShop)
    {
        $brandName = $this->getProductBrandName($product);
        $upc = $this->getProductUpc($product);
        $ean13 = $this->getProductEan13($product);
        $sku = $this->getProductSku($product);
        $mpn = $this->getProductMpn($product, 'mpn');
        $mpn = version_compare(_PS_VERSION_, '1.7.7', '<')
            ? $this->getProductMpn($product, 'supplier_reference')
            : $mpn;

        $isbn = $this->getProductIsbn($product);
        $productCategory = $this->getProductCategory($product, $lang);
        $productTitleImageId = $this->getProductTitleImageId($product);
        $uniquegoogleshoppinginfo = Configuration::get('AV_PRODUCTUNIGINFO', null, null, $idShop);
        if (1 == $uniquegoogleshoppinginfo) {
            $productUpc = $upc;
            $productEan13 = $ean13;
            $productSku = $sku;
            $productMpn = $mpn;
            $productIsbn = $isbn;
        } else {
            $productUpc = (isset($product['product_upc'])
                && !empty($product['product_upc'])) ? $product['product_upc'] : $upc;
            $productEan13 = (isset($product['product_ean13'])
                && !empty($product['product_ean13'])) ? $product['product_ean13'] : $ean13;
            $productSku = (isset($product['product_reference'])
                && !empty($product['product_reference'])) ? $product['product_reference'] : $sku;
            $productMpn = (isset($product['product_supplier_reference'])
                && !empty($product['product_supplier_reference'])) ?
                $product['product_supplier_reference'] : $mpn;
            $productIsbn = (isset($product['product_isbn'])
                && !empty($product['product_isbn'])) ?
                $product['product_isbn'] : $isbn;
        }

        return [
            'brandName' => $brandName,
            'upc' => $productUpc,
            'ean13' => $productEan13,
            'sku' => $productSku,
            'mpn' => $productMpn,
            'isbn' => $productIsbn,
            'titleImageId' => $productTitleImageId,
            'category' => $productCategory,
        ];
    }

    protected function getInfosForCsv($idShop, $order)
    {
        $delay = (Configuration::get('AV_DELAY', null, null, $idShop)) ?
            Configuration::get('AV_DELAY', null, null, $idShop) : 0;
        $avisProduit = Tools::getValue('productreviews');

        $orderStatusList = OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT'));
        $orderStatusIndice = [];
        foreach ((array) $orderStatusList as $value) {
            // $orderStatusIndice[$value['id_order_state']] = utf8_decode($value['name']);
            $orderStatusIndice[$value['id_order_state']] = mb_convert_encoding($value['name'], 'ISO-8859-1', 'UTF-8');
        }

        $oOrder = new Order($order['ID_ORDER']);
        $oCarrier = new Carrier((int) $oOrder->id_carrier);
        $countProducts = count($order['PRODUCTS']);
        $orderReference = (!empty($oOrder->reference)) ? $oOrder->reference : '';
        $currentState = (!empty($oOrder->current_state)) ? $oOrder->current_state : '';

        $line = [];
        for ($i = 0; $i < $countProducts; ++$i) {
            $line[$i][] = $order['ID_ORDER'];
            $line[$i][] = $orderReference;
            $line[$i][] = $order['MONTANT_COMMANDE'];
            $line[$i][] = $order['ID_CUSTOMER']['EMAIL'];
            // $line[] = utf8_decode($order['ID_CUSTOMER']['FIRST_NAME']);
            // $line[] = utf8_decode($order['ID_CUSTOMER']['LAST_NAME']);
            $line[$i][] = mb_convert_encoding($order['ID_CUSTOMER']['FIRST_NAME'], 'ISO-8859-1', 'UTF-8');
            $line[$i][] = mb_convert_encoding($order['ID_CUSTOMER']['LAST_NAME'], 'ISO-8859-1', 'UTF-8');
            $line[$i][] = $order['DATE_ORDER'];
            // $line[] = utf8_decode($oOrder->payment);
            // $line[] = utf8_decode($oCarrier->name);
            $line[$i][] = mb_convert_encoding($oOrder->payment, 'ISO-8859-1', 'UTF-8');
            $line[$i][] = mb_convert_encoding($oCarrier->name, 'ISO-8859-1', 'UTF-8');
            $line[$i][] = $delay;

            if ('1' == $avisProduit && $countProducts > 0) {
                $line[$i][] = $order['PRODUCTS'][$i]['ID_PRODUCT'];
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['CAT_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['NOM_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['EAN13_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['UPC_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['MPN_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['BRAND_NAME_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['URL_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['URL_IMAGE_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $numState = $currentState;
                $line[$i][] = $numState;
                $line[$i][] = (!empty($numState)) ? $orderStatusIndice[$numState] : '';
                $line[$i][] = $order['ISO_LANG'];
                if (!empty($idShop)) {
                    $line[$i][] = $idShop;
                } else {
                    $line[$i][] = null;
                }
            } else {
                $line[$i][] = ''; // id product
                $line[$i][] = ''; // Product category
                $line[$i][] = ''; // NOM_PRODUCT
                $line[$i][] = ''; // EAN13_PRODUCT
                $line[$i][] = ''; // UPC_PRODUCT
                $line[$i][] = ''; // MPN_PRODUCT
                $line[$i][] = ''; // BRAND_NAME_PRODUCT
                $line[$i][] = ''; // URL_PRODUCT
                $line[$i][] = ''; // URL_IMAGE_PRODUCT
                $numState = $currentState; // $order['ORDER_STATE'];
                $line[$i][] = $numState;
                $line[$i][] = (!empty($numState)) ? $orderStatusIndice[$numState] : '';
                $line[$i][] = $order['ISO_LANG'];
                if (!empty($idShop)) {
                    $line[$i][] = $idShop;
                } else {
                    $line[$i][] = null;
                }
                break;
            }
        }

        return $line;
    }

    protected function getPurchaseInfosForCsv($idShop, $order)
    {
        $delay = (Configuration::get('AV_DELAY', null, null, $idShop)) ?: 0;
        $delayProduct = (Configuration::get('AV_DELAY_PRODUIT', null, null, $idShop)) ?: 0;
        $avisProduct = Tools::getValue('productreviews');

        $oOrder = new Order($order['ID_ORDER']);
        $countProducts = count($order['PRODUCTS']);
        $orderReference = (!empty($oOrder->id)) ? $oOrder->id : $oOrder->reference;
        $orderDate = DateTime::createFromFormat('d/m/Y', $order['DATE_ORDER']);
        $line = [];
        for ($i = 0; $i < $countProducts; ++$i) {
            $line[$i] = [];
            $line[$i][] = 'online';
            $line[$i][] = $orderDate->format('Y-m-d');
            $line[$i][] = $order['MONTANT_COMMANDE'];
            $line[$i][] = $orderReference;
            $line[$i][] = mb_convert_encoding($order['ID_CUSTOMER']['FIRST_NAME'], 'ISO-8859-1', 'UTF-8');
            $line[$i][] = mb_convert_encoding($order['ID_CUSTOMER']['LAST_NAME'], 'ISO-8859-1', 'UTF-8');
            $line[$i][] = $order['ID_CUSTOMER']['EMAIL'];
            $line[$i][] = ''; // phone
            $line[$i][] = 'false'; // hide_personal_data
            $line[$i][] = $order['ISO_LANG']; // language
            $line[$i][] = $delay;
            $line[$i][] = $delayProduct;

            if ('1' === $avisProduct && $countProducts > 0) {
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['NOM_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['REF_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['CAT_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['BRAND_NAME_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['PRICE_PRODUCT_UNITY'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['EAN13_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['SKU_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['UPC_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['ISBN_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['JAN_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['MPN_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['URL_PRODUCT'], 'ISO-8859-1', 'UTF-8');
                $line[$i][] = mb_convert_encoding($order['PRODUCTS'][$i]['URL_IMAGE_PRODUCT'], 'ISO-8859-1', 'UTF-8');
            } else {
                $line[$i][] = ''; // name
                $line[$i][] = ''; // product_ref
                $line[$i][] = ''; // category
                $line[$i][] = ''; // brand
                $line[$i][] = ''; // product_price
                $line[$i][] = ''; // ean
                $line[$i][] = ''; // sku
                $line[$i][] = ''; // upc
                $line[$i][] = ''; // isbn
                $line[$i][] = ''; // jan
                $line[$i][] = ''; // mpn
                $line[$i][] = ''; // product_url
                $line[$i][] = ''; // image_url
            }

            $avOrder = $this->getOrder($order['ID_ORDER']);
            if (!empty($avOrder)) {
                if ($avOrder[0]['flag_get'] !== 1) {
                    $this->flagOrder(0, $order['ID_ORDER']);
                }
                $line[$i][] = 1;
            } else {
                $line[$i][] = 0;
            }
        }

        return $line;
    }

    /**
     * @param string $productId
     * @param int $langId
     *
     * @return string|void
     *
     * @throws PrestaShopException
     */
    public static function getUrlProduct($productId, $langId, $idImage = null, $idShop = null)
    {
        $cacheKey = md5("{$productId}_{$langId}_{$idImage}_{$idShop}");
        if (isset(self::$urlCache[$cacheKey])) {
            return self::$urlCache[$cacheKey];
        }

        $productExist = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'product WHERE id_product =' . (int) $productId);
        if ($productExist) {
            $orderProduct = new Product((int) $productId, false, $langId);
            $useSsl = (Configuration::get('PS_SSL_ENABLED') || self::avUsingSecureMode()) ? true : false;
            $protocolLink = $useSsl ? self::$https : self::$http;
            $protocolContent = $useSsl ? self::$https : self::$http;
            $link = new Link($protocolLink, $protocolContent);
            $url = $link->getProductLink($orderProduct, null, null, null, $langId, $idShop);

            if (isset($idImage) && !empty($idImage)) {
                $idCoverImage = $idImage;
                $imgTypeChosen = 'large';
                $imgType = ImageType::getFormattedName($imgTypeChosen);

                $url = $link->getImageLink($orderProduct->link_rewrite, $idCoverImage, $imgType);
            }
            self::$urlCache[$cacheKey] = $url;

            return $url;
        } else {
            self::$urlCache[$cacheKey] = null;

            return null;
        }
    }

    /**
     * @param array<mixed> $list
     *
     * @return string
     */
    protected static function generateCsvLine($list)
    {
        foreach ($list as &$l) {
            $l = '' . addslashes($l) . '';
        }

        return implode(';', $list) . "\r\n";
    }

    /**
     * @param string $sData
     *
     * @return string
     */
    public static function acEncodeBase64($sData)
    {
        $sBase64 = base64_encode($sData);

        return strtr($sBase64, '+/', '-_');
    }

    /**
     * @param string $sData
     *
     * @return false|string
     */
    public static function acDecodeBase64($sData)
    {
        $sBase64 = strtr($sData, '-_', '+/');

        return base64_decode($sBase64);
    }

    /**
     * @param string $sData
     *
     * @return false|string
     */
    public static function acDecodeBase64SetP($sData)
    {
        $sBase64 = strtr($sData, '-_', '+/');
        $sBase64 = urldecode($sBase64);

        return base64_decode($sBase64);
    }

    /**
     * @param $codes
     *
     * @return string|false
     */
    public static function avJsonEncode($codes)
    {
        $result = json_encode($codes);

        if (is_string($result)) {
            return $result;
        }

        return false;
    }

    /**
     * @param $codes
     *
     * @return false|array
     */
    public static function avJsonDecode($codes)
    {
        return json_decode($codes, true);
    }

    /**
     * @param string $text
     *
     * @return false|int
     */
    public static function strlen($text)
    {
        return (function_exists('mb_strlen') && ((int) ini_get('mbstring.func_overload')) & 2) ?
            mb_strlen($text, '8bit') : Tools::strlen($text);
    }

    /**
     * @param string $string
     *
     * @return mixed|string
     *
     * @throws Exception
     */
    public static function l($string)
    {
        return Translate::getModuleTranslation('netreviews', $string, 'ajax-load-tab-content');
    }

    public static function avFileGetContents($urlLink)
    {
        return Tools::file_get_contents($urlLink);
    }

    /**
     * @param int $idModule
     * @param int $idshop
     *
     * @return array|bool|mysqli_result|PDOStatement|resource|null
     *
     * @throws PrestaShopDatabaseException
     */
    public function listRegisteredHooks($idModule, $idshop)
    {
        if (!$idModule) {
            return [];
        }
        $condition = '';
        if (!empty($idshop)) {
            $condition = ' AND hm.`id_shop` = ' . (int) $idshop;
        }

        $sql = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'hook_module` hm
            LEFT JOIN `' . _DB_PREFIX_ . 'hook` h ON (h.`id_hook` = hm.`id_hook`)
            WHERE hm.`id_module` = ' . (int) $idModule . $condition;

        return Db::getInstance()->ExecuteS($sql);
    }

    /**
     * @param string $key
     * @param int $idLang
     *
     * @return array
     */
    public static function getMultiShopValues($key, $idLang = null)
    {
        $shops = Shop::getShops(false, null, true);
        $resultsArray = [];
        if (Shop::isFeatureActive()) {
            foreach ($shops as $id_shop) {
                $resultsArray[$id_shop] = Configuration::get($key, $idLang, null, $id_shop);
            }
        }

        return $resultsArray;
    }

    /**
     * @param string $string
     * @param bool $htmlOk
     * @param bool $bqSql
     *
     * @return array|float|int|mixed|string|string[]
     */
    public function escape($string, $htmlOk = false, $bqSql = false)
    {
        if (!is_numeric($string)) {
            $search = ["'", '"', '`', '&', ',', ';', '/', ' ', '\\',
                'SELECT', 'DROP', 'CREATE', 'ALTER', 'UPDATE',
                'DELETE', 'TRUNCATE', 'MERGE', 'INSERT', 'WHERE',
                'select', 'drop', 'create', 'alter', 'update', 'delete',
                'truncate', 'merge', 'insert', 'where', ];
            do {
                $lenString = Tools::strlen($string);
                $string = str_replace($search, '', $string);
            } while ($lenString != Tools::strlen($string));

            if (!$htmlOk) {
                $string = strip_tags(Tools::nl2br($string));
            }

            if (true === $bqSql) {
                $string = str_replace('`', '\`', $string);
            }
        }

        return $string;
    }

    /**
     * @return bool
     */
    public static function avUsingSecureMode()
    {
        return Tools::usingSecureMode();
    }

    public function getStatsProduct($idProduct, $groupName = null, $idShop = null)
    {
        if ('checked' === Configuration::get('AV_MULTILINGUE', null, null, $idShop) && empty($groupName)) {
            return false;
        }

        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'av_products_average WHERE ref_product = ' . (int) $idProduct;
        if (!empty($groupName)) {
            if ((!empty($idShop) && Shop::isFeatureActive()) || !empty($idShop)) {
                $avGroupConf = json_decode(Configuration::get('AV_GROUP_CONF' . $groupName, null, null, $idShop), true);
            } else {
                $avGroupConf = json_decode(Configuration::get('AV_GROUP_CONF' . $groupName), true);
            }
            if ($avGroupConf) {
                $sql .= ' and iso_lang in ("' . pSQL(implode('","', $avGroupConf)) . '")';
            }
        } else {
            $sql .= " and iso_lang = '0'";
        }

        if (!empty($idShop) && '1' != $idShop && Shop::isFeatureActive()) {
            $sql .= ' and id_shop = ' . (int) $idShop;
        } else {
            $sql .= ' and id_shop IN(0,1)';
        }

        return Db::getInstance()->getRow($sql);
    }

    public function getProductReviews($productId, $groupName = null, $idShop = null, $reviewsPerPage = 20, $currentPage = 1, $reviewFilterOrderBy = 'horodate_DESC', $reviewFilterByNote = 0, $getReviews = false)
    {
        if ('checked' === Configuration::get('AV_MULTILINGUE', null, null, $idShop) && empty($groupName)) {
            return;
        }

        $productId = self::escape($productId, false, false);
        $reviewFilterOrderBy = self::escape(pSQL($reviewFilterOrderBy), false, false);
        $reviewFilterByNote = self::escape($reviewFilterByNote, false, false);
        $currentPage = self::escape($currentPage, false, false);
        $reviewsPerPage = self::escape($reviewsPerPage, false, false);

        $filter = '';
        $multishopCondition = '';
        $limit = '';
        $start = 0; // $start = ($current_page > 1)? ($current_page-1) * $reviews_per_page : 0;
        $end = $currentPage * $reviewsPerPage;
        $helfulrating = '';
        $rateRange = [1, 2, 3, 4, 5];

        if (in_array($reviewFilterByNote, $rateRange)) {
            $filter .= " and rate = '" . pSQL($reviewFilterByNote) . "'";
        }
        $aSorting = explode('_', $reviewFilterOrderBy);
        if (('horodate' == $aSorting[0] || 'rate' == $aSorting[0]) && ('DESC' == $aSorting[1]
                || 'ASC' == $aSorting[1])) {
            $filter .= ' ORDER BY ' . $aSorting[0] . ' ' . $aSorting[1];
        } elseif ('helpfulrating' == $aSorting[0]) {
            $helfulrating = ', helpful-helpless as helpfulrating';
            $filter .= ' ORDER BY ' . $aSorting[0] . ' DESC';
        }
        if ('0' != $reviewsPerPage) {
            $limit .= ' LIMIT ' . $start . ', ' . $end;
        }

        $sql = 'SELECT *' . $helfulrating . ' FROM ' . _DB_PREFIX_ . 'av_products_reviews WHERE ref_product = \'' .
            pSQL($productId) . '\'';

        if (!empty($groupName)) {
            if ((!empty($idShop) && Shop::isFeatureActive()) || !empty($idShop)) {
                $avGroupConfiguration = json_decode(
                    Configuration::get('AV_GROUP_CONF' . $groupName, null, null, $idShop),
                    true
                );
            } else {
                $avGroupConfiguration = json_decode(Configuration::get('AV_GROUP_CONF' . $groupName), true);
            }
            $sql .= ' and iso_lang in ("' . pSQL(implode('","', $avGroupConfiguration)) . '")';
        } else {
            $sql .= " and iso_lang = '0'";
        }
        if (!empty($idShop) && '1' != $idShop && Shop::isFeatureActive()) {
            $multishopCondition .= ' and (id_shop = \'' . pSQL($idShop) . '\')';
        } else {
            $multishopCondition .= ' and id_shop IN(0,1)';
        }

        if (true === $getReviews) {
            $sql = 'SELECT COUNT(ref_product) as nbreviews FROM ' . _DB_PREFIX_
                . 'av_products_reviews WHERE ref_product = \'' . pSQL($productId) . '\'' .
                $multishopCondition . $filter;
        } else {
            $sql .= $multishopCondition . $filter . $limit;
        }

        // echo $sql.'<br>';
        return Db::getInstance()->ExecuteS($sql);
    }

    public static function tplFileExist($filename)
    {
        $tplFile = _PS_THEME_DIR_ . 'modules/netreviews/views/templates/hook/' . $filename;
        $overrideThemeFile = file_exists($tplFile);
        if ($overrideThemeFile) {
            return $tplFile;
        } else {
            return _PS_ROOT_DIR_ . '/modules/netreviews/views/templates/hook/' . $filename;
        }
    }

    public function saveOrderToRequest()
    {
        $qryOrder = 'SELECT id_order FROM ' . _DB_PREFIX_ . 'av_orders WHERE id_order = ' . (int) $this->orderId;
        $this->shopId = (!empty($this->shopId)) ? $this->shopId : 0;
        $this->isoLang = (!empty($this->isoLang)) ? $this->isoLang : '0';

        if (!Db::getInstance()->getRow($qryOrder, false)) {
            Db::getInstance()->Execute('INSERT INTO ' . _DB_PREFIX_ . 'av_orders (id_order, id_shop, iso_lang) VALUES (' . (int) $this->orderId . ', ' . (int) $this->shopId . ',"' . pSQL($this->isoLang) . '")');
        }
    }

    public function getQueryToGetOrders($idShop, $groupName, $process, $messageLang = null)
    {
        // Permet de rendre optionel la demande d'avis pour les marketplace contenu dans ce tableau.
        $globalMarketplaces = []; // 1 => 'priceminister'

        $idShopFilterSql = (!empty($idShop)) ? ' AND id_shop = ' . (int) $idShop : '';
        $queryIdShop = (!empty($idShop)) ? ' AND o.id_shop = ' . (int) $idShop : '';
        $processChoosen = Configuration::get('AV_PROCESSINIT' . $groupName, null, null, $idShop);
        $orderStatusChoosen = Configuration::get('AV_ORDERSTATESCHOOSEN' . $groupName, null, null, $idShop);
        $limit = Configuration::get('AV_ORDER_LIMIT' . $groupName, null, null, $idShop);
        $queryStatus = ' AND 1 = 0 ';

        if ('onorder' == $processChoosen) {
            $queryStatus = ' AND oh.id_order_state = o.current_state';
        }

        if ('onorderstatuschange' == $processChoosen && !empty($orderStatusChoosen)) {
            $orderStatusChoosen = str_replace(';', ',', $orderStatusChoosen);

            if ($process === 'reloaded') {
                $queryStatus = ' AND o.current_state IN (' . pSQL($orderStatusChoosen) . ')';
            }

            if ($process === 'genesis') {
                $queryStatus = ' AND o.current_state IN (' . pSQL($orderStatusChoosen) . ')
                AND oh.id_order_state = o.current_state';
            }
        }

        $queryIsoLang = '';
        if (isset($messageLang) && $process = 'genesis') {
            $orderLang = new Language();
            $idLang = $orderLang->getIdByIso(Tools::strtolower($messageLang));
            $queryIsoLang .= ' AND o.id_lang = ' . (int) $idLang;
        }

        if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
            $sql = 'SELECT value FROM ' . _DB_PREFIX_ . 'configuration WHERE name = '
                . '"AV_GROUP_CONF' . pSQL($groupName) . '"' . $idShopFilterSql;
            if ($row = Db::getInstance()->getRow($sql)) {
                $listIsoLangMultilingue = netreviewsModel::avJsonDecode($row['value']);
            }
            $idsLang = '';
            foreach ($listIsoLangMultilingue as $codeIso) {
                $orderLang = new Language();
                $idLang = $orderLang->getIdByIso(Tools::strtolower($codeIso));
                $idsLang .= "'" . (int) $idLang . "',";
            }
            $idsLang = Tools::substr($idsLang, 0, -1);
            $queryIsoLang .= ' AND o.id_lang in (' . $idsLang . ')';
        }
        $queryLimit = '';
        if (isset($limit) && !empty($limit)) {
            $queryLimit = ' LIMIT ' . (int) $limit;
        }

        $query = 'SELECT DISTINCT o.module, oav.id_order, o.date_add as date_order, oh.date_add as date_last_status,
                o.id_customer,o.total_paid,o.id_lang, o.id_shop, oh.id_order_state, o.current_state as state_order
                FROM ' . _DB_PREFIX_ . 'av_orders oav
                LEFT JOIN ' . _DB_PREFIX_ . 'orders o
                ON oav.id_order = o.id_order
                LEFT JOIN ' . _DB_PREFIX_ . 'order_history oh
                ON oh.id_order = o.id_order
                WHERE (oav.flag_get IS NULL OR oav.flag_get = 0)
                AND o.module NOT IN ("' . pSQL(implode('", "', $globalMarketplaces)) . '")'
            . $queryStatus . $queryIdShop . $queryIsoLang;

        if ($process === 'reloaded') {
            $query = 'SELECT DISTINCT o.module, oav.id_order, o.date_add as date_order, o.id_customer,o.total_paid, o.id_lang, o.id_shop, o.current_state as state_order FROM ' . _DB_PREFIX_ . 'av_orders oav LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON oav.id_order = o.id_order WHERE (oav.flag_get IS NULL OR oav.flag_get = 0) AND o.module NOT IN ("' . pSQL(implode('", "', $globalMarketplaces)) . '")'
                . $queryStatus . $queryIdShop . $queryIsoLang . $queryLimit;
        }

        return $query;
    }

    /**
     * For DE clients - final customer consent needs to be collected to collect order
     *
     * @param $idShop
     * @param $groupName
     * @param $orderId
     *
     * @return bool
     */
    public function isOrderWithoutConsent($idShop, $groupName, $orderId)
    {
        $isOrderWithoutConsent = false;

        $ordersWithoutConsentDE = json_decode(
            Configuration::get('AV_CONSENT_ANSWER_NO' . $groupName, null, null, $idShop, false),
            true
        );

        if (is_array($ordersWithoutConsentDE)
            && in_array((int) $orderId, array_values($ordersWithoutConsentDE))
        ) {
            $isOrderWithoutConsent = true;
            Db::getInstance()->Execute(
                'UPDATE ' . _DB_PREFIX_ . 'av_orders
                        SET horodate_get = "' . time() . '", flag_get = 1
                        WHERE id_order = ' . (int) $orderId
            );

            $key = array_search((int) $orderId, $ordersWithoutConsentDE);
            unset($ordersWithoutConsentDE[$key]);
            Configuration::updateValue(
                'AV_CONSENT_ANSWER_NO' . $groupName,
                json_encode(array_values($ordersWithoutConsentDE)),
                false,
                null,
                $idShop
            );
        }

        return $isOrderWithoutConsent;
    }

    /**
     * Set orders as getted but do not if it's a test request
     *
     * @param $flagIndication
     * @param $orderId
     *
     * @return void
     */
    public function flagOrder($flagIndication, $orderId)
    {
        if (!isset($flagIndication) || 0 == $flagIndication) {
            Db::getInstance()->Execute(
                'UPDATE ' . _DB_PREFIX_ . 'av_orders
                SET horodate_get = "' . time() . '", flag_get = 1
                WHERE id_order = ' . (int) $orderId
            );
        }
    }

    public function getOrder($orderId)
    {
        $query = 'SELECT oav.*
                FROM ' . _DB_PREFIX_ . 'av_orders oav
                WHERE oav.id_order = ' . (int) $orderId;

        return Db::getInstance()->ExecuteS($query);
    }
}
