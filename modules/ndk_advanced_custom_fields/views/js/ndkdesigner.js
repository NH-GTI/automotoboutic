/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
 */


$(document).on('click', '.addText', function(e) {
    e.preventDefault();
    maxlenght = $(this).attr('data-max');
    group = $(this).attr('data-group');
    zindex = $(this).attr('data-zindex');
    target = $(this).attr('data-id');
    view = $(this).attr('data-view');
    dragdrop = $(this).attr('data-dragdrop');
    resizeable = $(this).attr('data-resizeable');
    rotateable = $(this).attr('data-rotateable');
    price = $(this).attr('data-price');
    blend = $(this).attr('data-blend');
    ppcprice = $(this).attr('data-ppcprice');
    maxItems = parseInt($(this).attr('data-max-item'));
    $(this).parent().find('.max-limit').hide();

    others = $(this).parent().find('.designer-item');
    number = others.length + 1;
    if (maxItems == 0 || (number <= maxItems)) {
        $(this).parent().find('.designer-item').slideUp();

        del_btn = '<a href="#" class="remove-item-block"  data-group="' + group + '" data-group-target="' + group + '-' + parseInt(number) + '"><span><i class="material-icons">delete</i></span></a>';

        textItem = '<div id="designer-item-container-' + group + '-' + parseInt(number) + '" class="designer-item-container"><h4 data-target="#item-' + group + '-' + parseInt(number) + '" id="toggler-' + group + '-' + parseInt(number) + '" class="itemToggler">' + designerTextText + '<span> ' + parseInt(number) + '</span>' + del_btn + '</h4><div id="item-' + group + '-' + parseInt(number) + '" class="designer-item clearfix clear" data-number="' + parseInt(number) + '">' +
            '<textarea id="text-item-' + group + '-' + number + '" data-lines="1" ' +
            'data-max="' + maxlenght + '" ' +
            'data-group="' + group + '" ' +
            'data-number="' + number + '" ' +
            'data-zindex="' + zindex + '" ' +
            'data-id="' + target + '" ' +
            'data-view="' + view + '" ' +
            'data-dragdrop="' + dragdrop + '" ' +
            'data-resizeable="' + resizeable + '" ' +
            'data-rotateable="' + rotateable + '" ' +
            'data-price="' + price + '" ' +
            'data-blend="' + blend + '" ' +
            'data-ppcprice="' + ppcprice + '" ' +
            'data-pattern="" ' +
            'class="form-control textzone ndktextarea textItem"></textarea>';

        if ($(this).parent().find('.orientation_selection').length > 0) {
            orientable_block = $(this).parent().find('.orientation_selection').html();
            textItem += '<div data-group-target="' + group + '-' + parseInt(number) + '" class="clear clearfix orientation_selection">' + orientable_block + '</div>';
        }
        textItem += '</div></div>';

        textItem = $(this).parent().find('.itemsBlock').append(textItem);
        scrollToNdk(textItem, 800);
        initText($('#text-item-' + group + '-' + number));

        $('#ndkcsfield_' + group).val(designerValue).trigger('keyup');
        makeSortable();
        textItem.find('.itemToggler').trigger('click');
    } else {
        $(this).parent().find('.max-limit').show();
    }
});


$(document).on('click', '.addTextArea', function(e) {
    e.preventDefault();
    maxlenght = $(this).attr('data-max');
    group = $(this).attr('data-group');
    zindex = $(this).attr('data-zindex');
    target = $(this).attr('data-id');
    view = $(this).attr('data-view');
    dragdrop = $(this).attr('data-dragdrop');
    resizeable = $(this).attr('data-resizeable');
    rotateable = $(this).attr('data-rotateable');
    price = $(this).attr('data-price');
    ppcprice = $(this).attr('data-ppcprice');
    blend = $(this).attr('data-blend');
    maxItems = parseInt($(this).attr('data-max-item'));
    $(this).parent().find('.max-limit').hide();

    others = $(this).parent().find('.designer-item');
    number = others.length + 1;
    if (maxItems == 0 || (number <= maxItems)) {

        $(this).parent().find('.designer-item').slideUp();
        del_btn = '<a href="#" class="remove-item-block"  data-group="' + group + '" data-group-target="' + group + '-' + parseInt(number) + '"><span><i class="material-icons">delete</i></span></a>';

        textItem = '<div id="designer-item-container-' + group + '-' + parseInt(number) + '" class="designer-item-container"><h4 data-target="#item-' + group + '-' + parseInt(number) + '" id="toggler-' + group + '-' + parseInt(number) + '" class="itemToggler">' + designerTextText + '<span> ' + parseInt(number) + '</span>' + del_btn + '</h4><div id="item-' + group + '-' + parseInt(number) + '" class="designer-item clearfix clear" data-number="' + parseInt(number) + '">' +
            '<textarea id="text-item-' + group + '-' + number + '" data-lines="1" ' +
            'data-max="' + maxlenght + '" ' +
            'data-group="' + group + '" ' +
            'data-number="' + number + '" ' +
            'data-zindex="' + zindex + '" ' +
            'data-id="' + target + '" ' +
            'data-view="' + view + '" ' +
            'data-dragdrop="' + dragdrop + '" ' +
            'data-resizeable="' + resizeable + '" ' +
            'data-rotateable="' + rotateable + '" ' +
            'data-price="' + price + '" ' +
            'data-ppcprice="' + ppcprice + '" ' +
            'data-blend="' + blend + '" ' +
            'data-pattern="" ' +
            'class="form-control textzone ndktextarea type_textarea textItem"></textarea>';

        if ($(this).parent().find('.orientation_selection').length > 0) {
            orientable_block = $(this).parent().find('.orientation_selection').html();
            textItem += '<div data-group-target="' + group + '-' + parseInt(number) + '" class="clear clearfix orientation_selection">' + orientable_block + '</div>';
        }
        textItem += '</div></div>';
        textItem = $(this).parent().find('.itemsBlock').append(textItem);
        scrollToNdk(textItem, 800);
        initText($('#text-item-' + group + '-' + number));
        $('#ndkcsfield_' + group).val(designerValue).trigger('keyup');
        makeSortable();
        imgItem.find('.itemToggler').trigger('click');
    } else {
        $(this).parent().find('.max-limit').show();
    }
});


$(document).on('click', '.addImg', function(e) {
    e.preventDefault();

    group = $(this).attr('data-group');
    zindex = $(this).attr('data-zindex');
    target = $(this).attr('data-id');
    view = $(this).attr('data-view');
    dragdrop = $(this).attr('data-dragdrop');
    resizeable = $(this).attr('data-resizeable');
    rotateable = $(this).attr('data-rotateable');
    price = $(this).attr('data-price');
    blend = $(this).attr('data-blend');
    maxItems = parseInt($(this).attr('data-max-item'));
    $(this).parent().find('.max-limit').hide();

    others = $(this).parent().find('.designer-item');
    number = others.length + 1;
    if (maxItems == 0 || (number <= maxItems)) {
        clonedUpload = $(this).parent().find('.ndkhiddenuploadfile').clone();
        clonedUpload.removeClass('ndkhiddenuploadfile').addClass('imgItem');
        clonedUpload.find('.img-value').attr('data-group', group + '-' + number);

        clonedLibrary = $(this).parent().find('.ndkhiddenimglibrary').clone();
        clonedLibrary.removeClass('ndkhiddenimglibrary').addClass('imgItem').attr('id', 'main-' + group + '-' + number);
        clonedLibrary.find('.img-value').attr('data-group', group + '-' + number);

        $(this).parent().find('.designer-item').slideUp();
        del_btn = '<a href="#" class="remove-item-block"  data-group="' + group + '" data-group-target="' + group + '-' + parseInt(number) + '"><span><i class="material-icons">delete</i></span></a>';

        imgItem = '<div id="designer-item-container-' + group + '-' + parseInt(number) + '" class="designer-item-container"><h4 id="toggler-' + group + '-' + parseInt(number) + '"  data-target="#item-' + group + '-' + parseInt(number) + '" class="itemToggler">' + designerImgText + '<span> ' + parseInt(number) + '</span>' + del_btn + '</h4><div id="item-' + group + '-' + parseInt(number) + '" class="designer-item clearfix clear" data-number="' + parseInt(number) + '">' + clonedUpload.html() + clonedLibrary.html();

        if ($(this).parent().find('.orientation_selection').length > 0) {
            orientable_block = $(this).parent().find('.orientation_selection').html();
            imgItem += '<div data-group-target="' + group + '-' + parseInt(number) + '" class="clear clearfix orientation_selection">' + orientable_block + '</div>';
        }
        imgItem += '</div></div>';
        imgItem = $(this).parent().find('.itemsBlock').append(imgItem);
        scrollToNdk(imgItem, 800);

        $('#ndkcsfield_' + group).val(designerValue).trigger('keyup');

        $('.ndk_selector').each(function() {
            $(this).setNdkSelector();
        });
        makeSortable();
        imgItem.find('.itemToggler').trigger('click');
    } else {
        $(this).parent().find('.max-limit').show();
    }
});

