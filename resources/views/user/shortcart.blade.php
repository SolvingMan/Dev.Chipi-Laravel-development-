<div class="subtitle">
    <span class="cart-items-count">
        @if(!isset($_SESSION['products']) || count($_SESSION['products'])==0)
            @lang('general.empty_shop_cart')
        @else
            @lang('general.you_have')
            {{ count($_SESSION['products']) }}
            @lang('general.items_in_your_cart')
        @endif
    </span>
</div>
<div class="minicart-items-wrapper">
    <ol class="minicart-items">
        @if($products!=null)
            @foreach($products as $product)
                <li class="product-item">
                    <a class="product-item-photo" href="{{ $product->productUrl }}" title="The Name Product">
                        <img class="product-image-photo"
                             src="{{ str_replace("http:","https:",$product->image) }}"
                             alt="The Name Product">
                    </a>
                    <div class="product-item-details">
                        <strong class="product-item-name">
                            <a href="{{ $product->productUrl }}">{{$product->title}}</a>
                        </strong>
                        <div class="product-item-price">
                    <span class="price">{{ $product->productPrice }}
                        <i class="fa fa-ils"></i></span>
                        </div>
                        <div class="product-item-qty">
                            <span class="label">@lang('general.quantity'): </span><span
                                    class="number">{{ $product->quantity }}</span>
                        </div>
                        <div class="product-item-actions">
                            <a class="action delete" data-id="{{$product->numInCart }}" href="#" title="Remove item">
                                <span>Remove</span>
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        @endif
    </ol>
</div>
<div class="subtotal">
    <span class="label">@lang('general.total')</span>
    <span class="price"><?php
        $totalCost = 0;
        if ($products != null) {
            foreach ($products as $product) {
                $totalCost += (floatval($product->productPrice) + floatval($product->shippingPrice)) * floatval($product->quantity);
            }
        }
        ?>
        &#8362;{{  $totalCost }}
    </span>
</div>
<div class="actions">
    <a href="/checkout">
        <button class="btn btn-checkout" type="button" title="Check Out">
            <span>@lang('general.order_now')</span>
        </button>
    </a>
</div>