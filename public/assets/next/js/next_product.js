jQuery(document).ready(function () {
    var fit = null;
    var color = null;
    var size = null;
    var productData = {};
    productData.productURL = location.href;
    productData.availableQuantity = 1000;
    productData.selectedSku = "";
    productData.quantity = 1;
    $('.add-to-cart-button').on('click', function (e) {
        e.preventDefault();
        if (!validationVariations()) {
            $('#chooseVariationsModal').modal('show');
            $('.choose-variations').text("לא נבחרו אופציות , בחר: ");
            if ($('.select-fits').length > 0) {
                if (fit == null) {
                    $('.choose-variations').append('fit' + ' ');
                }
            }
            if ($('.select-color').length > 0) {
                if (color == null) {
                    $('.choose-variations').append('color' + ' ');
                }
            }
            if ($('.select-size').length > 0) {
                if (size == null) {
                    $('.choose-variations').append('size' + ' ');
                }
            }
            return;
        }
        else {
            var variations = '';
            if (fit != null) {
                variations += 'fit: ' + fit + ' ';
            }
            if (color != null) {
                variations += '<br> color: ' + color + ' ';
            }
            if (size != null) {
                variations += '<br> size: ' + size + ' ';
            }
            $('.modal-variation').html(variations);
            $('#addCart').modal('show');
        }

        // make some marafets to god-object Product data

        productData.image = imageUrl;
        productData.shippingCompany = '-';
        productData.shippingPrice = 0;
        productData.original_shippingPrice = 0;
        productData.mainPrice = $('#price').text();
        productData.original_mainPrice = $('#price').text();
        productData.title = $('.page-title').text();
        productData.sitename = "next";

        productData.productID = productUrl;

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
        recalculateQuantity(1);
    });


    function validationVariations() {
        var variations = {};
        if ($('.select-fits').length > 0) {
            if (fit != null) {
                variations['fit'] = fit;
            }
            else {
                return false;
            }
        }
        if ($('.select-color').length > 0) {
            if (color != null) {
                variations['color'] = color;
            }
            else {
                return false;
            }
        }
        if ($('.select-size').length > 0) {
            if (size != null) {
                variations['size'] = size;
            }
            else {
                return false;
            }
        }
        productData.selectedSku = variations;
        return true;
    }

    function recalculateQuantity(modifier) {
        var quant = +$('.input-qty').val();
        if (modifier == 0) $('.input-qty').val(1);

        var newQuantity = +(quant + modifier);
        if (newQuantity == 0) return;

        $('.input-qty').val(+newQuantity);

        var totalPrice = parseInt($("#price").text()) * parseInt($('.input-qty').val());
        $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
        productData.quantity = newQuantity;
    }

    $('.select-fits').on('change', function () {
        var fitSelector = $(this).val();
        $(".colorList").css('display', 'none');
        if (!fitSelector) {
            $(".sizeList").css('display', 'none');
            fit = null;
            size = null;
            color = null;
            $('.select-color').val('');
            $('.select-size').val('');
        } else {
            fit = fitSelector;
        }
        $(".colorList[data-name-first='" + fitSelector + "']").css('display', 'block');
    });
    $('.select-color').on('change', function () {
        var colorListVal = $(this).find(':selected').data('itemNumber');
        var price = $(this).val();
        if (price) {
            $('#price').text(price);
        }
        $(".sizeList").css('display', 'none');
        $(".sizeList[data-color='" + colorListVal + "']").css('display', 'block');
        if ($(this).val()) {
            color = $(this).find(':selected').text().trim();
            var totalPrice = parseInt($("#price").text()) * parseInt($('.input-qty').val());
            $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
        }
        else {
            $('.select-size').val('');
            size = null;
            color = null;
        }
    });
    $('.select-size').on('change', function () {
        var price = $(this).val();
        if (price) {
            $('#price').text(price);
        }
        if ($(this).val()) {
            size = $(this).find(':selected').text().trim();
            var totalPrice = parseInt($("#price").text()) * parseInt($('.input-qty').val());
            $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
        } else {
            size = null;
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
});