$(document).on('click', '.itemToggler', function() {
    group = $(this).attr('data-target').split('-');
    group = group[1];
    $('#main-' + group).find('.itemToggler').removeClass('selected');
    $(this).toggleClass('selected');

    $('#main-' + group).find('.designer-item').not($($(this).attr('data-target'))).slideUp();
    $('#main-' + group).find('.designer-item').not($($(this).attr('data-target'))).parent().removeClass('activeItem');

    $($(this).attr('data-target')).parent().toggleClass('activeItem')
    $($(this).attr('data-target')).slideToggle();

});


String.prototype.escape = function() {
    var tagsToReplace = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;'
    };
    return this.replace(/[&<>]/g, function(tag) {
        return tagsToReplace[tag] || tag;
    });
};

$(document).on('click', '.submitTextItem', function() {
    group = parseInt($(this).parent().parent().find('.ndktextarea:eq(0)').attr('data-group'));
    number = $(this).parent().parent().find('.ndktextarea').attr('data-number');
    zindex = $(this).parent().parent().find('.textzone').attr('data-zindex');
    price = $(this).parent().parent().find('.ndktextarea').attr('data-price');
    ppcprice = $(this).parent().parent().find('.ndktextarea').attr('data-ppcprice');
    blend = $(this).parent().parent().find('.ndktextarea').attr('data-blend');
    is_caracter = $(this).parent().parent().find('.ndktextarea').hasClass('caracter-text');

    view = $(this).parent().parent().find('.ndktextarea').attr('data-view');

    dragdrop = $(this).parent().parent().find('.ndktextarea').attr('data-dragdrop');
    resizeable = $(this).parent().parent().find('.ndktextarea').attr('data-resizeable');
    rotateable = $(this).parent().parent().find('.ndktextarea').attr('data-rotateable');
    charsCount = 0;

    svgPath = '';
    svgPath = $(this).parent().parent().find('.ndktextarea').attr('data-path');



    if ($(this).parent().find('.noborder').length > 0) {
        texte = '';
        $(this).parent().find('.noborder').each(function() {
            if ($(this).val() != '') {
                texte += $(this).val() + ' ';
                charsCount += $(this).val().replace(/\ /g, '').length;
            } else {
                //$(this).css('height', 0);
            }
        });
    } else {
        texte = $(this).parent().find('.textarea').text();
    }

    if (texte == '' || texte == ' ') {
        price = 0;
        texte = '';
    } else if (ppcprice > 0) {
        price = ppcprice * charsCount;
    } else {
        price = $(this).parent().parent().find('.ndktextarea').attr('data-price');
    }

    $(this).parent().parent().find('.ndktextarea').val(texte).trigger('keyup');

    $(this).parent().find('.textarea').css({ width: 'auto', height: 'auto', display: 'table' });

    /*if(texte !='')
    $(this).parent().parent().find('.fontSelectUl li.active').trigger('click');*/

    if (texte == '' || texte == ' ') {
        price = 0;
        texte = '';
    }

    verticalPadding = 10;
    horizontalPadding = 0;
    $(this).parent().find('.status_counter').hide();


    height = $(this).parent().find('.textarea').innerHeight() - parseFloat(verticalPadding);
    width = $(this).parent().find('.textarea').innerWidth() - parseFloat(horizontalPadding) - scrollbarWidth;
    //updatePriceNdk(price, group);
    $('.status_counter').hide();


    if ($(".zone_limit[data-group='" + group + "']").length > 0 && $(".view_tab[data-view='" + view + "']").length > 0 && $(this).parent().find('textarea.noborder').length > 0) {
        container = ".zone_limit[data-group='" + group + "']";
        zwidth = $(container).width();
        zheight = $(container).height();
        $(this).parent().find('.textarea').css({ width: zwidth });
        width = zwidth;
    }



    svglines = '';
    fontSize = parseFloat($(this).parent().css('font-size'));
    alignment = $(this).parent().find('.texteditor').css('text-align');
    x = '0%';
    txtanchord = 'text-anchor="start"';
    startOffset = ' startOffset="50%"';

    if (alignment == 'left') {
        x = '0%';
        txtanchord = 'text-anchor="start"';
        startOffset = ' startOffset="0%"';
    } else if (alignment == 'center') {
        x = '50%';
        txtanchord = 'text-anchor="middle"';
        startOffset = ' startOffset="50%"';
    } else if (alignment == 'right') {
        x = '100%';
        txtanchord = 'text-anchor="end"';
        startOffset = ' startOffset="100%"';
    }


    if (svgPath == 0)
        svgPath = '';


    if ($(this).parent().find('textarea.noborder').length > 0) {
        var lines = $(this).parent().find('textarea.noborder').val().split('\n');
        y = 1;
        onlyText = '';
        for (var i = 0; i < lines.length; i++) {
            if (svgPath != '') {
                svglines += '<textPath style="z-index:' + zindex + ';" ' + txtanchord + startOffset + ' xlink:href="#' + svgPath + '">' + lines[i] + '</textPath>';
            } else {
                svglines += '<tspan ' + txtanchord + ' x="' + x + '" y="' + fontSize * y + '">' + lines[i].escape() + '</tspan>';
            }
            y++;
        }
        textToWrite = $(this).parent().find('textarea.noborder').val();
    } else {
        textToWrite = '';
        y = 1;
        $(this).parent().find('.noborder').each(function() {
            //textToWrite += $(this).val()+' '+'\n'+' ';
            if (svgPath != '') {
                textToWrite += '<textPath style="z-index:' + zindex + ';" ' + txtanchord + startOffset + '  xlink:href="#' + svgPath + '">' + $(this).val() + '</textPath>';
                onlyText = $(this).val();
            } else {
                //textToWrite +='<tspan '+txtanchord+' x="'+x+'" y="'+fontSize*y+'">'+$(this).val();+'</tspan>';
                textToWrite += $(this).val() + '' + '\n' + '';
            }
            y++;
        });
    }

    style = $(this).parent().attr('style').replace('"', '\'').replace('"', '\'');

    var effect3d = false;

    var metalEffect = [];
    metalEffect['effect'] = '';
    metalEffect['fill'] = '';
    metalEffect['fillLight'] = '';
    metalEffect['fillShadow'] = '';

    fontFamily = $(this).parent().find('.texteditor').css('font-family');
    fontFamily = fontFamily.replace('"', "'").replace('"', "'");
    //gold effect
    if ($(this).parent().find('.texteditor').css('color') == 'rgb(255, 215, 0)') {
        effect3d = true;
        metalEffect = metaleffect('#efd8a2', '#a28156', '#efd8a2', '#2f1f05', '#fff3c6', group + '-' + number, textToWrite, width, height, fontFamily, fontSize, effect3d, false);

    }

    //silver effect
    else if ($(this).parent().find('.texteditor').css('color') == 'rgb(192, 192, 192)') {
        effect3d = true;
        metalEffect = metaleffect('#888888', '#dedede', '#F5F5F5', '#444444', '#dedede', group + '-' + number, textToWrite, width, height, fontFamily, fontSize, effect3d, false);

    } else if ($(this).parent().find('.texteditor').attr('data-effect') == 'concavMe') {
        color1 = $(this).parent().find('.texteditor').css('color');
        color2 = darkerColor($(this).parent().find('.texteditor').css('color'), .2);
        shadowcolor = darkerColor($(this).parent().find('.texteditor').css('color'), .4);
        lightcolor = lighterColor($(this).parent().find('.texteditor').css('color'), .2);
        strokecolor = darkerColor($(this).parent().find('.texteditor').css('color'), .2);
        effect3d = true;
        metalEffect = metaleffect(color2, color1, strokecolor, lightcolor, shadowcolor, group + '-' + number, textToWrite, width, height, fontFamily, fontSize, effect3d, false);

    } else if ($(this).parent().find('.texteditor').attr('data-effect') == 'convexMe') {
        color1 = $(this).parent().find('.texteditor').css('color');
        color2 = lighterColor($(this).parent().find('.texteditor').css('color'), .2);
        shadowcolor = darkerColor($(this).parent().find('.texteditor').css('color'), .2);
        lightcolor = lighterColor($(this).parent().find('.texteditor').css('color'), .3);
        strokecolor = lighterColor($(this).parent().find('.texteditor').css('color'), .2);
        effect3d = true;
        metalEffect = metaleffect(color1, color2, strokecolor, shadowcolor, lightcolor, group + '-' + number, textToWrite, width, height, fontFamily, fontSize, effect3d, false);

    } else {
        color1 = $(this).parent().find('.texteditor').css('color');
        strokecolor = $(this).parent().find('.texteditor').attr('stroke-color');
        effect3d = false;
        metalEffect = metaleffect(color1, color1, strokecolor, 'transparent', 'transparent', group + '-' + number, textToWrite, width, height, fontFamily, fontSize, effect3d, false, true);
    }



    if (svgPath != '' && typeof(svgPath) != 'undefined' && svgPath != 0 && $(this).parent().parent().find('.ndktextarea').hasClass('visual-effect')) {

        $('#svgText_' + group + '-' + number).remove();
        $('#svgUse_' + group + '-' + number).remove();

        $(".caracter-container > svg > [data-group-text='" + group + '-' + number + "']").remove();
        writeCurve = $('.caracter-container > svg ').append(metalEffect['textPathEffect'] + '<text dominant-baseline="middle" dy="0.1em"  data-font-family="' + fontFamily + '" data-group-text="' + group + '-' + number + '" id="svgText_' + group + '-' + number + '" style="font-family:' + fontFamily + ' ;font-size:' + fontSize + 'px;fill:' + metalEffect['fill'] + ';z-index:' + zindex + ';" >' + textToWrite + '</text>');

        $.when(writeCurve).then(function() {
            setTimeout(function() {
                $('#caracter-container-' + group + '-' + number + ' > svg').html($('#caracter-container-' + group + '-' + number + ' > svg').html());
                $('#caracter-container-' + group + '-' + number).css('z-index', zindex).css('mix-blend-mode', coloreffect);
            }, 500);
        });
    } else {
        if (is_caracter) {
            composeCaracter(metalEffect['svg'], group + '-' + number, view, zindex, dragdrop, resizeable, rotateable, width, height, 'svg', blend, '', 'text');
        } else {
            designCompo(metalEffect['svg'], group + '-' + number, view, zindex, dragdrop, resizeable, rotateable, width, height, 'svg', blend);
        }



    }

    if ($(this).parent().find('textarea.noborder').length > 0) {
        svg_textMultiline('svgText_' + group + '-' + number, textToWrite.replace(/\n/g, ' ± '), width, fontSize, txtanchord, x);
        svg_textMultiline('svgText_' + group + '-' + number + '-shadow', textToWrite.replace(/\n/g, ' ± '), width, fontSize, txtanchord, x);
        svg_textMultiline('svgText_' + group + '-' + number + '-light', textToWrite.replace(/\n/g, ' ± '), width, fontSize, txtanchord, x);
    }
    //console.log(parseInt(group))
    if (textToWrite != '')
        updatePriceNdk(price, parseInt(group));
    //$(this).parent().find('.noborder').css('height', '');
});



