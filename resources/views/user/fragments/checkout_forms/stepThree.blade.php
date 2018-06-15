<?php
$total = 0;
foreach ($products as $product) {
    $total += $product->quantity * ($product->productPrice + $product->shippingPrice);
}
$totalDisc = 0;
foreach ($productsDiscount as $product) {
    $quantity = $product->availableQuantity >= $product->quantity ? $product->quantity :
        $product->availableQuantity;
    $totalDisc += $quantity * ($product->productPrice + $product->shippingPrice);
}

?>

    {{--<div class="row">--}}
    {{--<div class="col-xs-12">--}}
    {{--<div class="form-group coupon-form">--}}
    {{--<div class="col-xs-6">--}}
    {{--<label for="coupon" class="text-right pull-left">@lang('general.select_coupon')</label>--}}
    {{--<input style="border: 1px solid #ececec;" type="text" class="form-control" id="coupon">--}}

    {{--</div>--}}

    {{--<div class="col-xs-3">--}}
    {{--<button style="margin-top: 25px;--}}
    {{--margin-right: 10px;" id="submit-coupon" class="orange-perfect form-control">שלח</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}


    <div class="row">
        <div class="col-xs-12">
            <h3 class="checkout-error">
                <span>
              @lang('general.Coupon code does not exist')
                </span>
            </h3>

            <h3 class="total-price-checkout">
                @lang('general.total') :
                <span style="color:#0464a0; direction: ltr">
                    <span class="totalCartPrice">{{$total}}</span>
                    <i class="fa fa-ils"></i>
                </span>
            </h3>
            @if($usedCoupon !== "")
            <h3 class="checkout-discount">
                @lang('general.discount_coupon')
                <span style="color:#0464a0; direction: ltr">
                    <span class="discount"> {{$total-$totalDisc}}</span>@lang('general.description_word')cxx
                    <i class="fa fa-ils"></i>
                </span>
            </h3>
            <h3 class="total-price-with-discount">
                @lang('general.price_after_discount')
                <span style="color:#0464a0; direction: ltr">
                    <span class="totalCartPriceDiscount"> {{$totalDisc}}</span>
                        <i class="fa fa-ils"></i>
                </span>
            </h3>
            @endif
        </div>
    </div>

<!--div style="height:25px;background-color: #f6f6f6"></div-->
<form action="/checkout" method="post" id="paymentForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="page-content checkout-page" style="margin-top: 0">
        <div class="box-border">
            <div class="row">
                <div class="col-xs-12">
                    <div id="payment-iframe-div">
                        <iframe id="payment-iframe"
                                scrolling="yes"
                                frameborder="0"
                                src="">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>