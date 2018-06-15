@foreach($products as $product)

    <?php
    $avalailable = $product->availableQuantity;
    if ($avalailable == 0) {
        $product->wantedQuantity = $product->quantity;
        $product->quantity = $avalailable;
        $color = "red";
    } else if ($avalailable < $product->quantity) {
        $product->wantedQuantity = $product->quantity;
        $product->quantity = $avalailable;
        $color = "yellow";
    } else {
        $color = "";
    }
    if ($product->sitename == "next") {
        $product->totalCost = $product->quantity * $product->productPrice + $product->shippingPrice;
        //dd($product->totalCost);
    } else {
        $product->totalCost = $product->quantity * ($product->productPrice + $product->shippingPrice);
    }

    ?>
    <div class="cart-item">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="cart-item-img">
                    @if(!isset($page))
                        @if($product->sitename == "next")
                            <a href="{{ $product->productUrl }}">
                                <img src="{{$product->image }}" class="cart-item-next" alt="Product">
                            </a>
                        @else
                            <a href="{{ $product->productUrl }}">
                                <img src="{{ str_replace("http:","https:",$product->image) }}" alt="Product">
                            </a>
                        @endif
                    @else
                        @if($product->sitename == "next")
                            <img src="{{ $product->image }}" alt="Product">
                        @else
                            <img src="{{ str_replace("http:","https:",$product->image) }}" alt="Product">
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-md-5 col-sm-6 col-xs-9">
                <div class="cart-item-description">
                    <p class="cart-prod-name">
                        @if(!isset($page))
                            <a href="{{ $product->productUrl }}"> {{ $product->title }} </a>
                        @else
                            {{ $product->title }}
                        @endif
                    </p>

                    @if(isset($product->selectedSku) && $product->selectedSku!="null" && $product->selectedSku!=null)
                        @foreach($product->selectedSku as $keySku=>$sku)
                            @if($keySku!="numberVariation")
                                <span class="cart-prod-character">{{ $keySku }} : {{ $sku }}</span>
                            @endif
                        @endforeach
                    @endif

                </div>
            </div>
            <div class="col-md-2 col-sm-1 col-xs-12">
                <?php
                switch ($product->sitename):
                case "aliexpress":
                ?>
                <img src="{!! asset('images/icon/index1/ali_logo.png') !!}" class="cart-item-shop">
                <?php break; ?>

                <?php case "ebay": ?>
                <img src="{!! asset('images/icon/index1/ebay_logo.png') !!}" class="cart-item-shop">
                <?php break; ?>

                <?php case "amazon": ?>
                <img src="{!! asset('images/icon/index1/amazon_logo.png') !!}" class="cart-item-shop">
                <?php break;?>

                <?php case "next": ?>
                <img src="{!! asset('images/icon/index1/next-logo.png') !!}" class="cart-item-shop cart-shop-next">
                <?php break;?>

                <?php case "asos": ?>
                <img src="{!! asset('images/icon/index1/asos_logo.png') !!}" class="cart-item-shop">
                <?php break;?>

                <?php default:
                    break; ?>
                <?php endswitch;  ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul class="cart-item-buy">
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.unit_price')</span>
                        <span class="cart-price" style="float:left;">
                            <span>{{ $product->productPrice }}</span>
                                            <i class="fa fa-ils"></i>
                        </span>
                    </li>
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.quantity')</span>

                        @if(isset($product->wantedQuantity))
                            <span><s>{{$product->wantedQuantity}}</s></span>
                            <p>@lang('general.available_only')</p>
                        @endif

                        @if($product->quantity==0)
                            <p>@lang('general.item_is_out_of_stock')</p>
                        @else
                            <div class="cart-buy-content control" style="float:left;">
                                <input class="form-control input-qty"
                                       value='{{ $product->quantity }}' data-field="{{$product->numInCart }}"
                                       maxlength="12" minlength="1" disabled="disabled">
                                <button class="quantity-less btn-number qtyminus"
                                        data-field="{{$product->numInCart }}">
                                    <span>-</span></button>
                                <button class="quantity-more btn-number qtyplus"
                                        data-field="{{$product->numInCart }}">
                                    <span>+</span></button>
                            </div>
                            {{--<input minlength="1" maxlength="12"--}}
                            {{--name="qty{{$product->numInCart }}" id="qty{{$product->numInCart }}"--}}
                            {{--value="{{ $product->quantity }}"--}}
                            {{--class="form-control input-sm "--}}
                            {{--type="text"--}}
                            {{--disabled>--}}
                        @endif

                        {{--<span data-field="qty{{$product->numInCart }}" data-type="minus" class="btn-number"><i--}}
                        {{--class="fa fa-caret-down"></i></span>--}}
                        {{--<span data-field="qty{{$product->numInCart }}" data-type="plus" class="btn-number"><i--}}
                        {{--class="fa fa-caret-up"></i></span>--}}
                    </li>
                    @if($product->sitename != "next")
                        <li class="clearfix">
                            <span class="cart-buy-title" style="float:right;">@lang('general.shipping_cost')</span>
                            <span class="cart-price" style="float:left;">
                                <span>{{ $product->shippingPrice }}</span>
                            <i class="fa fa-ils"></i>
                        </span>
                        </li>
                    @endif
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.total')</span>
                        <span class="cart-price" style="float:left;">
                            <span data-field="{{$product->numInCart }}" class="total-product-price">
                                @if($product->sitename == "next")
                                    {{floatval($product->productPrice)* floatval($product->quantity)}}
                                @else
                                    {{(floatval($product->productPrice)+$product->shippingPrice)* floatval($product->quantity)}}
                                @endif
                            </span>
                            <i class="fa fa-ils"></i>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        {{--@if(!isset($page))--}}
        <a href="#" class="delete-from-cart" data-id="{{ $product->numInCart }}">
            <i class="glyphicon glyphicon-trash"></i>
        </a>
        {{--@endif--}}

    </div>
