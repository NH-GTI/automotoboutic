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
var url = document.location.href;
setTimeout(show_button, 1000);
function show_button(){
    var ecoas_token = $('#ecoas_token').val();
    var id_order = $('#ecoas_id_order').val();
    $(document).on('click', '#ecoas_resetbutton', function (e) {
        e.preventDefault();
        $.ajax({
            url: "../modules/ecordersandstock/ajax.php",
            type: "POST",
            data: ({
                majsel: 89,
                ecoas_token: ecoas_token,
                id_order: id_order
            }),
            dataType: "text"})
        .done(function (data) {
            $('#ecoas_resetbutton').text(data);
        });
    });
    $(".well.hidden-print:first").append($('#ecoas_resetbutton'));
    $.ajax({
        url: "../modules/ecordersandstock/ajax.php",
        type: "GET",
        data: ({
            majsel: 88,
            ecoas_token: ecoas_token,
            id_order: id_order
        }),
        dataType: "text"})
    .done(function (data) {
        $('#ecoas_resetbutton').text(data);
    });
}