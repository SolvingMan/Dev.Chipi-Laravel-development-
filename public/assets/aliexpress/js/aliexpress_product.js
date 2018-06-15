$(document).ready(function () {
    var selectedSku = [];

    ProductData.productURL = location.href;
    // window.history.pushState("", "", location.pathname);

    // set init quantity
    ProductData.quantity = 1;
    var quantity = $('#quantity');

    // set initial shipping
    // var shipping = $('#shippingSelector').val();
    // var company = shipping.split("#")[0];
    var company = "";
    // var cost = shipping.split("#")[1] == "free" ? 0 : shipping.split("#")[1];
    var cost = "";
    var shipping = ProductData.shipping.freight[0];

    ProductData.shipping = [];
    ProductData.shipping['company'] = shipping.company;
    ProductData.shipping['cost'] = shipping.price;
    ProductData.shipping['original_cost'] = shipping.original_price;

    // ProductData
    if (ProductData.skuVariation === null) {
        ProductData.mainPrice = Math.ceil(ProductData.skuData[0].skuVal.skuCalPrice * (+ProductData.exchangeRate));
        ProductData.original_mainPrice = Math.ceil(ProductData.skuData[0].skuVal.original_skuCalPrice * (+ProductData.exchangeRate));
        ProductData.selectedSkuVariant = ProductData.skuData[0];
        ProductData.selectedSku = {};
    }

    $('#available-amount').html(ProductData.skuData[0].skuVal.availQuantity);

    setMainPrice();
    setTotalPrice();
    grayOut();


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////// BINDING actions to events ///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //  make pictures switch on click
    $('.mini-pic, .gallery-little-pic').on('click', function (e) {
        e.preventDefault();
        var bigpic = $(this).data('bigpic');
        $("#big-image").attr('src', bigpic)
    });

    $("#quantity-less").on('click', function (e) {
        e.preventDefault();
        recalculateQuantity(-1);
    });

    $("#quantity-more").on('click', function (e) {
        e.preventDefault();
        var qty1 = $('#qty1').val();
        var available_amount = $('#available-amount').text();
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

    $('.shipping-company').on('click', function (e) {
        e.preventDefault();
        $('#total-price').html("");

        var company = $(this).data('company-title');
        var cost = $(this).data('cost') == "free" ? 0 : $(this).data('cost');
        var selectedShipping = "selected-shipping";
        var wasSelected = $(this).hasClass(selectedShipping);

        delete ProductData.shipping;
        $('.shipping-company').removeClass(selectedShipping);
        if (!wasSelected) {
            $(this).addClass(selectedShipping);
            $('#no-shipping-warning').prop('disabled', false);

            ProductData.shipping = [];
            ProductData.shipping['company'] = company;
            ProductData.shipping['cost'] = cost;
        }
        setTotalPrice();
    });

    // shipping switcher
    $('#shippingSelector').on('change', function (e) {
        e.preventDefault();
        $('#total-price').html("");

        var shipping = $(this).val();

        var company = shipping.split("#")[0];
        var cost = shipping.split("#")[1] == "free" ? 0 : shipping.split("#")[1];

        // var company = $(this).data('company-title');
        // var cost = $(this).data('cost') == "free" ? 0 : $(this).data('cost');

        var selectedShipping = "selected-shipping";
        var wasSelected = $(this).hasClass(selectedShipping);

        delete ProductData.shipping;
        $('.shipping-company').removeClass(selectedShipping);
        if (!wasSelected) {
            $(this).addClass(selectedShipping);
            $('#no-shipping-warning').prop('disabled', false);
        }

        ProductData.shipping = [];
        ProductData.shipping['company'] = company;
        ProductData.shipping['cost'] = cost;

        setTotalPrice();

    });

    $('.mini-pic').on('click', function (e) {
        var bigpic = $(this).data('bigpic');
        if(bigpic) {
            $('#img_zoom').attr("src",bigpic);
        }
    });
    //  price changers on switches
    $('.no-pic, .mini-pic').on('click', function (e) {
        e.preventDefault();
        var name = $(this).data('name');
        var skuId = $(this).data('id');
        var selected = $(this).hasClass("selected");

        // remove it from selected array
        delete selectedSku[name];

        // remove class from this
        $("[data-name='" + name + "']").removeClass("selected");

        // add class if it was not selected
        if (!selected) {
            $(this).addClass("selected");
            selectedSku[name] = skuId;
            ProductData.selectedSku = selectedSku;
        }

        setMainPrice();
        setTotalPrice();
        grayOut();
    });

    $('.add-to-cart-button').on('click', function (e) {
        e.preventDefault();

        // console.log(ProductData);
        if (!ProductData.hasOwnProperty('mainPrice')) {
            var k = 0;
            $('.choose-variations').text("לא נבחרו אופציות , בחר: ");
            for (var variation in ProductData.skuVariations) {
                k = 0;
                for (var selectedVariation in ProductData.selectedSku) {
                    if (variation == selectedVariation) {
                        k++;
                    }
                }
                if (k == 0) {
                    variation = variation.substring(0, variation.length - 1);
                    $('.choose-variations').append(variation + ' ');
                }
            }
            $('#chooseVariationsModal').modal('show');
            return false;
        }
        //  console.log(ProductData);
        // make some marafets to god-object Product data
        tidyProductData();

        $('#modal-variations').html("");

        for (var key in ProductData.selectedSku) {
            if (key === "numberVariation") continue;
            $('#modal-variations').append('<p>' + key + " " + ProductData.selectedSku[key] + '</p>');
        }

        var url = "/cart/add";
        // console.log(ProductData);
        var data = jQuery.extend(true, {}, ProductData);
        delete data['skuData'];

        $.ajax({
            type: "POST",
            url: url,
            data: {
                "product": data
            },
            success: function (data) {
                // some animation here, probably
                // console.log(data);
                $('.cart-items-count').text(data);
                updateShortcart();
            }
        });
    });


    //////////////////////////////////////// FUNCTIONS ///////////////////////////////////////////////////
    function updateShortcart() {
        $.ajax({
            url: "/cart/shortcart",
            success: function (response) {
                $('.minicart-content-wrapper').html(response);
            }
        })
    }

    function recalculateQuantity(modifier) {
        var quant = +$('.input-qty').val();
        if (modifier === 0) $('.input-qty').val(1);

        var newQuantity = +(quant + modifier);
        if (newQuantity === 0) return;

        qty(newQuantity);

        var currentPrice = +ProductData.mainPrice;
        var endPrice = +(currentPrice * newQuantity);

        if (endPrice !== 0 && endPrice !== null && !isNaN(endPrice)) {
            $('#total-price').html(
                (endPrice + (+ProductData.shipping['cost']) * newQuantity)
                + '<i class="fa fa-ils custom-fa"></i> '
            );
        }


        setTotalPrice();
    }

    function qty(newValue) {
        if (newValue === null) return ProductData.quantity;

        $('.input-qty').val(+newValue);
        ProductData.quantity = newValue;
    }

    function tidySelectedVariation() {
        if (ProductData.selectedSku.numberVariation != "") {
            for (var i in ProductData.selectedSku) {
                var skuId = ProductData.selectedSku[i];
                for (var j in ProductData.skuVariations[i]) {
                    if (skuId == ProductData.skuVariations[i][j]['dataSkuId']) {
                        ProductData.selectedSku[i] = ProductData.skuVariations[i][j]['title']
                    }
                }
            }

        }
    }

    // few fixes to data format before sending it via ajax
    function tidyProductData() {
        // convert selected sku array and shipping array to less retarded arrays
        var tmp = {};
        for (var i in ProductData.selectedSku) {
            tmp[i] = ProductData.selectedSku[i];
        }
        tmp["numberVariation"] = ProductData.selectedSkuVariant.skuPropIds;
        ProductData.selectedSku = tmp;
        tidySelectedVariation();

        tmp = {};
        for (var i in ProductData.shipping) tmp[i] = ProductData.shipping[i];
        ProductData.shipping = tmp;

        ProductData.sitename = "aliexpress";
        ProductData.title = $("#product-title").data("title");
        ProductData.title = $(".page-title").data("title");
        ProductData.productID = $(".page-title").data("id");
    }

    // check if something should be greyed on unavailability
    function grayOut() {
        var selectedSku = ProductData.selectedSku;

        selectedNoKeys = [];
        for (var i in selectedSku) {
            selectedNoKeys.push(selectedSku[i]);
        }

        // remember if chosen variant was unavailable
        var addToCartUnavailable = $('.add-to-cart-button').hasClass("unavailable");

        // clean all unavailable
        $('.unavailable').removeClass("unavailable");
        if (addToCartUnavailable) {
            $('.add-to-cart-button').addClass("unavailable");
        }


        // loop through all variants
        for (var i in ProductData.skuData) {
            var item = ProductData.skuData[i];
            var skuSet = item.skuPropIds.split(",");

            skuSet = skuSet.map(function (item) {
                return +item;
            });

            if (_.intersection(selectedNoKeys, skuSet).length > 0) {

                var quantity = item.skuVal.availQuantity;
                if (quantity === 0) {

                    var props = item.skuPropIds.split(",");
                    props = props.map(function (item) {
                        return +item;
                    });

                    var removable = _.find(props, function (pr) {
                        return (_.intersection([pr], selectedNoKeys).length) == 0;
                    });

                    $('[data-id=' + removable + ']').addClass("unavailable");
                }
            }
        }
        if ($('.swatch-opt').length == 1) {
            var optionsArray = $('.swatch-option');
            optionsArray.each(function () {
                for (var i in ProductData.skuData) {
                    if ($(this).data("id") == ProductData.skuData[i].skuPropIds
                        && ProductData.skuData[i].skuVal.availQuantity == 0) {
                        $(this).addClass("unavailable");
                    }
                }
            })
        }
    }

    // recalculate main price ( qty*price )
    function setMainPrice() {
        var selectedSku = ProductData.selectedSku;
        var mainPrice = $('#main-price');
        var totalPrice = $('#total-price');
        totalPrice.html('<p>בחר מהאפשרויות</p>');

        var notSelectedMsg = $('#not-selected-sku');

        selectedNoKeys = [];
        for (var i in selectedSku) {
            selectedNoKeys.push(selectedSku[i]);
        }

        for (var i in ProductData.skuData) {
            var skuSet = ProductData.skuData[i].skuPropIds.split(",");


            ProductData.selectedSkuVariant = ProductData.skuData[i];
            // convert array to unnamed array ( to make intersections possible )
            skuSet = skuSet.map(function (item) {
                return +item;
            });

            // find intersection between selected things and available sku set ( array of IDs )
            intersect = _.intersection(skuSet, selectedNoKeys);

            // if selected stuff matches with any set of sku - set new price
            if (intersect.length === ProductData.totalSkuNum) {
                // ProductData.mainPrice = Math.ceil(+ProductData.skuData[i].skuVal.skuCalPrice);
                ProductData.mainPrice = isNaN(ProductData.skuData[i].skuVal.actSkuCalPrice)
                    ? Math.ceil(+ProductData.skuData[i].skuVal.skuCalPrice)
                    : ProductData.skuData[i].skuVal.actSkuCalPrice;
                ProductData.original_mainPrice = isNaN(ProductData.skuData[i].skuVal.original_actSkuCalPrice)
                    ? Math.ceil(+ProductData.skuData[i].skuVal.original_skuCalPrice)
                    : ProductData.skuData[i].skuVal.original_actSkuCalPrice;

                // reset quantity selector
                qty(1);

                // set main price ( in the top )
                mainPrice.html(ProductData.mainPrice + '<i class="fa fa-ils custom-fa"></i> ');
                $('.button-price').html(ProductData.mainPrice + '<i class="fa fa-ils custom-fa"></i> ');

                // set total price ( main * quantity + shipping )
                totalPrice.html(((+ProductData.mainPrice) + (+ProductData.shipping['cost'])) + '<i class="fa fa-ils custom-fa"></i> ');


                notSelectedMsg.addClass("my-hidden");

                // make add button active
                $('.add-to-cart-button').removeClass("unavailable");

                // show available amount of items
                $('#available-amount').html(ProductData.selectedSkuVariant.skuVal.availQuantity);

                return;
            } else delete ProductData.mainPrice;
        }


        // disable buy button and restore warning text
        // $('#add-to-cart-button').prop('disabled', true);

        notSelectedMsg.removeClass("my-hidden");

        // set price back to base if not all required sku's selected
        mainPrice.html(ProductData.priceRange + '<i class="fa fa-ils custom-fa"></i> ');
        $('.button-price').html(ProductData.priceRange + '<i class="fa fa-ils custom-fa"></i> ');
    }

    // recalculate main price ( qty*price + shipping ) ( dummy now )
    function setTotalPrice() {
        // all ok

        return;

        if (ProductData.hasOwnProperty('mainPrice')) {
            $('.add-to-cart-button').removeClass("unavailable");
            ProductData.totalPrice = +ProductData.mainPrice;
        } else {
            $('.add-to-cart-button').addClass("unavailable");
        }

        if (ProductData.hasOwnProperty('mainPrice') && ProductData.hasOwnProperty('shipping')) {
            ProductData.totalPrice = (+ProductData.mainPrice * ProductData.quantity) + (+ProductData.shipping['cost']);
            $('#total-price').html(ProductData.totalPrice);
            $('.add-to-cart-button').removeClass("unavailable");
        }
        // no shipping
        else {
            $('#total-price').html("");
            $('#no-shipping-warning').removeClass("hidden-warning");
            $('.add-to-cart-button').addClass("unavailable");
        }

        if (ProductData.hasOwnProperty('shipping')) {
            $('#no-shipping-warning').addClass("hidden-warning");
        }
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
});