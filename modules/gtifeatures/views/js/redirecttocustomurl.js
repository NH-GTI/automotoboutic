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

$(document).ready(function () {
    // Search for 'env' and 'setcenter' in querystring
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const env = urlParams.get('env') !== 'web' ? 'web' : urlParams.get('env');
    const setcenter = urlParams.get("setcenter") == "" ? "test" : urlParams.get("setcenter");
    prestashop.on(
        'updateCart',
        function (event) {
            var debug = prestashop.urls.base_url.indexOf('localhost') >= 0 ? true : false;
            var requestData = {};
            if (event && event.reason && typeof event.resp !== 'undefined' && !event.resp.hasError) {
                requestData = {
                    id_customization: event.reason.idCustomization,
                    id_product_attribute: event.reason.idProductAttribute,
                    id_product: event.reason.idProduct,
                    action: event.reason.linkAction,
                    env: env,
                    setcenter: setcenter,
                };
                $.post(
                    prestashop.urls.base_url + 'module/gtifeatures/ajax',
                    requestData
                ).then(function (resp) {
                    if (debug) {
                        alert(resp.status+"\n"+resp.redirectUrl);
                    }
			console.log(resp.redirectUrl);
                    if (resp.status === 'OK' && resp.redirectUrl) {
                        if (resp.redirectUrl.includes('fv-fo-re7')) {
                            window.parent.postMessage(resp.redirectUrl, 'https://fv-fo-re7.azurewebsites.net/static/gti-38444.html');
                        }
                        else if(resp.redirectUrl.includes('www.feuvert.fr')){
                            window.parent.postMessage(resp.redirectUrl, 'https://www.feuvert.fr/static/ilv-tapis-gti-38523.html');
                        }
                        else if(resp.redirectUrl.includes('borne.feuvert.fr')){
                            window.parent.postMessage(resp.redirectUrl, 'https://borne.feuvert.fr/static/tapis-voiture-sur-mesure-personnalisables-38523.html?env=borne');
                        }
                    } else if (resp.status === "KO") {
                        // @TODO : add error handling
                    }
                }).fail(function (resp) {
                    if (debug) {
                        alert("error");
                    }
                    prestashop.emit('handleError', { eventType: 'updateShoppingCart', resp: resp });
                });
            }
        }
    );
});
