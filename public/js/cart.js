$(document).ready(function () {
    $(".quantity-less").on('click', function (e) {
        e.preventDefault();
        recalculateQuantity(-1, $(this).data("field"));
    });

    $(".quantity-more").on('click', function (e) {
        e.preventDefault();
        recalculateQuantity(1, $(this).data("field"));
    });

    function recalculateQuantity(modifier, identificator) {
        var quant = $(".input-qty[data-field=\"" + identificator + "\"]").val();
        var newQuantity = parseInt(quant) + parseInt(modifier);
        for (var i in jsData.products) {
            if (jsData.products[i].numInCart == identificator && newQuantity >= 1 && newQuantity
                <= jsData.products[i].availableQuantity) {
                $(".input-qty[data-field=\"" + identificator + "\"]").val(newQuantity);
                if (jsData.products[i].sitename == "next") {
                    $(".total-product-price[data-field=\"" + identificator + "\"]").html(newQuantity *
                        parseInt(jsData.products[i].productPrice));
                }
                else {
                    $(".total-product-price[data-field=\"" + identificator + "\"]").html(newQuantity *
                        (parseInt(jsData.products[i].productPrice) + parseInt(jsData.products[i].shippingPrice)));
                }
                var url = "/cart/edit/" + jsData.products[i].numInCart;
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {"newQuantity": newQuantity},
                    success: function (data) {
                        var flag = false;
                        for (var i in data) {
                            if (data[i].sitename == "next" && data[i].shippingPrice == 20) {
                                $('.shipping-next-block').show();
                                $('.shipping-cost').text('20');
                                flag = true;
                            }
                        }
                        if (!flag) {
                            $('.shipping-next-block').hide();
                            $('.shipping-cost').text('');
                        }
                        // some animation here, probably
                        updateShortcart();
                        var newTotalPrice = 0;
                        $('.total-product-price').each(function () {
                            newTotalPrice += (+$(this).html());
                        });
                        if (flag) {
                            newTotalPrice += 20;
                        }
                        var discount = newTotalPrice * (jsData.usedCoupon.sumCoupon / 100);
                        discount = discount.toFixed(2);
                        $('.totalCartPrice').html(newTotalPrice);
                        $('.discount').html(discount);
                        $('.totalCartPriceDiscount').html(newTotalPrice - discount);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        }

        // if (newQuantity === 0) return;

        //qty(newQuantity);

        // var currentPrice = +ProductData.mainPrice;
        // var endPrice = +(currentPrice * newQuantity);
        //
        // if (endPrice !== 0 && endPrice !== null && !isNaN(endPrice)) {
        //     $('#total-price').html(
        //         (endPrice + (+ProductData.shipping['cost']) * newQuantity)
        //         + '<i class="fa fa-ils custom-fa"></i> '
        //     );
        // }
        //
        //
        // setTotalPrice();
    }

    // function qty(newValue) {
    //     if (newValue === null) return ProductData.quantity;
    //
    //     $('.input-qty').val(+newValue);
    //     ProductData.quantity = newValue;
    // }

    $('#myModal').modal('show');

    function updateShortcart() {
        $.ajax({
            url: "/cart/shortcart",
            success: function (response) {
                $('.minicart-content-wrapper').html(response);
            }
        })
    }

    $(".delete-from-cart").on('click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "/cart/remove/" + id;
        $(this).closest('.cart-item').remove();
        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {
                console.log(data);
                var flag = false;
                for (var i in data['products']) {
                    if (data['products'][i].sitename == "next" && data['products'][i].shippingPrice == 20) {
                        $('.shipping-next-block').show();
                        $('.shipping-cost').text('20');
                        flag = true;
                    }
                }
                if (!flag) {
                    $('.shipping-next-block').hide();
                    $('.shipping-cost').text('');
                }
                // some animation here, probably
                $('.cart-items-count').html(data['count']);
                updateShortcart();
                var newTotalPrice = 0;
                $('.total-product-price').each(function () {
                    newTotalPrice += (+$(this).html());
                });
                if (flag) {
                    newTotalPrice += 20;
                }
                var discount = newTotalPrice * (data['discount'].sumCoupon / 100);
                discount = discount.toFixed(2);
                $('.totalCartPrice').html(newTotalPrice);
                $('.discount').html(discount);
                $('.totalCartPriceDiscount').html(newTotalPrice - discount);
            },
            error: function (data) {
                console.log(data);
            }
        });
    })
});
