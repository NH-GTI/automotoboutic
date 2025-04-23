var formatedPrices = [];

function formatCurrency17_back(
  price,
  currencyFormat17,
  currencySign,
  currencyBlank
) {
  // if you modified this function, don't forget to modify the PHP function displayPrice (in the Tools.php class)

  var currencyFormat = currencyFormat17;

  var blank = " ";
  var priceDisplayPrecision = 2;
  price = parseFloat(price.toFixed(10));
  price = ps_round(price, priceDisplayPrecision);
  if (currencyBlank > 0) blank = " ";
  if (currencyFormat == 1)
    return (
      currencySign +
      blank +
      formatNumber(price, priceDisplayPrecision, ",", ".")
    );
  if (currencyFormat == 2)
    return (
      formatNumber(price, priceDisplayPrecision, " ", ",") +
      blank +
      currencySign
    );
  if (currencyFormat == 3)
    return (
      currencySign +
      blank +
      formatNumber(price, priceDisplayPrecision, ".", ",")
    );
  if (currencyFormat == 4)
    return (
      formatNumber(price, priceDisplayPrecision, ",", ".") +
      blank +
      currencySign
    );
  if (currencyFormat == 5)
    return (
      currencySign +
      blank +
      formatNumber(price, priceDisplayPrecision, "'", ".")
    );
  return price;
}

function formatCurrency17(
  price,
  currencyFormat17,
  currencySign,
  currencyBlank
) {
  if (parseFloat(price) in formatedPrices) {
    return formatedPrices[parseFloat(price)];
  } else {
    var response = "";
    $.ajax({
      type: "GET",
      async: false,
      url:
        baseUrl +
        "modules/ndk_advanced_custom_fields/front_ajax.php?action=formatPrice",
      data: {
        price: parseFloat(price),
      },
      success: function (data) {
        response = data;
        formatedPrices[parseFloat(price)] = data;
      },
    });
    return response;
  }
}

function formatNumber(value, numberOfDecimal, thousenSeparator, virgule) {
  value = value.toFixed(numberOfDecimal);
  var val_string = value + "";
  var tmp = val_string.split(".");
  var abs_val_string = tmp.length === 2 ? tmp[0] : val_string;
  var deci_string = ("0." + (tmp.length === 2 ? tmp[1] : 0)).substr(2);
  var nb = abs_val_string.length;

  for (var i = 1; i < 4; i++)
    if (value >= Math.pow(10, 3 * i))
      abs_val_string =
        abs_val_string.substring(0, nb - 3 * i) +
        thousenSeparator +
        abs_val_string.substring(nb - 3 * i);

  if (parseInt(numberOfDecimal) === 0) return abs_val_string;
  return abs_val_string + virgule + (deci_string > 0 ? deci_string : "00");
}

function ps_round(value, places) {
  if (typeof roundMode === "undefined") roundMode = 2;
  if (typeof places === "undefined") places = 2;

  var method = roundMode;

  if (method === 0) return ceilf(value, places);
  else if (method === 1) return floorf(value, places);
  else if (method === 2) return ps_round_half_up(value, places);
  else if (method == 3 || method == 4 || method == 5) {
    // From PHP Math.c
    var precision_places = 14 - Math.floor(ps_log10(Math.abs(value)));
    var f1 = Math.pow(10, Math.abs(places));

    if (precision_places > places && precision_places - places < 15) {
      var f2 = Math.pow(10, Math.abs(precision_places));
      if (precision_places >= 0) tmp_value = value * f2;
      else tmp_value = value / f2;

      tmp_value = ps_round_helper(tmp_value, roundMode);

      /* now correctly move the decimal point */
      f2 = Math.pow(10, Math.abs(places - precision_places));
      /* because places < precision_places */
      tmp_value /= f2;
    } else {
      /* adjust the value */
      if (places >= 0) tmp_value = value * f1;
      else tmp_value = value / f1;

      if (Math.abs(tmp_value) >= 1e15) return value;
    }

    tmp_value = ps_round_helper(tmp_value, roundMode);
    if (places > 0) tmp_value = tmp_value / f1;
    else tmp_value = tmp_value * f1;

    return tmp_value;
  }
}

function ps_round_half_up(value, precision) {
  var mul = Math.pow(10, precision);
  var val = value * mul;

  var next_digit = Math.floor(val * 10) - 10 * Math.floor(val);
  if (next_digit >= 5) val = Math.ceil(val);
  else val = Math.floor(val);

  return val / mul;
}

// colorize
var low = { r: 255, g: 255, b: 0 },
  mid = { r: 255, g: 0, b: 255 }, // mid
  high = { r: 0, g: 200, b: 200 }; // high

function updateColorLabels() {
  // set the swatch color
  $($("#LowColorPicker .ColorBlotch")[0]).css(
    "background-color",
    toRGBCSS(low)
  );
  $($("#MidColorPicker .ColorBlotch")[0]).css(
    "background-color",
    toRGBCSS(mid)
  );
  $($("#HighColorPicker .ColorBlotch")[0]).css(
    "background-color",
    toRGBCSS(high)
  );

  // show the color in the label (rgb | hex)
  $("#LowColorPicker .label").html(
    "Outline: rgb(" +
      low.r +
      ", " +
      low.b +
      ", " +
      low.g +
      ")  " +
      NDKrgbToHex(low.r, low.b, low.g)
  );
  $("#MidColorPicker .label").html(
    "Body: rgb(" +
      mid.r +
      ", " +
      mid.b +
      ", " +
      mid.g +
      ") " +
      NDKrgbToHex(mid.r, mid.b, mid.g)
  );
  $("#HighColorPicker .label").html(
    "Highlight: rgb(" +
      high.r +
      ", " +
      high.b +
      ", " +
      high.g +
      ") " +
      NDKrgbToHex(high.r, high.b, high.g)
  );
}

