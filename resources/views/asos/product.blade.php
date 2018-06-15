@extends('layouts.main')
@section('content')

    <main class="site-main next-product-wrap">
        <div class="columns container">
        
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">נקסט בעברית</a></li>
                @if(isset($breadCramb))
                    <li>
                        <a href="/{{$siteslug}}/categoryMap/{{ $breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                    </li>
                    <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                    <li><a href="/asos/category/{{ $breadCramb['subSubCategory'] }}/{{$categoryId}}/1">
                            {{ $breadCramb['subSubCategory'] }}</a></li>
                @endif
                <li class="active"><a href="#">{{$productData['title']}} </a></li>
            </ol><!-- Block  Breadcrumb-->
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-12  col-main">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-7">
                            <div class="product-media media-horizontal bleach-photo section-product-pictures">
                                <div class="image_preview_container images-large">
                                    <img id="img_zoom"
                                         data-zoom-image="{{ $productData['images'][0]}}"
                                         src="{{$productData['images'][0]}}" alt="">
                                    <button class="btn-zoom open_qv"><span>zoom</span></button>
                                </div>
                                <div class="product_preview images-small">
                                    <div class="owl-carousel thumbnails_carousel" id="thumbnails" data-nav="true"
                                         data-dots="false" data-margin="10"
                                         data-responsive='{"0":{"items":3},"480":{"items":4},"600":{"items":5},"768":{"items":3}}'>
                                        @foreach($productData['images'] as $image)
                                            <a href="#" data-image="{{$image}}" data-zoom-image="{{$image}}">
                                                <img class="carousel-same-size-image" src="{{$image}}"
                                                     data-large-image="{{$image}}" alt="">
                                            </a>
                                        @endforeach
                                    </div><!--/ .owl-carousel-->
                                </div><!--/ .product_preview-->
                            </div><!-- image product -->
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-5">
                            <div class="bleach section-product-information">
                                <div class="product-info-main">
                                    <h1 class="page-title">
                                        {{$productData['title']}}
                                    </h1>
                                    @foreach($productData['products'] as $index=>$product)
                                        @if(isset($product['title']))
                                            <h4 class="asos-product-title">{{$product['title']}}</h4>
                                        @endif
                                        <table class="table-product-detail">
                                            <tr>
                                                <td style="width: 150px;">
                                                    <span>@lang('general.product_price'):</span>
                                                </td>
                                                <td>
                                                <span id="price" class="price">
                                                        {{ $product['currentPrice'] }}
                                                    </span>
                                                    <i class="fa fa-ils custom-fa" aria-hidden="true"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span>משלוח:</span>
                                                </td>
                                                <td>
                                                    <span>חינם</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span>@lang('general.delivery_time'):</span>
                                                </td>
                                                <td>
                                                    <span>עד 14 ימי עסקים</span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="product-add-form">
                                            <div class="product-options-wrapper">
                                                <p>@lang('general.available_options'):</p>
                                                <table class="table-variations-ebay">
                                                    <tr>
                                                        <td style="width: 150px;">
                                                            <span>Colour:</span>
                                                        </td>
                                                        <td class="table-select-wrap">
                                                            <select class="variation form-control colour-{{$index}}">
                                                                @foreach ($product['colour'] as $indexColour=>$color)
                                                                    <option
                                                                            @if(strpos($color,"Not available"))
                                                                            disabled
                                                                            @endif
                                                                            value="
                                                                       @if($indexColour!=0)
                                                                            {{$color}}
                                                                            @endif
                                                                                    ">
                                                                        {{$color}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 150px;">
                                                            <span>Size:</span>
                                                        </td>
                                                        <td class="table-select-wrap">
                                                            <select class="variation form-control size-{{$index}}">
                                                                @foreach ($product['size'] as $indexSize=>$size)
                                                                    <option value="
                                                                    @if($indexSize!=0)
                                                                    {{$size}}
                                                                    @endif
                                                                            ">
                                                                        {{$size}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            {{--@endif--}}
                                            @endforeach
                                            <div class="product-options-wrapper">
                                                <div class="form-configurable">
                                                    <label class="total-price-label"
                                                           for="total-price">@lang("general.total_price"): </label>

                                                    <span id="total-price" class="total-price">
                                                </span>

                                                </div>
                                            </div>
                                            <form id='add-to-cart-form' action="/cart/add" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                                <div class="product-options-bottom clearfix">

                                                    <button type="submit"
                                                            title="Add to Cart"
                                                            class="action btn-cart add-to-cart-button">
                                                        <span>@lang("general.add_to_cart")</span>
                                                    </button>
                                                    <div class="fb-like" data-href="{{$_SERVER["REQUEST_URI"]}}"
                                                         data-layout="button" data-action="like" data-size="large"
                                                         data-show-faces="true" data-share="true"></div>
                                                    <!-- Modal popup on click -->
                                                    <div class="modal fade" id="addCart" role="dialog">
                                                        <br>

                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">&times;
                                                                    </button>
                                                                    <h4 class="modal-title">@lang("general.added_to_cart")</h4>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <img class="modal-image"
                                                                         src="{{ $productData['images'][0]}}">
                                                                    <p>
                                                                        {{$productData['title']}}
                                                                        @lang("general.added_to_cart")
                                                                    </p>
                                                                    <div class="modal-variation"></div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn modal-cart-button"
                                                                            data-dismiss="modal">
                                                                        @lang("general.back_to_shopping")
                                                                    </button>
                                                                    <a href="/checkout"
                                                                       class="btn modal-checkout-button">
                                                                        @lang("general.to_checkout")
                                                                    </a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- detail- product -->

                                        </div>
                                        @if(isset($productData['brandDescription']))
                                            <div role="tabpanel" class="tab-pane" id="description">
                                                <div class="block-title">מותג תיאור</div>
                                                <div class="block-content">
                                                    {{$productData['brandDescription']}}
                                                    @if(isset($productData['sizeFit']))
                                                        <br>
                                                        <br>
                                                        <?=$productData['sizeFit']?>
                                                    @endif
                                                    @if(isset($productData['aboutMe']))
                                                        <br>
                                                        <br>
                                                        <?=$productData['aboutMe']?>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                </div><!-- Main detail -->

                            </div>
                        </div>
                    </div><!-- Main Content -->
                    @if(isset($productData['productDetails']))
                        <div class="product-info-detailed">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#reviews" role="tab"
                                       data-toggle="tab">@lang('general.product_details')</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content bleach">
                                <div role="tabpanel" class="tab-pane active" id="reviews">
                                    <div class="block-title">@lang('general.product_details')</div>
                                    <div class="block-content row">
                                        <div class="overview-content col-xs-12 col-md-7">
                                            <a href="#" class="translate-tab translate-tab-hebrew">בשפה העברית <img
                                                        class="flag-img"
                                                        src="/images/flags/he.svg"></a>
                                            <a href="#" class="translate-tab translate-tab-english selected-tab">English<img
                                                        class="flag-img"
                                                        src="/images/flags/gb.svg"></a>
                                            <div class="characteristics characteristics-english">
                                                @if(isset($productData['productDetails']))
                                                    @foreach($productData['productDetails'] as $detail)
                                                        <div>
                                                            <p>
                                                                <span><?=$detail?></span>
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="characteristics characteristics-hebrew hebrew hidden">
                                                @if(isset($productData['productDetailsHebrew']))
                                                    @foreach($productData['productDetailsHebrew'] as $hebrewDetail)
                                                        <div>
                                                            <p>
                                                                <span><?=$hebrewDetail?></span>
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-image col-xs-12 col-md-5">

                                            <img @if(isset($productData['images'][0]))
                                                 src="{{str_replace("http:","https:", $productData['images'][0]) }}"
                                                    @endif >

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main><!-- end MAIN -->
    <?= $jsProductData ?>
    @include('includes.chooseVariationsModal')
    <script src="{{ URL::asset('assets/asos/js/asos_product.js') }}"></script>
@stop