function checkMultiFieldLimits(group) {
    mainBlock = $('.form-group[data-field=' + group + ']');
    console.log(mainBlock)
    maxItems = parseInt(mainBlock.attr('data-max-item'));
    minItems = parseInt(mainBlock.attr('data-min-item'));
    others = mainBlock.find('.designer-item-container');
    number = others.length;
    console.log(number);
    if (maxItems != 0 && (number > maxItems)) {
        mainBlock.find('.designer-item-container:last').remove();
        mainBlock.find('.max-limit').show();
    }

    if (minItems > 0) {
        if (parseInt(number) < parseInt(minItems)) {
            $('#main-' + group + ' .quantity_error_down').addClass('required_field');
        } else {
            $('#main-' + group + ' .quantity_error_down').removeClass('required_field').hide();
            $('.form-group[data-field="' + group + '"]').removeClass('focusRequired');
        }
    }

}

$(document).on('click', '.remove-item-block', function(event) {
    event.preventDefault();
    var group = $(this).attr('data-group-target');
    var group_parent = $(this).attr('data-group');
    others = $(this).parent().parent().find('.designer-item');
    $('#visual_' + group).remove();
    $('#designer-item-container-' + group).remove();
    $('#toggler-' + group).remove();
    $('#layer-edit-' + group).remove();
    idInput = group.split('-');
    //console.log(others.length);
    if (others.length > 1) {
        $('#ndkcsfield_' + idInput[0]).val(designerValue).trigger('keyup');
    } else {
        $('#ndkcsfield_' + idInput[0]).val('').trigger('keyup');
        updatePriceNdk(0, $(this).attr('data-group'));
    }
    $(this).hide();
    rootGroupBlock = $(".form-group[data-field='" + group + "']:not(.submitContainer)");
    /*if(others.length == 1)
     updatePriceNdk(0, group);*/
    $('#ndkcsfield_' + idInput[0]).trigger('keyup');
    selectedConfigValue[idInput[0]] = '';
    checkLayerChanges();
    makeSortable();
    checkMultiFieldLimits(group_parent)


});


$(document).on('click', '.remove-img-item', function(event) {
    event.preventDefault();
    group = $(this).attr('data-group-target');
    others = $(this).parent().parent().find('.designer-item');
    $('#visual_' + group).remove();
    $('#layer-edit-' + group).remove();
    $(this).parent().find('.selected-value').removeClass('selected-value');
    idInput = group;
    $('#ndkcsfield_' + group).val('').trigger('keyup');
    selectedConfigValue[group] = '';
    $(this).hide();
    if ($(this).hasClass('removePrice'))
        updatePriceNdk(0, group);

    checkLayerChanges();
    makeSortable();

})




