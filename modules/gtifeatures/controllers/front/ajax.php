<?php
/**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class GtifeaturesAjaxModuleFrontController extends ModuleFrontController
{
    /**
     * @var bool
     */
    public $ssl = true;

    /**
     * @see FrontController::initContent()
     *
     * @return void
     */
    public function initContent()
    {
        parent::initContent();

        $return = ['status' => 'skip'];

        if ($this->module instanceof GTIFeatures && Tools::getValue('action') === 'add-to-cart') {
            $return = ['status' => 'KO', 'message' => 'At least one of these params is missing or invalid: env, setcenter'];
            if (Tools::getValue('env') === 'web') {// && Tools::getValue('setcenter')
                $idCustomization = (int) Tools::getValue('id_customization');
//		var_dump($idCustomization);
                $dbresult = Db::getInstance()->executeS('
                    SELECT cde.`admin_name`, cde.`value`
                    FROM `' . _DB_PREFIX_ . 'ndk_customized_data_extended` cde
                    WHERE
                        cde.`id_customization` = ' . $idCustomization . '
                        AND cde.`admin_name` IN ("reference", "gabarit", "GA.marque", "GA.modele")
                    ');
		// replace center
		$redirectUrl = Configuration::get('GTIFEATURES_REDIRECT_URL_AFTER_ADD_TO_CART');
//		var_dump($redirectUrl);
//		exit();
                //$redirectUrl = str_replace('{center}', Tools::getValue('setcenter'), $redirectUrl);
		// replace value
                if ($dbresult) {
                    foreach ($dbresult as $line) {
                        $encodeValue = urlencode($line['value']);
                        $adminName = $line['admin_name'];
                        $keyAdminName = '{'.$adminName.'}';
                        if (strpos($redirectUrl, $keyAdminName)) {
                            $redirectUrl = str_replace($keyAdminName, $encodeValue, $redirectUrl);
                        }
                    }
                }

                $return = [
                    'status' => 'OK',
                    'redirectUrl' => $redirectUrl,
                ];

/*                if (count($data) === 4) {
                    $redirectUrl = 'https://www.feuvert.fr/product/';
                    $redirectUrl .= $data['reference'];
                    $redirectUrl .= '?setcenter=' . Tools::getValue('setcenter');
                    $redirectUrl .= '&infoLib=GTI';
                    $redirectUrl .= '&infoCar=' . $data['gabarit'];
                    $redirectUrl .= '&gtimarque=' . $data['GA.marque'];
                    $redirectUrl .= '&gtimodele=' . $data['GA.modele'];

                    $return = [
                        'status' => 'OK',
                        'redirectUrl' => $redirectUrl
                    ];
                } else {
                    $return['message'] = 'Cannot find product informations';
                }*/
            }
        }
        ob_end_clean();
        // Clear cart as it is useless by now
        //$this->context->cart->delete();
        header('Content-Type: application/json');
        die(json_encode($return));
    }
}
