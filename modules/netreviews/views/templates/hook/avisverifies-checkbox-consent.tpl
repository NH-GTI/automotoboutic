{**
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
 *}

<div id="checkboxNetreviewsConsent">
    <input id="netreviews-url-for-ajax" type="hidden" value="{$url|escape:'htmlall':'UTF-8'}">
    <input id="netreviews-id-shop" type="hidden" value="{$idShop|escape:'htmlall':'UTF-8'}">
    <input id="netreviews-group-name" type="hidden" value="{$groupName|escape:'htmlall':'UTF-8'}">
    <input id="netreviews-id-customer" type="hidden" value="{$idCustomer|escape:'htmlall':'UTF-8'}">
    <input id="netreviews-version-presta" type="hidden" value="{$prestashopVersion|escape:'htmlall':'UTF-8'}">
    <label><input id="checkbox" type="checkbox" name="consent_netreviews"> {l s='J\'accepte de recevoir une demande d\'avis de la part de la société Echte Bewertungen suite à ma commande.' mod='netreviews'}</label>
</div>
