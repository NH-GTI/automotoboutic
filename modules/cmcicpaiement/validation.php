<?php
/**
 * 2007-2015 PrestaShop
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2014 PrestaShop SA
 * @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
 * International Registered Trademark & Property of PrestaShop SA
 */

/*
 * This file is an obsolete entrypoint for approving payments from your bank.
 * Validations must now be run from the front controller.
 */

$_GET['fc'] = 'module';
$_GET['module'] = 'cmcicpaiement';
$_GET['controller'] = 'validation';

require_once dirname(__FILE__) . '/../../index.php';
