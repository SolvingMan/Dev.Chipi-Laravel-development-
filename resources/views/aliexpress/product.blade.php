@extends('layouts.main')
@section('content')

    <!-- MAIN -->
    <?php /*start*/

    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $start = $time; ?>

    <main class="site-main ali-product-wrap">
        <div class="columns container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.aliexpress')) </a></li>
                @if(isset($breadCramb))
                    <li>
                        <a href="/{{$siteslug}}/categoryMap/{{$breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                    </li>
                    <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                    <li><a href="/aliexpress/category/{{ $breadCramb['subSubCategory'] }}/{{$aliData['categoryID']}}">
                            {{ $breadCramb['subSubCategory'] }}</a></li>
                @endif
                <li class="active"><a href="#">{{$aliData['productName']}} </a></li>
            </ol><!-- Block  Breadcrumb-->
            {{--<script>(function (d, s, id) {--}}
            {{--var js, fjs = d.getElementsByTagName(s)[0];--}}
            {{--if (d.getElementById(id)) return;--}}
            {{--js = d.createElement(s);--}}
            {{--js.id = id;--}}
            {{--js.src = "//connect.facebook.net/he_IL/sdk.js#xfbml=1&version=v2.9&appId=418846555167177";--}}
            {{--fjs.parentNode.insertBefore(js, fjs);--}}
            {{--}(document, 'script', 'facebook-jssdk'));</script>--}}
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-12  col-main">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-7">
                            <div class="product-media media-horizontal bleach-photo section-product-pictures">
                                <div class="image_preview_container images-large">
                                    <img id="img_zoom" data-zoom-image="{{ $aliData['mainImages'][0] }}"
                                         src="{{ $aliData['mainImages'][0] }}" alt="">
                                    <button class="btn-zoom open_qv"><span>zoom</span></button>
                                </div>

                                <div class="product_preview images-small">
                                    <div class="owl-carousel thumbnails_carousel" id="thumbnails" data-nav="true"
                                         data-dots="false" data-margin="10"
                                         data-responsive='{"0":{"items":3},"480":{"items":4},"600":{"items":5},"768":{"items":3}}'>

                                        @foreach($aliData['mainImages'] as $src)
                                            <a href="#" data-image="{{$src}}" data-zoom-image="{{$src}}">
                                                <img class="carousel-same-size-image"
                                                     src="{{$src}}" data-large-image="{{$src}}" alt="">
                                            </a>
                                        @endforeach
                                    </div><!--/ .owl-carousel-->
                                </div><!--/ .product_preview-->
                            </div><!-- image product -->
                        </div>

                        <div class="col-sm-12 col-md-5 col-lg-5">

                            <div class="bleach section-product-information">
                                <div class="product-info-main">

                                    <h1 class="page-title" data-id="{{$productID}}"
                                        data-title="{{ $aliData['productName'] }}">
                                        {{ $aliData['productName'] }}
                                    </h1>

                                    <table class="table-product-detail">
                                        @if($aliData['oldPrice']!=0)
                                            <tr>
                                                <td>
                                                    <span>@lang('general.old_price'):</span>
                                                </td>
                                                <td>
                                            <span class="old-price">
                                            <s>
                                            {{ $aliData['oldPrice'] }}
                                                <i class="fa fa-ils custom-fa"></i>
                                            </s>
                                            </span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="width: 150px;">
                                                <span>@lang('general.product_price'):</span>
                                            </td>
                                            <td>
                                            <span class="price" id="main-price">
                                            <strong>
                                            @if(isset($aliData['salePrice']))
                                                    {{$aliData['salePrice']}}
                                                @else
                                                    {{ $aliData['mainPrice'] }}
                                                @endif
                                            </strong>
                                            <i class="fa fa-ils custom-fa"></i>
                                            </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.shipping_price'):</span>
                                            </td>
                                            <td>
                                                @if(!empty($aliData['shipping']->freight))
                                                    @if($aliData['shipping']->freight[0]->price==0)
                                                        @lang("general.free_shipping")
                                                    @else
                                                        <span class="shipping-price">{{ $aliData['shipping']->freight[0]->price }}</span>
                                                        <i class="fa fa-ils custom-fa"></i>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.positive_responses'):</span>
                                            </td>
                                            <td>
                                                <span>{{ $stars }}%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.delivery_time'):</span>
                                            </td>
                                            <td>
                                                <span>@lang("general.business_days") 21-35</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.product_status'):</span>
                                            </td>
                                            <td>
                                                <span>@lang('general.new')</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.product_shipped_from'):</span>
                                            </td>
                                            <td>
                                                @if(!empty($aliData['shipping']->freight))
                                                    <span>{{$aliData['shipping']->freight[0]->sendGoodsCountryFullName}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.name_of_seller'):</span>
                                            </td>
                                            <td>
                                                <span>{{$aliData['shopName']}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.the_amount_sold'):</span>
                                            </td>
                                            <td>
                                                <span>{{$aliData['orderNum']}}</span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="product-add-form">

                                        @if($aliData['productSKU']!=null)
                                            <p>@lang('general.available_options'):</p>
                                        @endif

                                        <div class="product-options-wrapper">
                                            @if($aliData['productSKU']!=null)
                                                @foreach($aliData['productSKU'] as $keySku=>$sku)
                                                    <div class="swatch-opt">
                                                        <div class="swatch-attribute">
                                                        <span class="swatch-attribute-label">
                                                            {{ $keySku }}
                                                        </span>
                                                            <div class="swatch-attribute-options">
                                                                @if(isset($sku[0]['src']))
                                                                    @foreach($sku as $key=>$value)
                                                                        @if(!isset($sku[$key]['bigpic']))
                                                                            <img class="mini-pic swatch-option"
                                                                                 data-id="{{$sku[$key]['dataSkuId']}}"
                                                                                 data-name="{{ $keySku }}"
                                                                                 data-bigpic=""
                                                                                 src=""
                                                                                 @if(!isset($sku[$key]['title'])) title="no image"
                                                                                 @else title="{{$sku[$key]['title']}}" @endif>
                                                                        @else
                                                                            <img class="mini-pic swatch-option"
                                                                                 data-id="{{$sku[$key]['dataSkuId']}}"
                                                                                 data-name="{{ $keySku }}"
                                                                                 data-bigpic="{{$sku[$key]['bigpic']}}"
                                                                                 src="{{$sku[$key]['src']}}"
                                                                                 @if(!isset($sku[$key]['title'])) title="no image"
                                                                                 @else title="{{$sku[$key]['title']}}" @endif>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    @foreach($sku as $key=>$value)
                                                                        <a href="#"
                                                                           class="no-pic"
                                                                           data-id="{{$sku[$key]['dataSkuId']}}"
                                                                           data-name="{{ $keySku }}"
                                                                           @if(!isset($sku[$key]['title'])) title="no image"
                                                                           @else title="{{$sku[$key]['title']}}" @endif>
                                                                            {{$sku[$key]['title']}}
                                                                        </a>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <br>
                                            <div class="form-qty">
                                                <label class="label">@lang('general.quantity'):</label>
                                                <div class="control">
                                                    <input type="text" class="form-control input-qty" value='1'
                                                           id="qty1"
                                                           name="qty1" maxlength="12" minlength="1">
                                                    <button id="quantity-less" class="btn-number  qtyminus" data-type=""
                                                            data-field="qty1">
                                                        <span>-</span></button>
                                                    <button id="quantity-more" class="btn-number  qtyplus" data-type=""
                                                            data-field="qty1">
                                                        <span>+</span></button>
                                                </div>
                                                <span class="quantity-in-stock">
                                            (<span id="available-amount"></span> @lang("general.units_in_stock"))
                                            </span>
                                            </div>
                                            <div class="form-configurable">
                                                @if(count($aliData['shipping']->freight)!=0)
                                                    {{--<label for="shippingSelector" class="label">Shipping: </label>--}}
                                                    {{--<div class="control">--}}
                                                    {{--<select id="shippingSelector" class="form-control attribute-select">--}}

                                                    {{--@foreach($aliData['shipping']->freight as $shipping)--}}
                                                    {{--<option value="{{ $shipping->company }}#{{$shipping->status == "free" ? "free" : $shipping->price }}">--}}
                                                    {{--{{ $shipping->company }}--}}
                                                    {{--({{--}}
                                                    {{--$shipping->status == "free" ? "free" : $shipping->price--}}
                                                    {{--}})--}}
                                                    {{--</option>--}}
                                                    {{--@endforeach--}}
                                                    {{--<h2 id="no-shipping-warning">--}}
                                                    {{--Please, choose a shipping method--}}
                                                    {{--</h2>--}}
                                                    {{--</select>--}}
                                                    {{--</div>--}}
                                                @else
                                                    <h2>@lang('general.shipping_your_country_not_supported')</h2>
                                                @endif
                                            </div>
                                            <div class="form-configurable">
                                                <label class="total-price-label"
                                                       for="total-price">@lang("general.total_price"): </label>

                                                <span id="total-price" class="total-price">
                                                     <i class="fa fa-ils"></i>
                                                </span>

                                            </div>
                                        </div>
                                        @if($aliData['productSKU']!=null)
                                            <div>
                                                {{--<es id="not-selected-sku">All variations should be chosen</es>--}}
                                            </div>
                                        @endif


                                        <form id='add-to-cart-form' action="/cart/add" method="post">
                                            {{--<input type="hidden" name="_token" value="{{ csrf_token() }}"/>--}}
                                            <div class="product-options-bottom clearfix">

                                                <button type="submit"
                                                        title="Add to Cart"
                                                        class="action btn-cart add-to-cart-button"
                                                        data-toggle="modal"
                                                        data-target="#myModal">
                                                    <span>@lang("general.add_to_cart")</span>
                                                </button>
                                                <div class="fb-like" data-href="{{$_SERVER["REQUEST_URI"]}}"
                                                     data-layout="button" data-action="like" data-size="large"
                                                     data-show-faces="true" data-share="true"></div>
                                                <!-- Modal popup on click -->
                                                <div class="modal fade" id="myModal" role="dialog">
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
                                                                     src="{{ $aliData['mainImages'][0] }}">
                                                                <p>
                                                                    {{ $aliData['productName'] }}
                                                                    @lang("general.added_to_cart")
                                                                </p>
                                                                <div id="modal-variations"
                                                                     class="modal-variation"></div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <!--a href="/cart" class="btn modal-cart-button"-->
                                                            {{--@lang("general.to_cart")--}}
                                                            <!--/a-->
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

                                    </div>
                                </div>
                            </div>
                        </div><!-- detail- product -->
                    </div><!-- Main detail -->
                </div>
            </div>

            <!-- product tab info -->
            <div class="product-info-detailed ">

                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tags" role="tab" data-toggle="tab">@lang('general.product_details')</a>
                    </li>
                    @if(isset($aliData['sellerInfo']))
                        <li role="presentation">
                            <a href="#description" role="tab" data-toggle="tab">@lang('general.seller_info')</a></li>
                    @endif
                    <li role="presentation">
                        <a href="#reviews" role="tab" data-toggle="tab">@lang('general.product_feedback')</a>
                    </li>
                    <li role="presentation">
                        <a href="#additional" role="tab" data-toggle="tab">@lang('general.shipping')</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content bleach">
                    <div role="tabpanel" class="tab-pane active" id="tags">
                        <div class="block-title">@lang('general.product_details')</div>
                        <div class="block-content row">
                            <div class="overview-content col-xs-12 col-md-7">
                                <a href="#" class="translate-tab translate-tab-hebrew">בשפה העברית <img class="flag-img"
                                                                                                        src="/images/flags/he.svg"></a>
                                <a href="#" class="translate-tab selected-tab translate-tab-english">English<img
                                            class="flag-img"
                                            src="/images/flags/gb.svg"></a>
                                <div class="characteristics characteristics-english">
                                    @if(isset($aliData['engCharacteristics']))
                                        @foreach($aliData['engCharacteristics'] as $specific)
                                            <div>
                                                <p>
                                                    <span><b><?=$specific->name?></b></span> :
                                                    <span><?=$specific->value?></span>
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="characteristics characteristics-hebrew hebrew hidden">
                                    @if(isset($aliData['characteristics']))
                                        @foreach($aliData['characteristics'] as $specific)
                                            <div>
                                                <p>
                                                    <span><b><?=$specific[1]?></b></span> :
                                                    <span><?=$specific[0]?></span>
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-image col-xs-12 col-md-5"><img src="{{ $aliData['mainImages'][0] }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="description">
                        <div class="block-title">@lang('general.seller_info')</div>
                        <div class="block-content">
                            @if(isset($aliData['sellerInfo']))
                                <p>
                                    @lang('general.times_rated'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->desc->ratings:"" }}
                                </p>
                                <p>@lang('general.description_accordance_score'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->desc->score:"" }}
                                </p>
                                <p>
                                    @lang('general.description_higher_than'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->desc->percent:"" }}
                                    &#37;
                                </p>
                                <p>@lang('general.seller_talk_score'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->seller->score:"" }}
                                </p>
                                <p>@lang('general.higher_than'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->seller->percent:"" }}
                                    &#37;
                                </p>

                                <p>@lang('general.shipping_speed_score'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->shipping->score:"" }}
                                </p>
                                <p>@lang('general.higher_than'):
                                    {{ isset($aliData['sellerInfo']->desc)?$aliData['sellerInfo']->shipping->percent:""}}
                                    &#37;
                                </p>
                            @endif
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="reviews">
                        <div class="block-title">@lang('general.product_feedback')</div>
                        <div class="block-content">
                            <iframe src="{{$feedback_link}}" class="feedback-iframe"></iframe>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="additional">
                        <div class="block-title">@lang('general.shipping')</div>
                        <div class="block-content">
                            @if(count($aliData['shipping']->freight)!=0)
                                @foreach($aliData['shipping']->freight as $shipping)
                                    <h4>
                                        <b>
                                            <a href="#"
                                               class="shipping-company"
                                               data-company-title="{{ $shipping->company }}"
                                               data-cost="{{ $shipping->status == "free" ? "free" : $shipping->price }}">
                                                {{ $shipping->company }}
                                            </a>
                                        </b>
                                    </h4>
                                    @if($shipping->status == "free")
                                        <h5>@lang('general.free_shipping')</h5>
                                    @else
                                        <h5>@lang('general.shipping_price'): &#8362;{{ $shipping->price }}</h5>
                                    @endif
                                @endforeach

                                {{--<h2 id="no-shipping-warning">Please, choose a shipping method</h2>--}}
                            @else
                                <h2>@lang('general.shipping_your_country_not_supported')</h2>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Block similar products of -->
            <div class="container ">
                <div class="block-deals-of-opt2">
                    <div class="block-title ">
                        <span class="title">@lang('general.similar_products')</span>
                    </div>
                    <div class="block-content">
                        @if(!isset($similar_products->result))
                            <h1 class="page-title page-title-ebay">@lang('general.product_no_similar_products')</h1>
                        @else
                            <div class="owl-carousel"
                                 data-nav="true"
                                 data-dots="false"
                                 data-margin="8"
                                 data-responsive='{
                    "0":{"items":1},
                            "480":{"items":2},
                            "640":{"items":3},
                            "992":{"items":4},
                            "1200":{"items":5}
                            }'>
                                @foreach($similar_products->result->products as $item)
                                    <div class="product-item product-item-opt-2">
                                        <div class="product-item-info">
                                            <div class="product-item-photo">
                                                <a class="product-item-img"
                                                   href="{{ $item->productUrl }}">
                                                    <img style="min-height: 250px;"
                                                         src='{{$item->imageUrl}}'
                                                         alt="{{$item->productTitle}}"
                                                         onerror=this.src='{{str_replace("http:","https:",$item->imageUrl)}}'>
                                                </a>
                                            </div>
                                            <div class="product-item-detail">
                                                <strong class="product-item-name"
                                                        style="min-height: 60px; height: 60px; overflow: hidden;">
                                                    <a href="{{ $item->productUrl}}"><span>{{$item->productTitle}}</span></a>
                                                </strong>
                                                <div class="clearfix">
                                                    <div class="product-item-price">
                                                        @if(isset($item->salePrice))
                                                            <span class="price">&#8362;{{$item->salePrice}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div> <!-- Block similar products of -->
            <!-- description page info -->
            <div class="product-info-detailed iframe-content">
                <div class="tab-content bleach ">
                    <div role="tabpanel" class="tab-pane active" id="translate-frame-english" style="width: 100%;">
                        <a href="#" class="translate-tab translate-iframe-hebrew">בשפה העברית <img
                                    class="flag-img"
                                    src="/images/flags/he.svg"></a>
                        <a href="#" class="translate-tab translate-iframe-english selected-tab">English<img
                                    class="flag-img"
                                    src="/images/flags/gb.svg"></a>
                        <div class="block-content bleach iframe-english" style="width: 100%; height: 1000px;">
                            <iframe class="description-iframe"
                                    src="/aliexpress/description/{{$title}}/{{$productID}}/en">
                            </iframe>
                        </div>
                        <div class="block-content bleach iframe-hebrew hidden" style="width: 100%; height: 1000px;">
                            <iframe class="description-iframe"
                                    src="/aliexpress/description/{{$title}}/{{$productID}}/he">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-cart-fixed">
            <button
                    type="submit"
                    title="Add to Cart"
                    class="action btn-cart add-to-cart-button"
                    data-toggle="modal"
                    data-target="#myModal">
                <span>@lang("general.add_to_cart")</span>
                <span class="button-price">
                                            <strong>
                                            @if(isset($aliData['salePrice']))
                                                    {{$aliData['salePrice']}}
                                                @else
                                                    {{ $aliData['mainPrice'] }}
                                                @endif
                                            </strong>
                                            <i class="fa fa-ils custom-fa"></i>
                                            </span>
            </button>
        </div>

    </main><!-- end MAIN -->
    {{--passing php to external js--}}
    <?= $productData; ?>
    @include('includes.chooseVariationsModal')

    <script src="{{ URL::asset('assets/aliexpress/js/aliexpress_product.js') }}"></script>
    <?php /*end*/


    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 4);
    echo 'Page generated in ' . $total_time . ' seconds.'; ?>
@stop
