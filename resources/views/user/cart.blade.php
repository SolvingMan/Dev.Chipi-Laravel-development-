@extends('layouts.noSidebar')
@section('content')
    <!-- MAIN -->
    <main class="site-main">

        <div class="columns container">
            <!-- Block  Breadcrumb-->
            <h2 class="page-heading cart-page-title">
                @lang('general.shopping_cart')
            </h2>

            <div class="page-content checkout-page">
                <div class="box-border">

                    @if(count($products)!=0)
                        <div class="product-list" >
                            @include('user.fragments.productsTable')
                            <a href="/checkout">
                                <button class="button pull-right cart-button">@lang('general.checkout')@endlang</button>
                            </a>
                        </div>
                    @else
                        <h1>@lang('general.shopping_cart_empty')@endlang</h1>
                    @endif
                </div>
            </div>

        </div>
    </main><!-- end MAIN -->
    <?= $jsData ?>
    <script src="{{ URL::asset('js/cart.js') }}"></script>
@stop
