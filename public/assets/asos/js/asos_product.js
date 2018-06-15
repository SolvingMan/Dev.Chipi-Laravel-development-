jQuery(document).ready(function () {
    var productData = {};
    productData.productURL = location.href;
    productData.availableQuantity = 1000;
    productData.selectedSku = "";
    productData.quantity = 1;
    $('.add-to-cart-button').on('click', function (e) {
        e.preventDefault();
        var totalPrice = getTotalPrice();
        if (totalPrice > 0) {
            $('#addCart').modal('show');
        }
        else {
            $('#chooseVariationsModal').modal('show');
            $('.choose-variations').text("לא נבחרו אופציות ");
            return;
        }
        for (var index = 0; index < jsProductData.products.length; index++) {
            var price = getPricePerProduct(index);
            if (price > 0) {
                var variations = {};
                variations['colour'] = $('.colour-' + index).val();
                variations['size'] = $('.size-' + index).val();
                // make some marafets to god-object Product data
                productData.image = jsProductData.images[index];
                productData.shippingCompany = '-';
                productData.shippingPrice = 0;
                productData.original_shippingPrice = 0;
                productData.mainPrice = price;
                productData.original_mainPrice = price;
                if (jsProductData.products.length > 1) {
                    productData.title = jsProductData.products[index].title;
                } else {
                    productData.title = jsProductData.title;
                }
                productData.sitename = "asos";
                productData.selectedSku = variations;
                productData.productID = jsProductData.productUrl;

                var url = "/cart/add";
                var data = productData;
                var token = $('#add-to-cart-form').serializeArray()[0];
                setTimeout($.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": token.value,
                        "product": data
                    },
                    success: function (data) {
                        // some animation here, probably
                        $('.cart-items-count').text(data);
                    },
                    complete: function (data) {
                    }
                }), 1000);
            }
        }
        updateShortcart();
    });

    function updateShortcart() {
        $.ajax({
            url: "/cart/shortcart",
            success: function (response) {
                $('.minicart-content-wrapper').html(response);
            }
        })
    }

    $('.variation').on('change', function (e) {
        e.preventDefault();
        var totalPrice = getTotalPrice();
        if (totalPrice != 0) {
            $('#total-price').html(totalPrice + '<i class="fa fa-ils"></i>');
        }
    });

    function getTotalPrice() {
        var totalPrice = 0;
        for (var i = 0; i < jsProductData.products.length; i++) {
            totalPrice += getPricePerProduct(i);
        }
        return totalPrice;
    }

    function getPricePerProduct(index) {
        var colour = $('.colour-' + index).val();
        var size = $('.size-' + index).val();
        if (!isEmpty(size) && !isEmpty(colour)) {
            var price = parseInt(jsProductData.products[index].currentPrice);
            return price;
        }
        else {
            return 0;
        }
    }

    function isEmpty(str) {
        return (!str || /^\s*$/.test(str));
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
});