function metaleffect(startColor, stopColor, strokeColor, shadowColor, lightColor, group, textToWrite, width, height, fontFamily, fontSize, effect3d, textureEffect, applat) {
    if (typeof(metaleffect_Override) == 'function') {
        return metaleffect_Override(startColor, stopColor, strokeColor, shadowColor, lightColor, group, textToWrite, width, height, fontFamily, fontSize, effect3d, textureEffect, applat);
    }
    applat = applat || false;
    var metalEffect = [];
    //console.log(width)
    //console.log(height)
    if (!applat) {
        metalEffect['effect'] = '<linearGradient class="svggradient" data-group-text="' + group + '"  id="metalEffect_' + group + '" x1="0%" y1="100%" y2="0%" x2="80%">';
        metalEffect['effect'] += '<stop stop-color="' + startColor + '" offset="0%"></stop>';
        metalEffect['effect'] += '<stop stop-color="' + stopColor + '" offset="30%"></stop>';
        metalEffect['effect'] += '<stop stop-color="' + startColor + '" offset="70%"></stop>';
        metalEffect['effect'] += '<stop stop-color="' + stopColor + '" offset="100%"></stop>';
        metalEffect['effect'] += '</linearGradient>';

        metalEffect['effect'] += '<filter class="svgfilter" data-group-text="' + group + '" id="shadow_' + group + '" height="140%" y="20%" width="140%" x="-30%"><feGaussianBlur result="shadow" stdDeviation="0 0"></feGaussianBlur><feGaussianBlur result="shadow" stdDeviation="1 0.5"></feGaussianBlur><feOffset dx="0" dy="2"></feOffset></filter>';

        metalEffect['effect'] += '<filter class="svgfilter" data-group-text="' + group + '"  id="shadow2_' + group + '" height="140%" y="20%" width="140%" x="-30%"><feGaussianBlur result="shadow" stdDeviation="0.2 0.2"></feGaussianBlur><feOffset dx="0" dy="-0.7"></feOffset></filter>';

        metalEffect['effect'] += '<filter class="svgfilter" data-group-text="' + group + '" id="light_' + group + '" height="140%" y="20%" width="140%" x="-30%"><feGaussianBlur result="shadow" stdDeviation="0 0"></feGaussianBlur><feGaussianBlur result="shadow" stdDeviation="0 0"></feGaussianBlur><feOffset dy="-1" dx="0"></feOffset></filter>';

        metalEffect['fill'] = (textureEffect ? 'url(#texture_' + group + ')' : 'url(#metalEffect_' + group + ')') + ' ;stroke: ' + strokeColor + '; stroke-width:0.8';
        metalEffect['fillShadow'] = shadowColor + '; filter: url(#shadow_' + group + ')';
        metalEffect['fillLight'] = lightColor + '; filter: url(#light_' + group + ')';
    } else {
        metalEffect['effect'] = '';
        metalEffect['fill'] = startColor + ' ;stroke: ' + strokeColor + '; stroke-width:0.8';
        metalEffect['fillShadow'] = '';
        metalEffect['fillLight'] = ''

    }

    textPathEffect = '';
    svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" class="textareaSvg composition_element composition_element-text" viewBox="0 0 ' + width + ' ' + (height + 5) + '" preserveAspectRatio="xMidYMid meet" style="' + style + '" height="' + height + '" width="' + width + '">';

    //svg ='<svg xmlns="http://www.w3.org/2000/svg" class="textareaSvg composition_element" >';


    svg += metalEffect['effect'];

    fontUrl = fontFamily.replace(/\s/g, "+");
    fontUrl = fontUrl.replace(/\"/g, "");
    fontUrl = fontUrl.replace(/\'/g, "");
    fullFontUrl = 'https://fonts.googleapis.com/css?family=' + fontUrl;
    styleContent = '@import url(\'' + fullFontUrl + '\')';
    svg += '<defs><style type="text/css" >' + styleContent + '</style></defs>';
    textPathEffect += metalEffect['effect'];

    var lines = textToWrite.split('\n');
    if (lines.length > 1) {
        y = 1;
        for (var i = 0; i < lines.length; i++) {
            if (lines[i].escape().replace(/\s/g, '') != '') {
                myX = x;
                if (txtanchord == 'text-anchor="start"' && y > 1)
                    myX = 0;

                svglines += '<tspan ' + txtanchord + ' x="' + myX + '" y="' + fontSize * y + '">' + lines[i].escape() + '</tspan>';
                y++;
            }
        }
    }


    if (effect3d == true && !applat) {
        svg += '<text dominant-baseline="middle" dy="0.1em"  data-font-family="' + fontFamily + '" id="svgText_' + group + '-light" x="0%" y="10" style="fill:' + metalEffect['fillLight'] + ';"font-family:' + fontFamily + ' ;>' + svglines + '</text>';
        svg += '<text dominant-baseline="middle" dy="0.1em"  data-font-family="' + fontFamily + '" id="svgText_' + group + '-shadow" x="0%" y="10" style="fill:' + metalEffect['fillShadow'] + ';font-family:' + fontFamily + ' ;">' + svglines + '</text>';

        textPathEffect += '<text dominant-baseline="middle" dy="0.1em"  data-font-family="' + fontFamily + '" data-group-text="' + group + '" id="svgText_' + group + '-' + number + '-light" style="font-family:' + fontFamily + ' ;font-size:' + fontSize * 1 + 'px; fill:' + metalEffect['fillLight'] + ';">' + svglines + '</text>';

        textPathEffect += '<text dominant-baseline="middle" dy="0.1em"  data-font-family="' + fontFamily + '" data-group-text="' + group + '" id="svgText_' + group + '-' + number + '-shadow" style="font-family:' + fontFamily + ' ;font-size:' + fontSize * 1 + 'px; fill:' + metalEffect['fillShadow'] + ';">' + svglines + '</text>';
    } else if (textureEffect) {
        svg += '<pattern id="texture_' + group + '" patternUnits="userSpaceOnUse" viewBox="0 0 ' + width + ' ' + (height + 5) + '" height="' + height + '" width="' + width + '">';
        svg += '<image xlink:href="' + textureEffect + '" height="' + height + '" width="' + width + '"/>';
        svg += '</pattern>';

    }
    svg += '<text dominant-baseline="middle" dy="0.1em"  data-font-family="' + fontFamily + '" id="svgText_' + group + '"  x="0%" y="10" style="fill:' + metalEffect['fill'] + ';font-family:' + fontFamily + ';">';

    svg += svglines;
    svg += '</text>';
    svg += '</svg>';

    metalEffect['textPathEffect'] = textPathEffect;
    //metalEffect['svg'] = setSvgDimension(svg, group, width, height);
    metalEffect['svg'] = svg;

    return metalEffect;

}




function setSvgDimension(svg, group, width, height) {
    if (typeof(setSvgDimension_Override) == 'function') {
        return setSvgDimension_Override(svg, group, width, height);
    }

    console.log(width)
    console.log(height)
    $temp = $('<div class="tempSvgText">');
    $("body").append($temp);
    $temp.css({ width: width, height: height })
    $temp.append(svg);
    BBox = $('.tempSvgText #svgText_' + group)[0].getBBox();
    mysvg = svg.replaceAll('viewBox="0 0 ' + width + ' ' + (height + 5) + '"', 'viewBox="0 0 ' + BBox.width + ' ' + (BBox.height + 5) + '"');
    mysvg = mysvg.replaceAll('height="' + height + '" width="' + width + '"', 'height="' + BBox.height + '" width="' + BBox.width + '"');
    $temp.remove();
    return mysvg;
}

String.prototype.replaceAll = function(str1, str2, ignore) {
    return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&"), (ignore ? "gi" : "g")), (typeof(str2) == "string") ? str2.replace(/\$/g, "$$$$") : str2);
}



var pad = function(num, totalChars) {
    var pad = '0';
    num = num + '';
    while (num.length < totalChars) {
        num = pad + num;
    }
    return num;
};

// Ratio is between 0 and 1
var changeColor = function(color, ratio, darker) {
    // Trim trailing/leading whitespace
    color = color.replace(/^\s*|\s*$/, '');

    // Expand three-digit hex
    color = color.replace(
        /^#?([a-f0-9])([a-f0-9])([a-f0-9])$/i,
        '#$1$1$2$2$3$3'
    );

    // Calculate ratio
    var difference = Math.round(ratio * 256) * (darker ? -1 : 1),
        // Determine if input is RGB(A)
        rgb = color.match(new RegExp('^rgba?\\(\\s*' +
            '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
            '\\s*,\\s*' +
            '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
            '\\s*,\\s*' +
            '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
            '(?:\\s*,\\s*' +
            '(0|1|0?\\.\\d+))?' +
            '\\s*\\)$', 'i')),
        alpha = !!rgb && rgb[4] != null ? rgb[4] : null,

        // Convert hex to decimal
        decimal = !!rgb ? [rgb[1], rgb[2], rgb[3]] : color.replace(
            /^#?([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i,
            function() {
                return parseInt(arguments[1], 16) + ',' +
                    parseInt(arguments[2], 16) + ',' +
                    parseInt(arguments[3], 16);
            }
        ).split(/,/),
        returnValue;

    // Return RGB(A)
    return !!rgb ?
        'rgb' + (alpha !== null ? 'a' : '') + '(' +
        Math[darker ? 'max' : 'min'](
            parseInt(decimal[0], 10) + difference, darker ? 0 : 255
        ) + ', ' +
        Math[darker ? 'max' : 'min'](
            parseInt(decimal[1], 10) + difference, darker ? 0 : 255
        ) + ', ' +
        Math[darker ? 'max' : 'min'](
            parseInt(decimal[2], 10) + difference, darker ? 0 : 255
        ) +
        (alpha !== null ? ', ' + alpha : '') +
        ')' :
        // Return hex
        [
            '#',
            pad(Math[darker ? 'max' : 'min'](
                parseInt(decimal[0], 10) + difference, darker ? 0 : 255
            ).toString(16), 2),
            pad(Math[darker ? 'max' : 'min'](
                parseInt(decimal[1], 10) + difference, darker ? 0 : 255
            ).toString(16), 2),
            pad(Math[darker ? 'max' : 'min'](
                parseInt(decimal[2], 10) + difference, darker ? 0 : 255
            ).toString(16), 2)
        ].join('');
};
var lighterColor = function(color, ratio) {
    return changeColor(color, ratio, false);
};
var darkerColor = function(color, ratio) {
    return changeColor(color, ratio, true);
};


$(document).on('keyup', '.visual-text-custom-font', function() {

    group = $(this).attr('data-group');
    text = $(this).val();
    textArray = text.split('');
    //console.log(textArray);
    htmlLetter = '';

    for (var i = 0; i < textArray.length; i++) {
        htmlLetter += '<span class="customFontLetter customFont_' + group + '_letter_' + textArray[i] + '">' + textArray[i] + '</span>';
    }

    $.when($(this).parent().find('.custom-font-rendering').html(htmlLetter)).then(function() {

        $('.customFontLetter').each(function() {
            content = $(this).css('content');
            content = content.replace('url("', '').replace('")', '');
            $(this).html('<img src="' + content + '"/>');
            convertPercentEl($(this));
        });


        //setLetterWidth();
    });



});

function setLetterWidth() {
    if (typeof(setLetterWidth_Override) == 'function') {
        return setLetterWidth_Override();
    }
    $('.customFontLetter').each(function() {
        parentWidth = $(this).parent().width();
        elWidth = $(this).width();
        $(this).width(elWidth / parentWidth * 100 + '%');
    });
    equalheight('.customFontLetter');
}

$(document).on('click', '.submitCSText', function() {
    group = $(this).parent().find('.visual-text-custom-font').attr('data-group');
    zindex = $(this).parent().find('.visual-text-custom-font').attr('data-zindex');
    price = $(this).parent().find('.visual-text-custom-font').attr('data-price');
    ppcprice = $(this).parent().find('.visual-text-custom-font').attr('data-ppcprice');
    blend = $(this).parent().find('.visual-text-custom-font').attr('data-blend');


    view = $(this).parent().find('.visual-text-custom-font').attr('data-view');

    dragdrop = $(this).parent().find('.visual-text-custom-font').attr('data-dragdrop');
    resizeable = $(this).parent().find('.visual-text-custom-font').attr('data-resizeable');
    rotateable = $(this).parent().find('.visual-text-custom-font').attr('data-rotateable');
    charsCount = 0;

    texte = $(this).parent().find('.visual-text-custom-font').text();

    if (texte == '' || texte == ' ') {
        price = 0;
        texte = '';
    } else if (ppcprice > 0) {
        price = ppcprice * charsCount;
    } else {
        price = $(this).parent().find('.visual-text-custom-font').attr('data-price');
    }

    if (texte == '' || texte == ' ') {
        price = 0;
        texte = '';
    }

    height = $(this).parent().find('.custom-font-rendering').innerHeight();
    width = $(this).parent().find('.custom-font-rendering').innerWidth();



    html = '<div id="cecft_' + group + '" class="composition_element customFontTextElement" style="height:' + height + 'px; width:' + width + 'px">' + $(this).parent().find('.custom-font-rendering').html() + '</div>';

    //html = '<div class="composition_element customFontTextElement">'+$(this).parent().find('.custom-font-rendering').html()+'</div>';
    updatePriceNdk(price, parseInt(group));
    //$('.status_counter').hide();
    /*html2canvas($(this).parent().find('.custom-font-rendering'), {
            onrendered: function(canvas) {
                    var dataURL = canvas.toDataURL("image/png");
                    designCompo(dataURL, group, view, zindex, dragdrop, resizeable, rotateable, width, 'auto', false);
                    //$('.custom-font-rendering').css({position : 'relative', height : 'auto', width : 'auto', zIndex : ''});
                    //$('.customFontLetter').css({height : '', width : ''});
                	
                    
            }
        });*/


    $.when(designCompo(html, group, view, zindex, dragdrop, resizeable, rotateable, width, height, 'svg', blend)).then(function() {
        /*$('#cecft_'+group+' .customFontLetter').each(function(){
            convertPercentEl($(this));
        });*/
        $('#cecft_' + group).css({ height: '', width: '' });
    });
});



function convertPercentEl(el) {
    if (typeof(convertPercentEl_Override) == 'function') {
        return convertPercentEl_Override(el);
    }
    container = el.parent();
    containerWidth = container.width();
    containerHeight = container.height();

    elWidth = el.width();
    elHeight = el.height();

    elLeft = el.css('left').replace('px', '');
    elTop = el.css('top').replace('px', '');


    widthPercent = (elWidth / containerWidth) * 100 + '%';
    heightPercent = (elHeight / containerHeight) * 100 + '%';
    heightPercent = 'auto';
    leftPercent = (elLeft / containerWidth) * 100 + '%';
    topPercent = (elTop / containerHeight) * 100 + '%';

    el.css({ width: widthPercent, height: heightPercent, left: leftPercent, top: topPercent, margin: '' });
}


function registerInitialValues() {
    if (typeof(registerInitialValues_Override) == 'function') {
        return registerInitialValues_Override();
    }
    $('.fieldPane > [id^="main-"]').each(function() {
        splittedGroup = $(this).attr('id').split('-');
        group = splittedGroup[1] + (typeof(splittedGroup[2]) != 'undefined' ? '-' + splittedGroup[2] : '');

        initialValues[group] = []
            //images
        if ($(this).find('.img-item-row').length > 0) {
            initialValues[group].push($(this).find('.img-item-row'));
        }
        //couleurs
        if ($(this).find('.color-ndk').length > 0) {
            initialValues[group].push($(this).find('.color-ndk'));
        }
        //select
        if ($(this).find('.ndk-select').length > 0) {
            initialValues[group].push($(this).find('.ndk-select'));
        }

        //radio
        if ($(this).find('.ndk-radio').length > 0) {
            initialValues[group].push($(this).find('.ndk-radio'));
        }

        //checkbox
        if ($(this).find('.ndk-checkbox').length > 0) {
            initialValues[group].push($(this).find('.ndk-checkbox'));
        }

    });
}

function loadInitialValues() {
    if (typeof(loadInitialValues_Override) == 'function') {
        return loadInitialValues_Override();
    }
    for (idGroup in initialValues) {
        if (typeof(initialValues[idGroup]) != 'undefined') {
            if (typeof(initialValues[idGroup][0]) != 'undefined') {
                for (var i = 0; i < initialValues[idGroup][0].length; i++) {
                    element = initialValues[idGroup][0][i];

                    container = $(".form-group[data-field='" + idGroup + "']");

                    //image
                    if ($(element).hasClass('img-item-row')) {
                        idValue = $(element).find('.img-value:eq(0)').attr('data-id-value');
                        if (idValue != 0 && typeof(idValue) != 'undefined' && idValue != '' && $(".form-group[data-field='" + idGroup + "'] .img-value[data-id-value='" + idValue + "']").length == 0) {
                            $(element).removeClass('selected-value');
                            $(element).find('.img-value').removeClass('selected-value');
                            $(element).find('.svg-container').removeClass('selected-svg');
                            $(".form-group[data-field='" + idGroup + "'] .img-value").last().parent().after($(element)[0].outerHTML);

                        }

                    }

                    //color
                    else if ($(element).hasClass('color-ndk')) {
                        idValue = $(element).attr('data-id-value');
                        if (idValue != 0 && typeof(idValue) != 'undefined' && idValue != '' && $(".form-group[data-field='" + idGroup + "'] .color-ndk[data-id-value='" + idValue + "']").length == 0) {
                            $(element).removeClass('selected-color');
                            $(".form-group[data-field='" + idGroup + "'] .color-ndk").last().after($(element)[0].outerHTML);

                        }

                    }

                    //select
                    else if ($(element).is('select')) {

                        $(element).find('option').each(function() {
                            option = $(this);
                            idValue = $(option).attr('data-id-value');

                            if (idValue != 0 && typeof(idValue) != 'undefined' && idValue != '' && $(".form-group[data-field='" + idGroup + "'] .ndk-select > option[data-id-value='" + idValue + "']").length == 0) {
                                $(".form-group[data-field='" + idGroup + "'] select").append($(option)[0].outerHTML);

                            }
                        });
                    }


                    /*else( $('.fieldPane > #main-'+idGroup+' #'+$(element).attr('id')).length < 1 ){
                        //console.log($(element)[0].attr('id'));
                        $('#main-'+idGroup).append($(element)[0].outerHTML);
                    }*/
                }
            }
        }
    }
    setTimeout(function() {
        equalheight('.img-item-row');
    }, 1000)


}


function makeGroupFieldsSlide() {
    if (typeof(makeGroupFieldsSlide_Override) == 'function') {
        return makeGroupFieldsSlide_Override();
    }
    $('.groupFieldBlock').addClass('sliderBlock');
    $('.groupFieldBlock > .form-group').addClass('ndkackFieldItem');

    setTimeout(function() {
        ndkCfShowSlide($('.sliderBlock .ndkackFieldItem:visible:eq(0)'));
    }, 500);

    $('.sliderBlock').each(function() {
        allItems = $(this).find(".ndkackFieldItem");
        if (allItems.length > 0) {
            if (allItems.length > 1) {
                pager = '<p class="ndkcfPager">';
                allItems.each(function() {
                    pager += '<span data-view="' + $(this).attr('data-view') + '" target="' + $(this).attr('data-iteration') + '" class="ndkcfPagerItem"></span>';
                });
                pager += '</p>';
                $('.ndkcfPager').remove();
                $(this).append(pager).addClass('multipleSlides');
            }
        } else {
            $(this).remove();
        }
    });


    $('.groupFieldBlock').css('padding-bottom', $('.sliderBlock .ndkackFieldItem:visible:eq(0)').innerHeight());
    $('.groupFieldBlock.sliderBlock').find('.ndkcfnav').remove();
    $('.groupFieldBlock.sliderBlock.multipleSlides').append('<p class="ndkcfnav"><a class="prevNdkcfItem">&nbsp;</a> <a class="nextNdkcfItem">&nbsp;</a></p>');


    $(document).on('click', '.nextNdkcfItem', function() {
        found = false;
        current = $(this).parent().parent().find('.ndkackFieldItem.activeItem:eq(0)');
        others = $(this).parent().parent().find(".ndkackFieldItem:visible:not(.activeItem)");
        others.each(function() {
            if (parseFloat($(this).attr('data-iteration')) > parseFloat(current.attr('data-iteration')) && !found) {
                ndkCfShowSlide($(this), 'rtl');
                found = true;
            }
        });
        if (!found) {
            ndkCfShowSlide(others.first(), 'rtl');
            found = true;
        }
    });


    $(document).on('click', '.prevNdkcfItem', function() {
        current = $(this).parent().parent().find('.ndkackFieldItem.activeItem:eq(0)');
        others = $(this).parent().parent().find(".ndkackFieldItem:visible:not(.activeItem)");
        bigger = -1;
        others.each(function() {
            if (parseFloat($(this).attr('data-iteration')) < parseFloat(current.attr('data-iteration')) && parseFloat($(this).attr('data-iteration')) > bigger) {
                bigger = parseFloat($(this).attr('data-iteration'));
                target = $(this);
            }
        });

        if (typeof(target) != 'undefined') {
            ndkCfShowSlide(target, 'ltr');
        } else {
            ndkCfShowSlide(others.last(), 'ltr');
        }
    });

    $(document).on('click', '.ndkcfPagerItem', function() {
        ndkCfShowSlide($(this).parent().parent().find(".ndkackFieldItem[data-iteration='" + $(this).attr('target') + "']"));
    });

    /*$(document).on('swiperight', '.sliderBlock', function(){
           $(this).find('.prevNdkcfItem').trigger('click');
    });
    
    $(document).on('swipeleft', '.sliderBlock', function(){
           $(this).find('.nextNdkcfItem').trigger('click');
    });*/

    $('.sliderBlock00').swipe({
        //Generic swipe handler for all directions
        swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
            if (direction == 'left')
                $(this).find('.nextNdkcfItem').trigger('click');
            else if (direction == 'right')
                $(this).find('.prevNdkcfItem').trigger('click');
        },
        //Default is 75px, set to 0 for demo so any distance triggers swipe
        threshold: 0
    });


    $('.sliderBlock .ndkackFieldItem').resize(function() {
        $(this).parent().css('padding-bottom', $('.ndkackFieldItem.activeItem:eq(0)').innerHeight());
    });


}


$("audio").on("play", function() {
    var id = $(this).attr('id');

    $("audio").not(this).each(function(index, audio) {
        audio.pause();
    });
});

$("video").on("play", function() {
    var id = $(this).attr('id');

    $("video").not(this).each(function(index, video) {
        video.pause();
    });
});



function ndkCfShowSlide(el, direction) {
    if (typeof(ndkCfShowSlide_Override) == 'function') {
        return ndkCfShowSlide_Override(el, direction);
    }
    direction = direction || 'ltr';
    $('.img-item-row').css('height', '');
    el.parent().find('.ndkackFieldItem').removeClass('activeItem');

    el.parent().find('.ndkackFieldItem').removeClass('slideInRight').removeClass('slideInLeft');
    if (direction == 'rtl')
        el.parent().find('.ndkackFieldItem').addClass('slideInRight').removeClass('slideInLeft');
    else
        el.parent().find('.ndkackFieldItem').removeClass('slideInRight').addClass('slideInLeft');


    el.addClass('activeItem');
    el.parent().find('.ndkcfPagerItem').removeClass('activePager');
    el.parent().find(".ndkcfPagerItem[target='" + el.attr('data-iteration') + "']").addClass('activePager');
    //scrollToNdk(el.parent(), 800);

    setTimeout(function() {
        equalheight('.img-item-row');
        el.parent().css('padding-bottom', $('.ndkackFieldItem.activeItem:eq(0)').innerHeight());
        el.parent().find('.ndkackFieldItem').removeClass('slideInRight').removeClass('slideInLeft');
    }, 500);
}




$(document).on('click', '.form-group', function() {
    field = $(this).attr('data-field');

    $('.editThisLayer').removeClass('layerActive');
    $('.resetZones').remove();

    $('.zone_limit, .absolute-visu').removeClass('activeZone').removeClass('discretZone');
    zone = $(".zone_limit[data-group='" + field + "'], .absolute-visu[data-group='" + field + "']");
    if ((zone.find('.ui-resizable, .ui-draggable, .rotatable').length > 0 || zone.is('.ui-resizable', '.ui-draggable', '.rotatable')) && !$(this).hasClass('activeFormGroup_ooooooo')) {
        $('.form-group').removeClass('activeFormGroup');
        $(this).addClass('activeFormGroup');
        $('.zone_limit, .absolute-visu').addClass('discretZone')
        zone.addClass('activeZone').removeClass('discretZone');
        $('#layer-edit-' + field).addClass('layerActive');
        $(".editThisLayer[data-group*='" + field + "-']").addClass('layerActive').find('.ui-resizable, .ui-draggable, .rotatable').trigger('mouseover');
        $('#layer-block').append('<span class="resetZones">' + resetText + '</span>');
    } else {
        $('.zone_limit, .absolute-visu').removeClass('activeZone').removeClass('discretZone');
        $('.editThisLayer').removeClass('layerActive');
        $('.form-group').removeClass('activeFormGroup');
        $('.resetZones').remove();
    }
});

$(document).on('click', '.editThisLayer', function() {
    group = $(this).attr('data-group').split('-');
    $('.editThisLayer').removeClass('layerActive');
    $(this).addClass('layerActive');
    $(".form-group[data-field='" + group[0] + "']").trigger('click');
});

$(document).on('mousedown', '.absolute-visu', function() {
    if ($(this).is('.ui-resizable', '.ui-draggable', '.rotatable')) {
        group = $(this).attr('data-group').split('-');
        $('.editThisLayer').removeClass('layerActive');
        $(this).addClass('activeZone');
        $(".form-group[data-field='" + group[0] + "']").trigger('click');
    }
});

$(document).on('click', '.resetZones', function() {
    $('.zone_limit, .absolute-visu').removeClass('activeZone').removeClass('discretZone');
    $('.editThisLayer').removeClass('layerActive');
    $('.form-group').removeClass('activeFormGroup');
    $('.resetZones').remove();
    //convertPercent();
    snapShotLight();
});

function checkLayerChanges() {
    if (typeof(checkLayerChanges_Override) == 'function') {
        return checkLayerChanges_Override();
    }
    if ($('#layer-block').find('.editThisLayer').length == 0)
        $('#layer-block').hide();

    else if ($('#layer-block').find('.editThisLayer').length == 1 && $('#layer-block').find('.layer_title').length == 0)
        $('#layer-block').show().prepend('<p class="layer_title">' + selectLayer + '</p>');

    else if ($('#layer-block').find('.editThisLayer').length > 0)
        $('#layer-block').show();
}

/*$(document).on('mouseout', '.activeZone > .absolute-visu', function(){
  $('.zone_limit').removeClass('activeZone');
});*/

/*$(document).on('mouseover', '#ndkcsfields-block', function(){
  $('.zone_limit').removeClass('activeZone');
});*/

/*$(document).on('mouseover', '#submitNdkcsfields', function(){
  $('.zone_limit, .absolute-visu').removeClass('activeZone').removeClass('discretZone');
  $('.form-group').removeClass('activeFormGroup');
  //convertPercent();
  snapShotLight();
});*/


$(document).on('keydown', '#ndkcsfields input[type="text"]', function(event) {
    if (event.keyCode == 13 || event.keyCode == 9) {
        $(this).focus();
        event.preventDefault();
    }
})

$(document).on('blur', '.noborder', function() {
    button = $(this).parent().parent().parent().find('.submitText:visible, .submitTextItem:visible');
    button.trigger('click');
})

/*$(document).on('mouseleave', '.ndkackFieldItem', function(){
    button = $(this).find('.submitText:visible, .submitTextItem:visible');
    button.trigger('click');
})*/

function makeSortable() {
    if (typeof(makeSortable_Override) == 'function') {
        return makeSortable_Override();
    }
    //$('.itemsBlock').sortable('destroy');
    $('.itemsBlock').sortable({
        items: '.designer-item-container',
        handle: '.itemToggler',
        cursor: 'move',
        opacity: 0.6,
        start: function(event, ui) {
            $('.resetZones').trigger('click');
        },
        stop: function(event, ui) {
            refZindex = ui.item.parent().parent().parent().find('button:eq(0)').attr('data-zindex');
            others = ui.item.parent().find('.designer-item-container');
            others.each(function() {
                $(this).attr('addzindex', $(others).index($(this)));
                block = $(this).find('.designer-item');
                target_key = block.attr('id').replace('item-', '');
                target = $('#visual_' + target_key);
                target.css('z-index', refZindex + $(others).index($(this)));

                //console.log($(others).index($(this)));
            });

        }
    });
}


$(document).on('click', '.colse-comb-tab', function() {
    $(this).parent().parent().parent().find('.combColumn').removeClass('openedCol').css('height', '');
});

$(document).on('click', '.ndkcf_col_title', function() {
    if (!$(this).parent().hasClass('openedCol')) {
        $(this).parent().parent().find('.combColumn').removeClass('openedCol').css('height', '');



        originalHeight = $(this).parent().innerHeight();
        refPosition = $(this).parent().position().top;
        //$(this).parent().addClass('openedCol').css('height', (combHeight+originalHeight+40)+'px');
        $.when($(this).parent().addClass('openedCol')).done(function() {
            combHeight = $(this).parent().find('.combRowList').innerHeight();
            //console.log(combHeight);
            $(this).parent().css('height', (combHeight + originalHeight) + 50 + 'px');
            $('.combColumn').each(function() {
                if ($(this).position().top == refPosition)
                    $(this).css('height', (combHeight + originalHeight) + 50 + 'px');
            });
        });
        equalheight($(this).parent().find('.combRow'));
    } else {
        //$(this).parent().parent().find('.combColumn').removeClass('openedCol').css('height', '');
    }

});



$(document).on('click', '.toggleQuantityDiscountBlock', function() {
    content = $(this).parent().find('.specificPriceBlock').html();
    if (!!$.prototype.fancybox) {
        $.fancybox.open([{
            type: 'inline',
            autoScale: false,
            minHeight: 30,
            width: '80%',
            height: '80%',
            showCloseButton: false,
            autoDimensions: false,
            content: '<div class="popupSpecificPrice clear clearfix">' + content + '</div>',
            beforeShow: function() {}
        }], {
            padding: 0
        });
    }
});


$(document).on('click', '.toggleQuantityDiscount', function() {
    $(this).parent().find('.quantityDiscount').slideToggle();
});


$(document).on('click', '.accessory-ndk-no-quantity', function(e) {
    if (!$(e.target).hasClass('accessory-more') && !$(e.target).hasClass('ndk_attribute_select')) {
        me = $(this);
        rootBlock = $(".form-group[data-field='" + me.attr('data-group') + "']");
        input = me.find('.ndk-accessory-quantity:eq(0)');
        my_id_value = input.attr('data-id-value');
        cancelFieldRestrictions(my_id_value, me.attr('data-group'));

        max = parseInt(rootBlock.attr('data-qtty-max'));
        total = 0;
        rootBlock.find('.ndk-accessory-quantity').each(function() {
            total += parseInt($(this).val())
        })
        if (max > 0) {
            if (total >= max) {
                rootBlock.find('.selected-accessory:eq(0) .ndk-accessory-quantity').not(input).val(0).trigger('keyup');;
                rootBlock.find('.selected-accessory:eq(0)').not(me).removeClass('selected-accessory')
                    /*me.removeClass('selected-accessory');
                    input.val(0).trigger('keyup');*/
            }
        }


        if (parseInt(input.val()) == 0) {
            me.addClass('selected-accessory');
            input.val(1).trigger('keyup');
            total += 1;
            selectedConfigValue['ndk-accessory-quantity-' + my_id_value] = 1;
        } else {
            if (parseInt(input.attr('data-qtty-min')) < 1) {

                selectedConfigValue['ndk-accessory-quantity-' + my_id_value] = 0;
                me.removeClass('selected-accessory');
                input.val(0).trigger('keyup');
                total -= 1;
            }

        }
        if (total == 0)
            applyDefaultValuesNdk(rootBlock);

        input.trigger('change');
    }
});

$(document).on('click', '.orientation-btn', function() {
    $(this).parent().find('.orientation-btn').removeClass('active_orientation');
    $(this).addClass('active_orientation');
    group = $(this).parent().attr('data-group-target');
    $('#visual_' + group + ' .composition_element, #visual_' + group + ' .colorize-cover-item').removeClassSVG('standard-orientation').removeClassSVG('reverse-orientation').addClassSVG($(this).attr('data-orientation'));
    $(this).parent().find('.orientation_input').val($(this).attr('data-orientation'));
});

/*
 * .addClassSVG(className)
 * Adds the specified class(es) to each of the set of matched SVG elements.
 */
$.fn.addClassSVG = function(className) {
    $(this).attr('class', function(index, existingClassNames) {
        return ((existingClassNames !== undefined) ? (existingClassNames + ' ') : '') + className;
    });
    return this;
};

/*
 * .removeClassSVG(className)
 * Removes the specified class to each of the set of matched SVG elements.
 */
$.fn.removeClassSVG = function(className) {
    $(this).attr('class', function(index, existingClassNames) {
        var re = new RegExp('\\b' + className + '\\b', 'g');
        return existingClassNames.replace(re, '');
    });
    return this;
};

$(document).on('focus', 'input.surface', function() {
    if ($(this).attr('step') != '')
        $(this).trigger('blur');
});

function makeSocialCompo(id_conf, popup) {
    if (typeof(makeSocialCompo_Override) == 'function') {
        return makeSocialCompo_Override(id_conf, popup);
    }


    if (showSocialTools == 1) {
        if (!!$.prototype.fancybox && popup) {
            data = $('.ndkShareCompo').html();
            $.fancybox.open([{
                type: 'inline',
                autoScale: false,
                minHeight: 30,
                width: '80%',
                height: '80%',
                showCloseButton: false,
                autoDimensions: false,
                content: '<div class="popupSocialContainer clear clearfix">' + data + '</div>',
                beforeShow: function() {
                    setSharedButtons(id_conf)
                }
            }], {
                padding: 0
            });
        } else {
            setSharedButtons(id_conf);
        }
    }


}



function setSharedButtons(id_conf) {
    if (typeof(setSharedButtons_Override) == 'function') {
        return setSharedButtons_Override(id_conf);
    }
    sharing_url = removeParamUrl('id_ndk_customization_field_configuration', window.location.href);
    sharing_url = addParameterToURL('id_ndk_customization_field_configuration=' + id_conf, sharing_url);
    sharing_url = addParameterToURL('date=' + $.now(), sharing_url);

    sharing_name = '';

    img_url = $('.current_config_img').attr('src');

    $('.copyLinkInput').text(sharing_url);
    if (img_url != '')
        $('.shareImgDl').attr('href', img_url).show();
    else
        $('.shareImgDl').hide();

    $.ajax({
        'async': true,
        'type': "GET",
        'global': false,
        'dataType': 'html',
        'url': baseUrl + 'modules/ndk_advanced_custom_fields/front_ajax.php',
        'data': { id_conf: id_conf, action: 'getConfImage' },
        'success': function(data) {
            //var conf_img_url = baseUrl+'img/scenes/'+data;
            var conf_img_url = $('#image-url-0').val();
            $('.current_config_img:eq(0)').attr('src', conf_img_url).show();
            $('button.ndk-social-sharing:not(.shareImgDl), button.social-sharing').on('click', function() {
                type = $(this).attr('data-type');
                if (type.length) {
                    switch (type) {
                        case 'twitter':
                            window.open('https://twitter.com/intent/tweet?text=' + sharing_name + ' ' + encodeURIComponent(sharing_url), 'sharertwt', 'toolbar=0,status=0,width=640,height=445');
                            break;
                        case 'facebook':
                            window.open('http://www.facebook.com/sharer.php?u=' + sharing_url, 'sharer', 'toolbar=0,status=0,width=660,height=445');
                            break;
                        case 'google-plus':
                            window.open('https://plus.google.com/share?url=' + sharing_url, 'sharer', 'toolbar=0,status=0,width=660,height=445');
                            break;
                        case 'pinterest':
                            window.open('http://www.pinterest.com/pin/create/button/?media=' + conf_img_url + '&url=' + sharing_url, 'sharerpinterest', 'toolbar=0,status=0,width=660,height=445');
                            break;
                        case 'copyLink':
                            copyToClipboard('.copyLinkInput:eq(0)');
                            break;
                    }
                }
            });
        }
    });


}


function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}


