<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL Ether Création
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL Ether Création is strictly forbidden.
 * In order to obtain a license, please contact us: contact@ethercreation.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe Ether Création
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL Ether Création est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter la SARL Ether Création a l'adresse: contact@ethercreation.com
 * ...........................................................................
 *  @package    ecordersandstock
 *  @author     Alec Page
 *  @copyright  Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */

if (!defined('_PS_VERSION_')) {
    exit();
}
require_once dirname(__FILE__).'/ecordersandstock_funcs.php';

class EcOrdersAndStock extends ecordersandstock_funcs
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getContent()
    {
        $this->smarty->assign(
            parent::_getContent()
        );

        return $this->display(__FILE__, 'views/templates/admin/config.tpl');
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookDisplayBackOfficeHeader($params)
    {
        if (Tools::getValue('configure') === $this->name && Tools::getValue('controller') === 'AdminModules') {
            $this->context->controller->addJQuery();
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            //$this->context->controller->addCSS($this->_path . 'views/css/back.css');
            return;
        }

        $id_order = Tools::getValue('id_order', 0);
        if ($this->order_button && ('AdminOrders' === Context::getContext()->controller->controller_name) && $id_order) {
            $this->context->controller->addJS(($this->_path) . 'views/js/ecoas_resetbutton.js');
            $this->smarty->assign(
                array(
                    'ecoas_token' => self::getConfigValue('ecoas_token'),
                    'ecoas_id_order' => $id_order
                )
            );

            return $this->display(__FILE__, 'views/templates/admin/ecoasreset.tpl');
        }

    }

    public function getCpanelData($prefix = false, $paramConnecteur = false)
    {
        if (!$prefix || !$paramConnecteur) {
            return;
        }

        // get infos about the cron task(s) prefixed by $prefix
        $prefix = trim($prefix, '_') . '_';

        $jobs_state = self::jGetLike($prefix . '%');
        if (!$jobs_state) {
            return;
        }

        $suffix = '_' . $paramConnecteur;
        $status = array(
            'start_time' => isset($jobs_state[$prefix . 'START_TIME' . $suffix]) ? $jobs_state[$prefix . 'START_TIME' . $suffix] : '',
            'end_time' => isset($jobs_state[$prefix . 'END_TIME' . $suffix]) ? $jobs_state[$prefix . 'END_TIME' . $suffix] : '',
            'state' => isset($jobs_state[$prefix . 'STATE' . $suffix]) ? $jobs_state[$prefix . 'STATE' . $suffix] : '',
            'act' => isset($jobs_state[$prefix . 'ACT' . $suffix]) ? $jobs_state[$prefix . 'ACT' . $suffix] : '',
            'shops_todo' => isset($jobs_state[$prefix . 'SHOPS_TODO' . $suffix]) ? $jobs_state[$prefix . 'SHOPS_TODO' . $suffix] : '',
            'shop' => isset($jobs_state[$prefix . 'SHOP' . $suffix]) ? $jobs_state[$prefix . 'SHOP' . $suffix] : '',
            'stage' => isset($jobs_state[$prefix . 'STAGE' . $suffix]) ? $jobs_state[$prefix . 'STAGE' . $suffix] : '',
            'loops' => isset($jobs_state[$prefix . 'LOOPS' . $suffix]) ? $jobs_state[$prefix . 'LOOPS' . $suffix] : '',
            'progress' => isset($jobs_state[$prefix . 'PROGRESS' . $suffix]) ? $jobs_state[$prefix . 'PROGRESS' . $suffix] : '',
            'progressmax' => isset($jobs_state[$prefix . 'PROGRESSMAX' . $suffix]) ? $jobs_state[$prefix . 'PROGRESSMAX' . $suffix] : '',
            'data' => isset($jobs_state[$prefix . 'DATA' . $suffix]) ? $jobs_state[$prefix . 'DATA' . $suffix] : '',
            'message' => isset($jobs_state[$prefix . 'MESSAGE' . $suffix]) ? $jobs_state[$prefix . 'MESSAGE' . $suffix] : ''
        );

        $this->smarty->assign('cpanelData', $status);

        return $this->display(__FILE__, 'views/templates/admin/cpaneltable.tpl');
    }

    public function displayInfoRetour($message, $etat = '')
    {
        $this->smarty->assign(
            array(
                'message' => $message,
                'etat' => $etat
            )
        );

        return $this->display(dirname(__FILE__), 'views/templates/admin/messages.tpl');
    }


}
