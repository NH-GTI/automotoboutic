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
 * @version   Release: $Revision: 8.1.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'netreviews/netreviews.php';

class NetreviewsAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        if (Tools::getValue('ajax')) {
            $consent = Tools::getValue('collect_consent');
            $idShop = (int) Tools::getValue('idShop');
            $groupName = Tools::getValue('groupName');
            $idCustomer = (int) Tools::getValue('idCustomer');
            echo $consent;

            $netreviews = new Netreviews();
            $order = $netreviews->getLastIdOrder($idShop, $idCustomer);
            $idOrder = $order['id_order'];

            if ('no' == $consent) {
                if ('checked' == Configuration::get('AV_MULTILINGUE', null, null, $idShop)) {
                    $key = 'AV_CONSENT_ANSWER_NO' . $groupName;
                } else {
                    $key = 'AV_CONSENT_ANSWER_NO';
                }
                echo $key;

                if (Configuration::hasKey($key, null, null, $idShop)) {
                    $value = json_decode(Configuration::get($key, null, null, $idShop, false), true);
                    $values = array_values($value);
                    $values[] = (int) $idOrder;
                } else {
                    $values = [];
                    $values[] = (int) $idOrder;
                }
                Configuration::updateValue($key, json_encode($values), false, null, $idShop);
            }

            exit(json_encode($consent));
        }
    }
}