function updateView(_low, _mid, _high, options) {
  // colorize the first image
  colorizeImg("#headImage", "#headCanvas", "#headTarget", low, mid, high, {
    base: 100,
    high: 190,
  });
}

function toRGBCSS(rgb) {
  // convert object to css data
  return "rgb(" + rgb.r + "," + rgb.b + "," + rgb.g + ")";
}

function cleanUpColor(str) {
  // convert css string to object
  var color = str
    .replace(" ", "")
    .replace(" ", "")
    .replace("rgb(", "")
    .replace(")", "")
    .split(",");
  return { r: color[0], b: color[1], g: color[2] };
}

// click events for swatch changes
$("#LowColorPicker .ColorBlotch").bind("click", function () {
  updateView((low = cleanUpColor($(this).css("background-color"))), mid, high);
});

// [r|g|b]->hex
function NDKcomponentToHex(c) {
  var hex = Math.floor(c).toString(16);
  if (hex.length < 2) hex = "0" + hex;
  return hex;
}

// [r,g,b]->hex
function NDKrgbToHex(r, g, b) {
  return (
    "#" + NDKcomponentToHex(r) + NDKcomponentToHex(g) + NDKcomponentToHex(b)
  );
}

function NDKhexToRgb(hex) {
  // http://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
  // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
  var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  hex = hex.replace(shorthandRegex, function (m, r, g, b) {
    return r + r + g + g + b + b;
  });

  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result
    ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16),
      }
    : null;
}

// convert original img src to new img with a canvas pass-through and alter colors
function colorizeImg(
  sourceImg,
  targetCanvas,
  targetImg,
  low,
  mid,
  high,
  options
) {
  // colors
  lowColor = low;
  midColor = mid;
  highColor = high;

  // threshold
  var base = 70;
  var high = 200;

  // get source data
  // grab image width/height from data
  image = $(sourceImg).get(0);
  var width = image.width;
  var height = image.height;

  // customize threshold
  if (options != undefined) {
    if (options["base"] != undefined) base = options["base"];
    if (options["high"] != undefined) high = options["high"];
    if (options["width"] != undefined) width = options["width"];
    if (options["height"] != undefined) height = options["height"];
  }

  // console.log(width + "  " + height);
  $(targetCanvas)[0].setAttribute("width", width);
  $(targetCanvas)[0].setAttribute("height", height);

  var canvas = $(targetCanvas).get(0);
  (context = canvas.getContext("2d")), context.drawImage(image, 0, 0);

  // grab the pixel data
  var imgd = context.getImageData(0, 0, width, height),
    pixels = imgd.data,
    pixelsLen = pixels.length;

  // Loop through all pixels
  for (var i = 0, n = pixelsLen; i < n; i += 4) {
    // average the pixel colors to get threshold
    var average = (pixels[i] + pixels[i + 1] + pixels[i + 2]) / 3;
    var color = lowColor;
    if (average > base) {
      color = average > high ? highColor : midColor;
    }

    // set colors
    pixels[i] = color.r; // RED
    pixels[i + 1] = color.b; // BLUE
    pixels[i + 2] = color.g; // GREEN
    //pixels[i+3] transparency
  }
  // apply the new pixel info to the canvas
  context.putImageData(imgd, 0, 0);

  // apply the next data to the target image src
  $(targetImg).attr("src", canvas.toDataURL("image/png"));
}

function utf8_to_b64(str) {
  return window.btoa(decodeURIComponent(encodeURIComponent(str)));
}

function b64_to_utf8(str) {
  return decodeURIComponent(encodeURIComponent(window.atob(str)));
}

function ndkZipEncode(data) {
  return utf8_to_b64(RawDeflate.deflate(encodeURIComponent(data)));
}

function addSymbolsToText() {
  var symbols = ["♬", "♛", "☂"];

  $(".symbol-select").remove();
  $(".noborder")
    .after('<select class="symbol-select"></select>')
    .trigger("blur");

  $(".symbol-select").append('<option value="">symbols</option>');
  for (var i = 0; i < symbols.length; i++) {
    $(".symbol-select").append(
      '<option value="' + symbols[i] + '">' + symbols[i] + "</option>"
    );
  }

  $(document).on("change", ".symbol-select", function () {
    targetField = $(this).prev(".noborder");
    currVal = targetField.val();
    targetField.val(currVal + $(this).val());
  });
}

function getParameterByName(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
  return results == null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf("?") !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, "$1" + key + "=" + value + "$2");
  } else {
    return uri + separator + key + "=" + value;
  }
}

$.fn.getVisibleOffset = function (){
  var scrollTop = $(window).scrollTop(),
        scrollBot = scrollTop + $(window).height(),
        elTop = $(this).offset().top,
        elBottom = elTop + $(this).outerHeight(),
        visibleTop = elTop < scrollTop ? scrollTop : elTop,
        visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
    return visibleBottom - visibleTop;
};

// Resize popup
$(window).resize(function(){
  autoHeightPopup()
})
$('#ndkacf-modal').on('shown.bs.modal', function (e) {
  autoHeightPopup();
})
function autoHeightPopup(){
  $('.ndk-imgs-autoHeight').css({
    width : '',
    height : ''
  })
  o_width = $('.ndk-imgs-autoHeight').innerWidth();
  o_height = $('.ndk-imgs-autoHeight').innerHeight();
  p_height = $('#ndkacf_modal_body').getVisibleOffset();
  ratio = o_height/o_width;
  
  new_height = p_height;
  new_width  = p_height/ratio;
  
  $('.ndk-imgs-autoHeight').css({
    width : new_width,
    height : new_height
  });
}
// Resize popup
