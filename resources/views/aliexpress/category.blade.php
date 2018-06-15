@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="columns container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.aliexpress') </a></li>
                @if(isset($breadCramb))
                    <li>
                        <a href="/{{$siteslug}}/categoryMap/{{$breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                    </li>
                    <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                    <li class="active"><a href="#">{{ $breadCramb['subSubCategory'] }} </a></li>
                @else
                    <li class="active"><a href="#">@lang('general.search') </a></li>

                @endif
            </ol>

            <div class="row">
                <div class="col-md-9 col-md-push-3 col-main cat-block">
                    <div class="toolbar-products toolbar-top">
                        <h1 class="cate-title">{{ $categoryData['title'] }}</h1>
                        {{--<div class="modes">--}}
                            {{--<strong class="label">View as:</strong>--}}
                            {{--<strong class="modes-mode active mode-grid" title="Grid">--}}
                                {{--<es>grid</es>--}}
                            {{--</strong>--}}
                            {{--<a href="" id="list" title="List" class="modes-mode mode-list">--}}
                                {{--<es>list</es>--}}
                            {{--</a>--}}
                        {{--</div><!-- View as -->--}}
                    </div>
                    <div class=" toolbar-products pagination-sort-direction">
                        <div class="toolbar-option">
                            <div class="toolbar-sorter ">
                                <label class="label">@lang('general.sort_by'):</label>
                                <form href="{{$_SERVER["REQUEST_URI"]}}">
                                    @if(isset($keyword))
                                        <input type="hidden"
                                               name="searchstring" value="{{$keyword}}">
                                    @endif
                                    <select id="sort_select" name="sort"
                                            class="category-sort sorter-options form-control">
                                        @if(!isset($_GET['sort']))
                                            <option>
                                                @lang('general.select_sort')
                                            </option>
                                        @endif
                                        <option @if(isset($_GET['sort'])&& $_GET['sort']=="sellerRateDown")
                                                selected="selected"
                                                @endif
                                                value="sellerRateDown">@lang('general.seller_rate_down')
                                        </option>
                                        <option @if(isset($_GET['sort'])&& $_GET['sort']=="orignalPriceUp")
                                                selected="selected"
                                                @endif
                                                value="orignalPriceUp">@lang('general.original_price_up')
                                        </option>
                                        <option @if(isset($_GET['sort'])&& $_GET['sort']=="orignalPriceDown")
                                                selected="selected"
                                                @endif
                                                value="orignalPriceDown">@lang('general.original_price_down')
                                        </option>
                                    </select>
                                    {{--<button type="submit" class="sorter-action"></button>--}}
                                </form>
                            </div><!-- Short by -->
                        </div>
                    </div>
                    @if(isset($totalResults))
                        @if($totalResults == 0 )
                            <div class="empty-search">
                                לא נמצאו תוצאות
                                <p class="lead">אנו עושים את מרב המאמצים לספק תוצאות מתאימות
                                </p>
                                <p class="lead">אנא נסה את האפשרויות הבאות:
                                <ol>
                                    <li>השתמש בהשלמה האוטומטית הקיימת בשורת החיפוש.</li>
                                    <li>נסה לחפש את הביטוי באנגלית.</li>
                                    <li>נסה מילת חיפוש אחרת.</li>
                                    <li>נסה לקצר את מונח החיפוש.</li>
                                    <li>נסה מילת חיפוש כללית.</li>
                                    <li>נסה חיפוש קטגוריה.</li>
                                    <li>וודא שהמונח המחופש מאויית נכון.</li>
                                </ol>
                            </div>
                        @endif
                    @endif
                    {{--<div class="category-banner">--}}
                        {{--<!-- code from sekindo - Chipi_Banner_Desk - 728x90 - banner -->--}}
                        {{--<script type="text/javascript" language="javascript" src="http://live.sekindo.com/live/liveView.php?s=88149&cbuster=[CACHE_BUSTER]&pubUrl=[PAGE_URL_ENCODED]"></script>--}}
                        {{--<!-- code from sekindo -->--}}
                    {{--</div>--}}
                    <div id="grid-block" class="products  products-grid">
                        <ol class="product-items row">
                            @for($i = 0; $i < count($aliData); $i += 1)
                                <li class="col-sm-4 product-item ">
                                    <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}?categoryID={{$categoryData["id"]}}">
                                        <div class="product-item-opt-1">
                                            <div class="product-item-info">
                                                <div class="product-item-photo">
                                                    <div class="loader-wrap"></div>
                                                    <div class="product-item-img">
                                                        <img class="category-fixed-size-image"
                                                             src="{{$aliData[$i]->imageUrl}}"
                                                             alt="{{$aliData[$i]->productTitle}}">
                                                    </div>
                                                    @if($aliData[$i]->discount != "0%")
                                                        <span class="product-item-label label-sale-off">{{$aliData[$i]->discount}}
                                                            -
                                            <span>@lang('general.off')</span></span>
                                                    @endif
                                                </div>
                                                <div class="product-item-detail">
                                                    <strong class="product-item-name"
                                                            style="min-height: 60px; height: 60px; overflow: hidden;">
                                                        <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}?categoryID={{$categoryData["id"]}}">
                                                            <span>{{$aliData[$i]->productTitle}}</span>
                                                        </a>
                                                    </strong>
                                                    <div class="clearfix">
                                                        <div class="product-item-price">
                                                            <span class="price">&#8362;{{ $aliData[$i]->salePrice }}</span>
                                                            @if($aliData[$i]->discount != "0%")
                                                                <span class="old-price">&#8362;{{$aliData[$i]->originalPrice}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="product-reviews-summary">
                                                            <div class="rating-summary">
                                                                <div class="rating-result"
                                                                     title="{{ $aliData[$i]->evaluateScore*20 }}%">
                                                                <span style="width:{{ $aliData[$i]->evaluateScore*20 }}%">
                                                                    <span>
                                                                        <span>{{ $aliData[$i]->evaluateScore*20 }}</span>
                                                                        % of
                                                                        <span>100</span>
                                                                    </span>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endfor
                        </ol><!-- list product -->
                    </div> <!-- List Products -->

                    <div id="list-block" class="products  products-list" style="display: none;">
                        <ol class="product-items row">
                            @for($i = 0; $i < count($aliData); $i += 1)
                                <li class="col-sm-12 product-item ">
                                    <div class="product-item-opt-1">
                                        <div class="product-item-info">
                                            <div class="product-item-photo">
                                                <div class="loader-wrap"></div>
                                                <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}?categoryID={{$categoryData["id"]}}"
                                                   class="product-item-img">
                                                    <img class="category-fixed-size-image"
                                                         src="{{$aliData[$i]->imageUrl}}" alt="product name">
                                                </a>

                                                <span class="product-item-label label-price">10% <span>off</span></span>
                                            </div>
                                            <div class="product-item-detail">
                                                <strong class="product-item-name">
                                                    <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}?categoryID={{$categoryData["id"]}}">
                                                        <b>{{$aliData[$i]->productTitle}}</b>
                                                    </a>
                                                </strong>
                                                <div class="clearfix product-info-des">
                                                    <div class="product-reviews-summary">
                                                        <div class="rating-summary">
                                                            <div class="rating-result"
                                                                 title="{{$aliData[$i]->evaluateScore*20}}%">
                                                                <span style="width:{{$aliData[$i]->evaluateScore*20}}%">
                                                                    <span>
                                                                        <span>
                                                                            {{$aliData[$i]->evaluateScore*20}}
                                                                        </span>% of <span>100</span>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-item-price">
                                                        <span class="price">&#8362;{{ $aliData[$i]->salePrice }}</span>
                                                    </div>
                                                    <div class="product-item-stock">
                                                        <span class="title">Availability:</span> In stock
                                                    </div>
                                                    <div class="product-item-actions">
                                                        <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}?categoryID={{$categoryData["id"]}}">
                                                            <button class="btn btn-cart" type="button">
                                                                <span>@lang('general.details')</span>
                                                            </button>
                                                        </a>
                                                        <a href=""
                                                           class="btn btn-wishlist"><span>wishlist</span></a>
                                                        <a href="" class="btn btn-compare"><span>compare</span></a>
                                                        <a href=""
                                                           class="btn btn-quickview"><span>quickview</span></a>
                                                    </div>
                                                </div>
                                                <div class="product-item-des">
                                                    Description
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endfor
                        </ol><!-- list product -->
                    </div> <!-- List Products -->

                    <!-- Toolbar -->
                    <div class=" toolbar-products toolbar-bottom pagination-sort-direction">
                        @if(isset($pageLinks))
                            <ul class="pagination">
                                @foreach($pageLinks as $pageNum=>$pageLink)
                                    <li @if($pageNum==$page)
                                        class="active"
                                            @endif
                                    >
                                        <a href="{{$pageLink}}">
                                            {{$pageNum}}
                                        </a>
                                    </li>
                                @endforeach
                                @if(isset($nextPageLink))
                                    <li class="action action-next">
                                        <a href="{{ $nextPageLink }}">
                                            <span><i aria-hidden="true" class="fa fa-angle-double-left"></i></span>
                                           @lang('general.next')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div><!-- Toolbar -->
                </div>
                <div class="col-md-3 col-md-pull-9  col-sidebar">
                    <div id="layered-filter-block" class="block-sidebar block-filter no-hide bleach">
                        <div class="close-filter-products">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </div>
                        @if(isset($similarCategories))
                            <div class="block-title">
                                <strong>@lang('general.more_categories')</strong>
                            </div>
                            <div class="block-content">
                                <ol class="items">
                                    @foreach($similarCategories as $key=>$category)
                                        @if($key==8)
                                            <div class="similar-categories-hide-block hidden">
                                                @endif
                                                @if($category->type == 0)
                                                    <li class="item">
                                                        <a href="/{{$siteslug}}/category/{{$category->subSubcatName}}/{{$category->aliexpressId}}/1">
                                                            {{$category->subSubcatName}}
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="item">
                                                        <a href="/{{$siteslug}}/search/{{$category->search}}/1">
                                                            {{$category->subSubcatName}}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if((count($similarCategories)>8) && ($key==count($similarCategories)-1))
                                            </div>
                                            <a class="similar-categories category-close" href="#">@lang('general.more_categories')</a>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                        @endif
                    </div>
                    {{--<div class="category-banner">--}}
                        {{--<!-- code from sekindo - Chipi_Banner_Desk - 160x600 - banner -->--}}
                        {{--<script type="text/javascript" language="javascript" src="http://live.sekindo.com/live/liveView.php?s=88148&cbuster=[CACHE_BUSTER]&pubUrl=[PAGE_URL_ENCODED]"></script>--}}
                        {{--<!-- code from sekindo -->--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </main>

    <script src="{{ URL::asset('assets/aliexpress/js/category.js') }}"></script>
@stop