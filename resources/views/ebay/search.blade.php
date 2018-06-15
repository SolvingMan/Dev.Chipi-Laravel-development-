@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="columns container ebay-products-search">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.ebay') </a></li>
                <li class="active"><a href="#">@lang('general.search') </a></li>
                <li><a>{{$keywordEnglish}}</a></li>
            </ol><!-- Block  Breadcrumb-->

            <div class="row">
                <div class="
               @if($totalEntries>0)
                        col-md-9 col-md-push-3
                        @else
                        col-md-12
                        @endif
                        col-main cat-block">
                    <div class="toolbar-products toolbar-top"><h1 class="cate-title">{{ $keyword}}</h1>
                    </div>
                    <div class=" toolbar-products pagination-sort-direction">

                        <div class="toolbar-option">
                            @if($totalEntries>0)
                                <div class="toolbar-sorter ">
                                    <label class="label">Short by:</label>
                                    <form href="{{$_SERVER["REQUEST_URI"]}}">
                                        <select id="sort_select" name="sortOrder"
                                                class="category-sort sorter-options form-control">
                                            <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="BestMatch")
                                                    selected="selected"
                                                    @endif
                                                    value="BestMatch">@lang('general.best_match')
                                            </option>
                                            <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="PricePlusShippingHighest")
                                                    selected="selected"
                                                    @endif
                                                    value="PricePlusShippingHighest">@lang('general.price_high_to_low')
                                            </option>
                                            <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="PricePlusShippingLowest")
                                                    selected="selected"
                                                    @endif
                                                    value="PricePlusShippingLowest">@lang('general.price_low_to_high')
                                            </option>
                                        </select>
                                        {{--<button type="submit" class="sorter-action"></button>--}}
                                    </form>
                                </div><!-- Short by -->
                            @endif

                            {{--<div class="toolbar-limiter">--}}
                            {{--<label   class="label">--}}
                            {{--<es>Show:</es>--}}
                            {{--</label>--}}

                            {{--<select class="limiter-options form-control" >--}}
                            {{--<option selected="selected" value="9"> Show 18</option>--}}
                            {{--<option value="15">Show 15</option>--}}
                            {{--<option value="30">Show 30</option>--}}
                            {{--</select>--}}

                            {{--</div><!-- Show per page -->--}}

                        </div>
                    </div>
                    <div class="category-banner">
                        <!-- code from sekindo - Chipi_Banner_Desk - 728x90 - banner -->
                        <script type="text/javascript" language="javascript" src="http://live.sekindo.com/live/liveView.php?s=88149&cbuster=[CACHE_BUSTER]&pubUrl=[PAGE_URL_ENCODED]"></script>
                        <!-- code from sekindo -->
                    </div>
                    @if($totalEntries>0)
                        <div class="products  products-grid">
                            <ol class="product-items row">
                                @for($i=0;$i<count($ebayData);$i+=1)
                                    <li class="col-sm-4 product-item ">
                                        <div class="product-item-opt-1">
                                            <div class="product-item-info">
                                                <div class="product-item-photo">
                                                    <div class="loader-wrap"></div>
                                                    <a href="{{$productBase}}/{{ (explode("/", $ebayData[$i]->productUrl)[4])}}/{{explode("?",explode("/", $ebayData[$i]->productUrl)[5])[0] }}"
                                                       class="product-item-img">
                                                        <img class="category-fixed-size-image"
                                                             src='{{"https://galleryplus.ebayimg.com/ws/web/" . $ebayData[$i]->productId . "_1_1_1.jpg"}}'
                                                             alt="{{$ebayData[$i]->productTitle}}"
                                                             onerror=this.src='{{str_replace("http:","https:",$ebayData[$i]->imageUrl)}}'>
                                                    </a>
                                                    @if(isset($ebayData[$i]->discount))
                                                        <span class="product-item-label label-sale-off">{{$ebayData[$i]->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                                    @endif
                                                </div>
                                                <div class="product-item-detail">
                                                    <strong class="product-item-name"
                                                            style="min-height: 60px; height: 60px; overflow: hidden;">
                                                        <a href="{{$productBase}}/{{ (explode("/", $ebayData[$i]->productUrl)[4])}}/{{explode("?",explode("/", $ebayData[$i]->productUrl)[5])[0] }}">
                                                            <span>{{$ebayData[$i]->productTitle}}</span>
                                                        </a>
                                                    </strong>
                                                    <div class="clearfix">
                                                        <div class="product-item-price">
                                                            <span class="price">&#8362;{{ $ebayData[$i]->price }}</span>
                                                        </div>
                                                        @if(isset($ebayData[$i]->oldPrice))
                                                            <span class="old-price">&#8362;{{$ebayData[$i]->oldPrice}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endfor
                            </ol><!-- list product -->
                        </div> <!-- List Products -->
                    @else
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

                <!-- Toolbar -->
                    <div class=" toolbar-products toolbar-bottom pagination-sort-direction">
                        @if(isset($pagination))
                            <ul class="pagination">
                                @foreach($pagination as $pageNum=>$pageUrl)
                                    <li @if($pageNum==$page)
                                        class="active"
                                            @endif>
                                        <a href="{{$pageUrl}}">
                                            {{$pageNum}}
                                        </a>
                                    </li>
                                @endforeach
                                @if(isset($pageNext))
                                    <li class="action action-next">
                                        <a href="{{$pageNext}}">
                                            <span><i aria-hidden="true" class="fa fa-angle-double-left"></i></span>
                                            @lang('general.next')
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
                @if($totalEntries>0)
                    <div class="col-md-3 col-md-pull-9  col-sidebar">
                        <div id="layered-filter-block" class="block-sidebar block-filter no-hide">
                            <div class="close-filter-products">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </div>
                            <div class="block-title">
                                <strong>@lang('general.filters')</strong>
                            </div>
                            <div class="block-content">
                                @if($filterData)
                                    @foreach($filterData->aspect as $filter)
                                        <div class="filter-options-item filter-options-categori">
                                            <div class="filter-options-title">{{ $filter->{"@name"} }}</div>
                                            <div class="filter-options-content"
                                                 data-filter-name="{{ $filter->{"@name"} }}">
                                                <ol class="items">
                                                    @for ($i=0;$i<count($filter->valueHistogram);$i++)
                                                        {{--@foreach($filter->valueHistogram as $key=>$filterName)--}}
                                                        {{--@if($i==6)--}}
                                                        {{--<div class="hidden"--}}
                                                        {{--data-filter-name="{{ $filter->{"@name"} }}">--}}
                                                        {{--@endif--}}
                                                        <li class="item
@if($i>=6)
                                                                item-hidden
                                                                hidden
@endif" data-filter-name="{{ $filter->{"@name"} }}">
                                                            <label>
                                                                <input value="{{ $filter->valueHistogram[$i]->{"@valueName"} }}"
                                                                       name="{{ $filter->{"@name"} }}"
                                                                       type="checkbox"
                                                                       @if($checkedInput)
                                                                       @foreach($checkedInput as $checkedFilterTitle=>$arrayChecked)
                                                                       @foreach($arrayChecked as $checkedFilterName)
                                                                       @if($filter->valueHistogram[$i]->{"@valueName"}==$checkedFilterName&$filter->{"@name"}==$checkedFilterTitle)
                                                                       checked
                                                                        @endif
                                                                        @endforeach
                                                                        @endforeach
                                                                        @endif
                                                                >
                                                                <span>{{ $filter->valueHistogram[$i]->{"@valueName"} }}</span>
                                                            </label>
                                                        </li>
                                                        {{--@if($i==count($filter->valueHistogram)&$i>4)--}}
                                                        {{--</div>--}}
                                                        {{--@endif--}}
                                                    @endfor
                                                </ol>
                                                @if(count($filter->valueHistogram)>6)
                                                    <a class="filters" href="#" data-name="{{ $filter->{"@name"} }}">@lang('general.apply_filters')</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    {{--<button id="submit" type="submit"--}}
                                    {{--name="submit"> @lang('general.apply_filters')</button>--}}
                                @else
                                    <span class="filter-options-title">@lang('general.no_filters_available')</span>
                                @endif
                            </div>
                        </div>
                        <div class="category-banner">
                            <!-- code from sekindo - Chipi_Banner_Desk - 160x600 - banner -->
                            <script type="text/javascript" language="javascript" src="http://live.sekindo.com/live/liveView.php?s=88148&cbuster=[CACHE_BUSTER]&pubUrl=[PAGE_URL_ENCODED]"></script>
                            <!-- code from sekindo -->
                        </div>
                        <div class="container-fluid filter-category-button">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" class="btn btn-success btn-fab submit" id="main"
                                           type="submit" name="submit">
                                            @lang('general.filter_results')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endif
        </div>
    </main>
    <script src="{{ URL::asset('assets/ebay/js/ebay_category.js') }}"></script>
@stop