function addParameterToURL(param, sourceURL) {
    _url = sourceURL;
    _url += (_url.indexOf("?") !== -1 ? '&' : '?') + param;
    return _url;
}

function removeParamUrl(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + (params_arr.lenght > 0 ? "?" + params_arr.join("&") : '');
    }
    return rtn;
}


$('textarea.noborder').on('keyup change', function() {
    $(this).css('height', 'auto').css('height', this.scrollHeight + (this.offsetHeight - this.clientHeight));
    $(this).parent().css('height', 'auto').css('height', this.scrollHeight + (this.offsetHeight - this.clientHeight) + 15);
});

/*
 * Browser Detect script
 */
BrowserDetect = (function() {
    // script settings
    var options = {
        osVersion: true,
        minorBrowserVersion: true
    };

    // browser data
    var browserData = {
        browsers: {
            chrome: uaMatch(/Chrome\/([0-9\.]*)/),
            firefox: uaMatch(/Firefox\/([0-9\.]*)/),
            safari: uaMatch(/Version\/([0-9\.]*).*Safari/),
            opera: uaMatch(/Opera\/.*Version\/([0-9\.]*)/, /Opera\/([0-9\.]*)/),
            msie: uaMatch(/MSIE ([0-9\.]*)/, /Trident.*rv:([0-9\.]*)/)
        },
        engines: {
            webkit: uaContains('AppleWebKit'),
            trident: uaMatch(/(MSIE|Trident)/),
            gecko: uaContains('Gecko'),
            presto: uaContains('Presto')
        },
        platforms: {
            win: uaMatch(/Windows NT ([0-9\.]*)/),
            mac: uaMatch(/Mac OS X ([0-9_\.]*)/),
            linux: uaContains('X11', 'Linux')
        }
    };

    // perform detection
    var ua = navigator.userAgent;
    var detectData = {
        platform: detectItem(browserData.platforms),
        browser: detectItem(browserData.browsers),
        engine: detectItem(browserData.engines)
    };

    // private functions
    function uaMatch(regExp, altReg) {
        return function() {
            var result = regExp.exec(ua) || altReg && altReg.exec(ua);
            return result && result[1];
        };
    }

    function uaContains(word) {
        var args = Array.prototype.slice.apply(arguments);
        return function() {
            for (var i = 0; i < args.length; i++) {
                if (ua.indexOf(args[i]) < 0) {
                    return;
                }
            }
            return true;
        };
    }

    function detectItem(items) {
        var detectedItem = null,
            itemName, detectValue;
        for (itemName in items) {
            if (items.hasOwnProperty(itemName)) {
                detectValue = items[itemName]();
                if (detectValue) {
                    return {
                        name: itemName,
                        value: detectValue
                    };
                }
            }
        }
    }

    // add classes to root element
    (function() {
        // helper functions
        var addClassJS = function(cls) {
            var html = document.documentElement;
            html.className += (html.className ? ' ' : '') + cls;
        };
        var getVersion = function(ver) {
            return typeof ver === 'string' ? ver.replace(/\./g, '_') : 'unknown';
        };

        // add classes
        if (detectData.platform) {
            addClassJS(detectData.platform.name);
            if (options.osVersion) {
                addClassJS(detectData.platform.name + '-' + getVersion(detectData.platform.value));
            }
        }
        if (detectData.engine) {
            addClassJS(detectData.engine.name);
        }
        if (detectData.browser) {
            addClassJS(detectData.browser.name);
            addClassJS(detectData.browser.name + '-' + parseInt(detectData.browser.value, 10));
            if (options.minorBrowserVersion) {
                addClassJS(detectData.browser.name + '-' + getVersion(detectData.browser.value));
            }
        }
    }());

    // export detection information
    return detectData;
}());




