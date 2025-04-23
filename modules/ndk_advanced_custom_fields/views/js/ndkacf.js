/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2020 Hendrik Masson
 *  @license   Tous droits réservés
 */
var groupAdded = [];
var groupUnitPrice = [];
var customizationId = 0;
var mesures = [];
var defaultMesures = [];
var preloadImg = [];
var filtersTags = [];
var maxQttyAvailable = [];
var htmlOutput = [];
var customizationPrice = 0;
var initialValues = [];
//var scrollbarWidth=(window.innerWidth-$(window).width());
var scrollbarWidth = 0;
var checked = false;
var allFonts = [];
var typingTimer; //timer identifier
var doneTypingInterval = 1000; //time in ms, 5 second for example
var alreadyModify = false;
var compoImages = [];
var selectedIdValue = [];
var ndkBrowserVersion = ndkDetectIE();
var mydiscount;
var ndkLoader =
    '<div class="ndk-loader" id="ndkloader"><ul role="progressbar"><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li></ul></div></div>';
var editAttr = null;
var thumbnailContainer = $('ul.js-qv-product-images');
const initialNbOfThumbnails = $(thumbnailContainer).find('li').length;
var customThumbsMapping = generateThumbnailsMapping(initialNbOfThumbnails);

if (showImgPreview == 0) {
    window.html2canvas = function (nodeList, options) {
        $(".ndkzoom").attr("disabled", false).removeClass("loadingButton");
        return true;
    };
}

function ndkDetectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
    }

    var trident = ua.indexOf("Trident/");
    if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf("rv:");
        return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
    }

    var edge = ua.indexOf("Edge/");
    if (edge > 0) {
        // Edge (IE 12+) => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
    }

    // other browser
    return false;
}

conf_img_url = $("#bigpic").attr("data-original-image");

if (typeof contentOnly == "undefined") contentOnly = false;

