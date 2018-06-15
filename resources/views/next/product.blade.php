@extends('layouts.main')
@section('content')

    <main class="site-main next-product-wrap">
        <div class="columns container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.available_hebrew')</a></li>
                @if(isset($breadCramb))
                    <li>
                        <a href="/{{$siteslug}}/categoryMap/{{ $breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                    </li>
                    <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                    <li><a href="/ebay/category/{{ $breadCramb['subSubCategory'] }}/{{$categoryID}}/1">
                            {{ $breadCramb['subSubCategory'] }}</a></li>
                @endif
                <li class="active"><a href="#">{{strip_tags($productData['titileProduct'])}} </a></li>
            </ol><!-- Block  Breadcrumb-->
            <div class="row">
                <!-- Main Content -->
                <div class="col-md-12  col-main">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-7">
                            <div class="product-media media-horizontal bleach-photo section-product-pictures">
                                <div class="image_preview_container images-large">
                                    <img id="img_zoom"
                                         data-zoom-image="{{ $productData['getMainImages']}}"
                                         src="{{$productData['getMainImages']}}" alt="">
                                    <button class="btn-zoom open_qv"><span>zoom</span></button>
                                </div>
                                <div class="product_preview images-small">
                                    <div class="owl-carousel thumbnails_carousel" id="thumbnails" data-nav="true"
                                         data-dots="false" data-margin="10"
                                         data-responsive='{"0":{"items":3},"480":{"items":4},"600":{"items":5},"768":{"items":3}}'>
                                        @foreach($productData['additionalImagesAndVarilables']['Media'] as $additionalImages)
                                            <a href="#" data-image="{{$productData['additionalImagesAndVarilables']['ShotBasePath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPublication'] . $productData['additionalImagesAndVarilables']['ShotViewPath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPage'] . '/' . $additionalImages['name'] . '.jpg'}}"
                                               data-zoom-image="{{$productData['additionalImagesAndVarilables']['ShotBasePath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPublication'] . $productData['additionalImagesAndVarilables']['ShotViewPath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPage'] . '/' . $additionalImages['name'] . '.jpg'}}">
                                                <img class="carousel-same-size-image"
                                                     src="{{$productData['additionalImagesAndVarilables']['ShotBasePath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPublication'] . $productData['additionalImagesAndVarilables']['ShotViewPath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPage'] . '/' . $additionalImages['name'] . '.jpg'}}"
                                                     data-large-image="{{$productData['additionalImagesAndVarilables']['ShotBasePath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPublication'] . $productData['additionalImagesAndVarilables']['ShotViewPath'] .
                                            $productData['additionalImagesAndVarilables']['hdnPage'] . '/' . $additionalImages['name'] . '.jpg'}}"
                                                     alt="">
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
                                        {{strip_tags($productData['titileProduct'])}}
                                    </h1>
                                    <table class="table-product-detail">
                                        <tr>
                                            <td style="width: 150px;">
                                                <span>@lang('general.product_price'):</span>
                                            </td>
                                            <td>
                                                <span id="price" class="price">
                                                        @if(isset($productData['price']))
                                                        {{ $productData['price'] }}
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
                                                <span>@lang('general.buy_over_150_NIS')</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>@lang('general.delivery_time'):</span>
                                            </td>
                                            <td>
                                                <span>@lang('general.up_to_14_business_days')</span>
                                            </td>
                                        </tr>
                                        @if(isset($productData['description']))
                                        <tr>
                                            <td>
                                                <span>@lang('general.description')</span>
                                            </td>
                                            <td>
                                                <span>{{$productData['description']}}</span>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                    <div class="product-add-form">
                                        <div class="product-options-wrapper">
                                            <p>@lang('general.available_options'):</p>
                                            <table class="table-variations-next">
                                                @if($fits != null)
                                                    <tr style="display: block;">
                                                        <td style="width: 150px;">
                                                            <span>Fit:</span>
                                                        </td>
                                                        <td class="table-select-wrap">
                                                            <select class="select-fits variation form-control">
                                                                <option value=""> Choose Fits</option>
                                                                @foreach ($fits as $fit)
                                                                    <option value="{{$fit}}">
                                                                        {{$fit}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($haveColor == true)
                                                    @foreach($productData['additionalImagesAndVarilables']['Styles']['Fits'] as $colors)
                                                        <tr class="colorList"
                                                            @if($fits != null)
                                                            style="display: none;"
                                                            @endif
                                                            data-name-first="{{$colors['Name']}}">
                                                            <td style="width: 150px;">
                                                                <span>Color:</span>
                                                            </td>
                                                            <td class="table-select-wrap">
                                                                <select class="select-color variation form-control">
                                                                    <option value=""> Choose Color</option>
                                                                    @foreach ($colors['Items'] as $color)
                                                                        <option value="{{str_replace('&#8362 ', '', $color['FullPrice'])}}"
                                                                                data-item-number="{{$color['ItemNumber']}}">
                                                                            <?php echo $color['Colour'];?>
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @foreach($productData['additionalImagesAndVarilables']['Styles']['Fits'] as $size)
                                                    @foreach($size['Items'] as $options_sizes)
                                                        <tr class="sizeList"
                                                            @if($fits != null || $haveColor == true)
                                                            style="display: none;"
                                                            @endif
                                                            data-color="{{$options_sizes['ItemNumber']}}">
                                                            <td style="width: 150px;">
                                                                <span>Size:</span>
                                                            </td>
                                                            <td class="table-select-wrap">
                                                                <select class="select-size variation form-control">
                                                                    <option value=""> Choose Size</option>
                                                                    @foreach($options_sizes['Options'] as $options_size)
                                                                        <option
                                                                                @if($options_size['StockStatus'] == 'SoldOut')
                                                                                disabled
                                                                                @endif
                                                                                value="{{str_replace('&#8362 ', '', $options_size['Price'])}}">
                                                                            <?php
                                                                            if ($options_size['StockStatus'] == 'SoldOut') {
                                                                                echo $options_size['Name'] . ' נגמר במלאי ';
                                                                            } else {
                                                                                echo $options_size['Name'];
                                                                            }
                                                                            ?>
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </table>
                                            <hr>
                                        </div>
                                        {{--@endif--}}
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
                                                                     src="{{ $productData['getMainImages']}}">
                                                                <p>
                                                                    {{$productData['titileProduct']}}
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
                                </div><!-- Main detail -->
                            </div>
                        </div>
                    </div><!-- Main Content -->
                    <!-- product tab info -->
                    <div class="product-info-detailed">
                        <!-- Block similar products of -->
                        <div class="container ">
                            <div class="block-deals-of-opt2">
                                <div class="block-title ">
                                    <span class="title">@lang('general.similar_products')</span>
                                </div>
                                <div class="block-content">
                                    @if(!isset($similarProducts))
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
                                            @foreach($similarProducts as $product)
                                                <div class="product-item product-item-opt-2">
                                                    <div class="product-item-info">
                                                        <div class="product-item-photo">
                                                            <a class="product-item-img"
                                                               href="{{$productBase}}/{{str_replace('/','-',$product->title)}}/{{$product->id}}">
                                                                <img style="min-height: 250px;"
                                                                     src='{{ $product->imageUrl }}'
                                                                     alt="{{$product->title}}">
                                                            </a>
                                                        </div>
                                                        <div class="product-item-detail">
                                                            <strong class="product-item-name"
                                                                    style="min-height: 60px; height: 60px; overflow: hidden;">
                                                                <a href="{{$productBase}}/{{str_replace('/','-',$product->title)}}/{{$product->id}}">
                                                                        <span>
                                                                            {{$product->title}}
                                                                        </span>
                                                                </a>
                                                            </strong>
                                                            <div class="clearfix">
                                                                <div class="product-item-price">
                                                                    <span class="price">{{$product->price}}</span>
                                                                    <span class="price">&#8362;</span>
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
                    </div>
                </div>
            </div>
        </div>
    </main><!-- end MAIN -->
    @include('includes.chooseVariationsModal')
    <?=$productUrl;?>
    <?=$imageUrl;?>
    <script src="{{ URL::asset('assets/next/js/next_product.js') }}"></script>
@stop