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
 *  @copyright  Copyright (c) 2010-2016 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */

var ecoas_baseDir = '';
var ecoas_token = '';
var ecoas_tmpDisplayInfoRetour = '';

$(document).ready(function () {
    var oldcss = "";
    ecoas_baseDir = $('#ecoas_baseDir').val();
    ecoas_token = $('#ecoas_token').val();
    var ecgcpCpanelInterval = 0;
    $(document).on('mouseenter', '.ecoas_cpanel', function () {
        var prefix = $(this).attr('data-prefix');
        var suffix = $(this).attr('data-suffix');
        if (ecgcpCpanelInterval === 0) {
            ecgcpCpanelInterval = setInterval(function(){updateCpanelDisplay(prefix, suffix);}, 2000);
        }
    });
    $(document).on('mouseleave', '.ecoas_cpanel', function () {
        if (ecgcpCpanelInterval !== 0) {
            clearInterval(ecgcpCpanelInterval);
            ecgcpCpanelInterval = 0;
        }
    });
    $(document).on('click', '#file-selectbutton', function(e){
        $('#ecoas_stockfile').trigger('click');
    });
    $(document).on('click', '#file-name', function(e){
        $('#ecoas_stockfile').trigger('click');
    });
    $(document).on('change', '#ecoas_stockfile', function(e){
        var val = $(this).val();
        var file = val.split(/[\\/]/);
        $('#file-name').val(file[file.length-1]);
    });
    $(document).on('dragenter', '#fileblock', function(e){
        oldcss = $(this).css("border");
        $(this).css('border', '3px dashed grey');
        return false;
    });
    $(document).on('dragover', '#fileblock', function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border', '3px dashed grey');
        return false;
    });
    $(document).on('dragleave', '#fileblock', function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).css("border", oldcss);
        return false;
    });
    document.querySelector('#fileblock')
        .addEventListener('drop', (ev) => {
            ev.preventDefault();
            document.querySelector('#ecoas_stockfile').files = ev.dataTransfer.files;
            $('#fileblock').css("border", oldcss);
            $('#file-name').val(ev.dataTransfer.files[0].name);
    });
    $(document).on('click', 'button[name=download]', function(e){
        e.preventDefault();
        var formData = new FormData($('form[name=ecoas_file_form]').get(0));// get the form data
        // on envoi formData vers mail.php
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : ecoas_baseDir+'ajax.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            processData : false,
            contentType : false
        })
        .done(function (data) {
            displayInfoRetour('20','success');
        })
        .fail(function (data) {
            displayInfoRetour('21','nosuccess');
        });
    });
});
function updateCpanelDisplay(prefix, suffix) {
    $.ajax({
        url: ecoas_baseDir+"ajax.php",
        type: "POST",
        data: ({
            majsel: 65,
            ecoas_token: ecoas_token,
            prefix: prefix,
            suffix: suffix
        }),
        dataType: "html"
    })
    .done(function (data) {
        $('#ecoas_vpanel').html(data);
    });
}
function save_parameter_ecordersandstock() {
    var ecordersandstock_conf = $(".ecordersandstock_conf").serialize();
    $.ajax({
        url: ecoas_baseDir+"ajax.php",
        type: "POST",
        data: ({
            majsel: 10,
            ecoas_token: ecoas_token,
            ecordersandstock_conf: ecordersandstock_conf
        }),
        dataType: "json"
    })
    .done(function (data) {
        displayInfoRetour('6','success');
    })
    .fail(function (data) {
        displayInfoRetour('7','nosuccess');
    });
}
function send_orders() {
    $.ajax({
        url: ecoas_baseDir+"ajax.php",
        type: "POST",
        data: ({
            majsel: 26,
            ecoas_token: ecoas_token
        }),
        dataType: "json"
    })
    .done(function (data) {
        displayInfoRetour(data.mc, data.suxs);
    })
    .fail(function (data) {
        displayInfoRetour('31', 'nosuccess');
    });
}
function send_orders_marketplace(){
    $.ajax({
        url: ecoas_baseDir+"ajax.php",
        type: "POST",
        data: ({
            majsel: 15,
            ecoas_token: ecoas_token
        }),
        dataType: "json"
    })
    .done(function (data) {
        displayInfoRetour(data.mc, data.suxs);
    })
    .fail(function (data) {
        displayInfoRetour('31', 'nosuccess');
    });
}
function confirm_task_start() {
    displayInfoRetour('12','success');
}
function confirm_task_stop() {
    displayInfoRetour('13','success');
}
function displayInfoRetour(message, etat) {
    clearTimeout(ecoas_tmpDisplayInfoRetour);
    if (etat === 'message') {
        $.ajax({
            url: ecoas_baseDir+"ajax.php",
            type: "POST",
            async : false,
            data: {
                majsel: 55,
                ecoas_token: ecoas_token,
                etat: etat,
                message: message
            },
            dataType: 'html'
        }).done(function( data ) {
            return data;
        });

    } else {
        $.ajax({
            url: ecoas_baseDir+"ajax.php",
            type: "POST",
            data: {
                majsel: 55,
                ecoas_token: ecoas_token,
                etat: etat,
                message: message
            },
            dataType: 'html'
        }).done(function( data ) {
            $("html, body").animate({ scrollTop: 0 }, 500);
            $('#display_message').show(500);
            $('#display_message').html(data);
            ecoas_tmpDisplayInfoRetour = setTimeout(function(){
                $('#display_message').hide(500);
                $('#display_message').html('');
            }, 15000);
        });
    }
}