@endforeach

<div class="cart-total">
    <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-9"></div>
        <div class="col-lg-3 col-md-4 col-sm-3">
            <?php
            $totalCost = 0;

            foreach ($products as $product) {
                $totalCost += floatval($product->totalCost);
            }
            ?>
            @if($usedCoupon !== "")
                <?php
                $totalCostDisc = 0;
                foreach ($productsDiscount as $product) {
                    $quantity = $product->availableQuantity >= $product->quantity ? $product->quantity :
                        $product->availableQuantity;

                    $product->totalCost = $quantity * ($product->productPrice + $product->shippingPrice);
                    $totalCostDisc += floatval($product->totalCost);
                }
                ?>
            @endif
            <?php
            $isShippindNextCost = false;
            foreach ($products as $product) {
                if ($product->sitename == "next" && $product->shippingPrice == 20) {
                    $isShippindNextCost = true;
                }
            }
            ?>
            <p class="clearfix shipping-next-block"
               @if(!$isShippindNextCost)
               style="display: none;"
                    @endif
            >
                <span class="cart-buy-title">@lang('general.next_shipping')</span>
                <span class="cart-price" style="float:left;">
                        <span class="shipping-cost">
                            @if($isShippindNextCost)
                                20
                            @endif
                        </span>
                        <i class="fa fa-ils"></i>
                    </span>
            </p>
            <p class="clearfix">
                <span class="cart-buy-title">@lang('general.total')</span>
                <span class="cart-price" style="float:left;">
                        <span class="totalCartPrice">{{  $totalCost }}</span>
                        <i class="fa fa-ils"></i>
                    </span>
            </p>

            @if($usedCoupon !== "")
                <p class="clearfix">
                    <span class="cart-buy-title">@lang('general.discount_coupon')</span>
                    <span class="cart-price" style="float:left;">
                                <span class="discount">{{$totalCost-$totalCostDisc}}</span>
                                <i class="fa fa-ils"></i>
                            </span>
                </p>
                <p class="clearfix">
                    <span class="cart-buy-title">@lang('general.price_after_discount')</span>
                    <span class="cart-price" style="float:left;">
                                <span class="totalCartPriceDiscount">{{$totalCostDisc}}</span>
                                <i class="fa fa-ils"></i>
                            </span>
                </p>
            @endif
        </div>
    </div>
</div>