function ndkImagetoDataURLForSvg(url, callback) {
    var image = new Image();
    if (url.indexOf("http") !== -1) {
        image.onload = function() {
            var canvas = document.createElement('canvas');
            canvas.width = this.naturalWidth; // or 'width' if you want a special/scaled size
            canvas.height = this.naturalHeight; // or 'height' if you want a special/scaled size

            canvas.getContext('2d').drawImage(this, 0, 0);
            //callback(canvas.toDataURL('image/png'));
            callback(image);
        };

        image.src = url;
    } else {
        callback(false);
    }
}

function png2svg(bgImage, maskUrl, color, group) {

    //var options = { numberofcolors : 2, strokewidth : 0 , viewbox : true};
    var options = {
        corsenabled: false,
        ltres: 1,
        qtres: 1,
        pathomit: 1,
        rightangleenhance: true,

        // Color quantization
        colorsampling: 2,
        numberofcolors: 2,
        mincolorratio: 0,
        colorquantcycles: 3,

        // Layering method
        layering: 0,

        // SVG rendering
        strokewidth: 0,
        linefilter: true,
        scale: 1,
        roundcoords: 1,
        viewbox: true,
        desc: false,
        lcpr: 0,
        qcpr: 0,

        // Blur
        blurradius: 0,
        blurdelta: 10
    };

    if (bgImage) {
        width = bgImage.naturalWidth;
        height = bgImage.naturalHeight;
        textureEffect = bgImage.src;

        background = '<defs><pattern xmlns="http://www.w3.org/2000/svg" id="texture_' + group + '" patternUnits="userSpaceOnUse" height="' + height + '" width="' + width + '" overflow="visible">';
        background += '<image xlink:href="' + textureEffect + '" height="' + height + '" width="' + width + '" x="0" y="0" />';
        background += '</pattern></defs>';
        ImageTracer.imageToSVG(
            maskUrl,
            function(svgstr) {
                $('#visual_' + group).prepend(svgstr);
                $('#visual_' + group).find('path').attr('fill', 'url(#texture_' + group + ')')
                svgNode = $('#visual_' + group).find('svg')[0];
                newSvgStr = svgNode.outerHTML;
                fullSvgStr = newSvgStr.replace('xml:space="preserve">', 'xml:space="preserve">' + background);
                imgNode = '<object class="replaced-svg composition_element" data="' + 'data:image/svg+xml;base64,' + window.btoa(fullSvgStr) + '" type="image/svg+xml">' +
                    '<img src="maskUrl"/>' +
                    '</object>';
                $('#visual_' + group).prepend(imgNode);
                $('#visual_' + group).find('svg').remove();
            },
            options
        );
    } else {
        ImageTracer.imageToSVG(
            maskUrl,
            function(svgstr) {
                $('#visual_' + group).find('svg').remove();
                $('#visual_' + group).prepend(svgstr);
                $('#visual_' + group).find('path').attr('fill', color);
                $('#visual_' + group).find('svg').addClassSVG('replaced-svg composition_element').attr('id', 'traced_svg_' + group);
            },
            options
        );

    }
    //$('.orientation_selection[data-group-target='+group+']').find('.active_orientation').trigger('click');

}


