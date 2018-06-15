@extends('layouts.main')
@section('content')

    <!-- MAIN -->
    <main class="site-main">
        <div class="columns container">
            
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.ebay') </a></li>
                @if(isset($breadCramb))
                    <li>
                        <a href="/{{$siteslug}}/categoryMap/{{ $breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                    </li>
                    <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                    <li><a href="/ebay/category/{{ $breadCramb['subSubCategory'] }}/{{$ebayData->PrimaryCategoryID}}">
                            {{ $breadCramb['subSubCategory'] }}</a></li>

                @endif
                <li class="active"><a href="#">{{$ebayData->Title}} </a></li>
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
                                    <img id="img_zoom"
                                         @if(isset($ebayData->PictureURL[0]))
                                         data-zoom-image="{{ str_replace("http:","https:",$ebayData->PictureURL[0]) }}"
                                         src="{{ str_replace("http:","https:",$ebayData->PictureURL[0]) }}"
                                         @endif
                                         alt="">
                                    <button class="btn-zoom open_qv"><span>zoom</span></button>
                                </div>
                                <div class="product_preview images-small">
                                    <div class="owl-carousel thumbnails_carousel" id="thumbnails" data-nav="true"
                                         data-dots="false" data-margin="10"
                                         data-responsive='{"0":{"items":3},"480":{"items":4},"600":{"items":5},"768":{"items":3}}'>
                                        @foreach($ebayData->PictureURL as $src)
                                            <a href="#" data-image="{{str_replace("http:","https:",$src)}}"
                                               data-zoom-image="{{str_replace("http:","https:",$src)}}">
                                                <img class="carousel-same-size-image"
                                                     src="{{str_replace("http:","https:",$src)}}"
                                                     data-large-image="{{str_replace("http:","https:",$src)}}" alt="">
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
                                        {{$ebayData->Title}}
                                    </h1>

                                    <div class="top-rated-seller text-center">
                                        <img class="top-rated-image"
                                             src="{!! asset("images/icon/ebay/toprated_one.png") !!}">
                                    </div>

                                    <table class="table-product-detail">
                                        @if($ebayData->haveImportCharge)
                                            <tr>
                                                <td colspan="2">
                                                    @lang('general.price_product_includes_taxes_israel')
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="width: 150px;">
                                                <span>@lang('general.product_price'):</span>
                                            </td>
                                            <td>
                                                @if(isset($ebayData->Variations))
                                                    <span style="display: none;" class="start-price"> @lang('general.starting_from')</span>
                                                @endif
                                                <span id="price" class="price">
                                                        @if(!isset($ebayData->Variations))
                                                        {{  $ebayData->shekelPrice }}
                                                    @endif
                                                    </span>
                                                <i class="fa fa-ils custom-fa" aria-hidden="true"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.shipping_price'):</span>
                                            </td>
                                            <td>

                                                @if($shippingToIsrael)
                                                    @if($ebayData->shekelShippingServiceCost==0)
                                                        <span>@lang('general.shipping_cost')</span>
                                                    @else
                                                        <span class="shipping-price">{{$ebayData->shekelShippingServiceCost}}</span>
                                                        <i class="fa fa-ils custom-fa" aria-hidden="true"></i>
                                                    @endif
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.positive_responses'):</span>
                                            </td>
                                            <td>
                                                <span>{{ $ebayData->Seller->PositiveFeedbackPercent }}%</span>
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
                                                <span>{{$ebayData->ConditionName}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.product_shipped_from'):</span>
                                            </td>
                                            <td>
                                                <span>{{$ebayData->Location}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.name_of_seller'):</span>
                                            </td>
                                            <td>
                                                <span>{{$ebayData->Seller->UserID}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.the_amount_sold'):</span>
                                            </td>
                                            <td>
                                                <span>{{$ebayData->QuantitySold}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                    {{--<div class="product-info-price">--}}
                                    {{--<div class="price-box">--}}
                                    {{--<es id="price" class="price">{{  $ebayData->shekelPrice }}</es>--}}
                                    {{--<i class="fa fa-ils custom-fa" aria-hidden="true"></i>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="product-info-stock">--}}
                                    {{--<div class="stock available">--}}
                                    {{--<es class="label">Availability: </es>In stock--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="product-info-stock">--}}
                                    {{--<div class="stock available">--}}
                                    {{--<es class="label">Sold: </es>{{$ebayData->QuantitySold}}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--@if(isset($ebayData->ConditionDisplayName))--}}
                                    {{--<div class="product-condition">--}}
                                    {{--Condition: {{$ebayData->ConditionDisplayName}}--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--<div class="shipping">--}}
                                    {{--@if($shippingToIsrael)--}}
                                    {{--@if($ebayData->shekelShippingServiceCost==0)--}}
                                    {{--<es>Free shipping</es>--}}
                                    {{--@else--}}
                                    {{--<es>Shipping ServiceController Cost: {{$ebayData->shekelShippingServiceCost}}</es>--}}
                                    {{--@endif--}}
                                    {{--<br>--}}
                                    {{--@if($ebayData->shippingService!="-")--}}
                                    {{--<es>Shipping ServiceController: {{$ebayData->shippingService}}</es>--}}
                                    {{--@endif--}}
                                    {{--<br>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}
                                    <div class="product-add-form">
                                        @if(isset($ebayData->Variations))

                                            {{--@foreach($ebayData->Variations->Variation as $variation)--}}
                                            {{--@foreach($variation->VariationSpecifics->NameValueList as $variation_spec)--}}
                                            {{----}}
                                            {{--@endforeach--}}
                                            {{--@endforeach--}}
                                            <div class="product-options-wrapper">
                                                <p>@lang('general.available_options'):</p>
                                                <table class="table-variations-ebay">
                                                    @foreach($ebayData->Variations->VariationSpecificsSet->NameValueList as $variation)
                                                        <tr>
                                                            <td style="width: 150px;">
                                                                <span>{{$variation->Name}}:</span>
                                                            </td>
                                                            <td class="table-select-wrap">
                                                                <select id="for{{$variation->Name}}"
                                                                        data-name="{{$variation->Name}}"
                                                                        class="variation form-control attribute-select inline-select-width">
                                                                    <option selected value="">
                                                                        Choose {{$variation->Name}}</option>
                                                                    @foreach($variation->Value as $item)
                                                                        <option value="{{$item}}">{{$item}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                                <hr>
                                            </div>
                                        @endif
                                        <div class="product-options-wrapper">
                                            <div class="form-qty">
                                                <label class="label">@lang('general.quantity'):</label>
                                                <div class="control">
                                                    <input type="text" class="form-control input-qty" value='1'
                                                           id="qty1"
                                                           name="qty1" maxlength="12" minlength="1">
                                                    <button id="quantity-less" class="btn-number  qtyminus"
                                                            data-type=""
                                                            data-field="qty1">
                                                        <span>-</span>
                                                    </button>
                                                    <button id="quantity-more" class="btn-number  qtyplus"
                                                            data-type=""
                                                            data-field="qty1">
                                                        <span>+</span>
                                                    </button>
                                                </div>
                                                <span class="quantity-in-stock">
                                                    (<span id="available-amount">{{$ebayData->Quantity}}</span> @lang("general.units_in_stock")
                                                    )
                                                    </span>
                                            </div>
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

                                                @if(!$shippingToIsrael)
                                                    <span style="font-size: 12pt;"><strong>@lang('general.shipping_is_impossible')</strong></span>
                                                    <br>
                                                @endif
                                                @if($ebayData->ListingStatus=="Completed")
                                                    <span style="font-size: 12pt;"><strong>@lang('general.sale_was_over')</strong></span>
                                                    <br>
                                                @endif
                                                <button type="submit"
                                                        title="Add to Cart"
                                                        class="action btn-cart add-to-cart-button

                                                        @if(!$shippingToIsrael || $ebayData->ListingStatus=="Completed")
                                                        {{'unavailable'}}
                                                        @endif"
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
                                                                     @if(isset($ebayData->PictureURL[0]))
                                                                     src="{{str_replace("http:","https:", $ebayData->PictureURL[0]) }}"
                                                                        @endif >
                                                                <p>
                                                                    {{$ebayData->Title}}
                                                                    @lang("general.added_to_cart")
                                                                </p>
                                                                <div class="modal-variation"></div>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <!--a href="/cart" class="btn modal-cart-button">
                                                                            {{--@lang("general.to_cart")--}}
                                                                    </a-->
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
                                </div><!-- Main detail -->
                            </div>
                        </div>
                    </div><!-- Main Content -->
                    <!-- product tab info -->
                    <div class="product-info-detailed">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills" role="tablist">
                            @if(isset($ebayData->ItemSpecifics))
                                <li role="presentation" class="active">
                                    <a href="#reviews" role="tab" data-toggle="tab">@lang('general.product_details')</a>
                                </li>
                            @endif
                            <li role="presentation">
                                <a href="#description" role="tab" data-toggle="tab">@lang('general.seller_info')</a>
                            </li>
                            <li role="presentation">
                                <a href="#feed" role="tab" data-toggle="tab">@lang('general.product_feedback')</a>
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
                                            @if(isset($ebayData->ItemSpecifics))
                                                @foreach($ebayData->ItemSpecifics->NameValueList as $specific)
                                                    <div>
                                                        <p>
                                                            <span><b><?=$specific->Name?></b></span> :
                                                            <span><?=$specific->Value[0]?></span>
                                                        </p>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="characteristics characteristics-hebrew hebrew hidden">
                                            @if(isset($ebayData->ItemSpecificsHebrew))
                                                @foreach($ebayData->ItemSpecificsHebrew as $specific)
                                                    <div>
                                                        <p>
                                                            <span><b><?=$specific->name?></b></span> :
                                                            <span><?=$specific->value?></span>
                                                        </p>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-image col-xs-12 col-md-5">

                                        <img @if(isset($ebayData->PictureURL[0]))
                                             src="{{str_replace("http:","https:", $ebayData->PictureURL[0]) }}"
                                                @endif >

                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="description">
                                <div class="block-title">@lang('general.seller_info')</div>
                                <div class="block-content">
                                    <span>Seller Name: {{$ebayData->Seller->UserID}}</span><br>
                                    <span>Feedback Rating Star: {{$ebayData->Seller->FeedbackRatingStar}}</span><br>
                                    <span>Feedback Score: {{$ebayData->Seller->FeedbackScore}}</span><br>
                                    <span>Positive Feedback: {{$ebayData->Seller->PositiveFeedbackPercent}}
                                        %</span><br>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="feed">
                                <div class="block-title">@lang('general.seller_info')</div>
                                <div class="block-content block-feedback" style="direction: ltr;">
                                    @if(isset($feedbackData->FeedbackDetailArray->FeedbackDetail))
                                        @foreach($feedbackData->FeedbackDetailArray->FeedbackDetail as $feed)
                                            <div class="ebay-feedback-item">
                                                <div class="row">
                                                    <div class="col-md-3"><span
                                                                class="feedback-item-title">Date: </span><span>{{substr($feed->CommentTime, 0, 10)}}</span>
                                                    </div>
                                                    <div class="col-md-4"><span
                                                                class="feedback-item-title feedback-comment">Comment: </span><span>{{$feed->CommentText}}</span>
                                                    </div>
                                                    <div class="col-md-2"><span
                                                                class="feedback-comment-type positive fa fa-thumbs-up feedback-item-title">{{$feed->CommentType}}</span>
                                                    </div>
                                                    <div class="col-md-3"><i class="fa fa-user"
                                                                             aria-hidden="true"></i><span
                                                                class="feedback-item-title">{{$feed->CommentingUser}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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
                                    @if(!isset($similar_products))
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
                                            @foreach($similar_products as $s_products)
                                                @foreach($s_products->itemRecommendations->item as $item)
                                                    <div class="product-item product-item-opt-2">
                                                        <div class="product-item-info">
                                                            <div class="product-item-photo">
                                                                <a class="product-item-img"
                                                                   href="{{$productBase}}/{{ (explode("/", $item->viewItemURL)[4])}}/{{explode("?",explode("/", $item->viewItemURL)[5])[0] }}">
                                                                    <img style="min-height: 250px;"
                                                                         src='{{"https://galleryplus.ebayimg.com/ws/web/" . $item->itemId . "_1_1_1.jpg"}}'
                                                                         alt="{{$item->title}}"
                                                                         onerror=this.src='{{str_replace("http:","https:",$item->imageURL)}}'>
                                                                </a>
                                                            </div>
                                                            <div class="product-item-detail">
                                                                <strong class="product-item-name"
                                                                        style="min-height: 60px; height: 60px; overflow: hidden;">
                                                                    <a href="{{$productBase}}/{{ (explode("/", $item->viewItemURL)[4])}}/{{explode("?",explode("/", $item->viewItemURL)[5])[0] }}"><span>{{$item->title}}</span></a>
                                                                </strong>
                                                                <div class="clearfix">
                                                                    <div class="product-item-price">
                                                                        @if(isset($item->currentPrice->__value__))
                                                                            <span class="price">&#8362;{{$item->currentPrice->__value__}}</span>
                                                                        @else
                                                                            <span class="price">&#8362;{{$item->buyItNowPrice->__value__}}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div> <!-- Block similar products of -->

                        <!-- description page info -->
                        <div class="product-info-detailed iframe-content">
                            <div class="container">
                                <div class="row">
                                    <div class="tab-content bleach ">
                                        <div role="tabpanel" class="tab-pane active" id="translate-frame-english"
                                             style="width: 100%;">
                                            <a href="#" class="translate-tab translate-iframe-hebrew">בשפה העברית <img
                                                        class="flag-img"
                                                        src="/images/flags/he.svg"></a>
                                            <a href="#" class="translate-tab translate-iframe-english selected-tab">English<img
                                                        class="flag-img"
                                                        src="/images/flags/gb.svg"></a>
                                            <div class="block-content bleach iframe-english"
                                                 style="width: 100%; height: 1000px;">
                                                <iframe class="description-iframe"
                                                        src="/ebay/description/{{ (explode("/",$ebayData->ViewItemURLForNaturalSearch)[4])}}/{{$ebayData->ItemID}}/en">
                                                </iframe>
                                            </div>
                                            <div class="block-content bleach iframe-hebrew hidden"
                                                 style="width: 100%; height: 1000px;">
                                                <iframe class="description-iframe"
                                                        src="/ebay/description/{{ (explode("/",$ebayData->ViewItemURLForNaturalSearch)[4])}}/{{$ebayData->ItemID}}/he">
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-cart-fixed">
                <button type="submit" title="Add to Cart" class="action btn-cart add-to-cart-button
            @if(!$shippingToIsrael || $ebayData->ListingStatus=="Completed")
                {{'unavailable'}}
                @endif"
                        data-toggle="modal" data-target="#myModal">
                    <span>@lang("general.add_to_cart")</span>
                    <span class="button-price">{{  $ebayData->shekelPrice }}</span>
                    <i class="fa fa-ils custom-fa" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </main><!-- end MAIN -->
    @include('includes.chooseVariationsModal')
    <?= $jsData ?>

    <script src="{{ URL::asset('assets/ebay/js/ebay_product.js') }}"></script>
@stop
