/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

function iwgtidisplayMessage(msgtype, msg) {
  var html = '<div class="bootstrap"><div class="alert '+msgtype+'"><button type="button" class="close" data-dismiss="alert">×</button><ul class="list-unstyled"><li>' + msg + '</li></ul></div></div>';
  var content = $('#content');
  content.prepend(html);
  $('html, body').animate({ scrollTop: 0 }, 0);
}

$(document).ready(function () {
  $(".uploadfile").on('click', function (e) {
    e.preventDefault();
    var typefile = $('#type_file').val();
    if (typefile == 'importfile') {
      var type = $('#type').val();
      var target = $('#target').val();
      if (type == undefined || type == "") {
        iwgtidisplayMessage('alert-warning', 'Il faut renseigner le type (ex: ST)');
        return;
      }  
    }
    if (typefile == 'imagefile') {
      var subpath = $('#subpath').val();
      if (subpath == undefined || subpath == "") {
        iwgtidisplayMessage('alert-warning', 'Il faut renseigner le sous-répertoire (ex: QM)');
        return;
      }  
    }
    var filename = $('#inputfile-name').val();
    var file = $('#inputfile').prop('files')[0];
    if (file == undefined || file == "") {
      iwgtidisplayMessage('alert-warning', 'Il faut choisir un fichier avant de le transférer sur le serveur');
      return;
    }
    var form_data = new FormData();
    form_data.append("typefile", typefile);
    if (typefile == 'importfile') {
      form_data.append("type", type);
      form_data.append("target", target);
    } else if (typefile == 'imagefile') {
      form_data.append("subpath", subpath);
    }
    form_data.append("file", file);
    form_data.append("filename", filename);
    form_data.append("action", "uploadfile");
    $.ajax({
      url: "../modules/iwgtiimport/iwgtiupload-ajax.php",
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      dataType: 'json',
      type: 'post',
      success: function (result) {
        iwgtidisplayMessage('alert-info', result.msg);
        if (result.typefile == 'importfile') {
          $('#save_import_file').trigger('click');
        } else if (result.typefile == 'imagefile') {
          $('#save_image_file').trigger('click');
        }
      },
      error: function (result) {
        iwgtidisplayMessage('alert-warning', 'Erreur');
      }
    });
  });

  $('#inputfile-selectbutton').click(function(e){
    $('#inputfile').trigger('click');
  });
  $('#inputfile').change(function(e){
    var val = $(this).val();
    var file = val.split(/[\\/]/);
    $('#inputfile-name').val(file[file.length-1]);
  });

  $('.file_selection').change(function() {
    var id_file = $(this).data('id');
    if (id_file == undefined) {
      iwgtidisplayMessage('alert-warning', 'id_file requis');
      return;
    }
    var form_data = new FormData();    
    form_data.append("id_file", id_file);
    form_data.append("action", "toggleFileSelection");
    $('.file_selection:not(#file_selected_'+id_file+')').prop('checked', false);
    $.ajax({
      url: "../modules/iwgtiimport/iwgtiupload-ajax.php",
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      dataType: 'json',
      type: 'post',
      success: function (result) {
        if (result.msg != false) {
          iwgtidisplayMessage('alert-info', result.msg);
        }
      },
      error: function (result) {
        iwgtidisplayMessage('alert-warning', result.msg);
      }
    });
  });

});