$(document).on('keyup', '.dimension_text_width', function() {

    me = $(this);
    rootBlock = $(".form-group[data-field='" + me.attr('data-group') + "']");
    max = parseInt(me.attr('max'));
    min = parseInt(me.attr('min'));
    if (max > 0) {
        if (me.val() > max)
            rootBlock.find('.quantity_error_width_up').addClass('required_field').fadeIn();
        else
            rootBlock.find('.quantity_error_width_up').removeClass('required_field').fadeOut();
    }
    if (min > 0) {
        if (me.val() < min)
            rootBlock.find('.quantity_error_width_down').addClass('required_field').fadeIn();
        else
            rootBlock.find('.quantity_error_width_down').removeClass('required_field').fadeOut();
    }
})

$(document).on('keyup', '.dimension_text_height', function() {
    me = $(this);
    rootBlock = $(".form-group[data-field='" + me.attr('data-group') + "']");
    max = parseInt(me.attr('max'));
    min = parseInt(me.attr('min'));
    if (max > 0) {
        if (me.val() > max)
            rootBlock.find('.quantity_error_height_up').addClass('required_field').fadeIn();
        else
            rootBlock.find('.quantity_error_height_up').removeClass('required_field').fadeOut();
    }
    if (min > 0) {
        if (me.val() < min)
            rootBlock.find('.quantity_error_height_down').addClass('required_field').fadeIn();
        else
            rootBlock.find('.quantity_error_height_down').removeClass('required_field').fadeOut();
    }
})


