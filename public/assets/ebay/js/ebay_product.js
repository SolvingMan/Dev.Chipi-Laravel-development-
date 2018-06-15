jQuery(document).ready(function () {
    init();
    setMainPrice();
    var productData = {};
    var possibleVariations = [];
    productData.productURL = location.href;
    //window.history.pushState("", "", location.pathname);
    productData.availableQuantity = jsData.Quantity;
    productData.selectedSku = "";
    productData.quantity = 1;
    if (typeof jsData.shekelShippingServiceCost == "number") {
        var shippingPrice = jsData.shekelShippingServiceCost;
    } else {
        var shippingPrice = 0;
    }

    function setMainPrice() {
        if (jsData.hasOwnProperty("Variations")) {
            var mainPrice = jsData.Variations.Variation[0].StartPrice.Value;
            var isChanged = false;
            for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                if (jsData.Variations.Variation[i].StartPrice.Value < mainPrice) {
                    mainPrice = jsData.Variations.Variation[i].StartPrice.Value;
                    isChanged = true;
                }
            }
            if (!isChanged) {
                $('.start-price').hide();
            } else {
                $('.start-price').show();
            }
            $("#price").html(mainPrice);
        }
    }

    function getVariations() {
        var variations = [];
        jQuery.each($('.variation'), function (index, value) {
            {
                var characterictic = {};
                characterictic.value = addslashes($(this).val());
                characterictic.dataName = $(this).data("name");
                variations[index] = characterictic;
            }
        });
        return variations;
    }

    function getSelectedVariations(variations) {
        var selectedVariations = [];
        for (var i = 0; i < variations.length; i++) {
            if (variations[i].value != "") {
                selectedVariations.push(variations[i]);
            }
        }
        return selectedVariations;
    }


    function init() {
        if ($('.variation').length == 1) {
            if (jsData.Variations.VariationSpecificsSet.NameValueList[0].Value.length == jsData.Variations.Variation.length) {
                // for (var i = 0; i < jsData.Variations.VariationSpecificsSet.NameValueList[0].Value.length; i++) {
                for (var j = 0; j < jsData.Variations.Variation.length; j++) {
                    // for (var k = 0; k < jsData.Variations.Variation[j].VariationSpecifics.NameValueList.length; k++) {
                    //     if (jsData.Variations.VariationSpecificsSet.NameValueList[0].Value[i] == jsData.Variations.Variation[j]
                    //             .VariationSpecifics.NameValueList[k].Value[0]) {
                    if (jsData.Variations.Variation[j].Quantity == 0) {
                        var option = $("option[value=\"" + jsData.Variations.Variation[j].VariationSpecifics
                            .NameValueList[0].Value[0] + "\"]");
                        option.attr("disabled", "disabled").text(option.text() + " [נגמר במלאי]");
                    }
                    // }
                    // }
                }
                // }
            }
            else {
                var isDisabled = true;
                for (var i = 0; i < jsData.Variations.VariationSpecificsSet.NameValueList[0].Value.length; i++) {
                    isDisabled = true;
                    for (var j = 0; j < jsData.Variations.Variation.length; j++) {
                        for (var k = 0; k < jsData.Variations.Variation[j].VariationSpecifics.NameValueList.length; k++) {
                            if (jsData.Variations.VariationSpecificsSet.NameValueList[0].Value[i] == jsData.Variations.Variation[j]
                                    .VariationSpecifics.NameValueList[k].Value[0]) {

                                if (jsData.Variations.Variation[j].Quantity != 0) {
                                    isDisabled = false;
                                }
                            }
                        }
                    }
                    if (isDisabled) {
                        var option = $("option[value=\"" + jsData.Variations.VariationSpecificsSet.NameValueList[0].Value[i] + "\"]");
                        option.attr("disabled", "disabled").text(option.text() + " [נגמר במלאי]");
                    }
                }
            }
        }
    }

    if ($('.variation').length > 1) {
        for (var i = 0; i < jsData.Variations.VariationSpecificsSet.NameValueList.length; i++) {
            for (var j = 0; j < jsData.Variations.VariationSpecificsSet.NameValueList[i].Value.length; j++) {
                if (!isStock(jsData.Variations.VariationSpecificsSet.NameValueList[i].Value[j])) {
                    var option = $("option[value=\"" + jsData.Variations.VariationSpecificsSet.NameValueList[i].Value[j] + "\"]");
                    option.attr("disabled", "disabled").text(option.text() + " [נגמר במלאי]");
                }
            }
        }
    }

    if (!jsData.hasOwnProperty("Variations")) {
        var totalPrice = jsData.shekelPrice + shippingPrice;
        $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
    } else {
        $('#total-price').html('בחר מהאפשרויות');
    }

    function isStock(valueName) {
        for (var i = 0; i < jsData.Variations.Variation.length; i++) {
            for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                if (jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0] == valueName &&
                    jsData.Variations.Variation[i].Quantity > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    function existInArray(array, element) {
        for (var i = 0; i < array.length; i++) {
            if (array[i].Name == element.Name && array[i].Value[0] == element.Value[0])
                return true;
        }
        return false;
    }

    function addslashes(str) {
        return str.replace('/([\"\'])/g', "\\$1");
    }

    $('.variation').on("change", function (e) {
        e.preventDefault();
        var coincidence = 0;
        var fillInSelect = [];
        var variations = getVariations();
        var selectedVariations = getSelectedVariations(variations);
        if (jsData.hasOwnProperty("Variations") && jsData.Variations.hasOwnProperty("Pictures")) {
            for (var i = 0; i < jsData.Variations.Pictures.length; i++) {
                for (var j = 0; j < variations.length; j++) {
                    if (variations[j].value != "") {
                        if (jsData.Variations.Pictures[i].VariationSpecificName == variations[j].dataName) {
                            for (var k = 0; k < jsData.Variations.Pictures[i].VariationSpecificPictureSet.length; k++) {
                                if (variations[j].value == addslashes(jsData.Variations.Pictures[i].VariationSpecificPictureSet[k].VariationSpecificValue)) {
                                    if (jsData.Variations.Pictures[i].VariationSpecificPictureSet[k].hasOwnProperty("PictureURL")) {
                                        $('#img_zoom').data("zoom-image", jsData.Variations.Pictures[i].VariationSpecificPictureSet[k].PictureURL[0]);
                                        $('#img_zoom').attr("src", jsData.Variations.Pictures[i].VariationSpecificPictureSet[k].PictureURL[0]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (variations.length == 2) {
            for (var i = 0; i < variations.length; i++) {
                if ($(this).data("name") != variations[i].dataName) {
                    $("[data-name=\"" + variations[i].dataName + "\"]").html("").append($('<option>',
                        {
                            value: "",
                            text: "Choose " + variations[i].dataName
                        }));
                }
            }
            if ($(this).val() == "") {
                for (var i = 0; i < jsData.Variations.VariationSpecificsSet.NameValueList.length; i++) {
                    if (jsData.Variations.VariationSpecificsSet.NameValueList[i].Name != $(this).data("name")) {
                        for (var j = 0; j < jsData.Variations.VariationSpecificsSet.NameValueList[i].Value.length; j++) {
                            $("[data-name=\"" + jsData.Variations.VariationSpecificsSet.NameValueList[i].Name + "\"]").append($('<option>',
                                {
                                    value: addslashes(jsData.Variations.VariationSpecificsSet.NameValueList[i].Value[j]),
                                    text: jsData.Variations.VariationSpecificsSet.NameValueList[i].Value[j]
                                }));
                        }
                    }
                }
            }
            for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                if (jsData.Variations.Variation[i].Quantity > 0) {
                    for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                        if ($(this).val() == jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0]) {
                            for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                if ($(this).data("name") != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name) {
                                    $("[data-name=\"" + jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name + "\"]").append($('<option>',
                                        {
                                            value: addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Value[0]),
                                            text: jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Value[0]
                                        }));
                                }
                            }
                        }
                    }
                }
            }
            for (var i = 0; i < variations.length; i++) {
                if ($(this).data("name") != variations[i].dataName) {
                    $("[data-name=\"" + variations[i].dataName + "\"] option[value=\"" + variations[i].value + "\"]").attr("selected", "selected");
                }
            }
        }
        if (variations.length == 3) {
            if (selectedVariations.length == 1) {
                for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                    if (jsData.Variations.Variation[i].Quantity > 0) {
                        for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                            if (selectedVariations[0].value == addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0])) {
                                for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                    if (selectedVariations[0].dataName != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name &&
                                        !existInArray(fillInSelect, jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k])) {
                                        fillInSelect.push(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k]);
                                    }
                                }
                            }
                        }
                    }
                }
                for (var i = 0; i < variations.length; i++) {
                    if (selectedVariations[0].dataName != variations[i].dataName) {
                        $("[data-name=\"" + variations[i].dataName + "\"]").html("").append($('<option>',
                            {
                                value: "",
                                text: "Choose " + variations[i].dataName
                            }));
                    }
                }
                for (var i = 0; i < fillInSelect.length; i++) {
                    $("[data-name=\"" + fillInSelect[i].Name + "\"]").append($('<option>',
                        {
                            value: addslashes(fillInSelect[i].Value[0]),
                            text: fillInSelect[i].Value[0]
                        }));
                }
                for (var i = 0; i < variations.length; i++) {
                    $("[data-name=\"" + variations[i].dataName + "\"] option[value=\"" + variations[i].value + "\"]").attr("selected", "selected");
                }
            }
            if (selectedVariations.length == 2) {
                var fillInSelect1 = [];
                for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                    if (jsData.Variations.Variation[i].Quantity > 0) {
                        for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                            if (selectedVariations[0].value == addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0])) {
                                for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                    if (selectedVariations[0].dataName != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name &&
                                        !existInArray(fillInSelect1, jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k])) {
                                        fillInSelect1.push(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k]);
                                    }
                                }
                            }
                        }
                    }
                }
                var fillInSelect2 = [];
                for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                    if (jsData.Variations.Variation[i].Quantity > 0) {
                        for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                            if (selectedVariations[1].value == addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0])) {
                                for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                    if (selectedVariations[1].dataName != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name &&
                                        !existInArray(fillInSelect2, jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k])) {
                                        fillInSelect2.push(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k]);
                                    }
                                }
                            }
                        }
                    }
                }
                for (var i = 0; i < fillInSelect1.length; i++) {
                    if (fillInSelect1[i].Name == selectedVariations[1].dataName ||
                        existInArray(fillInSelect2, fillInSelect1[i])) {
                        fillInSelect.push(fillInSelect1[i]);
                    }
                }
                for (var i = 0; i < fillInSelect2.length; i++) {
                    if ((fillInSelect2[i].Name == selectedVariations[0].dataName ||
                            existInArray(fillInSelect1, fillInSelect2[i]))
                        && !existInArray(fillInSelect, fillInSelect2[i])) {
                        fillInSelect.push(fillInSelect2[i]);
                    }
                }
                for (var i = 0; i < variations.length; i++) {
                    $("[data-name=\"" + variations[i].dataName + "\"]").html("").append($('<option>',
                        {
                            value: "",
                            text: "Choose " + variations[i].dataName
                        }));
                }
                for (var i = 0; i < fillInSelect.length; i++) {
                    $("[data-name=\"" + fillInSelect[i].Name + "\"]").append($('<option>',
                        {
                            value: addslashes(fillInSelect[i].Value[0]),
                            text: fillInSelect[i].Value[0]
                        }));
                }
                for (var i = 0; i < variations.length; i++) {
                    $("[data-name=\"" + variations[i].dataName + "\"] option[value=\"" + variations[i].value + "\"]").attr("selected", "selected");
                }
            }
            if (selectedVariations.length == 3) {
                var fillInSelect1 = [];
                for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                    if (jsData.Variations.Variation[i].Quantity > 0) {
                        for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                            if (selectedVariations[0].value == addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0])) {
                                for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                    if (selectedVariations[0].dataName != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name &&
                                        !existInArray(fillInSelect1, jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k])) {
                                        fillInSelect1.push(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k]);
                                    }
                                }
                            }
                        }
                    }
                }
                var fillInSelect2 = [];
                for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                    if (jsData.Variations.Variation[i].Quantity > 0) {
                        for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                            if (selectedVariations[1].value == addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0])) {
                                for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                    if (selectedVariations[1].dataName != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name &&
                                        !existInArray(fillInSelect2, jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k])) {
                                        fillInSelect2.push(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k]);
                                    }
                                }
                            }
                        }
                    }
                }
                var fillInSelect3 = [];
                for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                    if (jsData.Variations.Variation[i].Quantity > 0) {
                        for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                            if (selectedVariations[2].value == addslashes(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0])) {
                                for (var k = 0; k < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; k++) {
                                    if (selectedVariations[2].dataName != jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k].Name &&
                                        !existInArray(fillInSelect3, jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k])) {
                                        fillInSelect3.push(jsData.Variations.Variation[i].VariationSpecifics.NameValueList[k]);
                                    }
                                }
                            }
                        }
                    }
                }
                for (var i = 0; i < fillInSelect1.length; i++) {
                    if ((fillInSelect1[i].Name == selectedVariations[1].dataName
                            && existInArray(fillInSelect3, fillInSelect1[i])) ||
                        (fillInSelect1[i].Name == selectedVariations[2].dataName
                            && existInArray(fillInSelect2, fillInSelect1[i]))) {
                        fillInSelect.push(fillInSelect1[i]);
                    }
                }
                for (var i = 0; i < fillInSelect2.length; i++) {
                    if (((fillInSelect2[i].Name == selectedVariations[0].dataName
                            && existInArray(fillInSelect3, fillInSelect2[i])) ||
                            (fillInSelect2[i].Name == selectedVariations[2].dataName
                                && existInArray(fillInSelect1, fillInSelect2[i])))
                        && !existInArray(fillInSelect, fillInSelect2[i])) {
                        fillInSelect.push(fillInSelect2[i]);
                    }
                }
                for (var i = 0; i < fillInSelect3.length; i++) {
                    if (((fillInSelect3[i].Name == selectedVariations[0].dataName
                            && existInArray(fillInSelect2, fillInSelect3[i])) ||
                            (fillInSelect3[i].Name == selectedVariations[1].dataName
                                && existInArray(fillInSelect1, fillInSelect3[i])))
                        && !existInArray(fillInSelect, fillInSelect3[i])) {
                        fillInSelect.push(fillInSelect3[i]);
                    }
                }
                for (var i = 0; i < variations.length; i++) {
                    $("[data-name=\"" + variations[i].dataName + "\"]").html("").append($('<option>',
                        {
                            value: "",
                            text: "Choose " + variations[i].dataName
                        }));
                }
                for (var i = 0; i < fillInSelect.length; i++) {
                    $("[data-name=\"" + fillInSelect[i].Name + "\"]").append($('<option>',
                        {
                            value: addslashes(fillInSelect[i].Value[0]),
                            text: fillInSelect[i].Value[0]
                        }));
                }
                for (var i = 0; i < variations.length; i++) {
                    $("[data-name=\"" + variations[i].dataName + "\"] option[value=\"" + variations[i].value + "\"]").attr("selected", "selected");
                }
            }
        }
        if (selectedVariations.length == variations.length) {
            for (var i = 0; i < jsData.Variations.Variation.length; i++) {
                coincidence = 0;
                for (var j = 0; j < jsData.Variations.Variation[i].VariationSpecifics.NameValueList.length; j++) {
                    for (var k = 0; k < variations.length; k++) {
                        var dataName = jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Name.replace(/&amp;/g, '&');
                        if (variations[k].value == jsData.Variations.Variation[i].VariationSpecifics.NameValueList[j].Value[0] &&
                            variations[k].dataName == dataName) {
                            coincidence++;
                        }
                    }
                }
                if (coincidence == variations.length) {
                    var arrayResult = {};
                    $('.modal-variation').html("");
                    for (var z = 0; z < variations.length; z++) {
                        arrayResult[variations[z].dataName] = variations[z].value;
                        $('.modal-variation').append('<p>' + variations[z].dataName + " " + variations[z].value + '</p>');
                    }
                    arrayResult["numberVariation"] = i;
                    productData.selectedSku = arrayResult;
                    var totalPrice = $(".input-qty").val() * (jsData.Variations.Variation[i].StartPrice.Value
                        + shippingPrice);
                    $('.start-price').hide();
                    $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
                    $("#price").html(jsData.Variations.Variation[i].StartPrice.Value);
                    $(".button-price").html(jsData.Variations.Variation[i].StartPrice.Value);
                    $("#available-amount").html(jsData.Variations.Variation[i].Quantity);
                    jsData.shekelPrice = jsData.Variations.Variation[i].StartPrice.Value;
                    productData.availableQuantity = jsData.Variations.Variation[i].Quantity;
                    break;
                }
            }
        }
    });

    function updateShortcart() {
        $.ajax({
            url: "/cart/shortcart",
            success: function (response) {
                $('.minicart-content-wrapper').html(response);
            }
        })
    }

    $('.add-to-cart-button').on('click', function (e) {
        e.preventDefault();
        var variations = getVariations();
        var selectedVariations = getSelectedVariations(variations);
        // console.log(selectedVariations);
        // console.log(variations);

        if ($('.variation').length != 0 && selectedVariations.length != variations.length || jsData.ListingStatus == "Completed") {
            $('#chooseVariationsModal').modal('show');
            $('.choose-variations').text("לא נבחרו אופציות , בחר: ");
            var k = 0;
            for (var i = 0; i < variations.length; i++) {
                k = 0;
                for (var j = 0; j < selectedVariations.length; j++) {
                    if (selectedVariations[j].dataName == variations[i].dataName) {
                        k++;
                    }
                }
                if (k == 0) {
                    $('.choose-variations').append(variations[i].dataName + ' ');
                }
            }
            return false;
        }

        // make some marafets to god-object Product data

        productData.image = jsData.PictureURL[0];
        productData.shippingCompany = jsData.shippingService;
        productData.shippingPrice = jsData.shekelShippingServiceCost;
        productData.original_shippingPrice = jsData.shippingServiceCost;
        productData.mainPrice = jsData.shekelPrice;
        productData.original_mainPrice = jsData.ConvertedCurrentPrice.Value;
        productData.title = jsData.Title;
        productData.sitename = "ebay";

        productData.productID = jsData.ItemID;


        console.log(productData);
        var url = "/cart/add";
        var data = productData;
        var token = $('#add-to-cart-form').serializeArray()[0];
        $.ajax({
            type: "POST",
            url: url,
            data: {
                "_token": token.value,
                "product": data
            },
            success: function (data) {
                // some animation here, probably
                console.log(data);
                $('.cart-items-count').text(data);
                updateShortcart();
            },
            complete: function (data) {
            }
        });
    });
    $("#quantity-less").on('click', function (e) {
        e.preventDefault();
        recalculateQuantity(-1);
    });

    $("#quantity-more").on('click', function (e) {
        e.preventDefault();
        var available_amount = $('#available-amount').text();
        var qty1 = $('#qty1').val();
        if (qty1 == available_amount) {
            return false;
        } else {
            recalculateQuantity(1);
        }
    });

    $('#qty1').keyup(function () {
        var qty1 = $(this).val();
        var available_amount = $('#available-amount').text();

        if (Number(qty1) > Number(available_amount)) {
            $(this).val(available_amount);
            return false;
        }
    });

    function recalculateQuantity(modifier) {
        var quant = +$('.input-qty').val();
        if (modifier == 0) $('.input-qty').val(1);

        var newQuantity = +(quant + modifier);
        if (newQuantity == 0) return;

        $('.input-qty').val(+newQuantity);

        var totalPrice = (parseInt($("#price").text()) + parseInt(shippingPrice)) *
            (parseInt($('.input-qty').val()));
        $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
        //var currentPrice = +productData.mainPrice;
        //var endPrice = +(currentPrice * newQuantity);
        //$('#total-price').html(endPrice);
        productData.quantity = newQuantity;
        //console.log(productData.quantity);
    }

    $(".translate-tab-hebrew").on("click", function (e) {
        e.preventDefault();
        $(".translate-tab-english").removeClass("selected-tab");
        $(this).addClass("selected-tab");
        $(".characteristics-english").addClass("hidden");
        $(".characteristics-hebrew").removeClass("hidden");
    });
    $(".translate-tab-english").on("click", function (e) {
        e.preventDefault();
        $(".translate-tab-hebrew").removeClass("selected-tab");
        $(this).addClass("selected-tab");
        $(".characteristics-hebrew").addClass("hidden");
        $(".characteristics-english").removeClass("hidden");
    });
    $(".translate-iframe-hebrew").on("click", function (e) {
        e.preventDefault();
        $(".translate-iframe-english").removeClass("selected-tab");
        $(this).addClass("selected-tab");
        $(".iframe-english").addClass("hidden");
        $(".iframe-hebrew").removeClass("hidden");
    });
    $(".translate-iframe-english").on("click", function (e) {
        e.preventDefault();
        $(".translate-iframe-hebrew").removeClass("selected-tab");
        $(this).addClass("selected-tab");
        $(".iframe-hebrew").addClass("hidden");
        $(".iframe-english").removeClass("hidden");
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.btn-cart-fixed').addClass('btn-show');
        } else {
            $('.btn-cart-fixed').removeClass('btn-show');
        }
    });
})
;