$.jMaskGlobals = {
    maskElements: "input,td,span,div",
    dataMaskAttr: "*[data-mask]",
    dataMask: true,
    watchInterval: 300,
    watchInputs: true,
    watchDataMask: false,
    byPassKeys: [9, 16, 17, 18, 36, 37, 38, 39, 40, 91],
    translation: {
        0: { pattern: /\d/ },
        9: { pattern: /\d/, optional: true },
        "#": { pattern: /\d/, recursive: true },
        A: { pattern: /[a-zA-Z0-9]/ },
        M: { pattern: /[A-Z0-9]/, recursive: true, optional: true },
        S: { pattern: /[a-zA-Z]/ },
        s: { pattern: /[a-z]/, optional: true },
        a: { pattern: /[a-zà-ÿ'-]/, recursive: true },
    },
};

function formatCurrencyNdk(price) {
    if (typeof formatCurrencyNdk_Override == "function") {
        return formatCurrencyNdk_Override(price);
    }
    if (ps_version > 1.6) {
        /*if(parseFloat(price) in formatedPrices){
                return formatedPrices[parseFloat(price)];
            }
            else{
                var response = '';
                $.ajax({
                            type: "GET",
                            async: false,  
                            url: baseUrl+'modules/ndk_advanced_custom_fields/front_ajax.php?action=formatPrice',
                            data: {price : parseFloat(price)},
                            success: function(data) {
                                response =  data;
                                formatedPrices[parseFloat(price)] = data;
                            }
                 });
                 return response;
            }*/

        return formatCurrency17(
            parseFloat(price),
            currencyFormat17,
            currencySign,
            1
        );
    } else {
        return formatCurrency(
            parseFloat(price),
            currencyFormat,
            currencySign,
            currencyBlank
        );
    }
}

function formatCurrencyNdkCallback(price, element, prefix, suffix) {
    if (typeof formatCurrencyNdkCallback_Override == "function") {
        return formatCurrencyNdkCallback_Override(price, element, prefix, suffix);
    }
    prefix = prefix || "";
    suffix = suffix || "";
    if (ps_version > 1.6) {
        /*mask = currencyFormat17.split('0');
            myMask = mask[0].replace(/#/g, '0')+currencySign;
            console.log(myMask);
            $(element).html(prefix+price+suffix).mask("0,00 €");*/
        /*if(price in formatedPrices){
                $(element).html(prefix+formatedPrices[price]+suffix);
                console.log(formatedPrices);
                return true;
            }
            else{
                $.ajax({
                            type: "GET",
                            url: baseUrl+'modules/ndk_advanced_custom_fields/front_ajax.php?action=formatPrice',
                            data: {price : parseFloat(price)},
                            success: function(data) {
                                $(element).html(prefix+data+suffix);
                                formatedPrices[price] = data;
                            }
                 });
            }*/

        $(element).html(
            prefix +
            formatCurrency17(parseFloat(price), currencyFormat17, currencySign, 1) +
            suffix
        );
    } else {
        $(element).html(
            prefix +
            formatCurrency(
                parseFloat(price),
                currencyFormat,
                currencySign,
                currencyBlank
            ) +
            suffix
        );
    }
}

function getIdCombinationNdk(adding_to_cart) {
    if (typeof getIdCombinationNdk_Override == "function") {
        return getIdCombinationNdk_Override(adding_to_cart);
    }

    adding_to_cart = adding_to_cart || false;
    setTimeout(function () {
        var $form = $("#add-to-cart-or-refresh");
        //myDatas = $form.serialize();
        myDatas = $(".product-variants")
            .find(
                "input:not(.false_attribute), select:not(.false_attribute), textarea:not(.false_attribute)"
            )
            .serialize();
        //console.log(myDatas)
        $.ajax({
            type: "GET",
            url:
                baseUrl +
                "modules/ndk_advanced_custom_fields/front_ajax.php?action=getCombination&id_product=" +
                $("#ndkcf_id_product").val(),
            data: myDatas,
            dataType: "json",
            success: function (data) {
                //var data = $.parseJSON(response);

                if (data != null) {
                    $("#ndkcf_id_combination, #idCombination").val(
                        data.id_product_attribute
                    );
                    if ($(".view_tab.activeView").length == 0) {
                        $("#bigpic")
                            .attr("src", data.images[0])
                            .attr("data-original-image", data.images[0]);
                        $("#bigpic").css("background", "url(" + data.images[0] + ") 100%");
                    }
                    $("#image-block .colorize-cover-item img").each(function () {
                        mask = $(this).css("mask-image");
                        if (mask.indexOf("scenes") == -1) {
                            $(this).attr("src", data.images[0]);
                            $(this)
                                .css("mask-image", "url('" + data.images[0] + "')")
                                .css("-webkit-mask-image", "url('" + data.images[0] + "')");
                        }
                    });

                    if (data.images.length > 1) {
                        thumbs = "";
                        for (var i = 0; i < data.images.length; i++) {
                            thumbs +=
                                '<li class="thumb-container"><img class="thumb js-thumb" data-image-medium-src="' +
                                data.images[i] +
                                '" data-image-large-src="' +
                                data.images[i] +
                                '" src="' +
                                data.images[i] +
                                '" itemprop="image"></li>';
                        }
                        $("ul.product-images").html(thumbs);
                        if (typeof coverImage == "function") {
                            coverImage();
                            // @TODO Intégrer une fonctionnalité de scroll qui ne modifie pas l'ordre des élements
                            //imageScrollBox();
                        }
                    } else {
                        //$('ul.product-images').html('');
                    }

                    if (ps_version > 1.6 && !adding_to_cart) {
                        var productDetails = $("#product-details").data("product");
                        if (typeof productDetails != "undefined") {
                            ndkcfAttrStock = data.stock;
                            if (
                                parseFloat(data.stock) < 1 &&
                                productDetails.allow_oosp == 0 &&
                                (editConfig == 0 || editAttr != data.id_product_attribute)
                            ) {
                                $(".ndkcsfields-block").fadeOut(600);
                                $("#submitNdkcsfields, .falseButton").attr(
                                    "disabled",
                                    "disabled"
                                );
                                $("#product-availability").fadeIn();
                            } else {
                                $(".ndkcsfields-block").fadeIn(600);
                                $("#submitNdkcsfields, .falseButton")
                                    .removeAttr("disabled")
                                    .removeClass("loadingButton");
                                $("#product-availability").fadeOut();
                            }
                        }
                    }
                    formatCurrencyNdkCallback(data.price, ".current-price span");
                } else {
                    $("#ndkcf_id_combination").val(0);
                }
            },
        });
    }, 200);
    if (!adding_to_cart) {
        setTimeout(function () {
            $("#quantity_wanted").trigger("change");
        }, 400);
    }

    setTimeout(function () {
        $("#main").removeClass("-combinations-loading");
    }, 2000);
}

function imageScrollBox() {
    if ($("#main .js-qv-product-images li").length > 2) {
        $("#main .js-qv-mask").addClass("scroll");
        $(".scroll-box-arrows").addClass("scroll");
        $("#main .js-qv-mask").scrollbox({
            direction: "h",
            distance: 113,
            autoPlay: false,
        });
        $(".scroll-box-arrows .left").click(function () {
            $("#main .js-qv-mask").trigger("backward");
        });
        $(".scroll-box-arrows .right").click(function () {
            $("#main .js-qv-mask").trigger("forward");
        });
    } else {
        $("#main .js-qv-mask").removeClass("scroll");
        $(".scroll-box-arrows").removeClass("scroll");
    }
}

function coverImage() {
    /*if (!!$.prototype.bxSlider){
          $('.product-cover .layer').addClass('hideImportant');
          $(".js-qv-product-images").unwrap().unwrap().parent().find('.bx-controls').remove();
          if (!!$.prototype.bxSlider)
          var mySlider = $(".js-qv-product-images").bxSlider({responsive:true,useCSS:false,pager:false,slideWidth:150,slideMargin:4});
          //mySlider.destroySlider();
          //mySlider.reloadSlider();
      }*/
}

$(document).on(
    "click",
    ".ndkcfLoaded.ndkCfIsVisual .js-thumb",
    function (event) {
        event.preventDefault();
        $(".js-thumb.selected").removeClass("selected");
        current_img = $("#bigpic").attr("data-original-image");

        if ($(".view_tab.activeView").length > 0) {
            img = $(event.currentTarget).data("image-large-src");
            view_img = $(".view_tab.activeView").attr("data-img");
        } else {
            view_img = img = $(event.currentTarget).data("image-large-src");
        }

        $(event.target).addClass("selected");

        if (!is_visual) {
            $(".js-qv-product-cover").attr("src", view_img);
        } else {
            if ($(".view_tab.activeView").length > 0)
                $(".js-qv-product-cover").attr("src", view_img);
            else $(".js-qv-product-cover").attr("src", current_img);
        }

        $(".js-modal-product-cover, .js-qv-product-cover").attr("src", img);
        $("[data-target='#product-modal']").trigger("click");
        //console.log(img);
    }
);

$(document).on(
    "change, click",
    ".ndkcfLoaded .product-variants input:not(.false_attribute)",
    function () {
        $.when(getIdCombinationNdk()).done(function () {
            setTimeout(function () {
                initPriceVars();
            }, 800);
        });
    }
);

$(document).on(
    "change",
    ".ndkcfLoaded .product-variants select:not(.false_attribute)",
    function () {
        $.when(getIdCombinationNdk()).done(function () {
            setTimeout(function () {
                initPriceVars();
            }, 800);
        });
    }
);

var default_colors = [
    "AliceBlue",
    "AntiqueWhite",
    "Aqua",
    "Aquamarine",
    "Azure",
    "Beige",
    "Bisque",
    "Black",
    "BlanchedAlmond",
    "Blue",
    "BlueViolet",
    "Brown",
    "BurlyWood",
    "CadetBlue",
    "Chartreuse",
    "Chocolate",
    "Coral",
    "CornflowerBlue",
    "Cornsilk",
    "Crimson",
    "Cyan",
    "DarkBlue",
    "DarkCyan",
    "DarkGoldenRod",
    "DarkGray",
    "DarkGrey",
    "DarkGreen",
    "DarkKhaki",
    "DarkMagenta",
    "DarkOliveGreen",
    "Darkorange",
    "DarkOrchid",
    "DarkRed",
    "DarkSalmon",
    "DarkSeaGreen",
    "DarkSlateBlue",
    "DarkSlateGray",
    "DarkSlateGrey",
    "DarkTurquoise",
    "DarkViolet",
    "DeepPink",
    "DeepSkyBlue",
    "DimGray",
    "DimGrey",
    "DodgerBlue",
    "FireBrick",
    "FloralWhite",
    "ForestGreen",
    "Fuchsia",
    "Gainsboro",
    "GhostWhite",
    "Gold",
    "GoldenRod",
    "Gray",
    "Grey",
    "Green",
    "GreenYellow",
    "HoneyDew",
    "HotPink",
    "IndianRed",
    "Indigo",
    "Ivory",
    "Khaki",
    "Lavender",
    "LavenderBlush",
    "LawnGreen",
    "LemonChiffon",
    "LightBlue",
    "LightCoral",
    "LightCyan",
    "LightGoldenRodYellow",
    "LightGray",
    "LightGrey",
    "LightGreen",
    "LightPink",
    "LightSalmon",
    "LightSeaGreen",
    "LightSkyBlue",
    "LightSlateGray",
    "LightSlateGrey",
    "LightSteelBlue",
    "LightYellow",
    "Lime",
    "LimeGreen",
    "Linen",
    "Magenta",
    "Maroon",
    "MediumAquaMarine",
    "MediumBlue",
    "MediumOrchid",
    "MediumPurple",
    "MediumSeaGreen",
    "MediumSlateBlue",
    "MediumSpringGreen",
    "MediumTurquoise",
    "MediumVioletRed",
    "MidnightBlue",
    "MintCream",
    "MistyRose",
    "Moccasin",
    "NavajoWhite",
    "Navy",
    "OldLace",
    "Olive",
    "OliveDrab",
    "Orange",
    "OrangeRed",
    "Orchid",
    "PaleGoldenRod",
    "PaleGreen",
    "PaleTurquoise",
    "PaleVioletRed",
    "PapayaWhip",
    "PeachPuff",
    "Peru",
    "Pink",
    "Plum",
    "PowderBlue",
    "Purple",
    "Red",
    "RosyBrown",
    "RoyalBlue",
    "SaddleBrown",
    "Salmon",
    "SandyBrown",
    "SeaGreen",
    "SeaShell",
    "Sienna",
    "Silver",
    "SkyBlue",
    "SlateBlue",
    "SlateGray",
    "SlateGrey",
    "Snow",
    "SpringGreen",
    "SteelBlue",
    "Tan",
    "Teal",
    "Thistle",
    "Tomato",
    "Turquoise",
    "Violet",
    "Wheat",
    "White",
    "WhiteSmoke",
    "Yellow",
    "YellowGreen",
];

var default_fonts = [
    "Arial,Arial,Helvetica,sans-serif",
    "Arial Black,Arial Black,Gadget,sans-serif",
    "Comic Sans MS,Comic Sans MS,cursive",
    "Courier New,Courier New,Courier,monospace",
    "Georgia,Georgia,serif",
    "Impact,Charcoal,sans-serif",
    "Times New Roman,Times,serif",
    "Indie Flower",
    "Lobster",
    "Chewy",
    "Alpha Slab One",
    "Rock Salt",
    "Comfortaa",
    "Audiowide",
    "Yellowtail",
    "Black Ops One",
    "Frijole",
    "Press Star 2p",
    "Kranky",
    "Meddon",
    "Love Ya Like A Sister",
    "Bree Serif, serif",
];

if (typeof fonts == "undefined" || fonts.length == 0) var fonts = default_fonts;

if (typeof colors == "undefined" || colors.length == 0)
    var colors = default_colors;

function getCustomizationPrice(price, group, value) {
    discount = 0;
    groupUnitPrice[group] = parseFloat(price);
    linked_multiplicator = checkQuantityLink(group);
    value = value || false;
    // if (group.indexOf("-") > -1) {
    //   group = group.split("-");
    //   if (group.length > 1) {
    //     var group = group[0] + "-" + group[1];
    //   } else {
    //     var group = group[0];
    //   }
    // }
    if (linked_multiplicator == 0) linked_multiplicator = 1;

    qtty_wanted = $("#quantity_wanted").val();

    if ((linked_multiplicator > 1 || qtty_wanted > 1) && price > 0) {
        if (typeof ndkSpecificPrices[group] != "undefined")
            for (i = 0; i < ndkSpecificPrices[group].length; i++) {
                row = ndkSpecificPrices[group][i];
                selectedConfigValue = getSelectedValuesForPrice();
                if (
                    row.value == selectedConfigValue[group] &&
                    linked_multiplicator * qtty_wanted >= row.from_quantity
                ) {
                    if (row.reduction_type == "amount") {
                        mydiscount = parseFloat(row.reduction);
                    } else if (row.reduction_type == "percent") {
                        mydiscount = price * (row.reduction / 100);
                    }
                    if (mydiscount > discount) discount = mydiscount;
                }
            }
    }

    price = parseFloat(price) - parseFloat(discount);
    price = price * linked_multiplicator;
    groupAdded[group] = parseFloat(price);

    var idPrice;
    for (idPrice in groupAdded) {
        if (typeof groupAdded[idPrice] != "undefined") {
            price_type = $("#price_type_" + idPrice).attr("data-price-type");
            //console.log(price_type);
            if (price_type != "percent" && parseFloat(groupAdded[idPrice]) > 0) {
                if (price_type == "one_time")
                    customizationPrice =
                        parseFloat(customizationPrice) +
                        parseFloat(groupAdded[idPrice]) / $("#quantity_wanted").val();
                else
                    customizationPrice =
                        parseFloat(customizationPrice) + parseFloat(groupAdded[idPrice]);
            }
        }
    }

    for (idPrice in groupAdded) {
        if (typeof groupAdded[idPrice] != "undefined") {
            price_type = $("#price_type_" + idPrice).attr("data-price-type");
            //console.log(productPrice);
            if (typeof price_type != "undefined" && price_type == "percent") {
                multiplicator = groupAdded[idPrice] / 100;
                totalPrice =
                    parseFloat(productPrice * 1) + parseFloat(customizationPrice * 1);
                toAdd = totalPrice * multiplicator;
                if (parseFloat(toAdd) > 0)
                    customizationPrice = parseFloat(customizationPrice) + toAdd;
            }
        }
    }
    return customizationPrice;
}

function updatePriceNdk(price, group, value) {
    if (typeof updatePriceNdk_Override == "function") {
        return updatePriceNdk_Override(price, group, value);
    }
    value = value || 0;
    discount = 0;

    if (document.readyState == "complete") {
        $("#price_" + group)
            .val(price)
            .trigger("keyup");
        //var productPrice = $.trim($('#our_price_display').text().replace(currencySign, '').replace(',', '.').replace(/\ /g, '').replace('-', ''));
        customizationPrice = 0;
        if (isNaN(price)) price = 0;

        if (
            !isNaN(price) &&
            typeof price != "undefined" &&
            typeof group != "undefined" &&
            group != "undefined"
        ) {
            customizationPrice = getCustomizationPrice(price, group);

            new_price =
                parseFloat(productPrice * 1) + parseFloat(customizationPrice * 1);
            $(".additionnal_price").remove();
            if (customizationPrice > 0)
                $(".product-prices:eq(0)").after(
                    '<span id="additionnal_price" data-price="' +
                    customizationPrice +
                    '" class="price additionnal_price"></span>'
                );
            formatCurrencyNdkCallback(
                customizationPrice,
                "#additionnal_price",
                "(" + additionnalText + "+",
                ")"
            );
            $("#quantity_wanted").trigger("keyup");

            if (templateType == 1) {
                $("#timeline").trigger("mouseover");
                setTimeout(function () {
                    $("#timeline").trigger("mouseout");
                }, 5000);
            }
        }
        update_price_dynamic(0);
        checkQuantityLinked(group);
    }

    if (value > 0) checkFieldRestrictions(value, group);
}

function updatePriceNdkGeneric(price, group) {
    if (typeof updatePriceNdkGeneric_Override == "function") {
        return updatePriceNdkGeneric_Override(price, group);
    }

    customizationPrice = 0;
    var productPrice = $.trim(
        $("#our_price_display")
            .text()
            .replace(currencySign, "")
            .replace(",", ".")
            .replace(/\ /g, "")
            .replace("-", "")
    );
    var idPrice;
    for (idPrice in groupUnitPrice) {
        customizationPrice = getCustomizationPrice(
            groupUnitPrice[idPrice],
            idPrice
        );
    }
    new_price = parseFloat(productPrice * 1) + parseFloat(customizationPrice * 1);
    $(".additionnal_price").remove();
    if (customizationPrice > 0)
        $(".product-prices:eq(0)").after(
            '<span id="additionnal_price" data-price="' +
            customizationPrice +
            '" class="price additionnal_price"></span>'
        );
    formatCurrencyNdkCallback(
        customizationPrice,
        "#additionnal_price",
        "(" + additionnalText + "+",
        ")"
    );

    if (templateType == 1) {
        $("#timeline").trigger("mouseover");
        setTimeout(function () {
            $("#timeline").trigger("mouseout");
        }, 5000);
    }
}

function svg_textMultiline(element, text, width, fontSize, txtanchord, x) {
    var y = 1;
    var x = x;
    var new_line = 1;
    var last_line = 1;
    element = document.getElementById(element);

    /* split the words into array */
    var words = text.split(" ");
    //var words = text.match(/.{32}/g);
    var line = "";

    /* Make a tspan for testing */
    element.innerHTML =
        '<tspan style="font-size=' +
        fontSize +
        'px;" ' +
        txtanchord +
        ' id="PROCESSING">busy</tspan >';

    for (var n = 0; n < words.length; n++) {
        var testLine = line + words[n] + " ";
        //console.log(testLine)
        var testElem = document.getElementById("PROCESSING");
        /*  Add line in testElement */
        testElem.innerHTML = testLine;
        /* Messure textElement */
        var metrics = testElem.getBoundingClientRect();
        if (new_line > last_line) var testWidth = 0;
        else var testWidth = metrics.width;
        testWidth += 20;

        //console.log(width)
        //console.log(testWidth)

        last_line = y;
        if (words[n] == "±") {
            element.innerHTML +=
                '<tspan style="font-size=' +
                fontSize +
                'px;" ' +
                txtanchord +
                ' x="' +
                x +
                '" y="' +
                fontSize * y +
                '">' +
                line +
                "</tspan>";
            line = " ";
            y++;
        } else if (testWidth > width && n > 0) {
            element.innerHTML +=
                '<tspan style="font-size=' +
                fontSize +
                'px;" ' +
                txtanchord +
                ' x="' +
                x +
                '" y="' +
                fontSize * y +
                '">' +
                line +
                "</tspan>";
            line = words[n] + " ";
            y++;
        } else {
            line = testLine;
        }

        new_line = y;
    }

    element.innerHTML +=
        '<tspan style="font-size=' +
        fontSize +
        'px;" ' +
        txtanchord +
        ' x="' +
        x +
        '" y="' +
        fontSize * y +
        '">' +
        line +
        "</tspan>";
    document.getElementById("PROCESSING").remove();
}

function updateQuantityForValue(quantity, group) {
    if (typeof updateQuantityForValue_Override == "function") {
        return updateQuantityForValue_Override(quantity, group);
    }

    var maxQtty = 999999999999999;
    var customizationPrice = 0;
    if (
        typeof quantity != "undefined" &&
        typeof group != "undefined" &&
        group != "undefined"
    ) {
        //current_price = parseFloat($('#our_price_display').text().replace(currencySign, ''));
        if (quantity == "null") quantity = 999999999999999;

        maxQttyAvailable[group] = parseFloat(quantity);
    }

    var idQtty;
    for (idQtty in groupAdded) {
        if (
            typeof maxQttyAvailable[idQtty] != "undefined" &&
            parseFloat(maxQttyAvailable[idQtty]) <= parseFloat(maxQtty)
        )
            maxQtty = parseFloat(maxQttyAvailable[idQtty]);
    }

    $("#quantity_wanted").attr("max", quantity).trigger("keyup");
}

function resizeZones_old(el) {
    if (typeof resizeZones_Override == "function") {
        return resizeZones_Override(el);
    }
    lbkWidth = el.attr("original-width");
    lbkHeight = el.attr("original-height");

    newWidth = el.attr("data-zone-width");
    newHeight = el.attr("data-zone-height");
    el.width((newWidth / lbkWidth) * 100 + "%");
    el.height((newHeight / lbkHeight) * 100 + "%");

    newMarginL = el.attr("data-zone-left");
    newMarginT = el.attr("data-zone-top");

    el.css("left", (newMarginL / lbkWidth) * 100 + "%");
    el.css("top", (newMarginT / lbkHeight) * 100 + "%");
}

function resizeZones(el) {
    if (typeof resizeZones_Override == "function") {
        return resizeZones_Override(el);
    }
    zone_mode = el.attr("zone-mode");
    if (zone_mode != "percent") {
        return resizeZones_old(el);
    }

    newWidth = el.attr("data-zone-width");
    newHeight = el.attr("data-zone-height");
    el.width(newWidth + "%");
    el.height(newHeight + "%");
    newMarginL = el.attr("data-zone-left");
    newMarginT = el.attr("data-zone-top");

    el.css("left", newMarginL + "%");
    el.css("top", newMarginT + "%");
}

function redesignPage() {
    if (typeof redesignPage_Override == "function") {
        return redesignPage_Override();
    }

    if (ps_version > 1.6) {
        if (typeof contentOnly == "undefined") contentOnly = false;
        $("#content").parent().addClass("pb-left-column");
        $(".product-price:eq(0)").parent().addClass("pb-center-column");
    }

    //setTags();
    $(
        "#add_to_cart, .product-add-to-cart > *:not(.product-quantity), .add button, .add-to-cart:not(.falseButton), .product-customization"
    ).hide();
    //$("[data-target='#product-modal']").remove();
    $("h1").prependTo(".pb-left-column");
    $("#short_description_block").appendTo(".pb-left-column");
    $(".pb-left-column").removeClass("col-md-5").addClass("col-md-7");
    $(".pb-center-column").removeClass("col-sm-4").addClass("col-sm-5");
    $(".pb-right-column")
        .removeClass("col-sm-4")
        .removeClass("col-md-3")
        .removeClass("col-xs-12")
        .appendTo(".pb-left-column")
        .addClass("clearfix")
        .wrap('<div id="timeline" class="floatingBarre"></div>');

    if (typeof isFieldsPack != "undefined" && isFieldsPack == 1) {
        $(".replace-img-block").each(function () {
            viewsTabs = $(this).clone();
            if (viewsTabs.find(".view_tab").length < 2) viewsTabs.hide();
            //$(this).remove();
            $(this).parent().prepend(viewsTabs);
            $(this).remove();
        });
    } else {
        viewsTabs = $(".replace-img-block").clone().addClass("clonedTabs");
        $(".replace-img-block").remove();
        if (viewsTabs.find(".view_tab").length < 2) viewsTabs.hide();
        $("#image-block").before(viewsTabs);
    }

    $("#image-block").attr("data-view", 0);
    setTimeout(function () {
        $(".view_tab:first").trigger("click");
    }, 500);

    if ($(window).width() > 767) {
        $(".pb-right-column").addClass("clearfix");
        $(".box-info-product > div")
            .removeClass("clearfix")
            .addClass("col-md-4 col-sm-3 col-xs-6");
    }

    if ($(".ndkmask").length > 0) {
        var masks = $(".ndkmask");
        masks.each(function () {
            if ($(this).attr("data-zindex") > 0) zindex = $(this).attr("data-zindex");
            else zindex = "99";
            $("#image-block").append(
                '<img style="z-index:' +
                zindex +
                ';" src="' +
                $(this).attr("data-src") +
                '" id="view-' +
                $(this).attr("data-view") +
                '" class="absolute-visu absolute-mask view-' +
                $(this).attr("data-view") +
                '"/>'
            );
            $("#image-block")
                .find(
                    ":not(img, #view_full_size, #product-zoom, .fontSelect, canvas, .ui-rotatable-handle, .zone_limit)"
                )
                .hide();
        });
    }

    if ($(".zone_limit").length > 0) {
        var zones = $(".zone_limit");
        zones.each(function () {
            resizeZones($(this));
            cloned = $(this).clone();
            $("#image-block").append(cloned);
            $(this).remove();
        });
    }

    $(".colorize_svg").each(function () {
        myGroup = $(this).attr("data-group");
        myUl = $(this);
        if (window["fieldColors_" + myGroup].length > 0)
            myColors = window["fieldColors_" + myGroup];
        else myColors = colors;

        for (var i = 0; i < myColors.length; i++) {
            var item = $(
                "<li " +
                (i == 0 ? 'class="initial_color"' : "") +
                '><span data-color="' +
                myColors[i] +
                '" style="background:' +
                myColors[i] +
                ';">' +
                myColors[i] +
                "</span></li>"
            ).appendTo(myUl);
        }
    });

    setTimeout(function () {
        $(".initial_color").trigger("click");
    }, 500);

    $(".ndk_selector").each(function () {
        $(this).setNdkSelector();
    });
    $("#submitNdkcsfields").attr("disabled", false);

    /*$.when(restoreDesign()).done(function(){
          setTimeout(function(){
              $('.clonedTabs .view_tab:first').trigger('click');
          }, 2000);
      });*/
    //restoreForm();
    $(".tagify").tagify({ delimiters: [13, 188, 44], addTagPrompt: tagslabel });
    //$('#thumbs_list_frame li a, #views_block a').removeClass('fancybox');
    $(document).on("mouseover", "#views_block li a", function (e) {
        e.preventDefault();
    });

    /*$(document).on('click', 'li:visible .fancybox, .fancybox.shown', function(e){
          e.preventDefault();
      });*/

    var resumeBlock = new HoverWatcher("#timeline");
    $("#timeline").prepend("<h3>" + timelineText + "</h3>");
    setTimeout(function () {
        $(".pb-right-column").hide("slow");
    }, 4000);

    $("#timeline").hover(
        function () {
            $(".pb-right-column").stop(true, true).show(450);
            $(this).removeClass("active");
        },
        function () {
            setTimeout(function () {
                if (!resumeBlock.isHoveringOver())
                    $(".pb-right-column").stop(true, true).hide(450);
                $(this).addClass("active");
            }, 200);
        }
    );
    /* Add 'non-contractual pictures' under preview */
    $("#image-block").after(
        '<div class="mb-1" style="font-size: 12px"><span>' + nonContractualPic + '</span></div>'
    )
    /* /Add 'non-contractual pictures' under preview */
    $("#image-block").after(
        '<div id="layer-block" class="clearfix clear"></div>'
    );

    if (typeof is_visual != "undefined" && is_visual == true) {
        $("#views_block .shown").removeClass("shown");

        $(document).on("mouseover", "#views_block li a", function (e) {
            e.preventDefault();
            $(".view_tab.activeView").trigger("click");
            $("#views_block .shown").removeClass("shown");
        });
    }
}

function HoverWatcher(selector) {
    if (typeof HoverWatcher_Override == "function") {
        return HoverWatcher_Override(selector);
    }
    this.hovering = false;
    var self = this;

    this.isHoveringOver = function () {
        return self.hovering;
    };

    $(selector).hover(
        function () {
            self.hovering = true;
        },
        function () {
            self.hovering = false;
        }
    );
}

function restoreDesign() {
    if (typeof restoreDesign_Override == "function") {
        return restoreDesign_Override();
    }
    savedDesign = localStorage.getItem(
        "customNdk_" + $("#ndkcsfields-block").attr("data-key")
    );
    //console.log(savedDesign);
    if (
        savedDesign != null &&
        typeof savedDesign != "undefined" &&
        savedDesign != ""
    ) {
        setTimeout(function () {
            $("#image-block").html(savedDesign);
            $(".ui-draggable").draggable().rotatable({ wheelRotate: false });
            $(".ui-resizable > img").resizable();
        }, 3000);
    }
}

function restoreForm() {
    if (typeof restoreForm_Override == "function") {
        return restoreForm_Override();
    }
    savedForm = localStorage.getItem(
        "customNdkForm_" + $("#ndkcsfields-block").attr("data-key")
    );
    if (savedForm != null && typeof savedForm != "undefined" && savedForm != "") {
        setTimeout(function () {
            $("#ndkcsfields-block").html(savedForm);
        }, 3000);
    }
}

function redesignPageLight() {
    if (typeof redesignPageLight_Override == "function") {
        return redesignPageLight_Override();
    }
    $(
        "#add_to_cart, product-add-to-cart > *:not(.product-quantity), .add button:not(.falseButton), .add-to-cart:not(.falseButton), .product-customization"
    ).hide();
    //$("[data-target='#product-modal']").remove();
    //viewsTabs = $('.replace-img-block').clone();
    if (typeof isFieldsPack != "undefined" && isFieldsPack == 1) {
        $(".replace-img-block").each(function () {
            viewsTabs = $(this).clone();
            //$(this).remove();
            if (viewsTabs.find(".view_tab").length < 2) viewsTabs.hide();
            $(this).parent().prepend(viewsTabs);
            $(this).remove();
        });
    } else {
        viewsTabs = $(".replace-img-block").clone().addClass("clonedTabs");
        if (viewsTabs.find(".view_tab").length < 2) viewsTabs.hide();

        $(".replace-img-block").remove();
        $("#image-block").before(viewsTabs);
    }

    $("#image-block").attr("data-view", 0);
    setTimeout(function () {
        $(".view_tab:first").trigger("click");
    }, 500);

    if ($(".ndkmask").length > 0) {
        var masks = $(".ndkmask");
        masks.each(function () {
            if ($(this).attr("data-zindex") > 0) zindex = $(this).attr("data-zindex");
            else zindex = "99";
            $("#image-block").append(
                '<img style="z-index:' +
                zindex +
                ';" src="' +
                $(this).attr("data-src") +
                '" id="view-' +
                $(this).attr("data-view") +
                '" class="absolute-visu absolute-mask view-' +
                $(this).attr("data-view") +
                '"/>'
            );
            $("#image-block")
                .find(
                    ":not(img, #view_full_size, #product-zoom, .fontSelect, canvas, .ui-rotatable-handle, .zone_limit)"
                )
                .hide();
            $("#image-block")
                .find(
                    ":not(img, #view_full_size, #product-zoom, .fontSelect, canvas, .ui-rotatable-handle, .zone_limit)"
                )
                .hide();
        });
    }

    if ($(".zone_limit").length > 0) {
        var zones = $(".zone_limit");
        zones.each(function () {
            resizeZones($(this));
            cloned = $(this).clone();
            $("#image-block").append(cloned);
            $(this).remove();
        });
    }
    //setTags();

    $(".colorize_svg").each(function () {
        myGroup = $(this).attr("data-group");
        myUl = $(this);
        if (window["fieldColors_" + myGroup].length > 0)
            myColors = window["fieldColors_" + myGroup];
        else myColors = colors;

        for (var i = 0; i < myColors.length; i++) {
            var item = $(
                "<li " +
                (i == 0 ? 'class="initial_color"' : "") +
                '><span data-color="' +
                myColors[i] +
                '" style="background:' +
                myColors[i] +
                ';">' +
                myColors[i] +
                "</span></li>"
            ).appendTo(myUl);
        }
    });

    setTimeout(function () {
        $(".initial_color").trigger("click");
    }, 500);

    $(".ndk_selector").each(function () {
        $(this).setNdkSelector();
    });

    $("#submitNdkcsfields").attr("disabled", false);
    $(".tagify").tagify({ delimiters: [13, 188, 44], addTagPrompt: tagslabel });
    //$('#thumbs_list_frame li a, #views_block a').removeClass('fancybox');

    if (typeof is_visual != "undefined" && is_visual == true) {
        $("#views_block .shown").removeClass("shown");

        $(document).on("mouseover", "#views_block li a", function (e) {
            e.preventDefault();
            $(".view_tab.activeView").trigger("click");
            $("#views_block .shown").removeClass("shown");
        });
    }
    /*$(document).on('click', 'li:visible .fancybox, .fancybox.shown', function(e){
                      e.preventDefault();
                      $('.view_tab.activeView').trigger('click');
                  });*/
    /* Add 'non-contractual pictures' under preview */
    $("#image-block").after(
        '<div class="mb-1" style="font-size: 12px"><span>' + nonContractualPic + '</span></div>'
    )
    /* /Add 'non-contractual pictures' under preview */
    $("#image-block").after(
        '<div id="layer-block" class="clearfix clear"></div>'
    );
}

function redesignPageLight_bak() {
    $("#add_to_cart, .product-add-to-cart, .add, .add-to-cart").hide();
    if ($(".ndkmask").length > 0) {
        $("#image-block").append(
            '<img src="' +
            $(".ndkmask").attr("data-src") +
            '" class="absolute-visu absolute-mask view-' +
            $(".ndkmask").attr("data-view") +
            '"/>'
        );
        $("#image-block")
            .find(
                ":not(img, #view_full_size,  #product-zoom, .fontSelect, canvas, .ui-rotatable-handle)"
            )
            .hide();
    }
    if ($(".zone_limit").length > 0) {
        var zones = $(".zone_limit");
        zones.each(function () {
            setTimeout(function () {
                resizeZones($(this));
                cloned = $(this).clone();
                $("#image-block").append(cloned);
                $(this).remove();
            }, 1000);
        });
    }
    viewsTabs = $(".replace-img-block").clone();
    $(".replace-img-block").remove();
    $("#image-block").before(viewsTabs);
    $("#image-block").attr("data-view", 0);
    setTimeout(function () {
        $(".clonedTabs .view_tab:first").trigger("click");
    }, 500);
}

function getMultiFieldDetails(group) {
    if (group.indexOf("-") > -1) {
        group = group.split("-");
        var group = group[0];
    }
    totalBlock = "";
    $("#main-" + group)
        .find(".designer-item-container")
        .each(function () {
            me = $(this);
            subTitle = me.find(".itemToggler .item-name").text();
            totalBlock += subTitle + "\n";
            me.find(".group-title").each(function () {
                totalBlock += $(this).text() + "\n";
            });
            totalBlock += "\n";
        });
    if (totalBlock != "")
        $("#ndkcsfield_" + group)
            .val(totalBlock)
            .trigger("keyup");
}

function setImgValue(img, id) {
    if (typeof setImgValue_Override == "function") {
        return setImgValue_Override(img, id);
    }
    $("#ndkcsfield_" + id)
        .val(img.attr("data-value"))
        .trigger("keyup");
    $(img)
        .parent()
        .parent()
        .find(".selected-value")
        .removeClass("selected-value");
    $(img)
        .parent()
        .parent()
        .parent()
        .find(".svg-container")
        .removeClass("selected-svg");
    $(".img-value-" + id).removeClass("selected-value");
    $(img).addClass("selected-value");
    $(img).parent().addClass("selected-value");
    if (img.parent().hasClass("svg-container"))
        img.parent().addClass("selected-svg");

    checkFieldRestrictions(img.attr("data-id-value"), img.attr("data-group"));
    setTimeout(function () {
        getMultiFieldDetails(img.attr("data-group"));
    }, 800);
    img.trigger("imgValueSet");
}

function setEditable(
    el,
    zindex,
    ctnmt,
    layerOptions,
    dragdrop,
    resizeable,
    rotateable
) {
    if (typeof setEditable_Override == "function") {
        return setEditable_Override(
            el,
            zindex,
            ctnmt,
            layerOptions,
            dragdrop,
            resizeable,
            rotateable
        );
    }
    //console.log($(el).parent().height());
    ctnmt = "";
    if (resizeable == 1) {
        group = $(el).attr("data-group").split("-");

        $(el).resizable({
            aspectRatio: true,
            containment: ctnmt,
            autoHide: true,
            handles: "se",
            start: function () {
                if (!$(this).hasClass("activeZone") && group)
                    $(".form-group[data-field='" + group[0] + "']").trigger("click");
                $("#image-block").addClass("editing");
            },
            stop: function () {
                setTimeout(function () {
                    $(".resetZones").trigger("click");
                    convertPercent(); /*dragToPercent($(this));*/
                }, 2250);
                $(".editing").removeClass("editing");
            },
            /*stop: function (){
                          setTimeout(function(){$('.resetZones').trigger('click')}, 2850);
                      },*/
        });
        $(el).removeClass("notResizeable");
    } else {
        $(el).resizable({
            aspectRatio: true,
            containment: ctnmt,
            autoHide: true,
            maxHeight: $(el).parent().height(),
            maxWidth: $(el).parent().width(),
            minHeight: $(el).parent().height(),
            minWidth: $(el).parent().width(),
            handles: "se",

            create: function (event, ui) {
                $(".ui-icon-gripsmall-diagonal-se").remove();
            },
            stop: function () {
                setTimeout(function () {
                    $(".resetZones").trigger("click");
                    convertPercent(); /*dragToPercent($(this));*/
                }, 2250);
            },
        });
        //$(el).css('min-width', $(el).parent().width()+'px').css('max-width', $(el).parent().width()+'px')
        $(el).addClass("notResizeable");
    }
    rotDragEl = $(el);

    $(el)
        .attr("data-dragdrop", dragdrop)
        .attr("data-rotateable", rotateable)
        .attr("data-resizeable", resizeable);
    $(el).css("z-index", zindex).attr("data-zindex", zindex);
    //dragToPercent(rotDragEl)

    group = rotDragEl.attr("data-group").split("-");

    if (dragdrop == 1)
        rotDragEl.draggable({
            containment: ctnmt,
            start: function () {
                if (!$(this).hasClass("activeZone") && group)
                    $(".form-group[data-field='" + group[0] + "']").trigger("click");
                $("#image-block").addClass("editing");
            },
            stop: function () {
                setTimeout(function () {
                    $(".resetZones").trigger("click");
                    convertPercent(); /*dragToPercent($(this));*/
                }, 2250);
                $(".editing").removeClass("editing");
                $(el).css("z-index", zindex);
            },

            zIndex: zindex /*, containment: 'parent'*/,
        });

    if (rotateable == 1)
        rotDragEl
            .rotatable({
                wheelRotate: false,
                start: function () {
                    if (!$(this).hasClass("activeZone") && group)
                        $(".form-group[data-field='" + group[0] + "']").trigger("click");
                    $("#image-block").addClass("editing");
                },
                stop: function () {
                    setTimeout(function () {
                        $(".resetZones").trigger("click"); /*dragToPercent($(this));*/
                    }, 2250);
                    $(".editing").removeClass("editing");
                    $(el).css("z-index", zindex);
                },
            })
            .css("z-index", zindex)
            .addClass("rotatable");

    if ($(el).parent().find(".layer_view").length < 1)
        $("#layer-block").append(layerOptions);

    checkLayerChanges();

    //$('#image-block').find(':not(img, #view_full_size, .fontSelect, canvas, .ui-rotatable-handle)').hide();
    $("#image-block").find(".fontSelect >*, canvas > *").show();
    $("#image-block").find(".ui-wrapper").show().css("position", "absolute");
}

function dragToPercent($elm) {
    if (typeof dragToPercent_Override == "function") {
        return dragToPercent_Override($elm);
    }
    var pos = $elm.position(),
        parentSizes = {
            height: $elm.parent().height(),
            width: $elm.parent().width(),
        };
    var elTop = parseFloat($elm.css("top").replace("px", ""));
    var elLeft = parseFloat($elm.css("left").replace("px", ""));

    //console.log(elLeft);
    $elm
        .css("top", (elTop / parentSizes.height) * 100 + "%")
        .css("left", (elLeft / parentSizes.width) * 100 + "%")
        .css("width", ($elm.width() / parentSizes.width) * 100 + "%")
        .css("height", ($elm.height() / parentSizes.height) * 100 + "%")
        .addClass("percented");
}

function dragToPercentBak(el) {
    if (typeof dragToPercentBak_Override == "function") {
        return dragToPercentBak_Override(el);
    }
    if (!el.hasClass("percented")) {
        var l =
            (100 * parseFloat(el.css("left"))) /
            parseFloat(el.parent().css("width")) +
            "%";
        var t =
            (100 * parseFloat(el.css("top"))) /
            parseFloat(el.parent().css("height")) +
            "%";
        var w =
            (100 * parseFloat(el.css("width"))) /
            parseFloat(el.parent().css("width")) +
            "%";
        el.css("left", l);
        el.css("top", t);
        el.css("width", w);
        el.css("height", "auto");
        el.addClass("percented");
    }
}

function designCompo(
    visu,
    group,
    view,
    zindex,
    dragdrop,
    resizeable,
    rotateable,
    width,
    height,
    type,
    coloreffect,
    background,
    target,
    specialClass,
    category
) {
    if (typeof designCompo_Override == "function") {
        return designCompo_Override(
            visu,
            group,
            view,
            zindex,
            dragdrop,
            resizeable,
            rotateable,
            width,
            height,
            type,
            coloreffect,
            background,
            target,
            specialClass,
            category
        );
    }
    target = target || false;
    coloreffect = coloreffect || "normal";
    background = background || "";
    specialClass = specialClass || "";
    category = category || "";
    specifiView = true;
    //console.log(dragdrop);
    //console.log(resizeable);
    //console.log(rotateable);
    isItem = false;
    number = 0;
    itemGroup = 0;
    viewClass = " ";

    if (group.indexOf("-") > -1) {
        isItem = true;
        number = group.split("-")[1];
        itemGroup = group.split("-")[0];
    }
    originalWidth = width + "px";
    //console.log('w:'+originalWidth);
    //on cherce si une zone est définie
    if (target) {
        //console.log(target);
        container = target;
        containment = "parent";
        maxwidth = $(target).width() + "px";
        maxheight = $(target).height() + "px";
        height = "auto";
        maxwidth = "100%";
        maxheight = "100%";
    } else if (
        $(".zone_limit[data-group='" + (itemGroup > 0 ? itemGroup : group) + "']")
            .length > 0 &&
        $(".view_tab[data-view='" + view + "']").length > 0
    ) {
        container =
            ".zone_limit[data-group='" + (itemGroup > 0 ? itemGroup : group) + "']";
        containment = "parent";
        maxwidth =
            $(
                ".zone_limit[data-group='" + (itemGroup > 0 ? itemGroup : group) + "']"
            ).width() + "px";
        maxheight =
            $(
                ".zone_limit[data-group='" + (itemGroup > 0 ? itemGroup : group) + "']"
            ).height() + "px";
        height = "auto";
        maxwidth = "100%";
        maxheight = "100%";

        $(
            ".zone_limit[data-group='" + (itemGroup > 0 ? itemGroup : group) + "']"
        ).css("mix-blend-mode", coloreffect);
        //width = $(".zone_limit[data-group='"+(itemGroup > 0 ? itemGroup : group)+"']").width()+'px';
        //height = 'auto';
    } else {
        container = "#image-block";
        containment = "";
        maxwidth = "100%";
        maxheight = "100%";
        height = "auto";
        //width = $("#image-block").width()+'px';
        //width = '100%';
    }
    if (resizeable > 0) {
        maxwidth = "5000px";
        maxheight = "5000px";
    }

    $(container).css("z-index", zindex > -1 ? zindex : 0);

    editIndex = "";

    editButton = "<span " + editIndex + ' class="editIt"></span>';

    if ($("#image-block[data-view='" + view + "']").length == 0 || view == 0)
        specifiView = false;

    //console.log(specifiView);

    if (
        $("#image-block").attr("data-view") == view ||
        view == 0 ||
        !specifiView
    ) {
        if (typeof visu != "undefined") {
            if ($("#image-block .group-" + group).length > 0) {
                $("#image-block .group-" + group).fadeOut("fast");

                if (type == "canvas" || type == "text" || type == "svg") {
                    if (target) $(target).append(visu).attr("alt", "composition_element");
                    else
                        $("#image-block .group-" + group + " .composition_element")
                            .replaceWith(visu)
                            .attr("alt", "composition_element");
                } else if (type == "colorize_new") {
                    //console.log(background)
                    $("#image-block .group-" + group + " svg").remove();
                    $("#image-block .group-" + group + " object").remove();
                    ndkImagetoDataURLForSvg(background, function (bgImage) {
                        png2svg(bgImage, visu, background, group);
                    });
                } else if (type == "colorize") {
                    //console.log(visu);
                    if (!ndkBrowserVersion) {
                        $("#image-block .group-" + group + " .colorize-cover-item").css(
                            "background",
                            background
                        );
                        //$('#image-block .group-'+group+' .colorize-cover-item').css('mask-image', 'url(\''+$('.js-qv-product-cover:eq(0)').attr('src')+'\')');
                    } else {
                        $("#image-block .group-" + group + " svg").remove();
                        $("#image-block .group-" + group + " object").remove();
                        ndkImagetoDataURLForSvg(background, function (bgImage) {
                            png2svg(bgImage, visu, background, group);
                        });
                    }
                } else {
                    if (target)
                        $(target).append(
                            '<img alt="composition_element" class="composition_element img-reponsive ' +
                            category +
                            '" src="' +
                            visu +
                            '"/>'
                        );
                    else
                        $(
                            "#image-block .group-" + group + " .composition_element"
                        ).replaceWith(
                            '<img alt="composition_element" class="composition_element img-reponsive ' +
                            category +
                            '" src="' +
                            visu +
                            '"/>'
                        );
                }

                $("#image-block .group-" + group).fadeIn("slow");

                if (width != 0) {
                    //$('#image-block .group-'+group).css('height', height).css('width', width).css('max-width', maxwidth).css('max-height', maxheight);
                    //$('#image-block .group-'+group).parent(':not(.zone_limit)').css('height', height).css('width', width);
                }

                if (dragdrop == 1 || resizeable == 1 || rotateable == 1) {
                    layerOptions = "";

                    if (type == "canvas" || type == "text")
                        layerOptions +=
                            '<span id="layer-edit-' +
                            group +
                            '" data-view="' +
                            view +
                            '" data-group="' +
                            group +
                            '" class="editThisLayer">' +
                            visu +
                            "</span>";
                    else if (type == "svg") {
                        if (typeof visu == "object") output = visu.prop("outerHTML");
                        else output = visu;

                        //console.log(typeof(visu));
                        layerOptions +=
                            '<span id="layer-edit-' +
                            group +
                            '" data-view="' +
                            view +
                            '" data-group="' +
                            group +
                            '" class="editThisLayer">' +
                            output +
                            "</span>";
                    } else
                        layerOptions +=
                            '<span id="layer-edit-' +
                            group +
                            '" data-view="' +
                            view +
                            '" data-group="' +
                            group +
                            '" class="editThisLayer"><img class="img-responsive very-small-img" src="' +
                            visu +
                            '"/></span>';

                    if ($("#layer-edit-" + group).length > 0) {
                        $("#layer-edit-" + group).replaceWith(layerOptions);
                        layerOptions = "";
                    } else {
                        $("#layer-block").append(layerOptions).show();
                    }
                    //setEditable('#visual_'+group, zindex, containment, layerOptions, dragdrop, resizeable, rotateable );

                    if (
                        width != 0 &&
                        type != "canvas" &&
                        type != "text" &&
                        type != "svg"
                    ) {
                        //$('#visual_'+group).css('height', height).css('width', width).css('max-width', maxwidth).css('max-height', maxheight);
                        //$('#visual_'+group).parent().css('height', height).css('width', width);
                    }
                }
                $("#image-block .group-" + group).css("mix-blend-mode", coloreffect);
            } else {
                viewClass = " ";
                if (view.indexOf("|") > -1) {
                    views = view.split("|");
                    for (var i = 0; i < views.length; i++) {
                        viewClass += " view-" + views[i];
                    }
                }

                $("#image-block .group-" + group).remove();

                if (type == "canvas") {
                    $(container).append(
                        '<div data-group="' +
                        group +
                        '" data-zindex="' +
                        zindex +
                        '" id="visual_' +
                        group +
                        '" style="margin:none;width:auto; height:' +
                        height +
                        "; max-width:" +
                        maxwidth +
                        "; max-height:" +
                        maxheight +
                        "; z-index:" +
                        zindex +
                        "; mix-blend-mode:" +
                        coloreffect +
                        '" class="' +
                        (dragdrop == 1 ? "dragdrop" : "") +
                        " absolute-visu group-" +
                        group +
                        " view-" +
                        view +
                        viewClass +
                        specialClass +
                        '"></div>'
                    );
                    $("#visual_" + group).html(visu + editButton);
                } else if (type == "svg") {
                    $(container).append(
                        '<div data-group="' +
                        group +
                        '" data-zindex="' +
                        zindex +
                        '" id="visual_' +
                        group +
                        '" style="margin:none; height:' +
                        height +
                        "; max-width:" +
                        maxwidth +
                        "; max-height:" +
                        maxheight +
                        "; z-index:" +
                        zindex +
                        ";mix-blend-mode:" +
                        coloreffect +
                        '" class="' +
                        (dragdrop == 1 ? "dragdrop" : "") +
                        " absolute-visu group-" +
                        group +
                        " view-" +
                        view +
                        viewClass +
                        specialClass +
                        ' absolute-svg-text"><div class="composition_element ' +
                        category +
                        '"></div>' +
                        editButton +
                        "</div>"
                    );
                    $("#image-block .group-" + group + " .composition_element")
                        .replaceWith(visu)
                        .attr("alt", "composition_element");
                } else if (type == "text") {
                    $(container).append(
                        '<div data-group="' +
                        group +
                        '" data-zindex="' +
                        zindex +
                        '" id="visual_' +
                        group +
                        '" style="margin:none;width:' +
                        originalWidth +
                        "; height:" +
                        height +
                        "; max-width:" +
                        maxwidth +
                        "; max-height:" +
                        maxheight +
                        "; z-index:" +
                        zindex +
                        "; mix-blend-mode:" +
                        coloreffect +
                        '" class="' +
                        (dragdrop == 1 ? "dragdrop" : "") +
                        " absolute-visu group-" +
                        group +
                        " view-" +
                        view +
                        viewClass +
                        specialClass +
                        '">' +
                        visu +
                        editButton +
                        "</div>"
                    );
                } else if (type == "colorize") {
                    if (!ndkBrowserVersion) {
                        $(container).append(
                            '<div data-group="' +
                            group +
                            '" data-zindex="' +
                            zindex +
                            '" id="visual_' +
                            group +
                            '" style="height:' +
                            height +
                            "; max-width:" +
                            maxwidth +
                            "; max-height:" +
                            maxheight +
                            "; z-index:" +
                            zindex +
                            " ;mix-blend-mode:" +
                            coloreffect +
                            ';" class="' +
                            (dragdrop == 1 ? "dragdrop" : "") +
                            " absolute-visu group-" +
                            group +
                            " view-" +
                            view +
                            viewClass +
                            specialClass +
                            " absolute-img " +
                            (type == "color" ? "multiply-mode-color" : "") +
                            '"><div  style="background:' +
                            background +
                            "; mask-image: url('" +
                            visu +
                            "');-webkit-mask-image: url('" +
                            visu +
                            '\');" class="colorize-cover-item"><img alt="composition_element" class=" composition_element img-reponsive ' +
                            category +
                            '" src="' +
                            visu +
                            '"/></div>' +
                            editButton +
                            "</div>"
                        );
                    } else {
                        $(container).append(
                            '<div data-group="' +
                            group +
                            '" data-zindex="' +
                            zindex +
                            '" id="visual_' +
                            group +
                            '" style="height:' +
                            height +
                            "; max-width:" +
                            maxwidth +
                            "; max-height:" +
                            maxheight +
                            "; z-index:" +
                            zindex +
                            " ;mix-blend-mode:" +
                            coloreffect +
                            ';" class="' +
                            (dragdrop == 1 ? "dragdrop" : "") +
                            " absolute-visu group-" +
                            group +
                            " view-" +
                            view +
                            viewClass +
                            specialClass +
                            " absolute-img " +
                            (type == "color" ? "multiply-mode-color" : "") +
                            '">' +
                            editButton +
                            "</div>"
                        );

                        ndkImagetoDataURLForSvg(background, function (bgImage) {
                            png2svg(bgImage, visu, background, group);
                        });
                    }
                } else if (type == "colorize_new") {
                    $(container).append(
                        '<div data-group="' +
                        group +
                        '" data-zindex="' +
                        zindex +
                        '" id="visual_' +
                        group +
                        '" style="height:' +
                        height +
                        "; max-width:" +
                        maxwidth +
                        "; max-height:" +
                        maxheight +
                        "; z-index:" +
                        zindex +
                        " ;mix-blend-mode:" +
                        coloreffect +
                        ';" class="' +
                        (dragdrop == 1 ? "dragdrop" : "") +
                        " absolute-visu group-" +
                        group +
                        " view-" +
                        view +
                        viewClass +
                        specialClass +
                        " absolute-img " +
                        (type == "color" ? "multiply-mode-color" : "") +
                        '">' +
                        editButton +
                        "</div>"
                    );

                    ndkImagetoDataURLForSvg(background, function (bgImage) {
                        png2svg(bgImage, visu, background, group);
                    });
                } else {
                    $(container).append(
                        '<div data-group="' +
                        group +
                        '" data-zindex="' +
                        zindex +
                        '" id="visual_' +
                        group +
                        '" style="margin:none;width:auto; height:' +
                        height +
                        "; max-width:" +
                        maxwidth +
                        "; max-height:" +
                        maxheight +
                        "; z-index:" +
                        zindex +
                        " ;mix-blend-mode:" +
                        coloreffect +
                        ';" class="' +
                        (dragdrop == 1 ? "dragdrop" : "") +
                        " absolute-visu group-" +
                        group +
                        " view-" +
                        view +
                        viewClass +
                        specialClass +
                        " absolute-img " +
                        (type == "color" ? "multiply-mode-color" : "") +
                        '"><img  alt="composition_element" class=" composition_element img-reponsive ' +
                        category +
                        '" src="' +
                        visu +
                        '"/>' +
                        editButton +
                        "</div>"
                    );
                    //$(container).append('<img data-zindex="'+zindex+'" id="visual_'+group+'" style="margin:auto;width:auto; height:'+height+'; max-width:'+maxwidth+'; max-height:'+maxheight+'; z-index:'+zindex+'" src="'+visu+'" class="'+((dragdrop == 1) ? 'dragdrop' : '')+' absolute-visu group-'+group+' view-'+view+'"/>');
                }

                if (dragdrop == 1 || resizeable == 1 || rotateable == 1) {
                    layerOptions = "";

                    if (type == "canvas" || type == "text")
                        layerOptions +=
                            '<span id="layer-edit-' +
                            group +
                            '" data-view="' +
                            view +
                            '" data-group="' +
                            group +
                            '" class="editThisLayer">' +
                            visu +
                            "</span>";
                    else if (type == "svg") {
                        if (typeof visu == "object") output = visu.prop("outerHTML");
                        else output = visu;

                        //console.log(typeof(visu));
                        layerOptions +=
                            '<span id="layer-edit-' +
                            group +
                            '" data-view="' +
                            view +
                            '" data-group="' +
                            group +
                            '" class="editThisLayer">' +
                            output +
                            "</span>";
                    } else
                        layerOptions +=
                            '<span id="layer-edit-' +
                            group +
                            '" data-view="' +
                            view +
                            '" data-group="' +
                            group +
                            '" class="editThisLayer"><img class="img-responsive very-small-img" src="' +
                            visu +
                            '"/></span>';

                    //layerOptions = '';

                    if (type == "canvas")
                        setEditable(
                            "#visual_" + group + " > canvas",
                            zindex,
                            containment,
                            layerOptions,
                            dragdrop,
                            resizeable,
                            rotateable
                        );
                    else
                        setEditable(
                            "#visual_" + group,
                            zindex,
                            containment,
                            layerOptions,
                            dragdrop,
                            resizeable,
                            rotateable
                        );

                    //$('#cecft_'+group).css({height : height, width : width});
                    if (
                        width != 0 &&
                        type != "canvas" &&
                        type != "text" &&
                        type != "svg"
                    ) {
                        //$('#visual_'+group).css('height', height).css('width', width).css('max-width', maxwidth).css('max-height', maxheight);
                        //$('#visual_'+group).parent().css('height', height).css('width', width);
                    }
                }
            }
        }
        scrollToNdk($(container), 800);

        //test provisoire : snapShotLight();
    }

    //on met à jour les calques
    /*$(".layers[data-view='"+view+"']").show();
      layerRowId = 'layer_'+$(el).attr('id');
      rowTitle = $(".form-group[data-field='"+group+"']").find('.toggler').text();
      if($('#'+layerRowId).length > 0)
          $('#'+layerRowId).html(rowTitle);
      else
          $(".layers[data-view='"+view+"']").append('<div>test</div>');
      */
    setTimeout(function () {
        $(".orientation_selection[data-group-target='" + group + "']")
            .find(".active_orientation")
            .trigger("click");
    }, 800);

    $(document).trigger("ndkCompoDesigned");

    restoreViewBox(group);
    setTextViewBox(group);
}

function setTextViewBox(group) {
    $("#visual_" + group + " .textareaSvg:visible").each(function () {
        svg = $(this);
        textNode = svg.find("text:eq(0)");
        width = textNode[0].getBBox().width;
        height = textNode[0].getBBox().height;
        height += parseInt(textNode.find("tspan").attr("y"));
        viewBox = "0 0 " + width + " " + height;
        svg[0].setAttribute("viewBox", viewBox);
        svg[0].setAttribute("height", "auto");
        svg[0].setAttribute("width", "auto");
    });
}

function restoreViewBox(group) {
    $("#visual_" + group + " .viewBoxed:not(.restored)").each(function () {
        me = $(this);
        viewBox = me.attr("data-viewbox");
        if (typeof viewBox != "undefined")
            if (viewBox != "0 0 0 0")
                me[0].setAttribute("viewBox", me.attr("data-viewbox"));

        me.addClass("restored");
    });
}

function createPreview(callback) {
    if (typeof createPreview_Override == "function") {
        return createPreview_Override(callback);
    }

    if (
        customizationPrice > 0 ||
        (typeof is_visual != "undefined" && is_visual == true)
    ) {
        convertPercent();

        $(".ndk-img-url").val("").trigger("keyup");
        viewTabs = $(".view_tab");
        if (viewTabs.length > 0) {
            $("li.view_tab").first().trigger("click");
        }
        var snap = snapShot(false, false, true);
        $.when(snap).done(function () {
            setTimeout(function () {
                callback();
            }, 500);
        });
    } else {
        var snap = snapShot(false, false, true);
        $.when(snap).done(function () {
            setTimeout(function () {
                callback();
            }, 500);
        });
        //callback();
    }
}

function simuViews(force, first) {
    if (typeof simuViews_Override == "function") {
        return simuViews_Override(force);
    }
    force = force || false;
    first = first || false;
    //$('#image-block').addClass('hight_quality');
    snapShot(force, first);
    //$('#image-block').removeClass('hight_quality');
    return true;
}

function snapShot(force, first, onlyHtml) {
    if (typeof snapShot_Override == "function") {
        return snapShot_Override(force, first, onlyHtml);
    }
    if (showImgPreview == 1 && is_visual == true)
        $("#image-block").addClass("hight_quality");

    force = force || false;
    first = first || false;
    onlyHtml = onlyHtml || false;

    if (
        customizationPrice > 0 ||
        (typeof is_visual != "undefined" && is_visual == true)
    ) {
        //convertPercent();

        dataView = 0;
        if ($("li.view_tab").length == 0) {
            htmlOutput[0] =
                '<div class="print-page-breaker">' +
                $("#image-block").html() +
                "</div>";
        }

        //var compoImages = [];
        var processTabs = function (force, onlyHtml) {
            if (!first) var tabs = $("li.view_tab");
            else var tabs = $("li.view_tab:eq(0)");

            if (tabs.length > 0) {
                tabs.each(function () {
                    tab = $(this);
                    var clickView = tab.trigger("click");
                    $.when(clickView).done(function () {
                        processTabForSnapShot(
                            parseFloat(tab.attr("data-view")),
                            force,
                            onlyHtml
                        );
                    });
                });
            } else {
                processTabForSnapShot(0, force, onlyHtml);
            }
        };
        compoImages = [];
        $.when(processTabs(force, onlyHtml)).done(function () {
            $(".image-url").each(function () {
                compoImages.push($(this).val());
            });
            $("#image-block").removeClass("hight_quality");
        });
    }
}

function processTabForSnapShot(dataView, force, onlyHtml) {
    if (typeof processTabForSnapShot_Override == "function") {
        return processTabForSnapShot_Override(dataView, force, onlyHtml);
    }
    if (showImgPreview == 0) only_html = true;

    clonedView = $("#image-block[data-view ='" + dataView + "']").clone();
    $(clonedView)
        .find(
            "> :not(.view-" +
            dataView +
            " , .view-0, #view_full_size, #product-zoom, .ndk-svg-view, #bigpic, .tempSvg, .middle-content, .cover-content)"
        )
        .remove();
    $(clonedView)
        .find(".hiddenForSnapshot")
        .show()
        .removeClass(".hiddenForSnapshot");
    $(clonedView)
        .find("#image-block[data-view ='" + dataView + "'] svg")
        .show();

    htmlOutput[dataView] =
        '<div class="print-page-breaker">' + clonedView.html() + "</div>";
    clonedView.remove();
    if (!force) $(this).addClass("snapshoot");
    if (!onlyHtml) var CanvasConvert = svgToCanvas($("#image-block"));
    else
        var CanvasConvert = function () {
            return true;
        };

    $.when(CanvasConvert).done(function () {
        var takePhoto = function (dataview) {
            html2canvas($("#image-block"), {
                onrendered: function (canvas) {
                    var dataURL = canvas.toDataURL("image/png");
                    $("#image-url-" + dataView)
                        .val(dataURL)
                        .trigger("keyup");
                    $(".tempSvg").remove();
                    $(".hiddenForSnapshot").show().removeClass(".hiddenForSnapshot");
                    $("#image-block[data-view ='" + dataView + "']")
                        .find("svg")
                        .show();
                    $(".current_config_img").attr("src", dataURL).show();
                    $("#image-url-0").val(dataURL).trigger("keyup");
                },
            });
        };
        takePhoto(dataView);
    });
}

function svgToCanvas(targetElem) {
    if (typeof svgToCanvas_Override == "function") {
        return svgToCanvas_Override(targetElem);
    }
    if (showImgPreview > 0) {
        var svgElem = targetElem.find("svg");
        $(".tempSvg").remove();
        svgElem.each(function (index, node) {
            el = $(this);
            el.show();
            var image = new Image();
            var parentNode = node.parentNode;
            var svg = node.outerHTML;

            width = el[0].getBoundingClientRect().width;
            height = el[0].getBoundingClientRect().height;

            image.src = "data:image/svg+xml," + escape(svg);
            parentNode.appendChild(image);
            $(image).addClass("tempSvg replaced-svg composition_element");
            el.addClass("hiddenForSnapshot");

            $(image).load(function () {
                var canvas = document.createElement("canvas");
                image.width = width;
                image.height = height;
                canvas.width = width;
                canvas.height = height;
                var context = canvas.getContext("2d");
                $.when(context.drawImage(image, 0, 0)).done(function () {
                    image.src = canvas.toDataURL();
                });
            });
            el.hide();
        });
    }
}

function snapShotFirst() {
    if (typeof snapShotFirst_Override == "function") {
        return snapShotFirst_Override();
    }
    html2canvas($("#image-block"), {
        onrendered: function (canvas) {
            var dataURL = canvas.toDataURL("image/png");
            $("#image-url-0").val(dataURL).trigger("keyup");
            $(".ndkzoom").attr("href", dataURL);
        },
    });
}

//setup before functions
var typingTimer; //timer identifier
var doneTypingInterval = 1000; //time in ms, 5 second for example

/*$(document).on('mouseup', '.ui-wrapper', function(){
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function(){
        snapShotLight();
    }, doneTypingInterval);
    
});*/

function snapShotLight() {
    if (typeof snapShotLight_Override == "function") {
        return snapShotLight_Override();
    }

    if (
        customizationPrice > 0 ||
        (typeof is_visual != "undefined" && is_visual == true)
    ) {
        /*$('#image-block, .absolute-mask, .absolute-visu, .zone_limit').each(function(){
                $(this).css('width', $(this).innerWidth()*2);
                $(this).css('height', $(this).innerHeight()*2);
            });*/

        $(".ndkzoom").attr("disabled", "disabled").addClass("loadingButton");
        dataView = 0;
        dataView = $("#image-block").attr("data-view");
        if (typeof (dataView == "undefined")) dataView = 0;
        if (!dataView.length || dataView.length == 0) dataView = 0;
        html2canvas($("#image-block"), {
            onrendered: function (canvas) {
                var dataURL = canvas.toDataURL("image/png");

                $("#image-url-" + dataView)
                    .val(dataURL)
                    .trigger("keyup");
                $(".ndkzoom").attr("href", dataURL);
                $(".ndkzoom").attr("disabled", false).removeClass("loadingButton");

                /* $('#image-block, .absolute-mask, .absolute-visu, .zone_limit').each(function(){
                                    $(this).css('width', $(this).innerWidth()/2);
                                    $(this).css('height', $(this).innerHeight()/2);
                                });*/
            },
        });
    }

    /*if($('.image-url').length > 0)
                $('#image-url-0').val('');*/
}

function setToZone(el, zone) {
    if (typeof setToZone_Override == "function") {
        return setToZone_Override(el, zone);
    }
    left = $(zone).offset().left;
    top = $(zone).offset().top + $(zone).outerHeight();
    $(el).css({
        position: "absolute",
        left: left + "px",
        top: top + "px",
    });
}

function initText(el) {
    if (typeof initText_Override == "function") {
        return initText_Override(el);
    }
    var existing = el.parent().find(".texteditor");
    var myGroup = el.attr("data-group").split("_")[0];
    myGroup = el.attr("data-group").split("-")[0];
    var myNumber = el.attr("data-number");
    if (typeof myNumber == "undefined") myNumber = false;
    if (
        el.attr("data-pattern") != "" &&
        typeof el.attr("data-pattern") != "undefined"
    )
        pattern = el.attr("data-pattern");
    else pattern = "|";

    pattern = pattern.split("|");

    canvasDom =
        '<canvas id="myCanvas' +
        el.attr("data-group") +
        '" width="0" height="0"></canvas>';

    if (!existing.length || existing.length == 0) {
        if (el.attr("data-lines") > 0) {
            if (el.hasClass("type_textarea")) {
                zone_text = '<span class="textarea">';
                zone_text +=
                    "<textarea " +
                    (el.attr("data-max") > 0
                        ? 'maxlength="' + el.attr("data-max") + '"'
                        : "") +
                    ' class="noborder countmychars" data-pattern="' +
                    pattern[0] +
                    '"></textarea>';
                zone_text +=
                    "</span><style>.arcSelector{display:none!important;}</style>";
                el.after(
                    '<div class="fontSelect " id="textZone' +
                    el.attr("data-group") +
                    (myNumber != false ? "-" + myNumber : "") +
                    '"><span class="texteditor">' +
                    zone_text +
                    '</span><span class="submitText' +
                    (myNumber != false ? "Item" : "") +
                    '">' +
                    applyText +
                    '</span></div><div class="conter-container"></div>'
                );
            } else {
                zone_text = '<span class="textarea">';
                for (var i = 0; i < el.attr("data-lines"); i++) {
                    zone_text +=
                        '<input data-pattern="' +
                        pattern[i] +
                        '" type="text" ' +
                        (el.attr("data-max") > 0
                            ? 'maxlength="' + el.attr("data-max") + '"'
                            : "") +
                        ' size="15" value="" class="noborder" placeholder="Text line ' +
                        (i + 1) +
                        '"/>';
                }
                zone_text +=
                    "</span><style>.arcSelector{display:none!important;}</style>";
                el.after(
                    '<div class="fontSelect " id="textZone' +
                    el.attr("data-group") +
                    (myNumber != false ? "-" + myNumber : "") +
                    '"><span class="texteditor">' +
                    zone_text +
                    '</span><span class="submitText' +
                    (myNumber != false ? "Item" : "") +
                    '">' +
                    applyText +
                    "</span></div>"
                );
            }
        } else {
            if (el.hasClass("type_textarea")) {
                width = "auto";
                height = "auto";
                if ($(".zone_limit[data-group='" + myGroup + "']").length > 0) {
                    container = ".zone_limit[data-group='" + myGroup + "']";
                    width = $(container).width();
                    height = $(container).height();
                }

                zone_text = '<span class="textarea">';
                zone_text +=
                    '<textarea style="width:' +
                    width +
                    "; height:" +
                    height +
                    ' " ' +
                    (el.attr("data-max") > 0
                        ? 'maxlength="' + el.attr("data-max") + '"'
                        : "") +
                    ' type="text" class="noborder"></textarea>';
                zone_text += '<div id="poptext_' + myGroup + '" class="poptext"></div>';
                zone_text +=
                    "</span><style>.arcSelector{display:none!important;}</style>";
                el.after(
                    '<div class="fontSelect " "textZone' +
                    el.attr("data-group") +
                    (myNumber != false ? "-" + myNumber : "") +
                    '"><span class="texteditor">' +
                    zone_text +
                    '</span><span class="submitText' +
                    (myNumber != false ? "Item" : "") +
                    '">' +
                    applyText +
                    "</span></div>"
                );
            } else {
                zone_text = '<span class="textarea">';
                zone_text +=
                    "<input " +
                    (el.attr("data-max") > 0
                        ? 'maxlength="' + el.attr("data-max") + '"'
                        : "") +
                    ' type="text" size="15" value="Votre texte " class="noborder"/>';
                zone_text +=
                    "</span><style>.arcSelector{display:none!important;}</style>";
                el.after(
                    '<div class="fontSelect " "textZone' +
                    el.attr("data-group") +
                    (myNumber != false ? "-" + myNumber : "") +
                    '"><span class="texteditor">' +
                    zone_text +
                    '</span><span class="submitText' +
                    (myNumber != false ? "Item" : "") +
                    '">' +
                    applyText +
                    "</span></div>"
                );
            }
        }

        el.after(canvasDom);
        $(".noborder, .simpleText, .noborderSimple").each(function () {
            maxChars = $(this).attr("maxlength");
            if (parseInt(maxChars) > 0)
                $(this).maxlength({ slider: true, maxCharacters: maxChars });

            textmask = $(this).attr("data-pattern");
            if (textmask != "" && typeof textmask != "undefined") {
                mask = textmask.split("&&");

                if (mask[0] != "" && typeof mask[0] != "undefined")
                    $(this).mask(mask[0], { placeholder: mask[1] });
                else if (mask[1] != "" && typeof mask[1] != "undefined")
                    $(this).attr("placeholder", mask[1]);
            }
        });
    }

    var myFonts = fonts;
    myColors = colors;
    mySizes = ["20", "30", "40", "50", "60", "70", "80", "90", "100"];
    myEffects = ["applatMe", "concavMe", "convexMe"];
    myAlignments = ["left", "center", "right"];

    if (window["fieldFonts_" + myGroup].length > 0) {
        myFonts = window["fieldFonts_" + myGroup];
    }

    if (window["fieldColors_" + myGroup].length > 0)
        myColors = window["fieldColors_" + myGroup];

    //console.log(myColors);

    if (window["fieldSizes_" + myGroup].length > 0)
        mySizes = window["fieldSizes_" + myGroup];

    if (window["fieldEffects_" + myGroup].length > 0)
        myEffects = window["fieldEffects_" + myGroup];

    if (window["fieldAlignments_" + myGroup].length > 0)
        myAlignments = window["fieldAlignments_" + myGroup];

    allFonts.push(myFonts);
    color_url = getParameterByName("color").replace("@", "#");
    /*
            if(myFonts.indexOf(color_url) < 0)
                color_url = '';
    */

    $(
        "#textZone" +
        el.attr("data-group") +
        (myNumber != false ? "-" + myNumber : "")
    ).fontSelector({
        hide_fallbacks: true,
        initial: myFonts[0],
        initialSize: $(window).width() < 768 ? mySizes[0] : mySizes[0],
        initialColor: color_url != "" ? color_url : myColors[0],
        initialEffect: myEffects[0],
        initialAlignment: myAlignments[0],
        selected: function (style) { },
        selectedSize: function (size) { },
        selectedColor: function (color) {
            $(".colorSelector").css("background", color);
        },
        fonts: myFonts,
        sizes: mySizes,
        colors: myColors,
        effects: myEffects,
        alignments: myAlignments,
        showStroke: stroke_color[myGroup],
    });

    $("#textZone" + el.attr("data-group")).css("font-size", mySizes[0] + "px");
    setTimeout(function () {
        $(".fontSelectUl").each(function () {
            $(this).find("li").first().addClass("active");
        });
        $(".fontColorSelectUl").each(function () {
            $(this).find("li").first().addClass("active");
        });
    }, 1500);
}

function initTextLight(el) {
    if (typeof initTextLight_Override == "function") {
        return initTextLight_Override(el);
    }
    var existing = el.hasClass("initiated");
    var myGroup = el.attr("data-group");
    var myNumber = el.attr("data-number");
    if (typeof myNumber == "undefined") myNumber = false;

    if (
        el.attr("data-pattern") != "" &&
        typeof el.attr("data-pattern") != "undefined"
    )
        pattern = el.attr("data-pattern");
    else pattern = "";

    pattern = pattern.split("|");
    if (!existing) {
        if (el.attr("data-lines") > 0) {
            zone_text = '<div class="zone_text_inputs">';
            for (var i = 0; i < el.attr("data-lines"); i++) {
                zone_text +=
                    '<input data-pattern="' +
                    (typeof pattern[i] != "undefined" ? pattern[i] : "") +
                    '" type="text" ' +
                    (el.attr("data-max") > 0
                        ? 'maxlength="' + el.attr("data-max") + '"'
                        : "") +
                    ' size="15" value="" class="' +
                    (el.hasClass("required_field") ? "required_field_test" : "") +
                    ' noborderSimple" placeholder="Text line ' +
                    (i + 1) +
                    '" data-message="' +
                    el.attr("data-message") +
                    '"/>';
            }
            zone_text += "</div>";
            el.after(zone_text).addClass("initiated").hide();
        }

        $(".noborderSimple, .simpleText, .noborderSimple").each(function () {
            maxChars = $(this).attr("maxlength");
            if (parseInt(maxChars) > 0)
                $(this).maxlength({ slider: true, maxCharacters: maxChars });

            textmask = $(this).attr("data-pattern");
            if (textmask != "" && typeof textmask != "undefined") {
                mask = textmask.split("&&");

                if (mask[0] != "" && typeof mask[0] != "undefined")
                    $(this).mask(mask[0], { placeholder: mask[1] });
                else if (mask[1] != "" && typeof mask[1] != "undefined")
                    $(this).attr("placeholder", mask[1]);
            }
        });
    }
}

function stopWheel(e) {
    if (typeof stopWheel_Override == "function") {
        return stopWheel_Override(e);
    }
    if (!e) {
        /* IE7, IE8, Chrome, Safari */
        e = window.event;
    }
    if (e.preventDefault) {
        /* Chrome, Safari, Firefox */
        e.preventDefault();
    }
    e.returnValue = false; /* IE7, IE8 */
}

function calculateSurface(group, mesures, valuePrice) {
    if (typeof calculateSurface_Override == "function") {
        return calculateSurface_Override(group, mesures, valuePrice);
    }
    result = 1;
    //console.log(mesures);
    for (var i = 0; i < mesures.length; i++) {
        if (typeof mesures[i] != "undefined")
            result = parseFloat(parseFloat(result) * parseFloat(mesures[i]));
    }

    if (parseFloat(result) > 0) {
        $("#resultValue_" + group).html(parseFloat(result).toFixed(2));
        $(".resultValue_" + group).show();
        price = parseFloat(valuePrice * result);
    } else {
        $("#resultValue_" + group).html();
        $(".resultValue_" + group).hide();
        price = 0;
    }
    updatePriceNdk(price, group);
}

$(function () {
    if ($("#ndkcsfields-block").length < 1 || !isFields) {
        $(".ndkcsfields-block.config_boxes").remove();
        return;
    }

    $("section.product-customization").hide();
    $("body").addClass("ndkcfLoaded");
    $("body").addClass("is_customizable_product_ndk");

    $("body").append(ndkLoader);
});

function setNdkImgTooltip() {
    $(".img-item-row").each(function () {
        var me = $(this);
        var zoom_img = me.find(".img-value:eq(0)").attr("data-src");
        //me.attr('title', 'wait...');
        if (zoom_img != "") {
            html_img = '<img class="img-responsive" src="' + zoom_img + '"/>';
            me.tooltip({
                html: true,
                content: html_img,
                placement: "top",
            });
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
}

function setTooltipsDesc() {
    if (ps_version > 1.6) {
        $(".tooltipDescMark").each(function () {
            $(this).attr("title", "wait...");
            text = $(this).parent().find(".tooltipDescription").html();
            $(this).tooltip({
                html: true,
                content: text,
                placement: "auto",
            });
        });
    } else {
        $(".tooltipDescMark").each(function () {
            text = $(this).parent().find(".tooltipDescription").html();
            $(this).tooltip({
                html: true,
                title: text,
                placement: "auto",
            });
        });
    }
}

getSelectedValuesForDynamicInfluences = function(current_group, current_id_value) {
    var selected = new Array();
    $('#ndkcsfields .ndkackFieldItem').each(function() {
        var field_type = $(this).data('field-type');
        var group = 0;
        var id_value = 0;
        if (field_type == 1) {
            //select
            group = $(this).find('.form-control-ndk').data('group');
            if (group != current_group) {
                id_value = $(this).find('option:selected').data('id-value');
            }
        } else if (field_type == 2) {
            // img
            group = $(this).find('.img-value').data('group');
            if (group != current_group) {
                id_value = $(this).find('.img-value.selected-value').data('id-value');
            }
        } else if (field_type == 4) {
            group = $(this).find('input').data('group');
            if (group != current_group) {
                id_value = $(this).find('input').data('id-value');
            }
        }
        if (group == current_group) {
            selected.push({group:current_group, id_value:current_id_value});
            return false;
        }
        if (group != 0 && id_value != 0) {
            selected.push({group:group, id_value:id_value});
        }
    });
    return selected;
};

setDynamicInfluences = function(ndkcf_id_product, selected, current_dynamic_influences) {
    $.ajax({
        type: "GET",
        url: baseUrl + "modules/ndk_advanced_custom_fields/front_ajax.php",
        data: {
            ndkcf_id_product: ndkcf_id_product,
            selected: selected,
            dynamic_influences: current_dynamic_influences,
            action: "setDynamicInfluences",
        },
        dataType: "json",
        async: true,
        success: function (data) {
            if (data.error != undefined) {
                console.log(data.error);
                return;
            } 
            if (data.field_type == 0) {
                // text
                $('#ndkcsfield_'+data.group).next().find('input').val('').attr('disabled', 'disabled').attr('placeholder', '');
                data.show_values.forEach(function(value) {
                    $('#ndkcsfield_'+data.group).next().find('input').val(value).trigger('change');
                    $('#ndkcsfield_'+data.group).closest('.form-group').removeClass('notReadyStep');
                    if ($('#ndkcsfield_'+data.group).closest('.form-group').find('.fieldPane').is(':visible') == false) {
                        $('#ndkcsfield_'+data.group).closest('.form-group').find('.toggler').trigger('click');                        
                    }
                    $('#ndkcsfield_'+data.group).val(value);
                });
            } else if (data.field_type == 4) {
                // radio button
                $('#main-'+data.group+' .radio').addClass('disabled_value_by_'+data.from_group);
                data.show_values.forEach(function(value) {
                    $('#main-'+data.group+' .radio input[data-id-value="'+value+'"]').parent().removeClass('disabled_value_by_'+data.from_group);
                });
            } else {
                // image
                $('.img-item-row[data-group="'+data.group+'"]').addClass('disabled_value_by_'+data.from_group);
                // select
                $('.form-control-ndk[data-group="'+data.group+'"] option').addClass('disabled_value_by_'+data.from_group).attr('disabled', 'disabled');
                data.show_values.forEach(function(value) {
                    // image
                    $('.img-item-row[data-id-value="'+value+'"]').removeClass('disabled_value_by_'+data.from_group);
                    // select
                    $('.form-control-ndk[data-group="'+data.group+'"] option[data-id-value="'+value+'"]').removeClass('disabled_value_by_'+data.from_group).removeAttr('disabled');
                });
            }
            if (data.hidenext != undefined && data.hidenext == 1) {
                $('.form-group.stepTodo:not([data-open-status=3])').addClass('hidden');
                $('#submitNdkcsfields').addClass('hidden');
            }
            if (data.shownext != undefined && data.shownext == 1) {
                $('.form-group.stepTodo:not([data-open-status=3])').removeClass('hidden');
                $('#submitNdkcsfields').removeClass('hidden');
            }
        },
        error: function () {
            //alert('error handing here');
        },
    });
}

initApplication = function () {
    if (isFields != 1 || $("#ndkcsfields-block").length < 1) return;

    if (typeof initApplication_Override == "function") {
        return initApplication_Override();
    }
    $("section.product-customization").hide();
    $("body").addClass("ndkcfLoaded");
    //$('.product-refresh').remove();
    $("body").addClass("is_customizable_product_ndk");

    identifyResctictives();
    $("#submitNdkcsfields")
        .unbind("click")
        .click(function (event) {
            $("#ndkcsfields").ndkSubmit(event);
        });

    //console.log(editConfig)
    if (editConfig == 0) emptyFormNdk($("#ndkcsfields"));

    if (typeof oldRef != "undefined") {
        editAttr = oldRef.split("-")[2];
        //console.log(editAttr);
    }

    if (parseFloat($("#quantity_wanted").val() == 0))
        $("#quantity_wanted").val("1");

    if (makeSlide == 1) {
        makeGroupFieldsSlide();
    }

    if (ps_version > 1.6 && $("#image-block").length == 0) {
        if ($(".images-container .slick-current").length > 0) {
            $(".images-container .slick-current:eq(0) > div").attr(
                "id",
                "image-block"
            );
            $(". images-container.slick-current:eq(0)")
                .find("img")
                .attr("id", "bigpic");
        } else {
            $(".product-cover:eq(0)").attr("id", "image-block");
            $(".product-cover:eq(0) img[itemprop='image']").attr("id", "bigpic");
        }
        $(".images-container").removeClass("images-container");
    }
    $("#bigpic").attr("data-original-image", $("#bigpic").attr("src"));
    $("#bigpic").css(
        "background",
        "url(" + $("#bigpic").attr("data-original-image") + ") 100%"
    );
    if (typeof templateType == "undefined") templateType = 0;

    $(".colorize-ndk").each(function () {
        $(this).attr("data-src", $("#bigpic").attr("src"));
    });

    /*$(document).on('mouseover', '#image-block', function(){
            $(document).bind('mousewheel DOMMouseScroll',function(){ 
                stopWheel(window.event); 
            });
        }, function() {
            $(document).unbind('mousewheel DOMMouseScroll');
        });*/

    registerInitialValues();

    //console.log(initialValues);
    if (typeof contentOnly != "undefined" && !contentOnly) {
        if (templateType == 99) redesignPage();
        else redesignPageLight();
    } else {
        redesignPageLight();
    }
    //console.log(makeItFloat);

    $(".ndk_attribute_select").each(function () {
        $(this).trigger("change");
    });

    $(".ndkcf_totalprod_quantity").val(0);
    /*$('.ndk-accessory-quantity').each(function(){
            $(this).val($(this).attr('data-default-value'));
            if(parseFloat($(this).attr('data-default-value')) > 0)
            $(this).trigger('keyup')
        });*/

    $("input.surface").each(function () {
        if ($(this).attr("min") > 0) $(this).val($(this).attr("min"));

        $(this).trigger("change");
    });

    setTimeout(function () {
        if (editConfig > 0) {
            if ($("#configItem_" + editConfig + " .ndkLoadConfig").length > 0)
                $("#configItem_" + editConfig + " .ndkLoadConfig:eq(0)").trigger(
                    "click"
                );
            else loadCustomization(editConfig);

            $("#configItem_" + editConfig).addClass("active");
        }

        if (editConfig == 0) applyDefaultValuesNdk();
    }, 50); //test timeout

    if (typeof is_visual != "undefined" && is_visual == true) {
        $("body").addClass("ndkCfIsVisual");
        $("#image-block")
            //    .parent()
            .append('<button href="#" class="ndkzoom">&nbsp;</button>');
        $('.expander[data-toggle="modal"]').hide();
        //$('#image-block *[data-toggle = modal]').remove();
    }

    //$('#image-block').append('<canvas id="maskcanvas"></canvas>');
    $(".textzone").trigger("click");

    if ($(".textzone").length > 0)
        $(".textzone:not(.dontInit)").each(function () {
            initText($(this));
        });

    if ($(".simpleText").length > 0)
        $(".simpleText").each(function () {
            initTextLight($(this));
        });

    if (typeof contentOnly != "undefined" && !contentOnly) {
        if (!!$.prototype.fancybox)
            $(".fancybox").fancybox({
                hideOnContentClick: true,
                openEffect: "elastic",
                closeEffect: "elastic",
            });
    }

    $("#customizationForm").parent().hide();

    $(".datepicker").each(function () {
        me = $(this);
        //dateFormat = "yy-mm-dd";
        dateFormat = $.datepicker.regional[prestashop.language.iso_code].dateFormat;
        if (typeof me.attr("data-pattern") != "undefined")
            dateFormat = me.attr("data-pattern");
        me.datepicker({
            prevText: "",
            nextText: "",
            dateFormat: dateFormat,
        });
    });

    if (letOpen == 0) {
        setTimeout(function () {
            $(".toggler").each(function () {
                $(this).removeClass("active");
                if (parseInt(letOpen) == 0) $(this).parent().find(".fieldPane").hide();
            });
        }, 50); //test timeout
    } else {
        $(".toggler").addClass("letOpen");
    }

    setTimeout(function () {
        $(".toggleGroupField").trigger("click");
        $(".userPanel").hide();
    }, 50); //test timeout

    $(".img-value").each(function () {
        if (typeof $(this).attr("data-thumb") != "undefined") {
            preloadImg.push($(this).attr("data-thumb"));
            $(this).attr("src", $(this).attr("data-thumb"));
        }
    });

    $.when(preload(preloadImg)).done(function () {
        setTimeout(function () {
            equalheight(".img-item-row");
        }, 50); //test timeout
    });

    loadImgSvg();

    $("img.jpg").each(function () {
        var $img = $(this);
        $img.parent().find(".svg-container").html($img);
        //$img.hide();
    });

    if (!!$.prototype.fancybox)
        $(".accessory-more").fancybox({
            autoScale: true,
            minHeight: 30,
            showCloseButton: true,
            autoDimensions: false,
        });

    $(".toggleAccessoriesCutomization").fancybox({
        autoScale: true,
        minHeight: 300,
        maxWidth: "80%",
        showCloseButton: true,
        autoDimensions: false,
        afterShow: function () {
            reSetEditableFields();
        },
    });

    $("#submitNdkcsfields").attr("disabled", false);

    $("#layer_block").hide();

    setRecommends();

    $("label.toggler").each(function () {
        if (parseInt(letOpen) == 0)
            $(this).append('<span class="toggleText">' + toggleOpenText + "</span>");
    });

    setTooltipsDesc();

    setValueProdImage();

    if (showImgTooltips == 1) setNdkImgTooltip();

    if (showQuicknav == 1) setQuickNav();

    $(".product-variants select:not(.false_attribute)").trigger("change");
    $(".product-variants input:not(.false_attribute)").trigger("keyup");
    setScenario();
    coverImage();
    getIdCombinationNdk();

    equalheight(".accessory-ndk .accessory-infos");
    if ($("#quantity_wanted").val() < 1)
        $("#quantity_wanted").val(1).trigger("change");

    $("#submitNdkcsfields").prop("disabled", false).removeClass("loadingButton");
    $(".falseButton").prop("disabled", false).removeClass("loadingButton");

    $(".ndk-colorpicker").mColorPicker();
};

$(document).on("colorpicked", ".ndk-colorpicker", function () {
    clearTimeout(typingTimer);
    me = $(this);
    typingTimer = setTimeout(function () {
        color = me.val();
        me.parent()
            .find(".color-ndk")
            .attr("data-value", color)
            .attr("data-color", color)
            .attr("data-src", 0)
            .trigger("click");
    }, doneTypingInterval);
});

$(document).on("blur", ".ndk-colorpicker", function () {
    $(this).trigger("colorpicked");
});

document.onreadystatechange = function () {
    if (document.readyState === "complete") {
        $("#ndkloader").remove();
    }
};

//document ready
$(document).ready(function () {
    //var isFields = $('#ndkcsfields').length > 0;
    var isFields = $(".ndkcsfields-block").length > 0;
    //console.log(isFields)
    if (isFields) {
        //$('.product-refresh').remove();
        if (is_visual) $(".images-container").removeClass("images-container");
        setTimeout(function () {
            $.when(initApplication()).then(function () {
                setTimeout(function () {
                    //$('#ndkloader').remove();
                    $(".resetZones").trigger("click");
                    //$('#image-block').find('[data-target="#product-modal"]').remove();
                    if (ps_version > 1.6)
                        $("#content").parent().addClass("pb-left-column");

                    if (makeItFloat > 0) {
                        if (ps_version > 1.6) {
                            if (typeof contentOnly == "undefined") contentOnly = false;
                            $("#content").parent().addClass("pb-left-column");
                            makeFloat(".pb-left-column:eq(0)");
                            makeFloat(".pb-right-column", "#ndkcsfields");
                        } else {
                            makeFloat(".pb-left-column");
                            makeFloat(".pb-right-column", "#ndkcsfields");
                        }
                    }
                    setOpenedStatus();
                }, 50); //test timeout

                setTimeout(function () {
                    setTags();
                }, 500);
            });
        }, 150); //test timeout
    }
    setTimeout(function () {
        $("#main").removeClass("-combinations-loading");
    }, 2000);

    if ($('.img-select-container').length > 0) {
        /* shortened description expandable on click for image_type */
        function generateSeeMoreBtn(withEvent = true, seeMoreTextTrans) {
            seeMoreBtn = $('<span class="see-more-btn-desc-img">' + seeMoreTextTrans[0] + '</div>');
            if (withEvent) {
                seeMoreBtn.on('click', function () {
                    $(this).parent().toggleClass('shortened-desc');
                    btnContent = $(this).html() == seeMoreTextTrans[0] ? seeMoreTextTrans[1] : seeMoreTextTrans[0];
                    $(this).html(btnContent);
                })
            }
            return seeMoreBtn;
        }
        $('.img-select-container').each(function () {
            $(this).find('.custom-desc-for-img').each(function () {
                $(this).parent()
                    .addClass('shortened-desc')
                    .append(generateSeeMoreBtn($('li', $(this)).length > 2, seeMoreTextTrans));
            })
        });
    }

    // click on dynamic influence type select
    $('.ndk-select.dynamic_influences').on('change', function() {
        // current selected value
        var current_group = $(this).data('group');
        var current_id_value = $(this).find('option:selected').data('id-value');
        var current_dynamic_influences = $(this).closest('.form-group.ndkackFieldItem').data('dynamic_influences');
        var selected = getSelectedValuesForDynamicInfluences(current_group, current_id_value);
        setDynamicInfluences($("#ndkcf_id_product").val(), selected, current_dynamic_influences);
    });

    // click on dynamic influence type image
    $('.img-type-container.row.dynamic_influences .select-btn-img-type').on('click', function() {
        // current selected value
        var current_group = $(this).parent().data('group');
        var current_id_value = $(this).parent().data('id-value');
        var current_dynamic_influences = $(this).closest('.form-group.ndkackFieldItem').data('dynamic_influences');
        var selected = getSelectedValuesForDynamicInfluences(current_group, current_id_value);
        setDynamicInfluences($("#ndkcf_id_product").val(), selected, current_dynamic_influences);        
    });

    // click on dynamic influence type radio button
    $('.ndk-form-main .radio .dynamic_influences').on('click', function() {
        // current selected value
        var current_group = $(this).closest('.radio input').data('group');
        var current_id_value = $(this).closest('.radio input').data('id-value');
        var current_dynamic_influences = $(this).closest('.form-group.ndkackFieldItem').data('dynamic_influences');
        var selected = getSelectedValuesForDynamicInfluences(current_group, current_id_value);
        setDynamicInfluences($("#ndkcf_id_product").val(), selected, current_dynamic_influences);
    });
});

$(document).on("keyup", ".noborderSimple", function () {
    rootInput = $(this).parent().parent().find(".simpleText");
    group = rootInput.attr("data-group");
    price = rootInput.attr("data-price");
    ppcprice = rootInput.attr("data-ppcprice");
    el = $(this);
    texte = "";
    charsCount = 0;
    // clearTimeout(typingTimer);
    inputNumber = el.parent().find(".noborderSimple").length;
    if (inputNumber > 1) separator = "\n";
    else separator = "";

    // typingTimer = setTimeout(function () {
    el.parent()
        .find(".noborderSimple")
        .each(function () {
            if ($(this).val() != "") {
                texte += $(this).val() + separator;
                charsCount += $(this)
                    .val()
                    .replace(/\ |\n|\r|(\n\r)/g, "").length;
            }
        });

    if (texte == "" || texte == " ") {
        price = 0;
        texte = "";
    } else if (ppcprice > 0) {
        price = ppcprice * charsCount;
    } else {
        price = rootInput.attr("data-price");
    }

    if (texte == "" || texte == " ") {
        price = 0;
        texte = "";
    }
    rootInput.val(texte).trigger("keyup");
    //console.log(texte)
    //updatePriceNdk(price, group);
    // }, 1000);
});

/*$(document).on('keyup, change', '.surface', function(){
        group = $(this).attr('data-group');
        valuePrice = $(this).attr('data-price');
        //on garde le ratio
        if($(this).attr('data-preserve-ratio') == 1){
        
        }
        
        $('.surface_'+group).each(function(){
            $i = $(this).attr('data-val');
            mesures[$i] = parseFloat( $(this).val() );
        });
        
        calculateSurface(group, mesures, valuePrice);
    });*/

$(document).on("keyup, change", ".surface", function () {
    group = $(this).attr("data-group");
    valuePrice = $(this).attr("data-price");
    if (parseFloat($(this).attr("min")) > 0) {
        if (parseFloat($(this).val()) < parseFloat($(this).attr("min")))
            $(this).val($(this).attr("min"));
    }
    if (parseFloat($(this).attr("max")) > 0) {
        if (parseFloat($(this).val()) > parseFloat($(this).attr("max")))
            $(this).val($(this).attr("max"));
    }

    $(".surface_" + group).each(function () {
        $i = $(this).attr("data-val");
        mesures[$i] = parseFloat($(this).val());
        defaultMesures[$i] = parseFloat($(this).attr("min"));
    });
    //on garde le ratio
    if ($(this).attr("data-preserve-ratio") == 1) {
        defaultMesures.sort();
        ratio = defaultMesures[1] / defaultMesures[0];
        el = $(this);
        $(".surface_" + group)
            .not(this)
            .each(function () {
                if ($(this).attr("min") == defaultMesures[1]) newVal = el.val() * ratio;
                else newVal = el.val() / ratio;
                if ($(this).val() != newVal.toFixed(2))
                    $(this).val(newVal.toFixed(2)).trigger("change");
            });
    }

    calculateSurface(group, mesures, valuePrice);
});

$(document).on("click", ".toggleGroupField", function () {
    if ($(this).hasClass("opened")) {
        $(".groupFieldBlock:visible").hide();
        $(this).removeClass("opened").addClass("closed");
        $("#bigpic").attr("src", $("#bigpic").attr("data-original-image"));
        $(".zone_limit, .absolute-visu, .editThisLayer").hide();
    } else {
        target = $(this).attr("target");
        $(".toggleGroupField").removeClass("opened").addClass("closed");
        if ($(target).is(":visible") && !$(target).hasClass("groupFieldBlock")) {
            $(this).removeClass("opened").addClass("closed");
            $(target).hide();
        } else {
            $(this).removeClass("closed").addClass("opened");
            $(target).show();
            $(target + " .view_tab:first").trigger("click");
        }
        $(".groupFieldBlock:visible:not(" + target + ")").hide();
        $(target).find(".ndkcfPagerItem:eq(0)").trigger("click");
        $(".img-item-row, .svg-container").css("height", "");
        setTimeout(function () {
            equalheight(".img-item-row");
            equalheight(".svg-container");
        }, 500);
    }
});

$(document).on("submit", ".ajax_form", function (event) {
    event.preventDefault();
    $("#submitNdkcsfields").prop("disabled", true).addClass("loadingButton");
    $(".falseButton").prop("disabled", true).addClass("loadingButton");
    convertPercent();
    if (
        typeof is_visual != "undefined" &&
        is_visual == true &&
        showHdPreview == 1
    )
        loading_steps = 3;
    else loading_steps = 2;

    $("body").append(
        '<div class="ndk-loader" id="ndkloader"><h4 class="reveal-text"><span id="loader_step">(1/' +
        loading_steps +
        ") </span>" +
        loadingText +
        '</h4><ul role="progressbar"><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li><li role="presentation"></li></ul></div></div>'
    );
    $("#ndkcf_id_combination").val($("#idCombination").val()).trigger("keyup");
    if (ps_version > 1.6) getIdCombinationNdk(true);

    var $form = $(this),
        url = $form.attr("action");

    id_product = $("#ndkcf_id_product").val();
    qtty = $("#quantity_wanted").val();
    if (parseFloat(qtty) < 1) {
        qtty = 1;
        $("#quantity_wanted").val("1");
    }
    if (addProductPrice == 1) id_combination = $("#ndkcf_id_combination").val();
    else {
        id_combination = 0;
        $("#ndkcf_id_combination").val(0).trigger("keyup");
    }

    if (typeof oldRef != "undefined" && !alreadyModify) {
        old_ref = oldRef.split("-");
        old_id = refProd;
        old_id_customization = old_ref[4];
        old_conf = editConfig;
    } else {
        old_id = 0;
        old_id_customization = 0;
        old_conf = 0;
    }
    $(".form-group").removeClass("focusRequired");
    $(".form-group").find(".error").remove();
    myFormDatas = $form
        .find("input, select, textarea")
        .not(".ndk-accessory-quantity[value=0]")
        .not(".ndk_attribute_select")
        .not(".dontSend")
        .serialize();
    createPreview(function () {
        //console.log(myDatas);
        $.ajax({
            type: "POST",
            url:
                url +
                (customizationPrice > 0 ||
                    $(".ndk-accessory-quantity[value!=0]").length > 0
                    ? "createNdkcsfields.php"
                    : "createNdkcsfields.php"),
            data:
                myFormDatas +
                "&qty=" +
                qtty +
                "&old_id=" +
                old_id +
                "&old_id_customization=" +
                old_id_customization +
                "&old_conf=" +
                old_conf +
                "&is_visual=" +
                (is_visual ? 1 : 0) +
                "&cover=" +
                compoImages,
            dataType: "json",
            success: function (data) {
                $("#product_customization_id").val(data.id_customization);
                convertPercent();
                alreadyModify = true;
                //on enregistre la config
                $("#image-block").removeClass("hight_quality");
                $(".tempSvg").remove();
                $(".hiddenForSnapshot").show().removeClass(".hiddenForSnapshot");
                $("#image-block svg").show();
                left_block = $("#image-block").html();
                right_block = $("#ndkcsfields").html();
                //$(left_block).find(".textareaSvg").attr('viewBox', '')

                if ($("#layer-block").length > 0)
                    layer_block = $("#layer-block").html();
                else layer_block = "";
                id_product = $("#ndkcf_id_product").val();
                id_customer = $("#ndkcf_id_customer").val();
                config_name =
                    "custom-" +
                    id_product +
                    "-" +
                    (parseFloat(id_combination) > 0 ? parseFloat(id_combination) : 0) +
                    "-" +
                    data.id_cart +
                    "-" +
                    data.id_customization;
                config_tags = "";

                //on enregistre la config
                $(right_block).find(".image-url").val("");

                leftDatas = ndkZipEncode(left_block);
                rightDatas = ndkZipEncode(right_block);
                layerDatas = ndkZipEncode(layer_block);
                //$('.image-url').val('');
                $("#loader_step").html("(2/" + loading_steps + ") ");
                $.when(simuViews(true, true)).done(function () {
                    if (showImgPreview == 0) compoImages = [];

                    compoImages = [$("#image-url-0").val()];
                    configPrice = getPriceHt(totalUnitPrice, "", true);
                    //json_values = getSelectedValuesFromConfig($.parseHTML(strReplaceAll(right_Block, '¬', '€')));
                    if (allowEdit > 0)
                        configdatas = {
                            action: "saveConfig",
                            leftBlock: leftDatas,
                            rightBlock: rightDatas,
                            layerBlock: layerDatas,
                            idCustomer: id_customer,
                            idProduct: id_product,
                            idCustomization: data.id_customization,
                            configName: config_name,
                            configTags: config_tags,
                            configImg: compoImages,
                            preview_field: data.preview_field_img,
                            base_url: baseUrl,
                            skip: 0,
                            price: configPrice,
                        };
                    else configdatas = { skip: 1 };
                    $.ajax({
                        type: "POST",
                        url: baseUrl + "modules/ndk_advanced_custom_fields/saveConfig.php",
                        data: configdatas,
                        dataType: "html",
                        success: function (id_config) {
                            //$form.append('<p id="saved"><span>'+savedtext+'</span></p>');
                            $form.append(
                                '<p id="saved"><svg id="savedSvg" width="220" height="150"><path id="check" d="M30,50 l60,60 l95,-80"></path></svg></p>'
                            );
                            $("#ndkloader").fadeOut().remove();
                            //$('#add_to_cart').fadeIn();
                            if (ps_version > 1.6) {
                                if (data.id_product > 0 && data.id_product != id_product) {
                                    $(
                                        ".product-variants input:not(.false_attribute), .product-variants select:not(.false_attribute)"
                                    )
                                        .val("")
                                        .remove();
                                    $("#product_customization_id").val(data.id_customization);

                                    $.when(
                                        $("#product_page_product_id").val(data.id_product)
                                    ).then(function () {
                                        $("#add-to-cart-or-refresh .add-to-cart:not(.falseButton)")
                                            .prop("disabled", false)
                                            .trigger("click");
                                    });
                                    //console.log('added to cart');
                                } else {
                                    //ajaxCart.add(id_product, (id_combination > 0 ? id_combination : null), false, null, qtty);
                                    $("#add-to-cart-or-refresh .add-to-cart:not(.falseButton)")
                                        .prop("disabled", false)
                                        .trigger("click");
                                }
                            } else {
                                if (data.id_product > 0 && data.id_product != id_product)
                                    ajaxCart.add(data.id_product, null, false, null, qtty);
                                else {
                                    ajaxCart.add(
                                        id_product,
                                        id_combination > 0 ? id_combination : null,
                                        false,
                                        null,
                                        qtty
                                    );
                                }
                            }
                            //$('.current_config_img').attr('src', compoImages[0]).show();
                            $(".blockPrice").hide();
                            setTimeout(function () {
                                $("#saved").remove();
                                $(".snapshoot").removeClass("snapshoot");
                                $("#submitNdkcsfields")
                                    .prop("disabled", false)
                                    .removeClass("loadingButton");
                                $(".falseButton")
                                    .prop("disabled", false)
                                    .removeClass("loadingButton");
                                if (allowEdit > 0) makeSocialCompo(id_config, true);
                                $("body").addClass("ndkcf_done");
                            }, 200);

                            if (contentOnly) {
                                //parent.jQuery.fancybox.close();
                                //parent.document.location.reload(true);
                            }
                            $("#ndkacf-modal").modal("hide");
                        },
                        error: function () { },
                    });
                });

                customizationId = data.id_customization;

                if (
                    typeof is_visual != "undefined" &&
                    is_visual == true &&
                    showHdPreview == 1
                ) {
                    $("#loader_step").html("(3/" + loading_steps + ") ");

                    $.ajax({
                        type: "POST",
                        url: baseUrl + "modules/ndk_advanced_custom_fields/createPdf.php",
                        data: {
                            htmlNodes: htmlOutput.filter(Boolean),
                            idCustomer: data.id_customer,
                            idProduct: data.id_product,
                            idCustomization: data.id_customization,
                            preview_field: data.preview_field,
                            base_url: baseUrl,
                            images: "",
                            fonts: allFonts.filter(Boolean),
                        },
                        dataType: "html",
                        success: function (data) { },
                        error: function () { },
                    });
                } //if is_visual
            },
            error: function () {
                //alert('error handing here');
            },
        });
    });
});

function loadAccessoryAttrPrice(
    id_product,
    id_product_attribute,
    quantity,
    input,
    setPrice
) {
    if (typeof loadAccessoryAttrPrice_Override == "function") {
        return loadAccessoryAttrPrice_Override(
            id_product,
            id_product_attribute,
            quantity,
            input,
            setPrice
        );
    }
    if (!id_product) return true;
    group = input.attr("data-group");
    $.ajax({
        type: "GET",
        url: baseUrl + "modules/ndk_advanced_custom_fields/front_ajax.php",
        data: {
            id_product: id_product,
            id_product_attribute: id_product_attribute,
            quantity: quantity,
            action: "getAttributePrice",
        },
        dataType: "json",
        async: true,
        success: function (data) {
            if (setPrice) input.attr("data-price", data.price);
            if (input.hasClass("ndk-accessory-comb-tab")) {
                qtty = $(
                    "#ndkcf_totalprod_quantity_" + input.attr("data-id-value")
                ).val();
            } else {
                qtty = input.val();
            }

            qtty = input.val();
            if (setPrice) {
                unitPrice = data.price;
                price = qtty * unitPrice;

                valueId = input.attr("data-value-id");
                if (input.attr("data-original-price") == 0)
                    input.attr("data-original-price", data.price);

                $(
                    ".ndk-accessory-quantity[data-group='" +
                    group +
                    "'][data-id-product-accessory='" +
                    input.attr("data-id-product-accessory") +
                    "'][value=0]"
                ).attr("data-updated-for-qtty", 0);
                $(
                    ".ndk-accessory-quantity[data-group='" +
                    group +
                    "'][data-updated-for-qtty!='" +
                    quantity +
                    "'][data-id-product-accessory='" +
                    input.attr("data-id-product-accessory") +
                    "'][value!=0]"
                )
                    .not(input)
                    .trigger("keyup")
                    .attr("data-updated-for-qtty", quantity);


                if (input.hasClass("ndk-accessory-comb-tab"))
                    key = input.attr("data-value-id");
                else key = input.attr("data-value-id");

                if (input.hasClass("ndk-accessory-comb-tab")) {
                    // qtty = $(
                    //   "#ndkcf_totalprod_quantity_" + input.attr("data-id-value")
                    // ).val();
                    qtty = $(".ndk-accessory-quantity[data-value-id='" + key + "']").val();
                }
                price = qtty * unitPrice;

                //console.log(key);
                updatePriceNdk(price, key);

                $(".final_price_" + key).html(
                    formatCurrencyNdk(data.price)
                );
                if (displayPriceHT == 1) {
                    $(".final_price_" + key + " .priceht").remove();
                    $(".final_price_" + key).append(
                        '<span class="priceht clear clearfix"></span>'
                    );
                    getPriceHt(data.price, ".final_price_" + key + " .priceht");
                }
                if (data.price == 0) $(".final_price_" + key).hide();
                else $(".final_price_" + key).show();

                price_ratio = input.attr("data-price-ratio");
                if (price_ratio > 0) {
                    unit_price = data.price / price_ratio;
                    input
                        .parent()
                        .parent()
                        .parent()
                        .find(".unit_price_display")
                        .html(formatCurrencyNdk(unit_price));
                }
            }

            attr_stock = data.stock;

            if (attr_stock < 0) attr_stock = 0;

            input
                .parent()
                .parent()
                .find(".opt_qtty_available")
                .removeClass("qtty-warning");
            input.parent().parent().find(".opt_qtty_available b").html(data.stock);
            if (data.stock < 1)
                input
                    .parent()
                    .parent()
                    .find(".opt_qtty_available")
                    .addClass("qtty-warning");

            if (parseInt(data.oos) == 0) {
                input.attr("data-stock-available", attr_stock);
                if (
                    attr_stock < input.attr("data-qtty-max") ||
                    input.attr("data-qtty-max") == 0
                ) {
                    input.attr("max", attr_stock);
                }
                if (input.val() > attr_stock) input.val(attr_stock).trigger("keyup");

                if (attr_stock < 1) {
                    input.parent().parent().parent().removeClass("selected-accessory");
                    input.parent().parent().find(".oos_msg").remove();
                    input
                        .parent()
                        .parent()
                        .append('<span class="oos_msg">' + out_of_stock_text + "</span>");
                    input.parent().hide();
                } else {
                    input.parent().parent().find(".oos_msg").remove();
                    input.parent().show();
                }
            }

            productWeight = parseFloat(input.attr("data-product-weight"));
            if (data.weight) attrWeight = productWeight + parseFloat(data.weight);
            else attrWeight = productWeight;
            lastWeight = parseFloat(input.attr("data-weight"));
            input.attr("data-weight", attrWeight);

            if (
                lastWeight != attrWeight &&
                !isNaN(attrWeight) &&
                !isNaN(lastWeight)
            ) {
                //console.log(lastWeight+' - '+attrWeight)
                input.trigger("keyup");
            }
        },
    });
    //console.log(groupAdded);
}

function loadAccessoryAttrImg(
    id_product,
    id_product_attribute,
    link_rewrite,
    input
) {
    if (typeof loadAccessoryAttrImg_Override == "function") {
        return loadAccessoryAttrImg_Override(
            id_product,
            id_product_attribute,
            link_rewrite,
            input
        );
    }
    $.ajax({
        type: "GET",
        url: baseUrl + "modules/ndk_advanced_custom_fields/front_ajax.php",
        data: {
            id_product: id_product,
            id_product_attribute: id_product_attribute,
            link_rewrite: link_rewrite,
            action: "getAttributeImg",
        },
        dataType: "html",
        success: function (data) {
            input
                .parent()
                .parent()
                .parent()
                .find(".img-responsive")
                .attr("src", data);
            $(".accessory-ndk[data-id-product-value='" + id_product + "']")
                .trigger("click")
                .addClass("test");
        },
    });
}

$(document).on("change", ".ndk_attribute_select", function () {
    id_product = $(this).attr("ref");
    var input = $(this)
        .parent()
        .parent()
        .find("input[data-id-product-accessory='" + id_product + "']");
    if (input.length > 0) {
        currName = input.attr("name");
        newName =
            currName.split("|")[0] +
            "|" +
            currName.split("|")[1] +
            "|" +
            $(this).val() +
            "]";
        input.attr("name", newName).attr("data-id_combination", $(this).val());
        if (!input.hasClass("price_overrided")) {
            loadAccessoryAttrPrice(
                id_product,
                $(this).val(),
                input.val(),
                input,
                true
            );
        } else {
            loadAccessoryAttrPrice(
                id_product,
                $(this).val(),
                input.val(),
                input,
                false
            );
            if (displayPriceHT == 1 && input.val() > 0) {
                $(".final_price_" + input.attr("data-id-value") + " .priceht").remove();
                $(".final_price_" + input.attr("data-id-value")).append(
                    '<span class="priceht clear clearfix"></span>'
                );
                getPriceHt(
                    input.attr("data-price"),
                    ".final_price_" + input.attr("data-id-value") + " .priceht"
                );
            }
        }
        loadAccessoryAttrImg(
            id_product,
            $(this).val(),
            $(this).attr("data-link-rewrite"),
            input
        );
        //$(".accessory-ndk[data-id-product-value='"+id_product+"']").trigger('click');
    }
});

$(document).on("change, keyup", "#ndkcsfields-block input", function () {
    $(this).attr("value", $(this).val());
});

$(document).on("change", "#ndkcsfields-block select", function () {
    $(this).attr("data-selected", $(this).val());
});

$(document).on(
    "change, keyup",
    "#ndkcsfields-block textarea.noborder",
    function () {
        $(this).html($(this).val());
    }
);

$(document).on("click", "#ndkSaveCustomization", function (e) {
    e.preventDefault();
    if ($("#ndkcf_config_name").val() != "") {
        convertPercent();
        saveCustomizationNdk();
    } else {
        $("#ndkcf_config_name").css("background", "#F2DEDE").focus();
        $("#ndkcf_config_name").parent().find(".error").remove();
        $("#ndkcf_config_name").after(
            '<span class="error alert-danger clear clearfix">' +
            $("#ndkcf_config_name").attr("data-message") +
            "</span>"
        );
    }
});

function saveCustomizationNdk(name) {
    if (typeof saveCustomizationNdk_Override == "function") {
        return saveCustomizationNdk_Override(name);
    }
    name = name || false;

    $("#ndkcf_config_tags").val($("#ndkcf_config_tags").tagify("serialize"));

    left_block = $("#image-block").html();
    right_block = $("#ndkcsfields").html();
    layer_block = $("#layer-block").html();
    id_product = $("#ndkcf_id_product").val();
    id_customer = $("#ndkcf_id_customer").val();
    if (name) {
        config_name = name;
        config_tags = "";
    } else {
        config_name = $("#ndkcf_config_name").val();
        config_tags = $("#ndkcf_config_tags").val();
    }
    $("body").append(ndkLoader);
    //on enregistre la config
    $(right_block).find(".image-url").val("");
    leftDatas = ndkZipEncode(left_block);
    rightDatas = ndkZipEncode(right_block);
    layerDatas = ndkZipEncode(layer_block);
    json_values = getSelectedValuesFromConfig(
        $.parseHTML(strReplaceAll(encodeURIComponent(right_block), "¬", "€"))
    );
    //$('.image-url').val('');
    configPrice = getPriceHt(totalUnitPrice, "", true);
    $.when(simuViews(true, true)).done(function () {
        $.ajax({
            type: "POST",
            url: baseUrl + "modules/ndk_advanced_custom_fields/saveConfig.php",
            data: {
                action: "saveConfig",
                leftBlock: leftDatas,
                rightBlock: rightDatas,
                layerBlock: layerDatas,
                idCustomer: id_customer,
                idProduct: id_product,
                idCustomization: 0,
                configName: config_name,
                configTags: config_tags,
                configImg: compoImages,
                price: configPrice,
                json_values: json_values,
            },
            dataType: "html",

            success: function (id_config) {
                $("#name_already_exists").hide();
                $("#ndkcsfields").append(
                    '<p id="saved"><span>' + savedtext + "</span></p>"
                );
                $(".current_config_img").attr("src", compoImages[0]).show();
                makeSocialCompo(id_config, true);
                setTimeout(function () {
                    $("#saved, #ndkloader").remove();
                    $(".close-config-tool").trigger("click");
                }, 800);
            },
            error: function () { },
        });
    });
}

$(document).on("click", ".ndkLoadConfigImg", function () {
    $(this).parent().parent().find(".ndkLoadConfig").trigger("click");
});

$(document).on("click", ".ndkLoadConfig", function () {
    $(".tagify").tagify("destroy");
    var name = $(this).text();
    var tags = $(this).attr("data-tags");
    if ($(this).attr("data-id") != "0") {
        $("#ndkcf_config_name").val(name);
        $("#ndkcf_config_tags").val(tags);

        loadCustomization($(this).attr("data-id"));
        setTimeout(function () {
            $(".tagify").tagify({
                delimiters: [13, 188, 44],
                addTagPrompt: tagslabel,
            });
            $(".close-config-tool").trigger("click");
            //test provisoire : snapShotFirst();
        }, 500);
        $(".configItem").removeClass("active");
        $(this).parent().addClass("active");
    } else {
        window.location.reload();
    }
});

function loadCustomization(id_config) {
    if (typeof loadCustomization_Override == "function") {
        return loadCustomization_Override(id_config);
    }

    var id_product = $("#ndkcf_id_product").val();
    var qtty = $("#quantity_wanted").val();
    var id_combination = $("#ndkcf_id_combination").val();

    $.ajax({
        type: "GET",
        url: baseUrl + "modules/ndk_advanced_custom_fields/saveConfig.php",
        data: { action: "getConfig", idConfig: id_config },
        dataType: "json",
        success: function (data) {
            var copyHtml = copyHtmlBlocks(data);
            $.when(copyHtml).then(function () {
                $(".fieldPane").show();
                loadInitialValues();
                reCalculatePrice();
                applyConfigValuesNdk();
                reSetEditableFields();

                $(".pace").remove();
                if (parseInt(letOpen) == 0) $(".fieldPane").hide();

                if (makeSlide == 1) {
                    makeGroupFieldsSlide();
                } else {
                    $(".ndkackFieldItem").removeClass("sliderBlock");
                }

                $("#ndkcf_id_product").val(id_product);
                $("#ndkcf_id_combination").val(id_combination);
                $("#submitNdkcsfields").attr("disabled", false);
                $(".image-url").val("");
                $(".current_config_img").attr(
                    "src",
                    $("#configItem_" + id_config + " .ndkLoadConfigImg").attr("src")
                );
                setTimeout(function () {
                    equalheightbyRow(".img-item-row");
                    makeSocialCompo(id_config, false);
                    setTooltipsDesc();
                    if (!!$.prototype.uniform) {
                        //$('.ndk-checkbox, .ndk-radio').unwrap().unwrap().unwrap().unwrap();
                        //$('.ndk-checkbox, .ndk-radio').uniform()
                        //$.uniform.update('.ndk-checkbox, .ndk-radio')
                    }
                    $(".resetZones").trigger("click");
                    setOpenedStatus();
                    $(".ndk_tag_selector").val("all").trigger("change");
                    $("#submitNdkcsfields")
                        .unbind("click")
                        .click(function (event) {
                            $("#ndkcsfields").ndkSubmit(event);
                        });

                    $("#submitNdkcsfields")
                        .prop("disabled", false)
                        .removeClass("loadingButton")
                        .find(".material-icons")
                        .html("&#xE547;");
                    $(".falseButton")
                        .prop("disabled", false)
                        .removeClass("loadingButton")
                        .find(".material-icons")
                        .html("&#xE547;");
                    $("#submitNdkcsfields").find("span").text(submitBtnText);
                    $(".falseButton").find("span").text(submitBtnText);
                    $(".material-icons.zoom-in").html("&#xE8FF;");
                    setTags();
                }, 1000);
            });
        },
        error: function () { },
    });
}

function copyHtmlBlocks(data) {
    if (typeof copyHtmlBlocks_Override == "function") {
        return copyHtmlBlocks_Override(data);
    }

    $("#image-block").html(
        $.parseHTML(strReplaceAll(decodeURIComponent(data.leftBlock), "¬", "€"))
    );
    $("#layer-block").html(
        $.parseHTML(strReplaceAll(decodeURIComponent(data.layerBlock), "¬", "€"))
    );
    $("#ndkcsfields").html(
        $.parseHTML(strReplaceAll(decodeURIComponent(data.rightBlock), "¬", "€"))
    );

    configToSet = getSelectedValuesFromConfig(
        $.parseHTML(strReplaceAll(decodeURIComponent(data.rightBlock), "¬", "€"))
    );
    //console.log(configToSet);
    for (idField in configToSet) {
        if (typeof configToSet[idField] != "undefined") {
            //triggerConfig(idField, configToSet[idField]);
        }
    }
}

function triggerConfig(group, value) {
    if (typeof triggerConfig_Override == "function") {
        return triggerConfig_Override(group, value);
    }
    $("#main-" + group)
        .find(".img-value[data-id-value='" + value + "']")
        .trigger("click");
    $("#main-" + group)
        .find(".color-ndk[data-id-value='" + value + "']")
        .trigger("click");
    $("#main-" + group)
        .find(".ndk-select")
        .val(value)
        .trigger("change");
    $("#main-" + group)
        .find(".ndk-radio[value='" + value + "']")
        .prop("checked", true)
        .trigger("click")
        .trigger("change");
    $("#main-" + group)
        .find(".ndk-checkbox[value='" + value + "']")
        .prop("checked", true)
        .trigger("click")
        .trigger("change");
    $("#main-" + group)
        .find(".simpleText, input.visual-text, .noborder")
        .val(value)
        .trigger("change")
        .trigger("keyup");
}

function applyConfigValuesNdk() {
    if (typeof applyConfigValuesNdk_Override == "function") {
        return applyConfigValuesNdk_Override();
    }
    $(".color-ndk.selected-value").trigger("click");

    $(".img-value.selected-value:not(.img-caracter)").each(function () {
        if ($(this).parent().find(".svg-container").length > 0)
            $(this).parent().find(".svg-container").trigger("click");
        else $(this).trigger("click");
    });

    $(".ndk-radio:checked, .checked .ndk-radio")
        .prop("checked", true)
        .trigger("click")
        .trigger("change");
    $(".ndk-checkbox:checked, .checked .ndk-checkbox,.ndk-checkbox[checkme='1']")
        .prop("checked", true)
        .trigger("change");
    $(".ndk-select").each(function () {
        $(this).find("option:selected").prop("selected", "selected");
        $(this).trigger("change");
    });

    $(".dimension_text").each(function () {
        $(this).trigger("keyup").trigger("change");
    });

    $(".form-group").removeClass("activeFormGroup");
    if (!!$.prototype.uniform)
        $("select.form-control,input[type='radio'],input[type='checkbox']")
            .not(".not_uniform")
            .uniform();
}

function reCalculatePrice() {
    if (typeof reCalculatePrice_Override == "function") {
        return reCalculatePrice_Override();
    }

    $(".selected-value, .ndk-radio:checked, .ndk-select option:selected").each(
        function () {
            group = $(this).attr("data-group");
            price = $(this).attr("data-price");
            updatePriceNdk(price, group);
        }
    );

    $(".selected-svg").each(function () {
        group = $(this).parent().find(".img-value").attr("data-group");
        price = $(this).parent().find(".img-value").attr("data-price");
        updatePriceNdk(price, group);
    });

    $(".noborderSimple").each(function () {
        rootInput = $(this).parent().parent().find(".simpleText");
        group = rootInput.attr("data-group");
        price = rootInput.attr("data-price");
        ppcprice = rootInput.attr("data-ppcprice");
        el = $(this);
        texte = "";
        charsCount = 0;
        inputNumber = el.parent().find(".noborderSimple").length;
        if (inputNumber > 1) separator = " \n ";
        else separator = "";

        el.parent()
            .find(".noborderSimple")
            .each(function () {
                if ($(this).val() != "") {
                    texte += $(this).val() + separator;
                    charsCount += $(this)
                        .val()
                        .replace(/\ |\n|\r|(\n\r)/g, "").length;
                }
            });

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        } else if (ppcprice > 0) {
            price = ppcprice * charsCount;
        } else {
            price = rootInput.attr("data-price");
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        }
        rootInput.val(texte);
        //console.log(texte)
        updatePriceNdk(price, group);
    });

    $(".submitSimpleText").each(function () {
        group = $(this).parent().find(".visual-text").attr("data-group");
        price = $(this).parent().find(".visual-text").attr("data-price");
        ppcprice = $(this).parent().find(".visual-text").attr("data-ppcprice");
        charsCount = 0;

        charsCount = 0;
        if ($(this).parent().find("input.visual-text").length > 0) {
            texte = "";
            $(this)
                .parent()
                .find("input.visual-text")
                .each(function () {
                    if ($(this).val() != "") {
                        texte += $(this).val() + " ";
                        charsCount += $(this)
                            .val()
                            .replace(/\ |\n|\r|(\n\r)/g, "").length;
                    }
                });
        } else {
            texte = $(this).parent().find("textarea").text();
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        } else if (ppcprice > 0) {
            price = ppcprice * charsCount;
        } else {
            price = $(this).parent().parent().find("textarea").attr("data-price");
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        }
        updatePriceNdk(price, group);
    });

    $(".submitText").each(function () {
        group = $(this).parent().parent().find(".ndktextarea").attr("data-group");
        price = $(this).parent().parent().find(".ndktextarea").attr("data-price");
        ppcprice = $(this)
            .parent()
            .parent()
            .find(".ndktextarea")
            .attr("data-ppcprice");
        charsCount = 0;
        inputNumber = $(this).parent().find(".noborder").length;
        if (inputNumber > 1) separator = " \n ";
        else separator = "";

        if ($(this).parent().find(".noborder").length > 0) {
            texte = "";
            $(this)
                .parent()
                .find(".noborder")
                .each(function () {
                    if ($(this).val() != "") {
                        texte += $(this).val() + separator;
                        charsCount += $(this)
                            .val()
                            .replace(/\ |\n|\r|(\n\r)/g, "").length;
                    }
                });
        } else {
            texte = $(this).parent().find(".textarea").text();
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        } else if (ppcprice > 0) {
            price = ppcprice * charsCount;
        } else {
            price = $(this).parent().parent().find(".ndktextarea").attr("data-price");
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        }
        updatePriceNdk(price, group);
    });

    $("#ndkcsfields-block select").each(function () {
        $(this).val($(this).attr("data-selected")).trigger("change");
    });

    $(".ndk-accessory-quantity[value!=0]").each(function () {
        $(this).trigger("keyup");
    });

    $(".simpleText").each(function () {
        $(this).val($(this).attr("data-val"));
        $(this).trigger("keyup");
    });
}

function getSelectedValuesFromConfig(el) {
    if (typeof getSelectedValuesFromConfig_Override == "function") {
        return getSelectedValuesFromConfig_Override(el);
    }
    selectedConfig = [];
    el = $(el);
    el.find(".ndk-radio:checked").each(function () {
        group = $(this).attr("data-group");
        price = $(this).attr("data-price");
        selectedConfig[group] = $(this).val();
    });

    el.find(".ndk-checkbox:checked").each(function () {
        group = $(this).attr("data-value-id");
        price = $(this).attr("data-price");
        selectedConfig[group] = $(this).val();
    });

    el.find(".selected-value").each(function () {
        group = $(this).attr("data-group");
        price = $(this).attr("data-price");
        selectedConfig[group] = $(this).attr("data-id-value");
    });

    el.find(".selected-svg").each(function () {
        group = $(this).parent().find(".img-value").attr("data-group");
        price = $(this).parent().find(".img-value").attr("data-price");
        selectedConfig[group] = $(this)
            .parent()
            .find(".img-value")
            .attr("data-value");
    });

    el.find(".submitSimpleText").each(function () {
        group = $(this).parent().find(".visual-text").attr("data-group");
        price = $(this).parent().find(".visual-text").attr("data-price");
        ppcprice = $(this).parent().find(".visual-text").attr("data-ppcprice");
        charsCount = 0;

        charsCount = 0;
        if ($(this).parent().find("input.visual-text").length > 0) {
            texte = "";
            $(this)
                .parent()
                .find("input.visual-text")
                .each(function () {
                    if ($(this).val() != "") {
                        texte += $(this).val() + " ";
                        charsCount += $(this)
                            .val()
                            .replace(/\ |\n|\r|(\n\r)/g, "").length;
                    }
                });
        } else {
            texte = $(this).parent().find("textarea").text();
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        } else if (ppcprice > 0) {
            price = ppcprice * charsCount;
        } else {
            price = $(this).parent().parent().find("textarea").attr("data-price");
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        }
        selectedConfig[group] = texte;
    });

    el.find(".submitText").each(function () {
        group = $(this).parent().parent().find(".ndktextarea").attr("data-group");
        price = $(this).parent().parent().find(".ndktextarea").attr("data-price");
        ppcprice = $(this)
            .parent()
            .parent()
            .find(".ndktextarea")
            .attr("data-ppcprice");
        charsCount = 0;
        if ($(this).parent().find(".noborder").length > 0) {
            texte = "";
            $(this)
                .parent()
                .find(".noborder")
                .each(function () {
                    if ($(this).val() != "") {
                        texte += $(this).val() + " \n ";
                        charsCount += $(this)
                            .val()
                            .replace(/\ |\n|\r|(\n\r)/g, "").length;
                    }
                });
        } else {
            texte = $(this).parent().find(".textarea").text();
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        } else if (ppcprice > 0) {
            price = ppcprice * charsCount;
        } else {
            price = $(this).parent().parent().find(".ndktextarea").attr("data-price");
        }

        if (texte == "" || texte == " ") {
            price = 0;
            texte = "";
        }
        selectedConfig[group] = texte;
    });

    /*el.find('.ndk-select').each(function(){
                selectedConfig[group] = $(this).attr('data-selected');
            });*/

    el.find(".ndk-accessory-quantity[value!=0]").each(function () {
        group = $(this).attr("data-group");
        selectedConfig[group] = $(this).attr("data-id-value");
    });

    el.find(".simpleText").each(function () {
        group = $(this).attr("data-group");
        selectedConfig[group] = $(this).val();
    });

    el.find(".ndk-select").each(function () {
        group = $(this).attr("data-group");
        price = $(this).attr("data-price");
        selectedConfig[group] = $(this).val();
    });

    return selectedConfig;
}

function reSetEditableFields() {
    if (typeof reSetEditableFields_Override == "function") {
        return reSetEditableFields_Override();
    }
    var e = window.event;
    var event = window.event;
    $(".textzone").each(function () {
        el = $(this);
        myGroup = $(this).attr("data-group");
        number = $(this).attr("data-number");
        myFonts = fonts;
        myColors = colors;
        mySizes = ["20", "30", "40", "50", "60", "70", "80", "90", "100"];
        myEffects = ["applatMe", "concavMe", "convexMe"];
        myAlignments = ["left", "center", "right"];

        if (window["fieldFonts_" + myGroup].length > 0)
            myFonts = window["fieldFonts_" + myGroup];

        if (window["fieldColors_" + myGroup].length > 0)
            myColors = window["fieldColors_" + myGroup];

        if (window["fieldSizes_" + myGroup].length > 0)
            mySizes = window["fieldSizes_" + myGroup];

        if (window["fieldEffects_" + myGroup].length > 0)
            myEffects = window["fieldEffects_" + myGroup];

        if (window["fieldAlignments_" + myGroup].length > 0)
            myAlignments = window["fieldAlignments_" + myGroup];

        $("#textZone" + el.attr("data-group")).fontSelector({
            hide_fallbacks: true,
            initial: myFonts[0],
            initialSize: $(window).width() < 768 ? mySizes[0] : mySizes[0],
            initialColor: myColors[0],
            initialEffect: myEffects[0],
            initialAlignment: myAlignments[0],
            selected: function (style) { },
            selectedSize: function (size) { },
            selectedColor: function (color) {
                $(".colorSelector").css("background", color);
            },
            fonts: myFonts,
            sizes: mySizes,
            colors: myColors,
            effects: myEffects,
            alignments: myAlignments,
            showStroke: stroke_color[myGroup],
        });

        $(
            "#textZone" + el.attr("data-group") + "-" + el.attr("data-number")
        ).fontSelector({
            hide_fallbacks: true,
            initial: myFonts[0],
            initialSize: $(window).width() < 768 ? mySizes[0] : mySizes[0],
            initialColor: myColors[0],
            initialEffect: myEffects[0],
            initialAlignment: myAlignments[0],
            selected: function (style) { },
            selectedSize: function (size) { },
            selectedColor: function (color) {
                $(".colorSelector").css("background", color);
            },
            fonts: myFonts,
            sizes: mySizes,
            colors: myColors,
            effects: myEffects,
            alignments: myAlignments,
            showStroke: stroke_color[myGroup],
        });
    });

    $(".ndk_selector").each(function () {
        $(this).setNdkSelector();
    });

    equalheight(".svg-container");

    $(".absolute-visu").each(function () {
        is_resizeable = $(this).attr("data-resizeable");
        is_dragdrop = $(this).attr("data-dragdrop");
        is_rotateable = $(this).attr("data-rotateable");

        zindex = $(this).attr("data-zindex");

        if ($(this).parent().hasClass("zone_limit")) containment = "parent";
        else containment = "";

        layerOptions = "";

        $(this)
            .resizable()
            .resizable("destroy")
            .rotatable({ wheelRotate: false })
            .rotatable("destroy")
            .draggable()
            .draggable("destroy");

        if (is_resizeable == 1 || is_dragdrop == 1 || is_rotateable == 1)
            setEditable(
                "#" + $(this).attr("id"),
                zindex,
                containment,
                layerOptions,
                is_dragdrop,
                is_resizeable,
                is_rotateable
            );
    });

    if ($(".view_tab").length > 0) {
        $("li.view_tab").first().trigger("click");
    }
}

$(document).on("change", ".upload_ndk_visu", function (event) {
    event.preventDefault();
    url = $(this).attr("data-action");
    el = $(this);
    bidon = false;
    if (
        window.File &&
        window.FileReader &&
        window.FileList &&
        window.Blob &&
        !bidon
    ) {
        var reader = new FileReader();
        el.parent().parent().append(ndkLoader);

        upFileType = "other";

        reader.onloadend = function () {
            extension = reader.result.split("base64,")[0]; //data:application/pdf;
            //array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
            if (
                extension.indexOf("jpg") > -1 ||
                extension.indexOf("png") > -1 ||
                extension.indexOf("png") > -1 ||
                extension.indexOf("gif") > -1 ||
                extension.indexOf("jpeg") > -1 ||
                extension.indexOf("pjpeg") > -1 ||
                extension.indexOf("x-png") > -1
            )
                upFileType = "image";

            dataToBeSent = reader.result.split("base64,")[1];
            var posting = $.post(url, { data: dataToBeSent });
            posting.done(function (data) {
                //console.log(baseUrl+data);
                el.parent().parent().find(".upload-error").remove();
                if (data == "Forbidden!") {
                    el.parent()
                        .parent()
                        .append(
                            '<p class="alert alert-danger upload-error">Bad extension</p>'
                        );
                    el.parent().parent().find(".remove-upload").trigger("click");
                } else {
                    //el.parent().hide();
                    if (upFileType == "image") {
                        el.parent()
                            .parent()
                            .find(".img-value:eq(0)")
                            .removeClass("hidden")
                            .attr("src", baseUrl + data)
                            .attr("data-src", baseUrl + data)
                            .attr("data-value", baseUrl + data)
                            .trigger("click");
                    } else {
                        el.parent()
                            .parent()
                            .find(".img-value:eq(0)")
                            .removeClass("hidden")
                            .attr(
                                "src",
                                baseUrl +
                                "modules/ndk_advanced_custom_fields/views/img/file_picto.png"
                            )
                            .attr("data-value", baseUrl + data)
                            .addClass("pictoFileUpload")
                            .trigger("click");
                    }
                    el.parent().parent().find(".remove-upload").show();
                }
                $("#ndkloader").fadeOut().remove();
            });
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        uploadForSafari(event, $(this));
    }
    killer = $(this).parent().parent().parent().attr("data-killer");
    if (parseFloat(killer) > 0) {
        $(".form-group[data-field='" + killer + "'] .doneOption").remove();
        $(".form-group[data-field='" + killer + "']").append(
            '<span class="doneOption"></span>'
        );
        $(".form-group[data-field='" + killer + "']")
            .find(".ndk-select")
            .val("Oui")
            .trigger("change");
    }
});

function uploadForSafari(event, input) {
    if (typeof uploadForSafari_Override == "function") {
        return uploadForSafari_Override(event, input);
    }

    url = input.attr("data-action");
    el = input;
    el.parent().parent().append(ndkLoader);

    //get selected file
    files = event.target.files;
    upFileType = "other";
    //form data check the above bullet for what it is
    var data = new FormData();

    //file data is presented as an array
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if (file.type.match("image.*")) {
            upFileType = "image";
        }
        data.append("file", file, file.name);
        data.append("safari", true);

        //create a new XMLHttpRequest
        var xhr = new XMLHttpRequest();

        //post file data for upload
        xhr.open("POST", url, true);
        xhr.send(data);
        xhr.onload = function () {
            //get response and show the uploading status
            var data = xhr.responseText;
            if (xhr.status === 200) {
                //console.log(baseUrl+data);
                el.parent().hide();
                if (upFileType == "image") {
                    el.parent()
                        .parent()
                        .find(".img-value:eq(0)")
                        .removeClass("hidden")
                        .attr("src", baseUrl + data)
                        .attr("data-src", baseUrl + data)
                        .attr("data-value", baseUrl + data)
                        .trigger("click");
                } else {
                    el.parent()
                        .parent()
                        .find(".img-value:eq(0)")
                        .removeClass("hidden")
                        .attr(
                            "src",
                            baseUrl +
                            "modules/ndk_advanced_custom_fields/views/img/file_picto.png"
                        )
                        .attr("data-value", baseUrl + data)
                        .addClass("pictoFileUpload")
                        .trigger("click");
                }
                el.parent().parent().find(".remove-upload").show();
                $("#ndkloader").fadeOut().remove();
            }
        };
    }

    upFileType = "other";
    var extension = input.val().split(".").pop().toLowerCase();
    if (
        extension.indexOf("jpg") > -1 ||
        extension.indexOf("png") > -1 ||
        extension.indexOf("png") > -1 ||
        extension.indexOf("gif") > -1 ||
        extension.indexOf("jpeg") > -1 ||
        extension.indexOf("pjpeg") > -1 ||
        extension.indexOf("x-png") > -1
    )
        upFileType = "image";
}

$(document).on("click", ".remove-upload", function (event) {
    event.preventDefault();
    group = $(this).parent().find(".img-value").attr("data-group");
    $(this).parent().find(".img-value").addClass("hidden");
    $(this).parent().parent().find(".uploader").show();
    $("input#ndkcsfield_" + group)
        .val("")
        .trigger("keyup");
    $("#visual_" + group).remove();
    $(this).hide();
    updatePriceNdk(0, group);
    $("#layer-edit-" + group).remove();
    killer = $(".form-group[data-field='" + group + "']").attr("data-killer");
    $(".form-group[data-field='" + killer + "']")
        .find(".ndk-select")
        .val("Non")
        .trigger("change");
    $(".form-group[data-field='" + killer + "'] .doneOption").remove();
});

$("tr.customization > .cart_quantity").html("");

/*$('.ndkzoom, #submitNdkcsfields, .view_tab').mouseover(function(){
        snapShot();
    });*/
/*$('.ndkzoom').mouseover(function(){
        snapShotLight();
    });*/

$(document).on("click", ".ndkzoom", function (e) {
    e.preventDefault();
    $(".resetZones").trigger("click");
    //$('.ndkzoom').attr('disabled', 'disabled').addClass('loadingButton');
    convertPercent();
    //$('.ndkzoom').attr('disabled', false).removeClass('loadingButton');
    if (!!$.prototype.fancybox) {
        $.fancybox.open(
            [
                {
                    type: "inline",
                    autoScale: false,
                    minHeight: 30,
                    width: "80%",
                    height: "80%",
                    showCloseButton: false,
                    autoDimensions: false,
                    content:
                        '<div class="popupPreviewContainer clear clearfix">' +
                        $("#image-block").html() +
                        "</div>",
                    beforeShow: function () {
                        $(
                            ".popupPreviewContainer .svggradient, .popupPreviewContainer .svgfilter"
                        ).remove();
                    },
                },
            ],
            {
                padding: 0,
            }
        );
    }

    /*
            $.ajax({
                        type: "GET",
                        url: baseUrl+'modules/ndk_advanced_custom_fields/showPreview.php',
                        data: {htmlNodes : $('#image-block').html()},
                        dataType: "html",
                        success: function(data) {
                            $(data).find('.svggradient, .svgfilter').remove();
                            if (!!$.prototype.fancybox)
                                    {
                                        $.fancybox.open([
                                        {
                                            type: 'inline',
                                            autoScale: false,
                                            minHeight: 30,
                                            width: '80%',
                                            height:'80%',
                                            showCloseButton: false,
                                            autoDimensions: false,
                                            content: '<div class="popupPreviewContainer clear clearfix">'+data+'</div>',
                                            beforeShow: function(){
                                                $('.popupPreviewContainer .svggradient, .popupPreviewContainer .svgfilter').remove();
                                            }
                                        }],
                                        {
                                            padding: 0
                                        });
                            }
                            $('.ndkzoom').attr('disabled', false).removeClass('loadingButton');
                        },
                        error: function(){
                            //alert('error handing here');
                        }
                    });
                    */
});

if (typeof is_visual != "undefined" && is_visual == true) {
    $(document).on("click", "#image-block", function (e) {
        e.preventDefault();
        $("#custom-block-popup").removeClass("opened");
        $("#custom-block-popup").removeClass("slideInLeft");
        return false;
    });
    $(document).on("click", ".js-qv-product-cover", function (e) {
        e.preventDefault();

        return false;
    });
}

$(document).on("clickVisualEffect", ".visual-effect", function () {
    visu = $(this).attr("data-src");
    group = $(this).attr("data-group");
    zindex = $(this).attr("data-zindex");
    dragdrop = $(this).attr("data-dragdrop");
    resizeable = $(this).attr("data-resizeable");
    rotateable = $(this).attr("data-rotateable");
    view = $(this).attr("data-view");
    coloreffect = "normal";
    background = "";
    type = false;
    if ($(this).hasClass("img-value")) {
        coloreffect = $(this).attr("data-blend");
        if (typeof $(this).attr("data-mask-image") != "undefined") {
            if ($(this).attr("data-mask-image") != "") {
                type = "colorize";
                if (!ndkBrowserVersion)
                    background = "url('" + $(this).attr("data-src") + "')";
                else background = $(this).attr("data-src");

                visu = $(this).attr("data-mask-image");
            }
        }
    }

    if ($(this).hasClass("color-ndk")) {
        type = "color";
        coloreffect = $(this).attr("data-blend");
        if ($(this).hasClass("colorize-ndk")) {
            type = "colorize";
            background = $(this).attr("data-color");
            //visu='';
        }

        if (typeof $(this).attr("data-mask-image") != "undefined") {
            if ($(this).attr("data-mask-image") != "") {
                type = "colorize";
                if (!ndkBrowserVersion) background = $(this).attr("data-color");
                else background = "url('" + $(this).attr("data-src") + "')";

                visu = $(this).attr("data-mask-image");
            }
        }
        //console.log(ndkBrowserVersion)
        if (background.indexOf("http") > -1)
            background = "url('" + background + "')";
    }

    if ($(this).hasClass("accessory-ndk")) {
        //group = group+'-'+$(this).attr('data-id-product-value');
        if (visu == 0) visu = $(this).find(".accessory-img-block img").attr("src");
        if ($(this).find(".ndk-accessory-quantity:eq(0)").val() > 0) {
            designCompo(
                visu,
                group,
                view,
                zindex,
                dragdrop,
                resizeable,
                rotateable,
                0,
                0,
                type,
                coloreffect,
                background
            );
        } else {
            $("#visual_" + group).remove();
            $("#layer-edit-" + group).remove();
            $(this).find(".selected-value").removeClass("selected-value");
        }
    } else if ($(this).hasClass("img-caracter")) {
        composeCaracter(
            visu,
            group,
            view,
            zindex,
            dragdrop,
            resizeable,
            rotateable,
            0,
            0,
            type,
            coloreffect,
            background,
            $(this).attr("data-type")
        );
    } else {
        designCompo(
            visu,
            group,
            view,
            zindex,
            dragdrop,
            resizeable,
            rotateable,
            0,
            0,
            type,
            coloreffect,
            background
        );
    }
});

$(document).on("change", ".visual-effect-select", function () {
    visu = $(this).find("option:selected").attr("data-src");
    group = $(this).find("option:selected").attr("data-group");
    zindex = $(this).find("option:selected").attr("data-zindex");
    dragdrop = $(this).find("option:selected").attr("data-dragdrop");
    resizeable = $(this).find("option:selected").attr("data-resizeable");
    rotateable = $(this).find("option:selected").attr("data-rotateable");

    view = $(this).find("option:selected").attr("data-view");
    designCompo(
        visu,
        group,
        view,
        zindex,
        dragdrop,
        resizeable,
        rotateable,
        0,
        0,
        false
    );
    if (typeof visu == "undefined") {
        $("#visual_" + group + ", #layer-edit-" + group).remove();
    }
});

$(document).on("click", ".ac_container .toggler.letOpen", function () {
    toggler = $(this);
    //$('.toggler').removeClass('active').find('.toggleText').html(toggleOpenText);
    thisFieldPane = $(this).parent().find(".fieldPane");
    //$('.fieldPane:visible').not(thisFieldPane).hide();
    $.when($(this).parent().find(".fieldPane").toggle()).then(function () {
        if ($(this).parent().find(".fieldPane").is(":visible")) {
            toggler.addClass("active");
            toggler.find(".toggleText").html(toggleCloseText);
        } else {
            toggler.removeClass("active");
            toggler.find(".toggleText").html(toggleOpenText);
        }
        scrollToNdk($(this).parent(), 800);
    });
});

if (letOpen == 0) {
    $(document).on("click", ".toggler", function () {
        toggler = $(this);
        //$('.toggler').removeClass('active').find('.toggleText').html(toggleOpenText);
        thisFieldPane = $(this).parent().find(".fieldPane");
        //$('.fieldPane:visible').not(thisFieldPane).hide();
        $.when($(this).parent().find(".fieldPane").toggle()).then(function () {
            if ($(this).parent().find(".fieldPane").is(":visible")) {
                toggler.addClass("active");
                toggler.find(".toggleText").html(toggleCloseText);
            } else {
                toggler.removeClass("active");
                toggler.find(".toggleText").html(toggleOpenText);
            }
            scrollToNdk($(this).parent(), 800);
            equalheight(".img-item-row");
        });
        $(".ndkQuickAccessBox-item").removeClass("active");
        $(
            ".ndkQuickAccessBox-item[data-target='" +
            $(this).parent().attr("data-field") +
            "']"
        ).addClass("active");
    });
} else {
    $(".toggler").addClass("letOpen");
}

$(document).on("click", ".color-ndk", function () {
    group = $(this).attr("data-group");
    $("#temporary-ndk-color-image-" + group).remove();
    view = $(this).attr("data-view");
    price = $(this).attr("data-price");
    if (!$(this).hasClass("color_square")) {
        updatePriceNdk(price, group);
        setImgValue($(this), group);
    }

    $(this).parent().find(".color-ndk").removeClass("selected-color");
    $(this).addClass("selected-color");
    /*si effet visuel et pas d image*/
    myel = $(this);
    if (
        $(this).attr("data-quantity-available") != "null" &&
        !$(this).hasClass("color_square")
    )
        updateQuantityForValue(myel.attr("data-quantity-available"), group);

    if (
        $(".zone_limit[data-group='" + group + "']").length > 0 &&
        $(".view_tab[data-view='" + view + "']").length > 0
    ) {
        container = ".zone_limit[data-group='" + group + "']";
    } else {
        container = "#image-block";
    }

    //console.log(container);

    if ($(this).hasClass("visual-effect") && $(this).attr("data-src") == "0") {
        if ($(this).attr("data-color").indexOf("url") > -1) {
            uri = $(this).attr("data-color").replace("url('", "").replace("')", "");
            $(this).attr("data-color", uri);
        } else {
            svg =
                '<svg xmlns="http://www.w3.org/2000/svg" width="' +
                $(container).width() +
                '" height="' +
                $(container).height() +
                '"><rect width="' +
                $(container).width() +
                '" height="' +
                $(container).height() +
                '" style="fill:rgb(' +
                hexToRgb($(this).attr("data-color")) +
                ');"></rect></svg>';
            uri = "data:image/svg+xml;base64," + btoa(svg);
        }
        myel.attr("data-src", uri).trigger("click");
    }
});

function hexToRgb(hex) {
    hex = hex.replace(/[^0-9A-F]/gi, "");
    var bigint = parseInt(hex, 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;

    return [r, g, b].join();
}

$(document).on("click, change", ".ndk-radio", function () {
    group = $(this).attr("data-group");
    checkedRadio = $(".ndk-radio[data-group='" + group + "']:checked");
    $(".form-group[data-field='" + group + "']")
        .find(".selected_radio")
        .removeClass("selected_radio");
    checkedRadio.parent().addClass("selected_radio");
    //checkFieldRestrictions(checkedRadio.attr('data-id-value'), group);
    price = checkedRadio.attr("data-price");
    updatePriceNdk(price, group, checkedRadio.attr("data-id-value"));
    if (checkedRadio.attr("data-quantity-available") != "null")
        updateQuantityForValue(checkedRadio.attr("data-quantity-available"), group);
});

$(document).on("change", ".ndk-select", function () {
    group = $(this).find("option:selected").attr("data-group");
    price = $(this).find("option:selected").attr("data-price");
    //checkFieldRestrictions($(this).find('option:selected').attr('data-id-value'), group);
    updatePriceNdk(
        price,
        group,
        $(this).find("option:selected").attr("data-id-value")
    );
    if ($(this).find("option:selected").attr("data-quantity-available") != "null")
        updateQuantityForValue(
            $(this).find("option:selected").attr("data-quantity-available"),
            group
        );
});

//$('.textarea').lettering();
$(document).on("change", ".arcText", function () {
    $texteditor = $(".textarea");
    val = $(this).val();
    if (val > 680) val = 10000;

    if (val < -680) val = -10000;

    $texteditor.circleType({ radius: val });
    ghoape(".textarea");
});

$(document).on("keyup", ".simpleText", function () {
    clearTimeout(typingTimer);
    val = $(this).val();
    $(this).attr("data-val", val);
    group = $(this).attr("data-group");
    unitprice = $(this).attr("data-price");
    ppcprice = $(this).attr("data-ppcprice");
    typingTimer = setTimeout(function () {
        if (ppcprice > 0) {
            charcount = val.replace(/\ |\n|\r|(\n\r)/g, "").length;
            price = ppcprice * charcount;
        } else {
            price = unitprice;
        }
        if (val == "") price = 0;

        updatePriceNdk(price, group);
    }, doneTypingInterval);
});

/*PArtie z-index à garer pour les calques
    $('.form-group').on('click, mouseover', function(){
        $('.form-group').removeClass('activeGroup');
        $(this).addClass('activeGroup');
        var others = $('.ui-wrapper');
        var others_img = $('.absolute-visu');
    
        others.each(function(){
            $(this).css('z-index', $(this).find('img').attr('data-zindex'));
        });
            others_img.each(function(){
            $(this).css('z-index', $(this).attr('data-zindex'));
        });	
        var key = $(this).attr('data-field');
        targetImg = $('#visual_'+key);
        if(targetImg.hasClass('dragdrop'))
            targetImg.parent().css('z-index', 99);
            else
            targetImg.css('z-index', 99);
        });
    
    $('#image-block').on('mouseleave', function(){
        resetZindex();
    });
    
    $('.submitContainer').on('click, mouseover', function(){
            resetZindex();
    });
    
    function resetZindex(){
    if (typeof(resetZindex_Override) == 'function') { 
        return resetZindex_Override();
    }
        $('.form-group').removeClass('activeGroup');
            var others = $('.ui-wrapper');
                var others_img = $('.absolute-visu');
        
            others.each(function(){
                $(this).css('z-index', $(this).find('img').attr('data-zindex'));
            });
                others_img.each(function(){
                $(this).css('z-index', $(this).attr('data-zindex'));
            });	
    }
    */

$(document).on("click", ".visible_layer", function () {
    hideLayer($(this));
});

$(document).on("click", ".hidden_layer", function () {
    showLayer($(this));
});

$(document).on("click", ".submitSimpleText", function () {
    group = $(this).parent().find(".visual-text").attr("data-group");
    zindex = $(this).parent().find(".visual-text").attr("data-zindex");
    price = $(this).parent().find(".visual-text").attr("data-price");
    ppcprice = $(this).parent().find(".visual-text").attr("data-ppcprice");
    view = $(this).parent().find(".visual-text").attr("data-view");
    dragdrop = $(this).parent().find(".visual-text").attr("data-dragdrop");
    resizeable = $(this).parent().find(".visual-text").attr("data-resizeable");
    rotateable = $(this).parent().find(".visual-text").attr("data-rotateable");
    charsCount = 0;

    if ($(this).parent().find("input.visual-text").length > 0) {
        texte = "";
        $(this)
            .parent()
            .find("input.visual-text")
            .each(function () {
                if ($(this).val() != "") {
                    texte += $(this).val() + " ";
                    charsCount += $(this)
                        .val()
                        .replace(/\ |\n|\r|(\n\r)/g, "").length;
                }
            });
    } else {
        texte = $(this).parent().find("textarea").text();
    }

    if (texte == "" || texte == " ") {
        price = 0;
        texte = "";
    } else if (ppcprice > 0) {
        price = ppcprice * charsCount;
    } else {
        price = $(this).parent().parent().find("textarea").attr("data-price");
    }

    if (texte == "" || texte == " ") {
        price = 0;
        texte = "";
    }
    height = $(this).parent().find(".visual-text").height();
    width = $(this).parent().find(".visual-text").width();
    updatePriceNdk(price, group);
    //$('.status_counter').hide();
    html2canvas($(this).parent().find(".visual-text"), {
        onrendered: function (canvas) {
            var dataURL = canvas.toDataURL("image/png");
            designCompo(
                dataURL,
                group,
                view,
                zindex,
                dragdrop,
                resizeable,
                rotateable,
                width,
                height,
                false
            );
        },
    });
});

function writeMyCanvas(group, width, height, texte) {
    if (typeof writeMyCanvas_Override == "function") {
        return writeMyCanvas_Override(group, width, height, texte);
    }
    var canvas = document.getElementById("myCanvas" + group);

    //If you really need to you can access the shadow inline SVG created by calling:

    //var context = canvas.getContext('2d');
    var context = new C2S(width, height);
    newWidth = width + 10;
    newHeight = height + 20;

    context.canvas.width = newWidth;
    context.canvas.height = newHeight;

    context.clearRect(0, 0, newWidth, newHeight);
    var x = newWidth / 1.9;
    var y = newHeight / 1.5;

    context.clearRect(0, 0, newWidth, newHeight);

    context.font = "bold 20px Helvetica";
    context.lineWidth = 3;
    context.textAlign = "center";

    context.fillStyle = "black";
    context.fillText(texte, x, y);

    //context.fill();
    //context.stroke();

    //serialize your SVG
    var svg = context.getSvg();
    var mySerializedSVG = context.getSerializedSvg(); //true here, if you need to convert named to numbered entities.
    return svg;
}

$(document).on(
    "click",
    ".fontSelectUl li, .fontColorSelectUl li, .fontSizeSelectUl li, .strokeColorSelectUl li, .alignSelector i, .effectButton",
    function () {
        input = $(this).parent().parent().parent().find(".ndktextarea");
        color = $(this)
            .parent()
            .parent()
            .find(".fontColorSelectUl li.active")
            .text();
        font = $(this).parent().parent().find(".fontSelectUl li.active").text();
        if ($(this).parent().parent().find(".noborder").length > 0) {
            texte = "";
            inputNumber = input.parent().find(".noborder").length;
            if (inputNumber > 1) separator = " \n ";
            else separator = "";

            $(this)
                .parent()
                .parent()
                .find(".noborder")
                .each(function () {
                    texte += $(this).val() + separator;
                    //$(this).trigger('keyup')
                });
        } else {
            texte = $(this).parent().parent().find(".ndktextarea").text();
        }
        applyTextAndFonts(texte, color, font, input);
        //input.val(texte).trigger('keyup');
        $(this).parent().parent().find(".noborder:eq(0)").trigger("keyup");
    }
);

function applyTextAndFonts(text, color, font, textarea) {
    if (typeof applyTextAndFonts_Override == "function") {
        return applyTextAndFonts_Override(text, color, font, textarea);
    }
    charsCount = texte.replace(/\ |\n|\r|(\n\r)/g, "").length;
    //sauvegarde typo
    if (charsCount > 0) {
        textarea.val(texte + " [" + color + " - " + font + "]").trigger("keyup");
    } else {
        textarea.val("");
    }
    textarea.trigger("ndkTextSet");
}

$(document).on("click", ".submitText", function () {
    group = $(this).parent().parent().find(".ndktextarea").attr("data-group");
    zindex = $(this).parent().parent().find(".textzone").attr("data-zindex");
    price = $(this).parent().parent().find(".ndktextarea").attr("data-price");
    ppcprice = $(this)
        .parent()
        .parent()
        .find(".ndktextarea")
        .attr("data-ppcprice");
    blend = $(this).parent().parent().find(".ndktextarea").attr("data-blend");

    view = $(this).parent().parent().find(".ndktextarea").attr("data-view");

    dragdrop = $(this)
        .parent()
        .parent()
        .find(".ndktextarea")
        .attr("data-dragdrop");
    resizeable = $(this)
        .parent()
        .parent()
        .find(".ndktextarea")
        .attr("data-resizeable");
    rotateable = $(this)
        .parent()
        .parent()
        .find(".ndktextarea")
        .attr("data-rotateable");
    coloreffect = $(this)
        .parent()
        .parent()
        .find(".ndktextarea")
        .attr("data-blend");
    svgPath = "";
    svgPath = $(this).parent().parent().find(".ndktextarea").attr("data-path");
    charsCount = 0;

    inputNumber = $(this).parent().find(".noborder").length;
    if (inputNumber > 1) separator = " \n ";
    else separator = "";

    if ($(this).parent().find(".noborder").length > 0) {
        texte = "";
        $(this)
            .parent()
            .find(".noborder")
            .each(function () {
                if ($(this).val() != "") {
                    texte += $(this).val() + separator;
                    charsCount += $(this)
                        .val()
                        .replace(/\ |\n|\r|(\n\r)/g, "").length;
                } else {
                    //$(this).css('height', 0);
                }
            });
    } else {
        texte = $(this).parent().find(".textarea").text();
    }

    if (texte == "" || texte == " ") {
        price = 0;
        texte = "";
    } else if (ppcprice > 0) {
        price = ppcprice * charsCount;
    } else {
        price = $(this).parent().parent().find(".ndktextarea").attr("data-price");
    }

    //$(this).parent().parent().find('.fontColorSelectUl li.active').trigger('click');
    //$(this).parent().parent().find('.ndktextarea').val(texte).trigger('keyup');
    input = $(this).parent().parent().find(".ndktextarea");
    color = $(this).parent().parent().find(".fontColorSelectUl li.active").text();
    font = $(this).parent().parent().find(".fontSelectUl li.active").text();
    applyTextAndFonts(texte, color, font, input);

    //texte = texte.replace('||', '');
    /*if(texte !='')
        $(this).parent().find('.fontSelectUl li.active').trigger('click');*/

    if (texte == "" || texte == " ") {
        price = 0;
        texte = "";
    }

    verticalPadding = 10;
    horizontalPadding = 0;
    $(this).parent().find(".status_counter").hide();
    $(this).parent().find(".noborder").css("position", "relative");
    height =
        $(this).parent().find(".textarea").innerHeight() -
        parseFloat(verticalPadding);
    width =
        $(this).parent().find(".textarea").innerWidth() -
        parseFloat(horizontalPadding) -
        scrollbarWidth;
    $(this).parent().find(".noborder").css("position", "");

    updatePriceNdk(price, group);
    $(".status_counter").hide();

    if (
        $(".zone_limit[data-group='" + group + "']").length > 0 &&
        $(".view_tab[data-view='" + view + "']").length > 0 &&
        $(this).parent().find("textarea.noborder").length > 0
    ) {
        container = ".zone_limit[data-group='" + group + "']";
        zwidth = $(container).width();
        zheight = $(container).height();
        if (zwidth > 0 && zheight > 0)
            $(this).parent().find(".textarea").css({ width: zwidth });
        height = zheight;
        width = zwidth;
    }

    svglines = "";
    fontSize = parseFloat($(this).parent().css("font-size"));
    alignment = $(this).parent().find(".texteditor").css("text-align");
    x = "0%";
    txtanchord = 'text-anchor="start"';
    startOffset = ' startOffset="50%"';

    if (alignment == "left") {
        x = "0%";
        txtanchord = 'text-anchor="start"';
        startOffset = ' startOffset="0%"';
    } else if (alignment == "center") {
        x = "50%";
        txtanchord = 'text-anchor="middle"';
        startOffset = ' startOffset="50%"';
    } else if (alignment == "right") {
        x = "100%";
        txtanchord = 'text-anchor="end"';
        startOffset = ' startOffset="100%"';
    }

    if (svgPath == 0) svgPath = "";

    if ($(this).parent().find("textarea.noborder").length > 0) {
        var lines = $(this).parent().find("textarea.noborder").val().split("\n");
        y = 1;
        onlyText = "";
        for (var i = 0; i < lines.length; i++) {
            if (svgPath != "") {
                svglines +=
                    '<textPath style="z-index:' +
                    zindex +
                    ';" ' +
                    txtanchord +
                    startOffset +
                    ' xlink:href="#' +
                    svgPath +
                    '">' +
                    lines[i] +
                    "</textPath>";
            } else {
                svglines +=
                    "<tspan " +
                    txtanchord +
                    ' x="' +
                    x +
                    '" y="' +
                    fontSize * y +
                    '">' +
                    lines[i].escape() +
                    "</tspan>";
            }
            y++;
        }
        textToWrite = $(this).parent().find("textarea.noborder").val();
    } else {
        textToWrite = "";
        y = 1;
        $(this)
            .parent()
            .find(".noborder")
            .each(function () {
                //textToWrite += $(this).val()+' '+'\n'+' ';
                if (svgPath != "") {
                    textToWrite +=
                        '<textPath style="z-index:' +
                        zindex +
                        ';" ' +
                        txtanchord +
                        startOffset +
                        '  xlink:href="#' +
                        svgPath +
                        '">' +
                        $(this).val() +
                        "</textPath>";
                    onlyText = $(this).val();
                } else {
                    //textToWrite +='<tspan '+txtanchord+' x="'+x+'" y="'+fontSize*y+'">'+$(this).val();+'</tspan>';
                    textToWrite += $(this).val() + "" + "\n" + "";
                }
                y++;
            });
    }

    style = $(this).parent().attr("style").replace('"', "'").replace('"', "'");

    fontSize = parseInt($(this).parent().find(".texteditor").css("font-size"));
    fontFamily = $(this).parent().find(".texteditor").css("font-family");
    fontFamily = fontFamily.replace('"', "'").replace('"', "'");
    var effect3d = false;

    var metalEffect = [];
    metalEffect["effect"] = "";
    metalEffect["fill"] = "";
    metalEffect["fillLight"] = "";
    metalEffect["fillShadow"] = "";

    //gold effect
    if ($(this).parent().find(".texteditor").css("color") == "rgb(255, 215, 0)") {
        effect3d = true;
        metalEffect = metaleffect(
            "#efd8a2",
            "#a28156",
            "#efd8a2",
            "#2f1f05",
            "#fff3c6",
            group,
            textToWrite,
            width,
            height,
            fontFamily,
            fontSize,
            effect3d,
            false
        );
    }

    //silver effect
    else if (
        $(this).parent().find(".texteditor").css("color") == "rgb(192, 192, 192)"
    ) {
        effect3d = true;
        metalEffect = metaleffect(
            "#888888",
            "#dedede",
            "#F5F5F5",
            "#444444",
            "#dedede",
            group,
            textToWrite,
            width,
            height,
            fontFamily,
            fontSize,
            effect3d,
            false
        );
    } else if (
        $(this).parent().find(".texteditor").attr("data-effect") == "concavMe"
    ) {
        color1 = $(this).parent().find(".texteditor").css("color");
        color2 = darkerColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        shadowcolor = darkerColor(
            $(this).parent().find(".texteditor").css("color"),
            0.4
        );
        lightcolor = lighterColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        strokecolor = lighterColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        effect3d = true;
        metalEffect = metaleffect(
            color2,
            color1,
            strokecolor,
            lightcolor,
            shadowcolor,
            group,
            textToWrite,
            width,
            height,
            fontFamily,
            fontSize,
            effect3d,
            false
        );
    } else if (
        $(this).parent().find(".texteditor").attr("data-effect") == "convexMe"
    ) {
        color1 = $(this).parent().find(".texteditor").css("color");
        color2 = lighterColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        shadowcolor = darkerColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        lightcolor = lighterColor(
            $(this).parent().find(".texteditor").css("color"),
            0.3
        );
        strokecolor = darkerColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        effect3d = true;
        metalEffect = metaleffect(
            color1,
            color2,
            strokecolor,
            shadowcolor,
            lightcolor,
            group,
            textToWrite,
            width,
            height,
            fontFamily,
            fontSize,
            effect3d,
            false
        );
    } else if ($(this).parent().find(".texteditor").attr("data-texture") != "") {
        color1 = $(this).parent().find(".texteditor").css("color");
        color2 = darkerColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        shadowcolor = darkerColor(
            $(this).parent().find(".texteditor").css("color"),
            0.4
        );
        lightcolor = lighterColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        strokecolor = lighterColor(
            $(this).parent().find(".texteditor").css("color"),
            0.2
        );
        effect3d = false;
        texture = $(this)
            .parent()
            .find(".texteditor")
            .attr("data-texture")
            .replace('url("', "")
            .replace('")', "");
        metalEffect = metaleffect(
            color2,
            color1,
            strokecolor,
            lightcolor,
            shadowcolor,
            group,
            textToWrite,
            width,
            height,
            fontFamily,
            fontSize,
            effect3d,
            texture
        );
    } else {
        color1 = $(this).parent().find(".texteditor").css("color");
        strokecolor = $(this).parent().find(".texteditor").attr("stroke-color");
        effect3d = false;
        metalEffect = metaleffect(
            color1,
            color1,
            strokecolor,
            "transparent",
            "transparent",
            group,
            textToWrite,
            width,
            height,
            fontFamily,
            fontSize,
            effect3d,
            false,
            true
        );
    }

    if (
        svgPath != "" &&
        typeof svgPath != "undefined" &&
        svgPath != 0 &&
        $(this).parent().parent().find(".ndktextarea").hasClass("visual-effect")
    ) {
        $("#svgText_" + group).remove();
        $("#svgUse_" + group).remove();

        $(
            ".ndk-svg-view:visible > svg > [data-group-text='" + group + "']"
        ).remove();
        writeCurve = $(".ndk-svg-view:visible > svg ").append(
            metalEffect["textPathEffect"] +
            '<text data-font-family="' +
            fontFamily +
            '" data-group-text="' +
            group +
            '" id="svgText_' +
            group +
            '" style="font-family:' +
            fontFamily +
            " ;font-size:" +
            fontSize +
            "px;fill:" +
            metalEffect["fill"] +
            ";z-index:" +
            zindex +
            ';" >' +
            textToWrite +
            "</text>"
        );

        $.when(writeCurve).then(function () {
            setTimeout(function () {
                $("#ndk-svg-view-" + view + " > svg").html(
                    $("#ndk-svg-view-" + view + " > svg").html()
                );
                $("#ndk-svg-view-" + view)
                    .css("z-index", zindex)
                    .css("mix-blend-mode", coloreffect);
            }, 500);
        });
    } else {
        if (
            $(this).parent().parent().find(".ndktextarea").hasClass("caracter-text")
        ) {
            composeCaracter(
                metalEffect["svg"],
                group,
                view,
                zindex,
                dragdrop,
                resizeable,
                rotateable,
                width,
                height,
                "svg",
                blend,
                "",
                "text"
            );
        } else {
            designCompo(
                metalEffect["svg"],
                group,
                view,
                zindex,
                dragdrop,
                resizeable,
                rotateable,
                width,
                height,
                "svg",
                blend
            );
        }
    }

    if (
        $(this).parent().find("textarea.noborder").length > 0 &&
        typeof textToWrite != "undefined" &&
        textToWrite != ""
    ) {
        svg_textMultiline(
            "svgText_" + group,
            textToWrite.replace(/\n/g, " ± "),
            width,
            fontSize,
            txtanchord,
            x
        );
        if (effect3d) {
            svg_textMultiline(
                "svgText_" + group + "-shadow",
                textToWrite.replace(/\n/g, " ± "),
                width,
                fontSize,
                txtanchord,
                x
            );
            svg_textMultiline(
                "svgText_" + group + "-light",
                textToWrite.replace(/\n/g, " ± "),
                width,
                fontSize,
                txtanchord,
                x
            );
        }
    }

    /*html2canvas($(this).parent().find('.textarea'), {
                onrendered: function(canvas) {
                        //var dataURL = canvas.toDataURL("image/png");
                        designCompo(canvas, group, view, zindex, dragdrop, resizeable, rotateable, width, height, 'canvas');
                        //mySvg = ctx.getSerializedSvg(true);
                        //console.log(mySvg);
                },
                    width: width,
                    height: height
            });*/

    //$(this).parent().parent().parent().find('.fontColorSelectUl li.active').trigger('click');
    //$(this).parent().find('.noborder').css('height', '');
});

//on keyup, start the countdown
var typingTimer;
var doneTypingInterval = 1000;
$(document).on("keyup", ".textarea", function () {
    clearTimeout(typingTimer);
    button = $(this).parent().parent().find(".submitText, .submitTextItem");
    typingTimer = setTimeout(function () {
        button.trigger("click");
    }, doneTypingInterval);
});

$(document).on("keyup", ".visual-text", function () {
    clearTimeout(typingTimer);
    button = $(this).parent().find(".submitSimpleText");
    typingTimer = setTimeout(function () {
        button.trigger("click");
    }, doneTypingInterval);
});

$(document).on("click", ".svg-container", function () {
    var group = $(this).parent().find("img").attr("data-group");
    zindex = $(this).parent().find("img").attr("data-zindex");
    price = $(this).parent().find("img").attr("data-price");
    view = $(this).parent().find("img").attr("data-view");
    blend = $(this).parent().find("img").attr("data-blend");
    dragdrop = $(this).parent().find("img").attr("data-dragdrop");
    resizeable = $(this).parent().find("img").attr("data-resizeable");
    rotateable = $(this).parent().find("img").attr("data-rotateable");
    if ($(".zone_limit[data-group='" + group + "']").length > 0) {
        width = $(".zone_limit[data-group='" + group + "']").innerWidth();
        height = $(".zone_limit[data-group='" + group + "']").innerHeight();
    } else {
        width = $("#image-block").innerWidth();
        height = $("#image-block").innerHeight();
    }
    updatePriceNdk(price, group);
    $(this).parent().parent().parent().find(".remove-img-item").show();
    if ($(this).parent().find("img").attr("data-quantity-available") != "null")
        updateQuantityForValue(
            $(this).parent().find("img").attr("data-quantity-available"),
            group
        );

    clonedSvg = $(this).find("svg").clone();

    if ($(this).parent().find("img.visual-effect").length > 0) {
        if ($(this).parent().find("img.visual-effect.img-caracter").length > 0) {
            textPath = false;
            if ($(this).find("svg").length > 0) {
                $(this)
                    .find("path")
                    .each(function () {
                        pathId = $(this)
                            .attr("id")
                            .replace("_" + group, "");
                        $(this).attr("id", group + "_" + pathId);
                        $("#text-item-" + group).attr("data-path", group + "_" + pathId);
                    });
                textPath = true;
            }
            composeCaracter(
                clonedSvg,
                group,
                view,
                zindex,
                dragdrop,
                resizeable,
                rotateable,
                width,
                height,
                "svg",
                blend,
                "",
                $(this).attr("data-type")
            );
            $("#textZone" + group)
                .find(".submitTextItem")
                .trigger("click");
            $("#textZone" + group)
                .find(".submitText")
                .trigger("click");
        } else {
            designCompo(
                clonedSvg,
                group,
                view,
                zindex,
                dragdrop,
                resizeable,
                rotateable,
                width,
                height,
                "svg",
                blend
            );
        }
    }
    setImgValue($(this).parent().find("img"), group);
    $(this)
        .parent()
        .parent()
        .parent()
        .find(".img-value")
        .removeClass("selected-value");
    $(this)
        .parent()
        .parent()
        .parent()
        .find(".svg-container")
        .removeClass("selected-svg");
    $(this).addClass("selected-svg");
    //$(this).css('width', '100%').css('height', '30px');
    //$('body').css('min-width', '').css('min-height', '');

    equalheight(".svg-container");
    $(".view_tab.activeView").trigger("click");

    applyTypeValue($(this).parent().find(".img-value"));
});

$(document).on("click", ".editIt", function () {
    isItem = false;
    el = $(this).parent();
    group = el.attr("id").replace("visual_", "");
    if (group.indexOf("-") > -1) {
        isItem = true;
        number = group.split("-")[1];
        itemGroup = group.split("-")[0];
        group = itemGroup;
    }

    $("#ndkcsfields .toggler").removeClass("active");
    if (parseInt(letOpen) == 0) $("#ndkcsfields .fieldPane").hide();

    formGroup = $(".form-group[data-field='" + group + "']");
    scrollToNdk(formGroup, 800);
    if (makeSlide == 1) {
        $(".sliderBlock .ndkackFieldItem").removeClass("activeItem");
        formGroup.addClass("activeItem");
    }

    formGroup.find(".toggler:not(.active)").trigger("click");
    if (isItem) {
        $("#toggler-" + group + "-" + parseInt(number))
            .not(".selected")
            .trigger("click");
        /*
                    formGroup.find('.designer-item').hide();
                    scrollToNdk(formGroup.find(".designer-item[data-number='"+number+"']"), 800);
                    $(".designer-item[data-number='"+number+"']").show();
    */
    }
});

$(document).on("click", ".view_tab", function () {
    $(".resetZones").trigger("click");
    scrollToNdk($(".primary_block"), 800);
    $("#image-block").find(".ndk-svg-view").hide();
    $("#image-block")
        .attr("data-view", $(this).attr("data-view"))
        .attr("data-id", $(this).attr("data-id"));

    $(".groupFieldBlock").css(
        "padding-bottom",
        $(".sliderBlock .ndkackFieldItem:visible:eq(0)").height()
    );

    if ($("#ndkcsfieldSVGView_" + $(this).attr("data-view")).length > 0) {
        svgImage = $("#ndkcsfieldSVGView_" + $(this).attr("data-view"))
            .find("image")
            .attr("xlink:href");
        $("#bigpic").attr("src", svgImage);

        if ($("#ndk-svg-view-" + $(this).attr("data-view")).length == 0) {
            $.when(
                $("#image-block").append(
                    '<div class="ndk-svg-view" id="ndk-svg-view-' +
                    $(this).attr("data-view") +
                    '">' +
                    $("#ndkcsfieldSVGView_" + $(this).attr("data-view")).html() +
                    "</div>"
                )
            ).done(function () {
                $("#ndk-svg-view-" + $(this).attr("data-view"))
                    .find("image")
                    .remove();
            });
        }
    }

    if ($("#ndk-svg-view-" + $(this).attr("data-view")).length > 0) {
        $("#ndk-svg-view-" + $(this).attr("data-view")).show();
    } else {
        $("#bigpic").attr("src", $(this).attr("data-img"));
    }

    $("#ndkcsfields-block .form-group").hide();
    $(
        ".form-group[data-view='" +
        $(this).attr("data-view") +
        "'], .form-group[data-view='0']"
    ).show();
    $(".form-group[data-view*='" + $(this).attr("data-view") + "|']").show();
    $(".form-group[data-view*='|" + $(this).attr("data-view") + "']").show();

    $(".editThisLayer").hide();
    $(
        ".editThisLayer[data-view='" +
        $(this).attr("data-view") +
        "'], .editThisLayer[data-view='0']"
    ).show();
    $(".editThisLayer[data-view*='" + $(this).attr("data-view") + "|']").show();
    $(".editThisLayer[data-view*='|" + $(this).attr("data-view") + "']").show();

    if (makeSlide == 1 && !$(this).hasClass("activeView")) {
        $(".ndkcfPagerItem").hide();
        $(
            ".ndkcfPagerItem[data-view='" +
            $(this).attr("data-view") +
            "'], .ndkcfPagerItem[data-view='0']"
        ).show();
        $(
            ".ndkcfPagerItem[data-view*='" + $(this).attr("data-view") + "|']"
        ).show();
        $(
            ".ndkcfPagerItem[data-view*='|" + $(this).attr("data-view") + "']"
        ).show();
        ndkCfShowSlide($(".sliderBlock .ndkackFieldItem:visible:eq(0)"));
    }
    $(".view_tab").removeClass("activeView").removeClass("btn-primary");
    $(this).addClass("activeView").addClass("btn-primary");
    //$( ".form-group[data-view$='"+$(this).attr('data-view')+"|']" ).show();

    $(".absolute-visu").hide();
    $(".zone_limit").hide();

    $(".absolute-visu.view-" + $(this).attr("data-view")).show();
    $(".zone_limit.view-" + $(this).attr("data-view")).show();

    $("[class*='" + $(this).attr("data-view") + "|']").show();
    $("[class*='|" + $(this).attr("data-view") + "']").show();

    $(".absolute-visu.view-0").show();
    $(".zone_limit.view-0").show();
    $(".layer_view").addClass("visible_layer").removeClass("hidden_layer");
});

$(document).on("keyup", '#ndkcsfields-block input[type="text"]', function () {
    if ($(this).attr("id") != "search_query_top") {
        if ($(this).val() != "") {
            $(this).attr("size", $(this).val().length + 5);
            $(this).parent().css("width", $(this).innerWidth);
        } else {
            $(this).attr("size", 15);
            $(this).parent().css("width", $(this).innerWidth);
        }
    }
});

/*$(document).on('mouseout', '.noborder', function() {
            $(this).blur();
        });*/

// The button to increment the product value
$(document).on("click", ".quantity-ndk-plus", function (e) {
    e.preventDefault();
    if (typeof $(this).attr("data-target-class") != "undefined")
        targetClass = "." + $(this).attr("data-target-class");
    else targetClass = ".ndk-accessory-quantity";

    input = $(this).parent().find(targetClass);

    if (typeof input.attr("step") != "undefined")
        step = parseFloat(input.attr("step"));
    else step = 1;

    currentVal = parseInt($(this).parent().find(targetClass).val());
    if ($(this).parent().find(targetClass).attr("data-qtty-available") > 0)
        quantityAvailableNdk = $(this)
            .parent()
            .find(targetClass)
            .attr("data-qtty-available");
    else quantityAvailableNdk = 100000000;

    if ($(this).parent().find(targetClass).attr("data-qtty-max") > 0)
        quantityMaxNdk = $(this).parent().find(targetClass).attr("data-qtty-max");
    else quantityMaxNdk = quantityAvailableNdk;

    if (
        !isNaN(currentVal) &&
        currentVal + step < quantityAvailableNdk &&
        currentVal < quantityMaxNdk
    )
        $(this)
            .parent()
            .find(targetClass)
            .val(currentVal + step)
            .trigger("keyup")
            .trigger("change");
    else
        $(this)
            .parent()
            .find(targetClass)
            .val(quantityMaxNdk)
            .trigger("keyup")
            .trigger("change");

    if (input.attr("data-step_quantity") != "") {
        stepQtty = input.attr("data-step_quantity").split(";").map(Number);
        //console.log(stepQtty);
        val = parseInt(input.val());

        if ($.inArray(val, stepQtty) == -1) {
            input
                .val(goToStepQuantity(val, stepQtty, "+"))
                .trigger("keyup")
                .trigger("change");
        }
    }
});

function goToStepQuantity(val, stepQtty, direction) {
    if (typeof goToStepQuantity_Override == "function") {
        return goToStepQuantity_Override(val, stepQtty, direction);
    }
    if (direction == "-") {
        last_encountred = 0;
        for (var i = 0; i < stepQtty.length; i++) {
            if (stepQtty[i] < val && stepQtty[i] >= last_encountred) {
                last_encountred = stepQtty[i];
                nextVal = stepQtty[i];
            }
        }
    } else {
        last_encountred = 99999999999999999;
        for (var i = 0; i < stepQtty.length; i++) {
            if (stepQtty[i] > val && stepQtty[i] <= last_encountred) {
                last_encountred = stepQtty[i];
                nextVal = stepQtty[i];
            }
        }
    }
    return nextVal;
}

// The button to decrement the product value
$(document).on("click", ".quantity-ndk-minus", function (e) {
    e.preventDefault();
    if (typeof $(this).attr("data-target-class") != "undefined")
        targetClass = "." + $(this).attr("data-target-class");
    else targetClass = ".ndk-accessory-quantity";

    input = $(this).parent().find(targetClass);

    if (typeof input.attr("step") != "undefined")
        step = parseFloat(input.attr("step"));
    else step = 1;

    currentVal = parseInt($(this).parent().find(targetClass).val());
    if ($(this).parent().find(targetClass).attr("data-qtty-min") > 0)
        quantityMinNdk = $(this)
            .parent()
            .find(".ndk-accessory-quantity")
            .attr("data-qtty-min");
    else quantityMinNdk = 0;

    //console.log(quantityMinNdk);
    //console.log(currentVal - step);
    if (input.attr("data-step_quantity") != "") {
        stepQtty = input.attr("data-step_quantity").split(";").map(Number);
        val = parseInt(input.val());
        input
            .val(goToStepQuantity(val, stepQtty, "-"))
            .trigger("keyup")
            .trigger("change");
    } else {
        if (!isNaN(currentVal) && currentVal - step > quantityMinNdk)
            $(this)
                .parent()
                .find(targetClass)
                .val(currentVal - parseFloat(step))
                .trigger("keyup")
                .trigger("change");
        else
            $(this)
                .parent()
                .find(targetClass)
                .val(quantityMinNdk)
                .trigger("keyup")
                .trigger("change");
    }
});

$(document).on("click", ".ndkcfTitle", function (event) {
    //$( this ).toggleClass('opened');
    nextElement = $(this)[0].nextSibling;

    $(nextElement).find(".toggler:eq(0)").trigger("click");
});

$(document).on("click", ".userPanelTitle", function (event) {
    $(".userPanel").toggle();
});

$(document).on(
    "change",
    ".ndk-accessory-quantity[data-step_quantity!='']",
    function (e) {
        if ($(this).attr("data-step_quantity") != "") {
            stepQtty = $(this).attr("data-step_quantity").split(";").map(Number);
            val = parseInt($(this).val());
            if ($.inArray(val, stepQtty) == -1) {
                $(this).val(goToStepQuantity(val, stepQtty, "+")).trigger("keyup");
            }
        }
    }
);

$(document).on("change", ".ndk-accessory-quantity", function (e) {
    setAccessoryCustomization($(this));
    $(this).trigger("keyup");
});

$(document).on("click", ".trigger-close-fancybox", function (e) {
    e.preventDefault();
    $(".fancybox-close").trigger("click");
});

function setAccessoryCustomization(input) {
    customizationBlock = $(
        "#accessory_customization_" + input.attr("data-id-value")
    );
    qtty_total = parseInt(input.val());
    id_product = input.attr("data-id-product-accessory");
    id_combination = input.attr("data-id_combination");
    attrName = input.attr("data-attr-lang");
    valueId = input.attr("data-id-value");
    cusCount = customizationBlock.find(".ndkackFieldItem").length;
    //$('.cloned_accessory_customization_'+input.attr('data-id-value')+'_'+id_combination).remove();

    customizationBlock
        .find(".required_field_back")
        .addClass("required_field")
        .removeClass("required_field_back");
    customizationBlock.find("textarea, input, select").addClass("dontSend");

    for (var i = 0; i < qtty_total; i++) {
        if (cusCount > 0) {
            newCustomizationBlock = customizationBlock.clone();
            group = newCustomizationBlock
                .find(".ndkackFieldItem:eq(0)")
                .attr("data-field");
            newGroup =
                group +
                "_" +
                input.attr("data-id-value") +
                "_" +
                id_combination +
                "-" +
                i;
            customAllDescendants(newCustomizationBlock, group, newGroup);

            if (window["fieldFonts_" + group]) {
                window["fieldFonts_" + newGroup] = window["fieldFonts_" + group];
                window["fieldColors_" + newGroup] = window["fieldColors_" + group];
                window["fieldSizes_" + newGroup] = window["fieldSizes_" + group];
                window["fieldEffects_" + newGroup] = window["fieldEffects_" + group];
                window["fieldAlignments_" + newGroup] =
                    window["fieldAlignments_" + group];
            }

            newCustomizationBlock
                .attr(
                    "id",
                    "accessory_customization_" +
                    input.attr("data-id-value") +
                    "_" +
                    id_combination +
                    "-" +
                    i
                )
                .attr(
                    "class",
                    "col-xs-12 cloned_accessory_customization_" +
                    input.attr("data-id-value") +
                    "_" +
                    id_combination +
                    " cloned_accessory_customization_" +
                    input.attr("data-id-value") +
                    " accessory_customization cloned_accessory_customization clearfix"
                )
                .removeClass("hidden");
            newCustomizationBlock
                .find(".form-group .toggler")
                .removeClass("toggler")
                .addClass("smallToggler");
            newCustomizationBlock.find(".toggleText:eq(0)").remove();

            oldTitle = newCustomizationBlock.find(".toggler_title").text();
            newCustomizationBlock
                .find(".toggler_title")
                .text(oldTitle + " " + attrName);
            newCustomizationBlock.find("textarea, input, select").each(function () {
                if (typeof $(this).attr("name") != "undefined")
                    if ($(this).attr("name").indexOf("ndkcsfield") > -1)
                        $(this).attr(
                            "name",
                            $(this).attr("name") +
                            "[accessory_customization][" +
                            valueId +
                            "|" +
                            id_product +
                            "|" +
                            id_combination +
                            "|" +
                            i +
                            "|" +
                            attrName +
                            "]"
                        );

                //ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][quantityProd][{$value.id|escape:'intval'}|{$value.id_product_value|escape:'intval'}|{$id_combination}]
            });
            newCustomizationBlock
                .find("textarea, input, select")
                .removeClass("dontSend");
            customizationBlock.parent().prepend(newCustomizationBlock);
            customizationBlock
                .find(".required_field")
                .addClass("required_field_back")
                .removeClass("required_field");
            newCustomizationBlock.find(".fieldPane").slideUp();
        }
    }

    attrBlockCount = $(
        ".cloned_accessory_customization_" +
        input.attr("data-id-value") +
        "_" +
        id_combination
    ).length;
    blockDiff = attrBlockCount - qtty_total;
    //console.log(blockDiff);
    if (blockDiff > 0) {
        for (var i = 0; i < blockDiff; i++) {
            $(
                "#accessory_customization_" +
                input.attr("data-id-value") +
                "_" +
                id_combination +
                "-" +
                i
            ).remove();
        }
    }

    prodBlockCount = $(
        ".cloned_accessory_customization_" + input.attr("data-id-value")
    ).length;
    //console.log(prodBlockCount);
    if (prodBlockCount > 0) {
        $("#t_a_c_" + input.attr("data-id-value")).show();
        if (prodBlockCount > 1)
            customizationBlock
                .parent()
                .find(".cloned_accessory_customization")
                .addClass("col-md-4");
        else
            customizationBlock
                .parent()
                .find(".cloned_accessory_customization")
                .removeClass("col-md-4");
    } else {
        $("#t_a_c_" + input.attr("data-id-value")).hide();
    }

    $(".textzone").each(function () {
        initText($(this));
    });
}

function customAllDescendants(node, group, newGroup) {
    node.find("*").each(function () {
        var child = $(this);
        customAllDescendants(child);
        if (typeof $(this).attr("data-group") != "undefined") {
            $(this).attr("data-group", newGroup);
        }
        if (typeof $(this).attr("data-field") != "undefined") {
            $(this).attr("data-field", newGroup);
        }
        if (typeof $(this).attr("id") != "undefined") {
            $(this).attr("id", $(this).attr("id").replace(group, newGroup));
        }
    });
}

$(document).on(
    "keyup",
    ".ndk-accessory-quantity[value!=0], .ndk-accessory-quantity-block .ndk-accessory-quantity[value!=0]",
    function (e) {
        rootBlock = $(
            ".form-group[data-field='" + $(this).attr("data-group") + "']"
        );
        qttyCheckNode = rootBlock;
        current_row = $(this).attr("data-id-product-accessory");
        //console.log(current_row);
        rowNode = $(
            ".form-group[data-field='" + $(this).attr("data-group") + "']"
        ).find(".accessory-ndk[data-id-product-value='" + current_row + "']");
        //console.log(rowNode)
        max = parseInt(rootBlock.attr("data-qtty-max"));
        if (max == 0) {
            max = parseInt(rowNode.attr("data-qtty-max"));
            //qttyCheckNode = rowNode;
        }

        max_weight = parseFloat(rootBlock.attr("data-weight-max"));
        if ((max_weight = 0)) {
            max_weight = parseInt(rowNode.attr("data-weight-max"));
            //qttyCheckNode = rowNode;
        }

        if (max == 0) max = 9999999999;

        if (max_weight == 0) max_weight = 9999999999;

        min = parseInt(rootBlock.attr("data-qtty-min"));
        if (min == 0) {
            min = parseInt(rowNode.attr("data-qtty-min"));
            //qttyCheckNode = rowNode;
        }

        min_weight = parseFloat(rootBlock.attr("data-weight-min"));
        if (min_weight == 0) {
            min_weight = parseInt(rowNode.attr("data-weight-min"));
            //qttyCheckNode = rowNode;
        }

        qtty_total = 0;
        weight_total = 0;

        rootBlock.find(".quantity_error_up").fadeOut().delay(10000);
        //console.log(min);

        qttyCheckNode.find(".ndk-accessory-quantity[value!=0]").each(function () {
            qtty_total += parseInt($(this).val());
            weight_total +=
                parseInt($(this).val()) * parseFloat($(this).attr("data-weight"));
        });

        weight_total = weight_total.toPrecision(3);
        //console.log(qtty_total);
        parentBlock = rootBlock.find(
            ".accessory-ndk[data-id-product-value='" +
            $(this).attr("data-id-product-accessory") +
            "']"
        );
        parentBlock.addClass("selected-product-accessory");

        id_product = $(this).attr("data-id-product-accessory");
        //$('#attribute_combination_'+id_product).trigger('change');
        //console.log(min);

        rootBlock.find(".total_weight").html(parseFloat(weight_total).toFixed(3));
        rootBlock.find(".total_qtty").html(parseInt(qtty_total));

        if (parseInt(qtty_total) >= parseInt(min)) {
            rootBlock
                .find(".quantity_error_down")
                .removeClass("required_field")
                .fadeOut();
        } else {
            if (min > 0)
                rootBlock.find(".quantity_error_down").addClass("required_field");
        }
        if (parseFloat(weight_total) >= parseFloat(min_weight)) {
            rootBlock
                .find(".weight_error_down")
                .removeClass("required_field")
                .fadeOut();
        } else {
            rootBlock.find(".weight_error_down").addClass("required_field");
        }

        cancelFieldRestrictions(
            $(this).attr("data-id-value"),
            $(this).attr("data-group")
        );

        if (parseInt(qtty_total) > parseInt(max)) {
            rootBlock.find(".quantity_error_up").show();
            $(this).parent().find(".quantity-ndk-minus").trigger("click");
        } else if (parseFloat(weight_total) > parseFloat(max_weight)) {
            rootBlock.find(".weight_error_up").show();
            $(this).parent().find(".quantity-ndk-minus").trigger("click");
        } else {
            //rootBlock.find('.quantity_error_up').hide();
            qtty = parseInt(this.value);
            /*if($(this).hasClass('ndk-accessory-comb-tab')){
                    qtty = 0;
                    otherInputs = $(this).parent().parent().parent().find('.ndk-accessory-comb-tab');
                    otherInputs.each(function(){
                        qtty += parseInt($(this).val());
                    });
                    //console.log(qtty);
                }*/

            var input = $(this);
            unitPrice = input.attr("data-price");
            group = input.attr("data-group");
            price = qtty * unitPrice;
            valueId = input.attr("data-value-id");

            name = $(this).attr("data-value");
            if ($(this).val() == 0) {
                //$('.disabled_value_by_'+group).removeClass('disabled_value_by_'+group).addClass('enabled_value_by_'+group);
                parentBlock.removeClass("selected-product-accessory");
            } else {
                checkFieldRestrictions(
                    $(this).attr("data-id-value") + "[" + qtty + "]",
                    group
                );
            }

            //$(this).parent().parent().find('.ndk_attribute_select').trigger('change');

            id_product = $(this).attr("data-id-product-accessory");
            id_combination = $(this).attr("data-id_combination");

            group = input.attr("data-group");
            if (input.hasClass("ndk-accessory-comb-tab")) {
                qtty_total = 0;
                //parentBlock = $(this).parent().parent().parent().parent().parent();
                parentBlock = $(".form-group[data-field='" + group + "']");
                if (input.hasClass("ndk-accessory-comb-tab"))
                    targets =
                        ".ndk-accessory-quantity[value!=0][data-id-value=" +
                        input.attr("data-id-value") +
                        "]";
                else targets = ".ndk-accessory-quantity[value!=0]";
                parentBlock.find(targets).each(function () {
                    qtty_total += parseInt($(this).val());
                });

                $("#ndkcf_totalprod_quantity_" + input.attr("data-id-value"))
                    .val(qtty_total)
                    .trigger("change");

                attrBlock = input.parent().parent().parent();
                qtty_total_attr = 0;
                attrBlock.find(".ndk-accessory-quantity[value!=0]").each(function () {
                    qtty_total_attr += parseInt($(this).val());
                });
                attrBlock.parent().find(".color_counter").html(qtty_total_attr);

                qtty = qtty_total;
                //qtty = input.val();
                //on cherche s'il y a des champs custom
                //setAccessoryCustomization(input, input.val());
                //FIN on cherche s'il y a des champs custom
            } else {
                qtty = $(this).val();
            }

            if ($(this).val() == 0) {
                updatePriceNdk(0, valueId);
            } else {
                if (!input.hasClass("price_overrided")) {
                    if (qtty > 0 && id_combination > 0)
                        loadAccessoryAttrPrice(
                            id_product,
                            id_combination,
                            qtty,
                            input,
                            true
                        );
                    else
                        loadAccessoryAttrPrice(
                            id_product,
                            id_combination,
                            qtty,
                            input,
                            true
                        );
                    //updatePriceNdk(price, valueId);
                } else {
                    loadAccessoryAttrPrice(
                        id_product,
                        id_combination,
                        qtty,
                        input,
                        false
                    );
                    qttyDiscount = getPriceDiscount(
                        $(this).attr("data-group"),
                        $(this).attr("data-id-value"),
                        qtty
                    );
                    price -= qttyDiscount;
                    updatePriceNdk(price, valueId);
                }
            }
        }
        var input = $(this);
        if (displayPriceHT == 1 && qtty > 0) {
            $(".final_price_" + input.attr("data-id-value") + " .priceht").remove();
            $(".final_price_" + input.attr("data-id-value")).append(
                '<span class="priceht clear clearfix"></span>'
            );
            getPriceHt(
                input.attr("data-price"),
                ".final_price_" + input.attr("data-id-value") + " .priceht"
            );
        }

        //console.log(qtty_total);
        if (this.value > 0)
            getSubValues(
                $(this).attr("data-id-value"),
                group,
                input.parent().parent().parent()
            );
        else {
            $.when(
                $("#sub-" + group + "-" + $(this).attr("data-id-value"))
                    .find(".ndk-accessory-quantity")
                    .val(0)
                    .trigger("keyup")
            ).done(function () {
                $("#sub-" + group + "-" + $(this).attr("data-id-value")).remove();
            });
        }
    }
);

function getPriceDiscount(group, value, qtty) {
    if (typeof getPriceDiscount_Override == "function") {
        return getPriceDiscount_Override(group, value, qtty);
    }
    qtty_wanted = $("#quantity_wanted").val();
    discount = 0;
    if (typeof ndkSpecificPrices[group] != "undefined") {
        for (i = 0; i < ndkSpecificPrices[group].length; i++) {
            row = ndkSpecificPrices[group][i];
            if (
                row.id_ndk_customization_field_value == value &&
                qtty * qtty_wanted >= row.from_quantity
            ) {
                if (row.reduction_type == "amount") {
                    mydiscount = parseFloat(row.reduction);
                } else if (row.reduction_type == "percent") {
                    mydiscount = price * (row.reduction / 100);
                }
                if (mydiscount > discount) discount = mydiscount;
            }
        }
    }
    return discount;
}

$(document).on("change", ".ndk-checkbox", function (e) {
    qtty = 1;
    unitPrice = $(this).attr("data-price");
    group = $(this).attr("data-group");
    price = qtty * unitPrice;
    valueId = $(this).attr("data-value-id");
    name = $(this).attr("data-value");
    if (!$(this).is(":checked")) {
        $(".disabled_value_by_" + group)
            .removeClass("disabled_value_by_" + group)
            .addClass("enabled_value_by_" + group);
        updatePriceNdk(0, valueId);
    } else {
        checkFieldRestrictions($(this).attr("data-value-id"), group);
        updatePriceNdk(price, valueId);
    }
});

//  $(document).on("click", ".img-value", function () {
//    var group = $(this).attr("data-group");
//    price = $(this).attr("data-price");
//    updatePriceNdk(price, group);
//    if ($(this).attr("data-quantity-available") != "null")
//      updateQuantityForValue($(this).attr("data-quantity-available"), group);
//    setImgValue($(this), group);
//    $(this).parent().parent().parent().find(".remove-img-item").show();
//    applyTypeValue($(this));
//  });

/////////////////////////////////////
$(document).on("click", ".img-select-container", function () {
    var img = $(this).find('.img-value');
    var group = $(img).attr("data-group");
    price = $(img).attr("data-price");
    updatePriceNdk(price, group);
    if ($(img).attr("data-quantity-available") != "null")
        updateQuantityForValue($(img).attr("data-quantity-available"), group);
    setImgValue($(img), group);
    $(img).parent().parent().parent().find(".remove-img-item").show();
    applyTypeValue($(img));
});
/////////////////////////////////////

function applyTypeValue(el) {
    var group = el.attr("data-group");
    type = el.parent().attr("data-type");
    if (typeof type != "undefined") {
        $(
            "#item-" +
            group +
            ' .group-type[data-group-type="' +
            type +
            '"] .group-value'
        ).html(" : " + el.attr("title"));
        $(
            "#main-" +
            group +
            ' .group-type[data-group-type="' +
            type +
            '"] .group-value'
        ).html(" : " + el.attr("title"));
        $(
            "#item-" +
            group +
            ' .group-type[data-group-type="' +
            type +
            '"] .group_value_input'
        ).val(el.attr("title"));
        $(
            "#main-" +
            group +
            ' .group-type[data-group-type="' +
            type +
            '"] .group_value_input'
        ).val(el.attr("title"));
        el.trigger("typeValueSet");
    }
}
function strpos(haystack, needle, offset) {
    var i = (haystack + "").indexOf(needle, offset || 0);
    return i === -1 ? false : i;
}

function checkEmptyForm(event) {
    if (typeof checkEmptyForm_Override == "function") {
        return checkEmptyForm_Override(event);
    }
    event = event || false;
    emptyForm = true;
    $i = 0;
    $("*[name^='ndkcsfield[']").each(function () {
        rootBlock = "";
        if (
            $(this).hasClass("quantity_error_down") ||
            $(this).hasClass("quantity_error_up")
        ) {
            group = $(this).parent().parent().parent().attr("data-field");
            val = $(this).attr("val");
        } else {
            if (typeof $(this).attr("data-name") != "undefined")
                group = $(this).attr("data-name").split("ndkcsfield[");
            else group = $(this).attr("name").split("ndkcsfield[");

            group = group[1].split("]");
            group = group[0];

            val = $(this).val();
        }

        rootBlock = $(
            ".form-group[data-field='" + group + "']:not(.submitContainer)"
        );
        if ($(this).is(":radio")) {
            $k = 1;
            others = rootBlock.find('input[type="radio"]');
            if ($(this).is(":checked")) {
                for (var j = 0; j < others.length; j++) {
                    $i++;
                    $k++;
                }
            }
        } else if ($(this).is(":checkbox")) {
            $k = 0;
            group = $(this).attr("data-group");
            others = rootBlock.find('input[type="checkbox"]');
            if ($(this).is(":checked")) {
                for (var j = 0; j < others.length; j++) {
                    $i++;
                    $k++;
                }
            }
        } else {
            if (val == "" || val.slice(-2) == ": ") {
            } else {
                $i++;
            }
        }
    });
    //console.log($i);

    if ($i == 0) {
        if (event) event.preventDefault();

        $(
            "#add-to-cart-or-refresh .add-to-cart, #add_to_cart .exclusive, #add_to_cart button"
        )
            .prop("disabled", false)
            .trigger("click");
        return false;
    } else {
        return true;
    }
}

$.fn.ndkSubmit = function (event) {
    if (typeof $.fn.ndkSubmit_Override == "function") {
        return $.fn.ndkSubmit_Override(event);
    }
    if (checked) {
        var checked = false;
        return true;
    }
    /* 
            call : $('#form').ndkSubmit(event);
            stop form from submitting normally */
    $("html, body").animate({ scrollTop: 0 }, "fast");
    var $form = $(this);
    var required = $(".form-group:not([class*='disabled_value_by'])").find(
        ".required_field"
    );

    /*check required fields*/
    $i = 0;
    required.each(function () {
        rootBlock = "";
        if (
            $(this).hasClass("quantity_error_down") ||
            $(this).hasClass("quantity_error_up")
        ) {
            group = $(this).parent().parent().parent().attr("data-field");
            val = $(this).attr("val");
        } else if ($(this).hasClass("group_value_input")) {
            group = $(this).attr("data-group");
            val = $(this).val();
        } else {
            if (typeof $(this).attr("data-name") != "undefined")
                group = $(this).attr("data-name").split("ndkcsfield[");
            else group = $(this).attr("name").split("ndkcsfield[");

            group = group[1].split("]");
            group = group[0];

            val = $(this).val();
        }

        rootBlock = $(
            ".form-group[data-field='" + group + "']:not(.submitContainer)"
        );
        if ($(this).is(":radio")) {
            $k = 1;
            rootBlock.find(".error").remove();
            others = rootBlock.find('input[type="radio"]');
            if ($(this).is(":checked")) {
                for (var j = 0; j < others.length; j++) {
                    $i += 1;
                    $k += 1;
                }
                rootBlock.removeClass("focusRequired");
                rootBlock.find(".error").remove();
            } else {
                c = rootBlock.find('input[type="radio"]:checked').length;
                if (c < 1) {
                    rootBlock.addClass("focusRequired").focus();
                    rootBlock.find(".error").remove();

                    //$(".view_tab[data-view='"+rootBlock.attr('data-view')+"']").trigger('click');
                    if (makeSlide == 1) {
                        ndkCfShowSlide(rootBlock);
                    }
                    scrollToNdk($(this), 800, true);

                    if (rootBlock.parent().hasClass("groupFieldBlock")) {
                        targetButtonPack = $(
                            ".toggleGroupField[target='#" +
                            rootBlock.parent().attr("id") +
                            "']"
                        );
                        targetButtonPack.trigger("click");
                    }

                    if (
                        typeof $(this).attr("data-message") != "undefined" &&
                        $(this).attr("data-message") != "undefined"
                    )
                        rootBlock
                            .find(".fieldPane")
                            .append(
                                '<span class="error alert-danger clear clearfix">' +
                                $(this).attr("data-message") +
                                "</span>"
                            );
                } else {
                    rootBlock.removeClass("focusRequired");
                    rootBlock.find(".error").remove();
                }
            }
        } else if ($(this).is(":checkbox")) {
            $k = 0;
            group = $(this).attr("data-group");
            rootBlock.find(".error").remove();
            others = rootBlock.find('input[type="checkbox"]');
            if ($(this).is(":checked")) {
                for (var j = 0; j < others.length; j++) {
                    $i++;
                    $k++;
                }
                rootBlock.removeClass("focusRequired");
                rootBlock.find(".error").remove();
            } else {
                if ($k < others.length) {
                    //console.log($k);
                    rootBlock.addClass("focusRequired").focus();

                    //$(".view_tab[data-view='"+rootBlock.attr('data-view')+"']").trigger('click');
                    if (makeSlide == 1) {
                        ndkCfShowSlide(rootBlock);
                    }
                    scrollToNdk($(this), 800, true);

                    if (rootBlock.parent().hasClass("groupFieldBlock")) {
                        targetButtonPack = $(
                            ".toggleGroupField[target='#" +
                            rootBlock.parent().attr("id") +
                            "']"
                        );
                        targetButtonPack.trigger("click");
                    }

                    if (
                        typeof $(this).attr("data-message") != "undefined" &&
                        $(this).attr("data-message") != "undefined"
                    )
                        rootBlock
                            .find(".fieldPane")
                            .append(
                                '<span class="error alert-danger clear clearfix">' +
                                $(this).attr("data-message") +
                                "</span>"
                            );
                }
            }
        } else {
            if (val == "" || val.slice(-2) == ": ") {
                rootBlock.addClass("focusRequired").focus();
                //$(this).parent().find('span').css('color', 'red').focus();
                rootBlock.find(".fieldPane").show().find(".error").remove();
                $(".view_tab[data-view='" + rootBlock.attr("data-view") + "']").trigger(
                    "click"
                );
                if (makeSlide == 1) {
                    ndkCfShowSlide(rootBlock);
                }
                scrollToNdk($(this), 800, true);

                if (rootBlock.parent().hasClass("groupFieldBlock")) {
                    targetButtonPack = $(
                        ".toggleGroupField[target='#" + rootBlock.parent().attr("id") + "']"
                    );
                    targetButtonPack.trigger("click");
                }

                if (
                    typeof $(this).attr("data-message") != "undefined" &&
                    $(this).attr("data-message") != "undefined"
                )
                    rootBlock
                        .find(".fieldPane")
                        .append(
                            '<span class="error alert-danger clear clearfix">' +
                            $(this).attr("data-message") +
                            "</span>"
                        );
                if ($(this).is("p")) $(this).show();

                if (rootBlock.parent().parent().hasClass("ac_container")) {
                    $("#" + rootBlock.parent().parent().attr("data-button-id")).trigger(
                        "click"
                    );
                    rootBlock.parent().parent().find(".fieldPane").show();
                }
            } else {
                $i++;
                rootBlock.removeClass("focusRequired");
                rootBlock.find(".error").remove();
            }
        }
    });
    //console.log($i);
    //console.log(required.length);
    $(document).trigger("ndk_sumbit_form_checked");

    if ($i >= required.length) {
        if (checked) {
            $(".popup_required").removeClass("focusOnMe");
            return checkEmptyForm(event);
        } else {
            checkRecommends($form, event);
        }
    } else {
        event.preventDefault();
        checked = false;
        $(".popup_required").addClass("focusOnMe");
        return false;
    }
};

$(document).on("click", ".popup_required", function () {
    $('[data-target="#ndkacf-modal"]').trigger("click");
});

function setRecommends() {
    if (typeof setRecommends_Override == "function") {
        return setRecommends_Override();
    }
    for (var i = 0; i < recommended.length; i++) {
        //$('#ndkcsfield_'+recommended[i]).addClass('recommended_field');
        $(".form-group[data-field='" + recommended[i] + "']").addClass(
            "recommended_field"
        );
        $(".form-group[data-field='" + recommended[i] + "']")
            .find(
                "input:not([name*='ndkcsfieldPdf']):not('.noborder'),textarea:not([name*='ndkcsfieldPdf']), select:not(.ndk_tag_selector)"
            )
            .attr("data-group-recommend", recommended[i]);
    }
}

function checkRecommends($form, event) {
    if (typeof checkRecommends_Override == "function") {
        return checkRecommends_Override($form, event);
    }

    recommend_list = [];
    var required = $(
        ".form-group.recommended_field:not([class*='disabled_value_by'])"
    ).find(
        "input:not(.ndk_tag_selector, .dontCare),textarea:not(.ndk_tag_selector), select:not(.ndk_tag_selector)"
    );

    /*check required fields*/
    $i = 0;
    required.each(function () {
        group = $(this).attr("data-group-recommend");
        val = $(this).val();
        rootBlock = $(".form-group[data-field='" + group + "']");
        groupTitleEl = rootBlock.find("label:eq(0)").clone();
        groupTitleEl.find(".toggleText").remove();
        groupTitle = groupTitleEl.text();

        if (typeof group == "undefined") {
            $i++;
        } else if ($(this).is(":radio")) {
            $k = 1;
            others = rootBlock.find('input[type="radio"]');
            //console.log(others.length);
            if ($(this).is(":checked")) {
                for (var j = 0; j < others.length; j++) {
                    $i++;
                    $k++;
                }
                //recommend_list.push($(this).parent().parent().parent().find('label:eq(0)').text());
            } else {
                if ($k < others.length) {
                    recommend_list[group] = groupTitle;
                }
            }
        } else if ($(this).is(":checkbox")) {
            $k = 0;
            others = rootBlock.find('input[type="checkbox"]');
            //console.log(others.length);
            if ($(this).is(":checked")) {
                for (var j = 0; j < others.length; j++) {
                    $i++;
                    $k++;
                }
                //recommend_list.push($(this).parent().parent().parent().find('label:eq(0)').text());
            } else {
                if ($k < others.length) {
                    recommend_list[group] = groupTitle;
                }
            }
        } else if ($(this).is(".ndk-accessory-quantity")) {
            $k = 1;
            others = rootBlock.find(".ndk-accessory-quantity[value!=0]");
            //console.log(others.length);
            if (parseFloat(val) > 0) {
                for (var j = 0; j < others.length; j++) {
                    $i++;
                    $k++;
                }
                //recommend_list.push($(this).parent().parent().parent().find('label:eq(0)').text());
            } else {
                if ($k < others.length) {
                    recommend_list[group] = groupTitle;
                }
            }
        } else {
            if (val == "") {
                recommend_list[group] = groupTitle;
            } else {
                $i++;
            }
        }
    });

    if ($i >= required.length) {
        checked = true;
        return checkEmptyForm(event);
    } else {
        event.preventDefault();
        ret = false;

        $("#recommends_list").html("");
        //recommend_list = $.unique(recommend_list);

        //console.log(recommend_list);
        var idGroupR;
        for (idGroupR in recommend_list) {
            if (recommend_list[idGroupR] != "")
                $("#recommends_list").append(
                    '<li id="showRecommendItem_' +
                    idGroupR +
                    '"><span class="showRecommendItem  btn-default btn-primary">' +
                    recommend_list[idGroupR] +
                    "</span></li>"
                );
        }
        /*for(var i = 0;i < recommend_list.length;i++){
                            $('#recommends_list').append('<li>'+recommend_list[i]+'</li>');
                }*/

        $.fancybox("#confirm_recommends", {
            modal: true,

            afterShow: function () {
                $(".confirm_recommends").on("click", function (event) {
                    if ($(event.target).is(".yes")) {
                        checked = true;
                        //$('#submitNdkcsfields').click();
                        if (checkEmptyForm(event)) $("#ndkcsfields").trigger("submit");

                        $.fancybox.close();
                    } else {
                        checked = false;
                        $.fancybox.close();
                    }
                });
            },
            afterClose: function () { },
        });
    }
}

$(document).on("click", ".showRecommendItem", function () {
    isItem = false;
    el = $(this).parent();
    group = el.attr("id").replace("showRecommendItem_", "");
    if (group.indexOf("-") > -1) {
        isItem = true;
        number = group.split("-")[1];
        itemGroup = group.split("-")[0];
        group = itemGroup;
    }

    $("#ndkcsfields .toggler").removeClass("active");
    if (parseInt(letOpen) == 0) $("#ndkcsfields .fieldPane").hide();

    formGroup = $(".form-group[data-field='" + group + "']");
    id_product_pack = formGroup.parent().attr("id");

    if (formGroup.parent().hasClass("groupFieldBlock")) {
        targetButtonPack = $(
            ".toggleGroupField.closed[target='#" +
            formGroup.parent().attr("id") +
            "']"
        );
        targetButtonPack.trigger("click");
    }
    $(
        ".view_tab[data-view='" +
        formGroup.attr("data-view") +
        "']:not(.activeView)"
    ).trigger("click");
    scrollToNdk(formGroup, 800, true);
    if (makeSlide == 1) {
        $(".sliderBlock .ndkackFieldItem").removeClass("activeItem");
        formGroup.addClass("activeItem");
    }
    $.fancybox.close();
    checked = false;
    formGroup.find(".toggler:not(.active)").trigger("click");
    scrollToNdk(formGroup.find(".toggler"), 800, true);

    if (isItem) {
        scrollToNdk(
            formGroup.find(".designer-item[data-number='" + number + "']"),
            800,
            true
        );
        $(".designer-item[data-number='" + number + "']").show();
    }
});

function checkCustomizations() {
    if (typeof checkCustomizations_Override == "function") {
        return checkCustomizations_Override();
    }
    return true;
}

function resizeInput() {
    if (typeof resizeInput_Override == "function") {
        return resizeInput_Override();
    }
    if ($(this).attr("id") != "search_query_top") {
        $(this).attr("size", $(this).val().length + 5);
        $(this).parent().css("width", $(this).innerWidth);
    }
}

var ghoape = function getHeightOfAbsolutelyPositionedElement(element) {
    if (typeof getHeightOfAbsolutelyPositionedElement_Override == "function") {
        return getHeightOfAbsolutelyPositionedElement_Override(element);
    }

    var max_y = 0;
    var max_x = 0;
    var dimensions = [];
    $.each($(element).find("*"), function (idx, desc) {
        max_y = Math.max(max_y, $(desc).offset().top + $(desc).height());
        max_x = Math.max(max_x, $(desc).offset().left + $(desc).width());
    });
    dimensions["y"] = max_y - $(element).offset().top;
    dimensions["x"] = max_x - $(element).offset().left;
    $(element)
        .css("width", dimensions["x"])
        .css("height", dimensions["y"])
        .css("position", "unset");
    return dimensions;
};

function preload(arrayOfImages) {
    if (typeof preload_Override == "function") {
        return preload_Override(arrayOfImages);
    }
    $(arrayOfImages).each(function () {
        $("<img/>")[0].src = this;
    });
}

$(document).on("click", ".colorize_svg li", function () {
    root = $(this).parent().parent().parent().parent().parent();
    color = $(this).find("span").text();
    $(this).parent().find("li").removeClass("selected");
    $(this).addClass("selected");
    $(this).parent().parent().find(".index-value").html($(this).html());
    root.find(".replaced-svg").each(function () {
        $(this).attr("fill", color);
        var target = $(this).parent();
        if ($(this).parent().hasClass("selected-svg")) {
            $.when(target.parent().parent().trigger("click")).done(function () {
                target.trigger("click");
            });
        }
    });
});

$.fn.setNdkSelector = function () {
    visible = false;
    var ul = $(this).find("ul");
    var firstLi = ul.find("li").first();
    if ($(this).find(".index-value").length == 0)
        $(this).prepend('<div class="index-value"></div>');

    var selectedLi = ul.find("li.selected");
    if (selectedLi.length) {
        //index.remove();
        $(this).find(".index-value").html(selectedLi.html());
    } else {
        $(this).find(".index-value").html(firstLi.html());
    }

    if (ul.find("li").length < 2) $(this).parent().hide();

    ul.hide();
    $(this).click(function () {
        if (visible) return;

        ul.show("fast", function () {
            visible = true;
        });

        var selectedLi = ul.find("li.selected");
        if (selectedLi.length) {
            //index.remove();
            $(this).find(".index-value").html(selectedLi.html());
        }
    });
    $("html").click(function () {
        if (visible) {
            $(".ndk_selector")
                .find("ul")
                .hide("fast", function () {
                    visible = false;
                });
        }
    });
};

function setTags_Override__() { }
function setTags() {
    if (typeof setTags_Override == "function") {
        return setTags_Override();
    }
    //console.log("tagging");
    $(".tag-selector-container").remove();

    $(".tagged").each(function () {
        fullClass = $(this).attr("class");
        $(this).attr(
            "class",
            "filterTag " +
            fullClass
                .replace(/[!\"#$%&'\(\)\*\+,\.\/:;<=>\?\@\[\\\]\^`\{\|\}~]/g, "")
                .toLowerCase()
        );

        tags = $(this).attr("data-tags");
        tags = tags.split("|");
        rootBlock = $(this).attr("data-group");
        for (var i = 0; i < tags.length; i++) {
            if (
                tags[i] != "" &&
                $.inArray(tags[i] + "|" + rootBlock, filtersTags) == -1
            ) {
                filtersTags.push(tags[i] + "|" + rootBlock);
            }
        }
    });

    //on créé le block de tags
    encountred = [];
    for (var i = 0; i < filtersTags.length; i++) {
        value = filtersTags[i].split("|");
        if ($.inArray(value[1], encountred) == -1) {
            mySelector = $("#main-" + value[1])
                .find(".visu-tools")
                .prepend(
                    '<div id="tag-selector-container-' +
                    value[1] +
                    '" class="tag-selector-container"><p class="clear clearfix" style="display:none"><label>' +
                    filterText +
                    '</label></p><select data-placeholder="' +
                    filterText +
                    '" data-group-target="' +
                    value[1] +
                    '" id="tag-select-' +
                    value[1] +
                    '" class=" ndk_tag_selector" multiple="multiple"><option selected="selected" disabled>' +
                    filterText +
                    '</option><option value="all">' +
                    allText +
                    "</option></select></div>"
                );
            encountred.push(value[1]);
        }
    }

    for (var i = 0; i < filtersTags.length; i++) {
        value = filtersTags[i].split("|");
        properValue = value[0].replace(
            /[!\"#$%&'\(\)\*\+,\.\/:;<=>\?\@\[\\\]\^`\{\|\}~]/g,
            ""
        );
        //console.log(properValue.replace(/ /g,"-").toLowerCase())
        $("#tag-select-" + value[1]).append(
            '<option value="' +
            properValue.replace(/ /g, "-").toLowerCase() +
            '">' +
            value[0] +
            "</option>"
        );
    }

    $(".ndk_tag_selector").each(function () {
        group = $(this).attr("data-group-target");
        if ($(this).find("option").length < 4)
            $("#tag-selector-container-" + group).remove();
    });

    $(".ndk_tag_selector").chosen("destroy");
    $(".ndk_tag_selector").chosen({ disable_search_threshold: 10 });

    $(document).on("change", ".ndk_tag_selector", function () {
        group = $(this).attr("data-group-target");
        val = $(this).val();
        if (val === undefined || val === null || val == "") {
            val = ["all"];
            //$(this).val('all').trigger('change')
        }

        $(".form-group[data-field='" + group + "']")
            .find(".filterTag")
            .hide();
        for (var i = 0; i < val.length; i++) {
            if (val[i] == "all") {
                $(".form-group[data-field='" + group + "']")
                    .find(".filterTag")
                    .show();
            } else {
                $(".form-group[data-field='" + group + "']")
                    .find("." + val[i])
                    .show();
            }
        }
    });
}

equalheight = function (container) {
    var currentTallest = 0,
        currentRowStart = 0,
        rowDivs = new Array(),
        $el,
        topPosition = 0;
    $(container).each(function () {
        $el = $(this);
        $el.height("");
        topPostion = $el.position().top;
        rowDivs.push($el);
        currentTallest =
            currentTallest < $el.height() ? $el.height() : currentTallest;
        //  for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
        //    rowDivs[currentDiv].height(currentTallest);
        //  }
    });
};

equalheightbyRow = function (container) {
    var currentTallest = 0,
        currentRowStart = 0,
        rowDivs = new Array(),
        $el,
        topPosition = 0;
    $(container).each(function () {
        $el = $(this);
        $($el).height("auto");
        topPostion = $el.position().top;
        topPositionParent = $el.parent().parent().position().top;

        if (currentRowStart != topPostion) {
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
            rowDivs.length = 0; // empty the array
            currentRowStart = topPostion;
            currentTallest = $el.height();
            rowDivs.push($el);
        } else if (currentRowStart != topPositionParent) {
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
            rowDivs.length = 0; // empty the array
            currentRowStart = topPositionParent;
            currentTallest = $el.height();
            rowDivs.push($el);
        } else {
            rowDivs.push($el);
            currentTallest =
                currentTallest < $el.height() ? $el.height() : currentTallest;
        }
        for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
            rowDivs[currentDiv].height(currentTallest);
        }
    });
};

$(document).on("click", ".ndkcfLoaded .color_pick", function (e) {
    //updatePriceAttrNdk();
    updatePriceNdkGeneric();
    update_price_dynamic(0);
    snapShotLight();
});

/*$(document).on('change', '.our_price_display', function(){
    //updatePriceAttrNdk();
    updatePriceNdkGeneric();
    update_price_dynamic(0);
});*/

$(document).on("change", ".ndkcfLoaded .attribute_select", function () {
    //updatePriceAttrNdk();
    updatePriceNdkGeneric();
    update_price_dynamic(0);
    updateDisplayAttrNdk();
});

$(document).on("click", ".ndkcfLoaded .attribute_radio", function () {
    //updatePriceAttrNdk();
    updatePriceNdkGeneric();
    update_price_dynamic(0);
    updateDisplayAttrNdk();
});

function updatePriceAttrNdk() {
    if (typeof updatePriceAttrNdk_Override == "function") {
        return updatePriceAttrNdk_Override();
    }
    // Get combination prices
    var combID = $("#idCombination").val();
    if (typeof combinationsFromController == "undefined") return;
    var combination = combinationsFromController[combID];
    if (typeof combination == "undefined") return;

    // Set product (not the combination) base price
    var basePriceWithoutTax = +productPriceTaxExcluded;
    var basePriceWithTax = +productPriceTaxIncluded;
    var priceWithGroupReductionWithoutTax = 0;

    priceWithGroupReductionWithoutTax =
        basePriceWithoutTax * (1 - groupReduction);

    // Apply combination price impact (only if there is no specific price)
    // 0 by default, +x if price is inscreased, -x if price is decreased
    basePriceWithoutTax = basePriceWithoutTax + +combination.price;
    basePriceWithTax =
        basePriceWithTax + +combination.price * (taxRate / 100 + 1);

    // If a specific price redefine the combination base price
    if (combination.specific_price && combination.specific_price.price > 0) {
        basePriceWithoutTax = +combination.specific_price.price;
        basePriceWithTax = +combination.specific_price.price * (taxRate / 100 + 1);
    }

    var priceWithDiscountsWithoutTax = basePriceWithoutTax;
    var priceWithDiscountsWithTax = basePriceWithTax;

    if (default_eco_tax) {
        // combination.ecotax doesn't modify the price but only the display
        priceWithDiscountsWithoutTax =
            priceWithDiscountsWithoutTax +
            default_eco_tax * (1 + ecotaxTax_rate / 100);
        priceWithDiscountsWithTax =
            priceWithDiscountsWithTax + default_eco_tax * (1 + ecotaxTax_rate / 100);
        basePriceWithTax =
            basePriceWithTax + default_eco_tax * (1 + ecotaxTax_rate / 100);
        basePriceWithoutTax =
            basePriceWithoutTax + default_eco_tax * (1 + ecotaxTax_rate / 100);
    }

    // Apply specific price (discount)
    // We only apply percentage discount and discount amount given before tax
    // Specific price give after tax will be handled after taxes are added
    if (combination.specific_price && combination.specific_price.reduction > 0) {
        if (combination.specific_price.reduction_type == "amount") {
            if (
                typeof combination.specific_price.reduction_tax !== "undefined" &&
                combination.specific_price.reduction_tax === "0"
            ) {
                var reduction = combination.specific_price.reduction;
                if (combination.specific_price.id_currency == 0)
                    reduction = reduction * currencyRate * (1 - groupReduction);
                priceWithDiscountsWithoutTax -= reduction;
                priceWithDiscountsWithTax -= reduction * (taxRate / 100 + 1);
            }
        } else if (combination.specific_price.reduction_type == "percentage") {
            priceWithDiscountsWithoutTax =
                priceWithDiscountsWithoutTax *
                (1 - +combination.specific_price.reduction);
            priceWithDiscountsWithTax =
                priceWithDiscountsWithTax * (1 - +combination.specific_price.reduction);
        }
    }

    // Apply Tax if necessary
    if (noTaxForThisProduct || customerGroupWithoutTax) {
        basePriceDisplay = basePriceWithoutTax;
        priceWithDiscountsDisplay = priceWithDiscountsWithoutTax;
    } else {
        basePriceDisplay = basePriceWithTax;
        priceWithDiscountsDisplay = priceWithDiscountsWithTax;
    }

    // If the specific price was given after tax, we apply it now
    if (combination.specific_price && combination.specific_price.reduction > 0) {
        if (combination.specific_price.reduction_type == "amount") {
            if (
                typeof combination.specific_price.reduction_tax === "undefined" ||
                (typeof combination.specific_price.reduction_tax !== "undefined" &&
                    combination.specific_price.reduction_tax === "1")
            ) {
                var reduction = combination.specific_price.reduction;

                if (
                    typeof specific_currency !== "undefined" &&
                    specific_currency &&
                    parseInt(combination.specific_price.id_currency) &&
                    combination.specific_price.id_currency != currency.id
                )
                    reduction = reduction / currencyRate;
                else if (!specific_currency) reduction = reduction * currencyRate;

                if (typeof groupReduction !== "undefined" && groupReduction > 0)
                    reduction *= 1 - parseFloat(groupReduction);

                priceWithDiscountsDisplay -= reduction;
                // We recalculate the price without tax in order to keep the data consistency
                priceWithDiscountsWithoutTax =
                    priceWithDiscountsDisplay - reduction * (1 / (1 + taxRate / 100));
            }
        }
    }

    // Compute discount value and percentage
    // Done just before display update so we have final prices
    if (basePriceDisplay != priceWithDiscountsDisplay) {
        var discountValue = basePriceDisplay - priceWithDiscountsDisplay;
        var discountPercentage =
            (1 - priceWithDiscountsDisplay / basePriceDisplay) * 100;
    }

    var unit_impact = +combination.unit_impact;
    if (productUnitPriceRatio > 0 || unit_impact) {
        if (unit_impact) {
            baseUnitPrice = productBasePriceTaxExcl / productUnitPriceRatio;
            unit_price = baseUnitPrice + unit_impact;

            if (!noTaxForThisProduct || !customerGroupWithoutTax)
                unit_price = unit_price * (taxRate / 100 + 1);
        } else unit_price = priceWithDiscountsDisplay / productUnitPriceRatio;
    }

    var newCustomizationPrice = 0;
    for (var i = 0; i < groupAdded.length; i++) {
        if (typeof groupAdded[i] != "undefined")
            newCustomizationPrice += parseFloat(groupAdded[i] * 1);
    }

    if (parseFloat(productPrice) > 0) {
        var productPrice = priceWithDiscountsDisplay;
        new_price =
            parseFloat(productPrice * 1) + parseFloat(newCustomizationPrice * 1);
        newCustomizationPrice = 0;
        setTimeout(function () {
            $("#our_price_display").text(formatCurrencyNdk(new_price * currencyRate));
        }, 50);
    }
    $(
        "#add_to_cart, .product-add-to-cart > *:not(.product-quantity), .add, .add-to-cart"
    ).hide();
}

function showLayer(caller) {
    if (typeof showLayer_Override == "function") {
        return showLayer_Override(caller);
    }
    view = caller.attr("data-view");
    group = caller.attr("data-group");
    if (view > 0 && $(".zone_limit[data-group='" + group + "']").length > 0)
        target = $(".zone_limit[data-group='" + group + "']");
    else target = $("#visual_" + group);

    //target = $("#visual_"+group);

    target.show();
    caller.addClass("visible_layer").removeClass("hidden_layer");
    $(".hidden_layer[data-group='" + group + "']").trigger("click");
}

function hideLayer(caller) {
    if (typeof hideLayer_Override == "function") {
        return hideLayer_Override(caller);
    }
    view = caller.attr("data-view");
    group = caller.attr("data-group");
    if (view > 0 && $(".zone_limit[data-group='" + group + "']").length > 0)
        target = $(".zone_limit[data-group='" + group + "']");
    else target = $("#visual_" + group);

    //target = $("#visual_"+group);

    target.hide();
    caller.addClass("hidden_layer").removeClass("visible_layer");
    $(".visible_layer[data-group='" + group + "']").trigger("click");
}

function makeFloat(el, parent) {
    if (typeof makeFloat_Override == "function") {
        return makeFloat_Override(el, parent);
    }
    if (!$("body").hasClass("ndkcfLoaded")) return false;

    parent = parent || "";
    var element = $(el);
    if (parent != "") $parent = $(parent);
    else $parent = $(element).parent();

    elOffset = $(el).offset();

    if ($(window).width() > 768 && contentOnly != true) {
        (function ($) {
            var element = $(el);

            if (typeof elOffset != "undefined") var originalY = elOffset.top;
            else var originalY = 0;
            var topMargin = 20;
            element.css("position", "relative").removeClass("ndk-floating");

            $(window).on("scroll", function (event) {
                var maxPosition = $parent.innerHeight() - $(element).innerHeight();
                var scrollTop = $(window).scrollTop();
                if (scrollTop - $parent.offset().top + topMargin < maxPosition) {
                    topToSet =
                        scrollTop < originalY ? 0 : scrollTop - originalY + topMargin;
                    if (scrollTop > originalY) element.addClass("ndk-floating");
                    else element.removeClass("ndk-floating");

                    element.stop(false, false).animate(
                        {
                            top: topToSet,
                            //marginBottom:  scrollTop < originalY ? 0 : scrollTop - originalY + topMargin
                        },
                        150
                    );
                } else {
                    //element.removeClass('ndk-floating');
                }
            });
        })(jQuery);
    }
}

$(document).on("change", "#quantity_wanted", function (e) {
    $("#max_options_quantity").remove();
    if (
        typeof $(this).attr("max") != "undefined" &&
        $(this).attr("max") != "null"
    ) {
        if (parseFloat($(this).val()) > parseFloat($(this).attr("max"))) {
            $(this).val($(this).attr("max")).trigger("keyup");
            $(this)
                .parent()
                .append(
                    '<span class="quantity_warning" id="max_options_quantity">' +
                    textMaxQuantity +
                    " " +
                    $(this).attr("max") +
                    "</span>"
                );
        } else {
            $("#max_options_quantity").remove();
        }
    } else {
        $("#max_options_quantity").remove();
    }
    updatePriceNdkGeneric();

    setTimeout(function () {
        $("#main").removeClass("-combinations-loading");
    }, 2000);
});

$(document).on("keyup", ".dimension_text", function (e) {
    $(this).trigger("change");
});

$(document).on("change", ".dimension_text", function (e) {
    groupf = parseInt($(this).attr("data-group"));
    width = $("#dimension_text_width_" + groupf).val();
    height = $("#dimension_text_height_" + groupf).val();

    if (width != null && height != null && height != " " && width != " ") {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
            $.ajax({
                type: "GET",
                async: true,
                url:
                    baseUrl +
                    "modules/ndk_advanced_custom_fields/front_ajax.php?action=getRangePrice",
                data: {
                    width: width,
                    height: height,
                    group: groupf,
                    id_product: $("#ndkcf_id_product").val(),
                },
                success: function (data) {
                    if (!isNaN(data)) {
                        if (
                            parseFloat(data) < 0 &&
                            $("#dimension_text_height_" + groupf).attr("type") == "number" &&
                            parseFloat(height) <
                            $("#dimension_text_height_" + groupf).attr("max")
                        ) {
                            getMinHeight(groupf, width, height);
                            //$('#dimension_text_height_'+groupf).val(parseFloat(height)+1).attr('min', parseFloat(height)+1).trigger('change');
                        } else {
                            if (
                                parseFloat(height) >
                                $("#dimension_text_height_" + groupf).attr("max")
                            ) {
                                $("#dimension_text_height_" + groupf)
                                    .val($("#dimension_text_height_" + groupf).attr("max"))
                                    .trigger("change");
                            } else {
                                //$('#dimension_text_height_'+groupf).attr('min', 0);
                                updatePriceNdk(parseFloat(data), groupf);
                                //console.log(groupf);
                            }
                        }
                    }
                },
            });
        }, 500);
    } else {
        updatePriceNdk(0, groupf);
    }
});

function getMinHeight(group, width) {
    $.ajax({
        type: "GET",
        async: true,
        url:
            baseUrl +
            "modules/ndk_advanced_custom_fields/front_ajax.php?action=getMinHeight",
        data: { width: width, group: groupf },
        success: function (data) {
            if (!isNaN(data)) {
                $("#dimension_text_height_" + groupf)
                    .val(parseFloat(data))
                    .attr("min", parseFloat(data))
                    .trigger("change");
            }
        },
    });
}

(function ($) {
    $.fn.rotationDegrees = function () {
        var matrix =
            this.css("-webkit-transform") ||
            this.css("-moz-transform") ||
            this.css("-ms-transform") ||
            this.css("-o-transform") ||
            this.css("transform");
        if (typeof matrix === "string" && matrix !== "none") {
            var values = matrix.split("(")[1].split(")")[0].split(",");
            var a = values[0];
            var b = values[1];
            var angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
        } else {
            var angle = 0;
        }
        return angle < 0 ? angle + 360 : angle;
    };
})(jQuery);

function cancelFieldRestrictions(id_value, group) {
    //console.log('disabled_value_by_'+group+'_'+id_value)
    $("[class*='disabled_value_by_" + group + "']")
        .removeClass("disabled_value_by_" + group)
        .addClass("enabled_value_by_" + group);
    $("[class*='disabled_value_by_" + group + "']").removeClass(function (
        index,
        className
    ) {
        return (className.match(/\bdisabled_value_by_\S+/g) || []).join(" ");
    });

    $(".disabled_value_by_" + group + "_" + id_value).removeClass(
        "disabled_value_by_" + group + "_" + id_value
    );
}

function checkFieldRestrictions(id_value, group) {
    if (typeof checkFieldRestrictions_Override == "function") {
        return checkFieldRestrictions_Override(id_value, group);
    }

    selectedConfigValue = getSelectedValuesForPrice();
    data = { restrictions: false, obligations: false };
    $(".form-group[data-field='" + group + "']")
        .removeClass("focusRequired")
        .find(".error")
        .remove();

    if (id_value && id_value.indexOf("[") > -1) {
        idValArr = id_value.split("[");
        my_id_value = idValArr[0];
    } else my_id_value = id_value;

    if (
        typeof jsonDatas[group] != "undefined" &&
        typeof jsonDatas[group][my_id_value] != "undefined"
    )
        data = jsonDatas[group][my_id_value];

    if (
        typeof data != "undefined" &&
        data !== null &&
        data != "" &&
        ((typeof data.restrictions != "undefined" &&
            data.restrictions !== null &&
            data.restrictions != "") ||
            (typeof data.obligations != "undefined" &&
                data.obligations !== null &&
                data.obligations != ""))
    ) {
        //goTonextStep(group);
        zeroValue = false;
        if (
            typeof selectedConfigValue["ndk-accessory-quantity-" + my_id_value] !=
            "undefined"
        ) {
            $(".disabled_value_by_" + group)
                .removeClass("disabled_value_by_" + group)
                .addClass("enabled_value_by_" + group);
            //console.log(selectedConfigValue['ndk-accessory-quantity-'+my_id_value] )
            zeroValue =
                parseInt(
                    selectedConfigValue["ndk-accessory-quantity-" + my_id_value]
                ) == 0;
            //zeroValue = parseInt( $('#ndk-accessory-quantity-'+my_id_value ).val() ) == 0;
            //console.log(zeroValue)
        }

        //if(selectedIdValue[group] != my_id_value && !zeroValue){
        if (!zeroValue) {
            selectedIdValue[group] = my_id_value;
            if (
                !$(".form-group[data-field='" + group + "']").hasClass(
                    "hasRestrictions"
                )
            )
                return true;

            id_value = id_value || false;
            if (id_value && id_value.indexOf("[") > -1) {
                idValArr = id_value.split("[");
                killer = idValArr[0];
            } else {
                killer = 0;
            }
            $(".disabled_value_by_" + group).each(function () {
                selector =
                    ".form-group[data-field='" + $(this).attr("data-field") + "']:eq(0)";
                //applyDefaultValuesNdk($(selector));
            });
            $(".form-group[data-field='" + group + "']")
                .removeClass("focusRequired")
                .find(".error")
                .remove();
            $(".disabled_value_by_" + group)
                .removeClass("disabled_value_by_" + group)
                .addClass("enabled_value_by_" + group);
            $(
                ".disabled_value_by_" + group + (killer > 0 ? "_" + killer : "")
            ).removeClass(
                "disabled_value_by_" + group + (killer > 0 ? "_" + killer : "")
            );

            my_restrictions = [];
            if (
                typeof data != "undefined" &&
                data !== null &&
                typeof data.restrictions != "undefined" &&
                data.restrictions !== null &&
                data.restrictions != ""
            ) {
                $(".disabled_value_by_" + group)
                    .removeClass("disabled_value_by_" + group)
                    .addClass("enabled_value_by_" + group);
                for (var i = 0; i <= data.restrictions.length; i++) {
                    if (
                        typeof (data.restrictions[i] != "undefined") &&
                        data.restrictions[i] !== null &&
                        data.restrictions[i]
                    ) {
                        splitted = data.restrictions[i].split("|");
                        //console.log(splitted);
                        targetVal = splitted[1];
                        targetValValue = splitted[2];
                        targetGroup = splitted[0];
                        targetGroup = targetGroup.replace("]", "").replace("[", "");

                        if (
                            $("#ndkcsfield_" + targetGroup).val() == targetValValue ||
                            targetVal == "all"
                        ) {
                            rootBlock = $(".form-group[data-field='" + targetGroup + "']");
                            $("#visual_" + targetGroup).remove();
                            $("#ndkcsfield_" + targetGroup).val("");
                            rootBlock
                                .find(
                                    ".noborder, .noborderSimple, .falsenoborder, .recipient-text"
                                )
                                .val("");
                            rootBlock
                                .find("input[type='checkbox']")
                                .prop("checked", false)
                                .trigger("change");
                            rootBlock.find(".selected-value").removeClass("selected-value");

                            if (
                                rootBlock.find(
                                    '.noborder[value!=""], .noborderSimple[value!=""], .falsenoborder[value!=""]'
                                ).length > 0
                            ) {
                                rootBlock
                                    .find(".noborder, .noborderSimple, .falsenoborder")
                                    .val("");
                                rootBlock
                                    .find(".submitText, .submitSimpleText")
                                    .trigger("click");
                            }
                            $("#layer-edit-" + targetGroup).remove();
                            if (!!$.prototype.uniform)
                                $.uniform.update("#ndkcsfields input, #ndkcsfields select");
                            $("#dimension_text_width_" + targetGroup).val("");
                            $("#dimension_text_height_" + targetGroup).val("");
                            $(".recap_group_" + targetGroup).html("");

                            updatePriceNdk(0, targetGroup);
                            groupAdded[targetGroup] = 0;
                            checkLayerChanges();
                        }
                        disableFieldRestriction(targetVal, group, targetGroup, killer);
                    }
                }
            } else {
                $(".disabled_value_by_" + group)
                    .removeClass("disabled_value_by_" + group)
                    .addClass("enabled_value_by_" + group);
                $(
                    ".disabled_value_by_" + group + (killer > 0 ? "_" + killer : "")
                ).removeClass(
                    "disabled_value_by_" + group + (killer > 0 ? "_" + killer : "")
                );
            }

            my_obligations = [];
            targetCurrentValue = false;

            if (typeof data === "object" && typeof data.obligations === "object") {
                for (var i = 0; i <= data.obligations.length; i++) {
                    if (
                        typeof (data.obligations[i] != "undefined") &&
                        data.obligations[i] !== null &&
                        data.obligations[i]
                    ) {
                        splitted = data.obligations[i].split("|");
                        targetVal = splitted[1];
                        targetValValue = splitted[2];
                        targetGroup = splitted[0];
                        targetGroup = targetGroup.replace("]", "").replace("[", "");
                        targetCurrentValue = selectedConfigValue[targetGroup];
                        rootBlock = $(".form-group[data-field='" + targetGroup + "']");
                        //console.log(targetGroup+':'+targetCurrentValue );
                        //console.log(targetValValue+' : '+targetVal)

                        //if(targetCurrentValue != false && targetCurrentValue != targetValValue && selectedConfigValue[group] != '')
                        my_obligations.push({
                            block: rootBlock,
                            value: '[data-id-value="' + targetVal + '"]',
                        });
                    }
                }
                if (my_obligations.length > 0)
                    for (var i = 0; i <= my_obligations.length; i++) {
                        if (typeof my_obligations[i] != "undefined") {
                            //console.log(my_obligations[i].value);
                            block = my_obligations[i].block;
                            value = my_obligations[i].value;
                            applyDefaultValuesNdk(block, value, false);
                        }
                    }
            }
        }
    } else {
        $(".disabled_value_by_" + group)
            .removeClass("disabled_value_by_" + group)
            .addClass("enabled_value_by_" + group);
        //$('.disabled_value_by_'+group+(killer > 0 ? '_'+killer : '')).removeClass('disabled_value_by_'+group+(killer > 0 ? '_'+killer : ''));
        selectedIdValue[group] = id_value;
        // IW : display only field with specific parent specified
        $('option[data-influences-parent-group-id="' + group + '"]')
            .addClass("disabled_value_by_" + group)
            .removeClass("enabled_value_by_" + group);

        $('option[data-influences-parent-group-id="' + group + '"][data-influences-parent-id="' + id_value + '"]')
            .removeClass("disabled_value_by_" + group)
            .addClass("enabled_value_by_" + group);
        // /IW
    }
    $(".view_tab.activeView").trigger("click");
    /* Use overriden next step */
    goTonextStepOverride(group);
    /* /Use overriden next step */
    getSubValues(id_value, group);
}

function getSubValues(id_value, group, targetEl) {
    if (typeof getSubValues_Override == "function") {
        return getSubValues_Override(id_value, group, targetEl);
    }
    targetEl = targetEl || $("#main-" + group);

    if (group.indexOf("-") > -1) mainGroup = group.split("-")[0];
    else mainGroup = group;

    if (typeof jsonDatas[mainGroup] != "undefined") {
        $("#ndkloader").fadeOut().remove();
        $(".ndkackFieldItem[data-field=" + mainGroup + "]")
            .append(ndkLoader)
            .addClass("small_loader_container");
        myType = 0;

        type = jsonDatas[mainGroup].type;
        $("#sub-" + group + "-" + id_value).remove();
        $.ajax({
            type: "GET",
            url:
                baseUrl +
                "modules/ndk_advanced_custom_fields/front_ajax.php?action=getSubValues",
            data: { id_field: group, id_value: id_value, type: type },
            success: function (data) {
                mytype = "";
                if (data.length > 0) {
                    if ($("#sub-" + group + "-" + id_value).length < 1) {
                        depth = $("#item-" + group).find(".subValues").length + 1;
                        $("#item-" + group)
                            .find(".subValues")
                            .each(function () {
                                if (
                                    $(this).find("[data-id-value=" + id_value + "]").length == 0
                                )
                                    $(this).remove();
                            });
                        targetEl.find(".subValues").each(function () {
                            if ($(this).find("[data-id-value=" + id_value + "]").length == 0)
                                $(this).remove();
                        });

                        if ($("#item-" + group).length > 0) {
                            $("#item-" + group).append(
                                '<div id="sub-' +
                                group +
                                "-" +
                                id_value +
                                '" class="item-sub subValues clear clearfix" data-depth="' +
                                depth +
                                '" data-parent-type="' +
                                mytype +
                                '">' +
                                data +
                                "</div>"
                            );
                            $("#sub-" + group + "-" + id_value)
                                .find(".img-value")
                                .attr("data-group", group);
                            $("#sub-" + group + "-" + id_value)
                                .find(".img-item-row")
                                .attr("data-group", group);
                        } else {
                            targetEl.append(
                                '<div id="sub-' +
                                group +
                                "-" +
                                id_value +
                                '" class="subValues clear clearfix" data-depth="' +
                                depth +
                                '" data-parent-type="' +
                                mytype +
                                '">' +
                                data +
                                "</div>"
                            );
                            $("#sub-" + group + "-" + id_value)
                                .find(".img-value")
                                .attr("data-group", group);
                            $("#sub-" + group + "-" + id_value)
                                .find(".img-item-row")
                                .attr("data-group", group);
                        }

                        setTimeout(function () {
                            reloadImg($("#sub-" + group + "-" + id_value));
                            setTypesGroups("#sub-" + group + "-" + id_value);
                            applyDefaultValuesNdk(targetEl);
                            applyDefaultValuesNdk($("#item-" + group));
                            $("#sub-" + group + "-" + id_value).trigger("subLoaded");
                        }, 800);
                    }
                }
            },
        });
    }
    $(".small_loader_container #ndkloader").fadeOut().remove();
}

function setTypesGroups(target) {
    if (typeof setTypesGroups_Override == "function") {
        return setTypesGroups_Override(target);
    }

    $("#ndkloader").fadeOut().remove();
    $(target).append(ndkLoader).addClass("small_loader_container");

    var filtersTypes = [];
    target = target || "body";

    $(target)
        .find(".typed")
        .each(function () {
            type = $(this).attr("data-type");
            rootBlock = $(this).attr("data-group");
            filtersTypes.push(type + "|" + rootBlock);
        });

    //on créé le block de types
    encountred = [];
    for (var i = 0; i < filtersTypes.length; i++) {
        value = filtersTypes[i].split("|");
        if ($.inArray(value[0], encountred) == -1) {
            group = value[1];
            textValue = value[0];

            if (target == "body") my_target = "#item-" + group;
            else my_target = target;

            delTypeButton = "";
            if ($.inArray(textValue, ["with-baby", "accessories"]) > -1) {
                delTypeButton =
                    '<a class="delTypeButton" data-type="' +
                    textValue +
                    '" data-group="' +
                    group +
                    '"><span><i class="material-icons">delete</i></span></a>';
            }
            if (
                $(my_target).find(".group-type[data-group-type=" + textValue + "]")
                    .length == 0
            )
                $(my_target).append(
                    '<div class="group-type clear clearfix" data-group-type="' +
                    textValue +
                    '"><p class="group-title">' +
                    (typeof typeText[textValue] != "undefined"
                        ? typeText[textValue]
                        : textValue) +
                    '<span class="group-value"></span>' +
                    delTypeButton +
                    '</p><div class="groupList"></div></div>'
                );

            encountred.push(value[0]);
        }
        // setTimeout(function(){
        // 	$(my_target).find('.group-title:eq(0):not(.active)').trigger('click');
        // }, 800)
    }

    $(target)
        .find(".typed")
        .each(function () {
            type = $(this).attr("data-type");
            rootBlock = $(this).attr("data-group");
            if (target == "body") my_target = "#item-" + rootBlock;
            else my_target = target;
            $(this)
                .detach()
                .appendTo(
                    $(target).find(".group-type[data-group-type=" + type + "] .groupList")
                );
        });

    $(".group-type").each(function () {
        type = $(this).attr("data-group-type");
        setColorSelector(type, target);
    });

    $(".small_loader_container #ndkloader").fadeOut().remove();
    $(target).trigger("typesSet");
}

function reloadImg(parent) {
    parent = parent || $("body");
    $(".img-value").each(function () {
        if (typeof $(this).attr("data-thumb") != "undefined") {
            preloadImg.push($(this).attr("data-thumb"));
            $(this).attr("src", $(this).attr("data-thumb"));
        }
    });

    $.when(preload(preloadImg)).done(function () {
        setTimeout(function () {
            equalheight(".img-item-row");
        }, 50); //test timeout
    });

    loadImgSvg(parent);
}

function loadImgSvg(parent) {
    parent = parent || $("body");
    parent.find("img.svg").each(function () {
        var $img = $(this);
        var imgID = $img.attr("id");
        var idVal = $img.attr("data-id-value");
        var imgClass = "";
        $img.hide();
        $svg = $img.parent().find(".svg-container").find("svg");
        if (typeof imgID !== "undefined") {
            $svg.attr("id", imgID);
        }
        // Add replaced image's classes to the new SVG
        if (typeof imgClass !== "undefined") {
            $svg.attr(
                "class",
                imgClass +
                " replaced-svg composition_element  composition_element-" +
                $img.attr("data-type")
            );
        }
        $svg.attr("data-type", $img.attr("data-type"));
        $svg.removeAttr("xmlns:a");
        $svg.find("use").each(function () {
            xlink = $(this).attr("xlink:href");
            my_xlink = xlink.replace("#", "") + "-" + idVal;
            $(this).attr("xlink:href", "#" + my_xlink);
            $svg.find(xlink).attr("id", my_xlink);
        });
        $img.addClass("loaded_svg");
    });
}

// function goTonextStep(group) {
//     formGroup = $(".form-group.steppedField[data-field='" + group + "']");
//     required = formGroup.find(".required_field").length > 0;

//     if (typeof selectedIdValue[group] != "undefined" || !required) {
//         //console.log(group+'-'+selectedIdValue[group]+'-'+required)
//         formIteration = parseInt(formGroup.attr("data-iteration"));
//         nextIteration = parseInt(formIteration + 1);
//         formGroup.addClass("stepDone").removeClass("stepTodo");

//         nextBlock = formGroup.nextAll(".notReadyStep:visible:first");
//         next_group = nextBlock.attr("data-field");
//         nextBlock.removeClass("notReadyStep").find(".toggler").trigger("click");

//         if (parseInt(next_group) > 0) goTonextStep(next_group);
//     } else {
//         return false;
//         //console.log('stop')
//     }
// }

function goTonextStepOverride(group) {
    // Retrieve modified element
    formGroup = $(".form-group.steppedField[data-field='" + group + "']");
    required = formGroup.find(".required_field").length > 0;
    // Reset custom thumbs gallery if needed
    resetCustomThumbnails(findThumbnailIndex($(formGroup).find('label').eq(0).clone().children().remove().end().text().trim()));
    // Retrieve list of all steps elements
    stepList = formGroup.parent().children('.form-group.steppedField');
    // Set relative element position in list
    elementIndex = $(stepList).index(formGroup);
    // Set next opening tab
    if (typeof selectedIdValue[group] != "undefined" || !required) {
        // Set next block in iteration
        nextBlock = $(stepList).eq(elementIndex + 1);
        // Validate step on modified element
        formGroup.addClass("stepDone").removeClass("stepTodo");
        // Select all other steps
        slicedStepList = $(stepList).slice(elementIndex + 1);
        // Close their tabs if opened
        $(slicedStepList).each(function () {
            if ($(this).find(".fieldPane").is(":visible") && $(this).find('.required_field').length > 0) {
                $(this).find(".toggler").trigger("click");
            }
        });
        // Modify classes relating to steps
        $(slicedStepList)
            .removeClass("stepDone")
            .addClass("notReadyStep")
            .toggleClass("stepToDo");
        // Reinit all fields
        $(slicedStepList.find(".required_field")).each(function () {
            $(this).val("");
            // Specific for img-type (delete select border)
            if ($(this).parent().hasClass('img-type-container')) {
                $(this).parent().find('.selected-value').removeClass('selected-value');
            }
        })
        // Open next element tab
        nextBlock.removeClass("notReadyStep");
        if (nextBlock.is(':visible')) {
            if (nextBlock.find(".fieldPane").is(':hidden') && nextBlock.find(".toggler").length) {
                nextBlock.find(".toggler").trigger("click");
            }
            if (nextBlock.find(".toggler").length) {
                setTimeout(function() {
                    var top = nextBlock.find(".toggler").closest('.form-group.ndkackFieldItem').offset().top-43;
                    if (window.matchMedia("(max-width: 768px)").matches) {
                        top -= $('#image-block').outerHeight();
                    }
                    $(window).scrollTop(top);
                },500);
            }
        }
    } else {
        return false;
    }
}

function setScenario() {
    var step_done = [];
    var step_to_do = [];
    $(".overDisabler").remove();
    for (var i = 0; i < scenario.length; i++) {
        var fg = $(".form-group[data-field='" + scenario[i] + "']");
        fg.addClass("steppedField notReadyStep stepTodo")
        // if (fg.find(".required_field").length > 0) {
        fg.append('<div class="overDisabler"></div>');
        // }
    }
    $(".notReadyStep:eq(0)").removeClass("notReadyStep");
}

function setOpenedStatus() {
    if (isFields != 1 || $("#ndkcsfields-block").length < 1) return;
    for (var i = 0; i < opened_fields.length; i++) {
        $(".form-group[data-field='" + opened_fields[i] + "']")
            .find(".toggler:not(.active)")
            .trigger("click");
        setTimeout(function () {
            equalheight(
                ".form-group[data-field='" + opened_fields[i] + "'] .img-item-row"
            );
        }, 500);
    }
    for (var i = 0; i < closed_fields.length; i++) {
        $(".form-group[data-field='" + closed_fields[i] + "']")
            .find(".toggler.active")
            .trigger("click");
    }

    for (var i = 0; i < hidden_fields.length; i++) {
        $(".form-group[data-field='" + hidden_fields[i] + "']").addClass(
            "hidden hidden-important"
        );
    }
}

function identifyResctictives() {
    for (var i = 0; i < hasRestrictions.length; i++) {
        $(".form-group[data-field='" + hasRestrictions[i] + "']").addClass(
            "hasRestrictions"
        );
    }
}

function disableFieldRestriction(idVal, group, targetGroup, killer) {
    if (typeof disableFieldRestriction_Override == "function") {
        return disableFieldRestriction_Override(idVal, group, targetGroup, killer);
    }
    if (typeof idVal == "undefined" || idVal == "" || idVal == "undefined")
        return true;

    $(".disabled_value_by_" + targetGroup)
        .removeClass("disabled_value_by_" + targetGroup)
        .addClass("enabled_value_by_" + targetGroup);
    if (idVal == "all") {
        $(".form-group[data-field='" + targetGroup + "']:not(.submitContainer)")
            .addClass("disabled_value_by_" + group)
            .removeClass("enabled_value_by_" + group);
        $(".form-group[data-field='" + targetGroup + "']")
            .find(".ndk-accessory-quantity[value!=0]")
            .val(0)
            .trigger("change")
            .trigger("keyup")
            .addClass("disabled_value_by_" + group)
            .removeClass("enabled_value_by_" + group);
        $(
            ".ndkQuickAccessBox-item[data-target='" +
            targetGroup +
            "']:not(.submitContainer)"
        )
            .addClass("disabled_value_by_" + group)
            .removeClass("enabled_value_by_" + group);
    }

    //$('*').removeClass('disabled_value_by_'+group).addClass('enabled_value_by_'+group);
    if (idVal.indexOf("[") > -1) {
        idValArr = idVal.split("[");
        idValue = idValArr[0];
        qttyVal = idValArr[1].replace("]", "");
        if ($("#ndk-accessory-quantity-" + killer).val() == qttyVal) {
            //$('#ndk-accessory-quantity-'+idValue).addClass('disabled_value_by_'+group+'[value!=0]').val(0).trigger('change').trigger('keyup');
            $("#ndk-accessory-quantity-" + idValue)
                .addClass(
                    "disabled_value_by_" + group + (killer > 0 ? "_" + killer : "")
                )
                .val(0)
                .trigger("change")
                .trigger("keyup");
            $("#ndk-accessory-quantity-" + idValue)
                .parent()
                .parent()
                .parent()
                .addClass("disabled_value_by_" + group)
                .removeClass("enabled_value_by_" + group);
            $("#ndk-accessory-quantity-" + idValue)
                .parent()
                .parent()
                .parent()
                .addClass(
                    "disabled_value_by_" + group + (killer > 0 ? "_" + killer : "")
                )
                .val(0)
                .trigger("change")
                .trigger("keyup");
        }
    } else {
        //$('#ndk-accessory-quantity-'+idVal).addClass('disabled_value_by_'+group+'[value!=0]').val(0).trigger('change').trigger('keyup');
        $("#ndk-accessory-quantity-" + idVal)
            .addClass("disabled_value_by_" + group + (killer > 0 ? "_" + killer : ""))
            .val(0)
            .trigger("change")
            .trigger("keyup");
        $("#ndk-accessory-quantity-" + idVal)
            .parent()
            .parent()
            .parent()
            .addClass("disabled_value_by_" + group)
            .removeClass("enabled_value_by_" + group);
        $("#ndk-accessory-quantity-" + idVal)
            .parent()
            .parent()
            .parent()
            .addClass("disabled_value_by_" + group + (killer > 0 ? "_" + killer : ""))
            .val(0)
            .trigger("change")
            .trigger("keyup");
        //$("input[name='price_'"+idVal+"]").val(0);
    }

    $("[data-id-value='" + idVal + "']")
        .addClass("disabled_value_by_" + group)
        .removeClass("enabled_value_by_" + group);
    $(".ndk-radio[data-id-value='" + idVal + "']")
        .parent()
        .addClass("disabled_value_by_" + group)
        .removeClass("enabled_value_by_" + group);
    //$("[data-id-value='"+idVal+"']").parent(':not(select)').addClass('disabled_value_by_'+group).removeClass('enabled_value_by_'+group);
    // setTimeout(function(){
    // 	targetForDefault = $('.enabled_value_by_'+group+':visible').not('[class*="disabled_value_by"]');
    // 	applyDefaultValuesNdk(targetForDefault, '[data-default-value="1"]', false);
    //  }, 80);
}

/*$('textarea').live('keyup', function() {
    text = $(this).val();
    var last = $(this).val().split('\n').pop();
    var width = $(this).parent().find('.poptext').text(last).width(); 
    if ( width >  $(this).width() ) {
    $(this).val(text+'\n');
    //console.log($(this).val());
    }
});*/

/*$(document).on('keypress', 'textarea', function(){
    var length = $(this).val().length;
    if (length % 51 == 0 &&
        length > 0) {
        var val = $(this).val();
        $(this).val(val + '\n');
    }
});*/

function convertPercent() {
    if (typeof convertPercent_Override == "function") {
        return convertPercent_Override();
    }

    $(".absolute-visu:visible").each(function () {
        dragToPercent($(this));
    });
    /*$('.absolute-visu:visible').each(function(){
            group = $(this).attr('id').replace('visual_', '');
            if( group.indexOf('-') > -1 ){
                group = group.split('-')[0]; 
            }	
            if($(".zone_limit[data-group='"+group+"']").length > 0 ) {
                    container = ".zone_limit[data-group='"+group+"']";
                target = 2;
            }
            else{
                container = '#image-block'; 
                target = 1;
            }
            containerWidth = $(container).width();
            containerHeight = $(container).height(); 
            
            var l = ( 100 * parseFloat($(this).css("left")) / parseFloat($(this).parent().css("width")) )+ "%" ;
            var t = ( 100 * parseFloat($(this).css("top")) / parseFloat($(this).parent().css("height")) )+ "%" ;
            var w = ( 100 * parseFloat($(this).css("width")) / parseFloat($(this).parent().css("width")) )+ "%" ;
            
            if($(this)[0].style.left.indexOf('%') < 1)
                $(this).css("left" , l);
            if($(this)[0].style.top.indexOf('%') < 1)
                $(this).css("top" , t);
            if($(this)[0].style.width.indexOf('%') < 1)
                $(this).css("width" , w);
            if($(this)[0].style.height.indexOf('%') < 1)
                $(this).css("height" , 'auto');
                
            if(target != 1 &&  target == 2 && !$(this).parent().hasClass('zone_limit') && $(this).parent().attr('id') != '#image-block'){
            if($(this).parent().css("width").indexOf('%') < 1){
                $(this).parent().css({width : w, height : 'auto'});
                $(this).parent().addClass('percented');
            }
            }
            
            $(this).addClass('percented');	
                    
        });*/

    $("#image-block").css({ width: "100%", height: "auto" });
}

function convertPercentBAK() {
    if (typeof convertPercentBAK_Override == "function") {
        return convertPercentBAK_Override();
    }
    $(".absolute-visu:visible").each(function () {
        group = $(this).attr("id").replace("visual_", "");

        if (group.indexOf("-") > -1) {
            group = group.split("-")[0];
        }

        //console.log(group);
        if ($(".zone_limit[data-group='" + group + "']").length > 0) {
            container = ".zone_limit[data-group='" + group + "']";
            target = 2;
        } else {
            container = "#image-block";
            target = 1;
        }
        //$(container).hide();
        containerWidth = $(container).width();
        containerHeight = $(container).height();

        elWidth = $(this).width();
        elHeight = $(this).height();

        //elLeft = $(this).css('left').replace('px', '');
        //elTop = $(this).css('top').replace('px', '');

        //elLeft = $(this).clone().appendTo('body').wrap('<div style="display: none"></div>').css('left');
        //elTop = $(this).clone().appendTo('body').wrap('<div style="display: none"></div>').css('top');
        elLeft = $(this)[0].style.left.replace("px", "");
        elTop = $(this)[0].style.top.replace("px", "");

        //convertDegree($(this).attr('id'));
        //console.log('left : '+elLeft);
        //console.log('top : '+ elTop);

        widthPercent = (elWidth / containerWidth) * 100 + "%";
        heightPercent = (elHeight / containerHeight) * 100 + "%";
        heightPercent = "auto";
        leftPercent = (elLeft / containerWidth) * 100 + "%";
        topPercent = (elTop / containerHeight) * 100 + "%";

        $(this).css({
            width: widthPercent,
            height: heightPercent,
            margin: "",
        });

        if (elLeft.indexOf("%") < 1) {
            $(this).css({
                left: leftPercent,
            });
        }
        if (elTop.indexOf("%") < 1) {
            $(this).css({
                top: topPercent,
            });
        }
        if (
            target != 1 &&
            target == 2 &&
            !$(this).parent().hasClass("zone_limit") &&
            $(this).parent().attr("id") != "#image-block"
        ) {
            $(this).parent().css({ width: widthPercent, height: heightPercent });
        }
        $("#image-block").css({ width: "100%", height: "auto" });

        //$(container).show();
    });
}

function convertDegree(id) {
    if (typeof convertDegree_Override == "function") {
        return convertDegree_Override(id);
    }
    var el = document.getElementById(id);
    var st = window.getComputedStyle(el, null);
    var tr =
        st.getPropertyValue("-webkit-transform") ||
        st.getPropertyValue("-moz-transform") ||
        st.getPropertyValue("-ms-transform") ||
        st.getPropertyValue("-o-transform") ||
        st.getPropertyValue("transform") ||
        "FAIL";

    var values = tr.split("(")[1].split(")")[0].split(",");
    var a = values[0];
    var b = values[1];
    var c = values[2];
    var d = values[3];

    var scale = Math.sqrt(a * a + b * b);

    //console.log('Scale: ' + scale);

    // arc sin, convert from radians to degrees, round
    var sin = b / scale;
    // next line works for 30deg but not 130deg (returns 50);
    // var angle = Math.round(Math.asin(sin) * (180/Math.PI));
    var angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
    /*$(id).css({
                                '-webkit-transform': 'rotate(' + angle + 'deg)',
                                '-moz-transform': 'rotate(' + angle + 'deg)',
                                '-ms-transform': 'rotate(' + angle + 'deg)',
                                '-o-transform': 'rotate(' + angle + 'deg)',
                                'transform': 'rotate(' + angle + 'deg)',
                                'zoom': 1
                    });*/

    //console.log('Rotate: ' + angle + 'deg');
}

function applyDefaultValuesNdk(root, defaultValue, checkHideField) {
    root = root || $("#ndkcsfields-block");
    defaultValue = defaultValue || '[data-default-value="1"]';
    checkHideField = checkHideField || true;

    if (typeof applyDefaultValuesNdk_Override == "function") {
        return applyDefaultValuesNdk_Override(root, defaultValue, checkHideField);
    }

    if (checkHideField) {
        root.find('*[data-hide-field="1"]').each(function () {
            if ($(this).is(":checkbox"))
                checkFieldRestrictions(
                    $(this).attr("data-value-id"),
                    $(this).attr("data-group")
                );
            else
                checkFieldRestrictions(
                    $(this).attr("data-id-value"),
                    $(this).attr("data-group")
                );
        });
    }

    root.find(".color-ndk" + defaultValue).trigger("click");

    root.find(".accessory-ndk-no-quantity" + defaultValue).trigger("click");
    //console.log(defaultValue);
    root.find(".img-value" + defaultValue).each(function () {
        if ($(this).parent().find(".svg-container").length > 0)
            $(this).parent().find(".svg-container").trigger("click");
        else $(this).trigger("click");
    });

    root
        .find(".ndk-radio" + defaultValue)
        .prop("checked", true)
        .trigger("change");
    root
        .find(".ndk-checkbox" + defaultValue)
        .prop("checked", true)
        .trigger("change");
    root.find(".ndk-select").each(function () {
        if ($(this).find("option" + defaultValue).length > 0) {
            $(this)
                .find("option" + defaultValue)
                .prop("selected", "selected");
            $(this).trigger("change");
        }
    });

    root.find(".dimension_text").each(function () {
        $(this).trigger("keyup").trigger("change");
    });

    root.find(".form-group").removeClass("activeFormGroup");
}

function scrollToNdk(el, speed, force) {
    if (typeof scrollToNdk_Override == "function") {
        return scrollToNdk_Override(el, speed);
    }
    speed = speed || 750;
    force = force || false;
    if (ndk_disableAutoScroll != 1 || force) {
        if (el.length) {
            $("html").animate(
                {
                    scrollTop: el.offset().top,
                    scrollLeft: el.offset().left,
                },
                speed
            );
        }
    }
}

$(document).on(
    "click",
    ".ndkcfLoaded #blockcart-modal [data-dismiss='modal']",
    function () {
        location.reload();
    }
);
$(document).on("hidden.bs.modal", ".ndkcfLoaded #blockcart-modal", function () {
    location.reload();
});

// prestashop.on('updateCart', function(){
//   if($('body').hasClass('ndkcfLoaded'))
//     location.reload();
// });

function strReplaceAll(string, Find, Replace) {
    try {
        return string.replace(new RegExp(Find, "gi"), Replace);
    } catch (ex) {
        return string;
    }
}

function emptyFormNdk(form) {
    if (typeof emptyFormNdk_Override == "function") {
        return emptyFormNdk_Override(form);
    }

    checkbox = form.find('input[type="checkbox"]');
    radio = form.find('input[type="radio"]');
    text = form.find('input[type="text"], input[type="hidden"]');
    select = form.find("select");

    checkbox.prop("checked", false).trigger("change");
    radio.prop("checked", false).trigger("change");
    //select.val('').trigger('change');
    //text.val('').trigger('change');
}

function setQuickNav() {
    if (typeof setQuickNav_Override == "function") {
        return setQuickNav_Override();
    }
    $("#ndkQuickAccessBox").remove();
    if ($(".pb-right-column").length > 0)
        $(".pb-right-column").append(
            '<div id="ndkQuickAccessBox" class="quickFullWidth"><ul></ul></div>'
        );
    else
        $("#ndkcsfields-block")
            .append('<div id="ndkQuickAccessBox"><ul></ul></div>')
            .addClass("withQuickNav");

    $(".toggler:not(.dontquick)").each(function () {
        groupTitleEl = $(this).clone();
        groupTitleEl
            .find(".toggleText, .tooltipDescription, .tooltipDescMark")
            .remove();
        title = groupTitleEl.text();
        group = $(this).parent().attr("data-field");
        $("#ndkQuickAccessBox ul").append(
            '<li class="ndkQuickAccessBox-item" data-target="' +
            group +
            '">' +
            title +
            "</li>"
        );
    });

    $(document).on("click", ".ndkQuickAccessBox-item", function () {
        $(".ndkQuickAccessBox-item").removeClass("active");
        group = $(this).attr("data-target");
        $(this).addClass("active");
        $(".ndkackFieldItem[data-field='" + group + "'] label:eq(0)").trigger(
            "click"
        );
    });
    if ($(".pb-right-column").length < 1) makeFloat("#ndkQuickAccessBox");
}

$(document).on("change", ".ndk-radio, .ndk-checkbox", function () {
    if ($(this).is(":radio"))
        $("input[name='" + $(this).attr("name") + "']")
            .not(this)
            .removeAttr("checked")
            .removeAttr("checkme");

    if ($(this).is(":checked"))
        $(this).attr("checked", "checked").attr("checkme", "1");
    else {
        $(this).removeAttr("checked");
        $(this).removeAttr("checkme");
    }
});

function updateDisplayAttrNdk() {
    if (ps_version <= 1.6) {
        var productPriceDisplay = productPrice;
        var productPriceWithoutReductionDisplay = productPriceWithoutReduction;
        if (
            !selectedCombination["unavailable"] &&
            quantityAvailable > 0 &&
            productAvailableForOrder == 1
        ) {
            $(".ndkcsfields-block").fadeIn(600);
            $("#submitNdkcsfields, .falseButton").removeAttr("disabled");
        } else {
            //show the 'add to cart' button ONLY IF it's possible to buy when out of stock AND if it was previously invisible
            if (
                allowBuyWhenOutOfStock &&
                !selectedCombination["unavailable"] &&
                productAvailableForOrder
            ) {
                $(".ndkcsfields-block").fadeIn(600);
                $("#submitNdkcsfields, .falseButton").removeAttr("disabled");
            } else {
                if (editConfig == 0) {
                    $(".ndkcsfields-block").fadeOut(600);
                    $("#submitNdkcsfields, .falseButton").attr("disabled", "disabled");
                }
            }
        }
    }
}

$(document).on("focus", ".ndk-select", function () {
    $(this).find("option").removeAttr("disabled");
    $(this).find('[class*="disabled_value_by"]').attr("disabled", "disabled");
});

// prestashop.on('updatedProduct', function(){
//   setFalseButton();
// })

function setFalseButton() {
    if (typeof setFalseButton_Override == "function") {
        return setFalseButton_Override();
    }
    if ($(".ndkackFieldItem").length > 0) {
        $(".falseButtonContainer").remove();
        if (ps_version < 1.7) {
            node = $("#add_to_cart").clone();
            node.addClass("falseButtonContainer");
            node.attr("id", "ndkacf_add_to_cart").show().addClass("ndkcf_add");
            node.find("button").addClass("falseButton").addClass("add-to-cart");
            falseButton = node.find("button")[0].outerHTML;
            falseButton = falseButton
                .replace('<button', '<span')
                .replace('</button>', '</span>');
            $("#add_to_cart")
                .parent()
                .addClass("clear")
                .addClass("clearfix")
                .append('<p class="buttons_bottom_block">' + falseButton + '</p>');
        } else {
            node = $(".add .add-to-cart:eq(0)").parent().clone();
            node.addClass("falseButtonContainer");
            node = $(".add .add-to-cart:eq(0)").parent().clone();
            node.addClass("falseButtonContainer");
            node.find(".add-to-cart").addClass("falseButton").removeAttr('data-button-action');
            falseButton = node.find(".add-to-cart")[0].outerHTML;
            falseButton = falseButton
                .replace('<button', '<span')
                .replace('</button>', '</span>');

            $(".add .add-to-cart:eq(0)")
                .addClass("clear")
                .addClass("clearfix")
                .after(falseButton);

            // $("#add-to-cart-or-refresh")
            //   .addClass("clear")
            //   .addClass("clearfix")
            //   .after(node[0].outerHTML);
        }
        $('body').addClass('ndkacf-falseButton');
        $(".ndkcfLoaded .add .add-to-cart.falseButton").removeAttr("disabled");
    }
}

$(document).on("click", ".ndkcfLoaded .add-to-cart.falseButton", function (e) {
    e.preventDefault();
    $("#submitNdkcsfields").trigger("click");
});

function setValueProdImage() {
    if (typeof setValueProdImage_Override == "function") {
        return setValueProdImage_Override();
    }
    $(".load_product_image").each(function () {
        prodImg = $("#bigpic").attr("data-original-image");
        $(this)
            .attr("src", prodImg)
            .attr("data-src", prodImg)
            .attr("data-thumb", prodImg);
    });
}

/* Manage custom thumbnails and preview */
function generateThumbnailsMapping(initThumbsNb) {
    var mappingArray = [];
    var index = 0;
    $('.groupFieldBlock .fieldPane > div:first-child').each(function (i, el) {
        if ($(this).is('.img-type-container') || $(this).find('[class*=visual-effect]').length > 0) {
            mappingArray.push({
                'thumbnailIndex': initThumbsNb + index,
                'lastEncounteredThumbnailIndex': initThumbsNb + index,
                'optionLabel': $(el).parents().eq(1).find('label').eq(0).clone().children().remove().end().text().trim(),
            })
            index++;
        } else {
            mappingArray.push({
                'lastEncounteredThumbnailIndex': initThumbsNb + index,
                'optionLabel': $(el).parents().eq(1).find('label').eq(0).clone().children().remove().end().text().trim(),
            })
        }
    })
    return mappingArray;
}

function findThumbnailIndex(clickedElLabel) {
    var a = 0;
    $.each(customThumbsMapping, function (index, value) {
        if (clickedElLabel === value.optionLabel) {
            a = value.lastEncounteredThumbnailIndex;
            return false;
        }
    })
    return a;
}

function resetCustomThumbnails(fromIndex) {
    // Remove uneeded thumbs
    removeFrom = fromIndex > initialNbOfThumbnails ? fromIndex : initialNbOfThumbnails;
    thumbnailContainer.find('li').slice(removeFrom).remove();
    $('#product-modal ul.product-images').find('li').slice(removeFrom).remove();

    // Reset all ?
    if (fromIndex === initialNbOfThumbnails) {
        img = $('#bigpic');
        // Reset #bigpic src to original one
        img.attr('src', $('#bigpic').attr('data-original-image'));
        // Remove all visuals from preview
        $('#image-block').find('[id^=visual_]').remove();
        //Reset first thumb
        $(thumbnailContainer).find('li').eq(0).replaceWith(formatImgForGallery(img));
        $('#product-modal ul.product-images').find('li').eq(0).replaceWith(formatImgForGallery(img, true));
    }
}

function formatImgForGallery(originalImg, isModal = false) {
    if (originalImg.parent().is('.img-select-container')) {
        dataLargeSrc = originalImg.prop('src').replace('/thumbs/', '/');;
    } else {
        dataLargeSrc = originalImg.prop('src');
    }
    className = isModal ? 'js-modal-thumb' : 'js-thumb';
    width = isModal ? '250px' : '100px';

    html = '<li class="thumb-container">'
    html += '<img class="thumb ' + className + '"';
    if (!isModal) {
        html += ' data-image-medium-src=' + dataLargeSrc + '"';
    }
    html += ' data-image-large-src="' + dataLargeSrc + '"';
    html += ' src="' + originalImg.prop('src') + '"';
    html += ' "alt="" title="" itemprop="image" width="' + width + '">';
    if (!isModal) html += '&nbsp';
    html += '</li>'

    return html;
}

function handleCustomThumbnails(el) {
    var imgElement = $(el).find('.img-value');
    // Trigger ndk module img click event (visual change of preview)
    imgElement.trigger('clickVisualEffect');
    // Retrieve option label for visual
    categoryImgLabel = $(el).parents().eq(2).find('label').eq(0).clone().children().remove().end().text().trim();
    // Retrieve thumbs gallery expected index
    thumbIndex = findThumbnailIndex(categoryImgLabel);
    // Check if this expected index exists in thumbs gallery
    thumbnailEl = thumbnailContainer.find('li').eq(thumbIndex);
    // If it does not exist, add img to gallery and hidden modal
    if (thumbnailEl.length === 0) {
        thumbnailContainer
            .append(formatImgForGallery(imgElement));
        $('#product-modal ul.product-images')
            .append(formatImgForGallery(imgElement, true));
        // Ndk module add arrows if more than 2 elements
        // @TODO Intégrer une fonctionnalité de scroll qui ne modifie pas l'ordre des élements
        // imageScrollBox();
    } else {
        // If it already exists, replace it with the good one
        $(thumbnailEl)
            .replaceWith(formatImgForGallery(imgElement));
        $('#product-modal ul.product-images')
            .find('li').eq(thumbIndex)
            .replaceWith(formatImgForGallery(imgElement, true));
    }
    // Change first img to last visual
    lastVisual = $('[id^=visual_]:last-child img').eq(0);
    $(thumbnailContainer).find('li').eq(0).replaceWith(formatImgForGallery(lastVisual));
    $('#product-modal ul.product-images').find('li').eq(0).replaceWith(formatImgForGallery(lastVisual, true));
};

$(document).on('click', '.img-select-container', function () {
    handleCustomThumbnails(this);
})
/* /Manage custom thumbnails and preview */
/* Add next step to text type fields */
$(document).on("change keydown", ".noborderSimple", function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (event.type == 'change' || keycode === 13 || keycode === 9) {
        rootInput = $(this).parent().parent().find(".simpleText");
        goTonextStepOverride(rootInput.attr("data-group"));
    }
});
/* /Add next step to text type fields */
