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

class OrderPurchaseFormatter
{
    /**
     * @var array<string,string>
     */
    private static $refList = [
        'ean13' => 'ean13',
        'product_reference' => 'sku',
        'upc' => 'upc',
        'isbn' => 'isbn',
        'mpn' => 'mpn',
    ];

    /**
     * @param array<mixed> $tab
     * @param string|int $index
     *
     * @return mixed
     */
    public static function getSecureData($tab, $index)
    {
        if (array_key_exists($index, $tab)) {
            $data = $tab[$index];
        } else {
            $data = null;
        }

        return $data;
    }

    /**
     * @param array<mixed> $ordersPresta
     * @param string $websiteId
     * @param string $purchaseType
     * @param bool $isRetroactiveOrder
     *
     * @return array<int,mixed>
     */
    public static function formatToPurchase($ordersPresta, $websiteId, $purchaseType = 'BRAND_AND_PRODUCT', $isRetroactiveOrder = false)
    {
        $ret = [];

        foreach ($ordersPresta as $oldFormatOrder) {
            if (is_array($oldFormatOrder) && array_key_exists('id_order', $oldFormatOrder)) {
                $date = self::getSecureData($oldFormatOrder, 'date_add');
                if (!array_key_exists((string) $oldFormatOrder['id_order'], $ret)) {
                    $tmpOrder = [
                        'purchase_reference' => self::getSecureData($oldFormatOrder, 'id_order'),
                        'price' => self::getSecureData($oldFormatOrder, 'total_paid'),
                        'purchase_date' => $date,
                        'consumer' => [
                            'hide_personal_data' => false,
                            'first_name' => self::getSecureData($oldFormatOrder, 'firstname'),
                            'last_name' => self::getSecureData($oldFormatOrder, 'lastname'),
                            'email' => self::getSecureData($oldFormatOrder, 'email'),
                        ],
                        'solicitation_parameters' => [
                            'delay' => 0,
                            'delay_product' => 0,
                            'purchase_event_type' => $purchaseType,
                        ],
                        'products' => [],
                        'sales_channel' => [
                            'channel' => 'online',
                            'website_id' => $websiteId,
                        ],
                    ];
                } else {
                    $tmpOrder = $ret[self::getSecureData($oldFormatOrder, 'id_order')];
                    if ($isRetroactiveOrder) {
                        $tmpOrder = $tmpOrder['orderData'];
                    }
                }
                // @phpstan-ignore-next-line
                $tmpOrder['products'] = array_merge($tmpOrder['products'], [self::formatProductToPurchase($oldFormatOrder)]);

                if ($isRetroactiveOrder) {
                    $ret[self::getSecureData($oldFormatOrder, 'id_order')] = [
                        'orderData' => $tmpOrder,
                        'status' => '' . self::getSecureData($oldFormatOrder, 'current_state'),
                        'purchase_date' => $date,
                    ];
                } else {
                    $ret[self::getSecureData($oldFormatOrder, 'id_order')] = $tmpOrder;
                }
            }
        }
        $retData = [];
        foreach ($ret as $order) {
            $retData[] = $order;
        }

        return $retData;
    }

    /**
     * @param array<string,int|string|bool|float> $oldFormatOrder
     *
     * @return array<int|string,mixed>
     */
    public static function formatProductToPurchase($oldFormatOrder)
    {
        if (empty($oldFormatOrder['product_id'])) {
            return [];
        }

        $productUrl = netreviewsModel::getUrlProduct(
            (string) $oldFormatOrder['product_id'],
            (int) $oldFormatOrder['id_lang'],
            null,
            (int) $oldFormatOrder['id_shop']
        );

        $idImage = (!empty($oldFormatOrder['id_image'])) ? $oldFormatOrder['id_image'] : '';

        $imageUrl = netreviewsModel::getUrlProduct(
            (string) $oldFormatOrder['product_id'],
            (int) $oldFormatOrder['id_lang'],
            $idImage,
            (int) $oldFormatOrder['id_shop']
        );

        $dataProduct = [
            'product_ref' => [
                'reference' => $oldFormatOrder['product_id'],
            ],
            'name' => $oldFormatOrder['product_name'],
            'not_received' => false,
            'brand' => (!empty($oldFormatOrder['manufacturer_name']) ? $oldFormatOrder['manufacturer_name'] : $oldFormatOrder['shop_name']),
            'price' => $oldFormatOrder['product_price'],
            'product_url' => $productUrl,
            'image_url' => $imageUrl,
            'category' => $oldFormatOrder['category'],
        ];

        foreach (self::$refList as $dbName => $peName) {
            if (!empty($oldFormatOrder[$dbName])) {
                $dataProduct['product_ref'][$peName] = $oldFormatOrder[$dbName];
            }
        }

        return $dataProduct;
    }
}