/*curveText(284, 200);
function curveText(group, radius)
{
  svg = $('#visual_'+group).find('svg:eq(0)');
  width = svg[0].getBoundingClientRect().width;
  height = svg[0].getBoundingClientRect().height;
  
  
  console.log(width, height, radius);
  $('#curvedTextPath_'+group).remove();
  svg.find('defs').append('<path id="curvedTextPath_'+group+'" d="'+getPathData(width, height, radius)+'"></path>')
  svg.append('<circle cx="'+width/2+'" cy="'+height/2+'" r="'+radius+'" id="mainCircle_'+group+'"></circle>')
  svg.find('tspan').wrapAll('<textPath startOffset="50%" xlink:href="#curvedTextPath_'+group+'"></textPath>');
    
  


}

function getPathData(width, height, radius) {
  // adjust the radius a little so our text's baseline isn't sitting directly on the circle
  var r = radius * 0.95;
  var startX = width/2 - r;
  return 'm' + startX + ',' + (height/2) + ' ' +'a' + r + ',' + r + ' 0 0 0 ' + (2*r) + ',0';
}*/




/*$(document).on('keyup', '.ndkcf_totalprod_quantity', function(){
    group = $(this).attr('data-group');
    rootBlock = $(".form-group[data-field='"+group+"']");
    if($(this).val() >= $(this).attr('data-qtty-min'))
    {
        rootBlock.find('.quantity_error_down').removeClass('required_field').fadeOut();
    }
    else
    {
        rootBlock.find('.quantity_error_down').addClass('required_field');
    }
	
    if($('.ndk_itself').length > 0)
        var addProductPrice = 0;
})*/


$('#ndkacf-modal').on('shown.bs.modal', function() {
    $('.view_tab:eq(0)').trigger('click')
});

$(document).on('click', '#imgs-bloc-popup', function() {
    $('#custom-block-popup').removeClass('opened');
    $('#custom-block-popup').removeClass('slideInLeft');
});

$(document).on('click', '#custom-block-popup', function(e) {
    if (!$(this).hasClass('opened') &&
        !$(e.target).hasClass('ndkackFieldItem') ||
        $(e.target).attr('id') == 'ndkcf_mobile_options_toggler' ||
        $(e.target).parent().attr('id') == 'ndkcf_mobile_options_toggler'
    ) {
        $('#custom-block-popup').toggleClass('opened');
        $('#custom-block-popup').toggleClass('slideInLeft');
    }
})