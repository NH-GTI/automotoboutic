$(document).ready(function () {
    function getCustomizationIds() {
        var customizations = Array();
        $('.product-line-info a.label[data-id_customization]').each(function(k,v) {
            customizations.push(parseInt($(this).data('id_customization')));
        });
        return customizations;
    }

    /* Add SAP to cart */
    $('#sap_reference_form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            'async': true,
            'type': "POST",
            'dataType': 'json',
            'url': '/modules/ndk_advanced_custom_fields/front_ajax.php',
            'data': {
                'action': 'addSapToCartProducts',
                'customizations' : getCustomizationIds(),
                'sapValue': $('#sap_value').val()
            },
            'success': function (data) {
                if (data.success == true) {
                    location.reload();
                }
            }
        });
    });
    /* /Add SAP to cart */

    /* Prevent order if SAP is not filled in */
    $('.sap_warning').on('click', function(e) {
        e.preventDefault();

        $.ajax({
            'async': true,
            'type': "POST",
            'dataType': 'json',
            'url': '/modules/ndk_advanced_custom_fields/front_ajax.php',
            'data': {
                'action': 'isOrderAvailable',
                'customizations' : getCustomizationIds()
            },
            'success': function (data) {
                if (data.activateOrderBtn == true) {
                    $(location).attr('href',$(e.target).attr('href'));
                } else {
                    alert(sapMissingOrIncorrectMsg);
                }
            }
        })
    })
    /* /Prevent order if SAP is not filled in */
});