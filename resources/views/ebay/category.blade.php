@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="columns container ebay-products">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.ebay') </a></li>
                <li>
                    <a href="/{{$siteslug}}/categoryMap/{{ $breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                </li>
                <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                <li class="active"><a href="#">{{ $breadCramb['subSubCategory'] }} </a></li>
            </ol><!-- Block  Breadcrumb-->
            <div class="row">
                <div class="col-md-9 col-md-push-3  col-main cat-block">
                    <div class="toolbar-products toolbar-top"><h1 class="cate-title">{{ $categoryData['title'] }}</h1>
                    </div>
                    <div class=" toolbar-products pagination-sort-direction">
                        <div class="toolbar-option">
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
                                                value="PricePlusShippingHighest">@lang("general.price_high_to_low")
                                        </option>
                                        <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="PricePlusShippingLowest")
                                                selected="selected"
                                                @endif
                                                value="PricePlusShippingLowest">@lang("general.price_low_to_high")
                                        </option>
                                    </select>
                                </form>
                            </div><!-- Short by -->

                        </div>
                    </div>

                     {{--<div class="category-banner">--}}
                                                {{--<!-- code from sekindo - Chipi_Banner_Desk - 728x90 - banner -->--}}
                                                {{--<script type="text/javascript" language="javascript" src="http://live.sekindo.com/live/liveView.php?s=88149&cbuster=[CACHE_BUSTER]&pubUrl=[PAGE_URL_ENCODED]"></script>--}}
                                                {{--<!-- code from sekindo -->--}}
                     {{--</div>--}}
                    <div class="products  products-grid">
                        <ol class="product-items row">
                            @for($i=0;$i<count($ebayData);$i+=1)
                                <li class="col-sm-4 product-item ">
                                    <div class="product-item-opt-1">
                                        <a href="{{$productBase}}/{{ (explode("/", $ebayData[$i]->productUrl)[4])}}/{{explode("?",explode("/", $ebayData[$i]->productUrl)[5])[0] }}?categoryID={{$categoryData["id"]}}">
                                            <div class="product-item-photo">
                                                <div class="loader-wrap"></div>
                                                <img class="category-fixed-size-image"
                                                     src='{{"https://galleryplus.ebayimg.com/ws/web/" . $ebayData[$i]->productId . "_1_1_1.jpg"}}'
                                                     alt="{{$ebayData[$i]->productTitle}}"
                                                     onerror=this.src='{{str_replace("http:","https:",$ebayData[$i]->imageUrl)}}'>
                                            </div>
                                            @if(isset($ebayData[$i]->discount))
                                                <span class="product-item-label label-sale-off">{{$ebayData[$i]->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                            @endif
                                            <div class="product-item-detail">
                                                <strong class="product-item-name"
                                                        style="min-height: 60px; height: 60px; overflow: hidden;">
                                                    <span>{{$ebayData[$i]->productTitle}}</span>
                                                </strong>
                                                <div class="clearfix">
                                                    <div class="product-item-price">
                                                        <span class="price">&#8362;{{ $ebayData[$i]->price }}</span>
                                                        @if(isset($ebayData[$i]->oldPrice))
                                                            <span class="old-price">&#8362;{{$ebayData[$i]->oldPrice}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @endfor
                        </ol><!-- list product -->
                    </div> <!-- List Products -->

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
                                            <span><i aria-hidden="true" class="fa fa-angle-double-left"></i></span>@lang('general.next')
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
                                                    <a href="/{{$siteslug}}/category/{{$category->subSubcatName}}/{{$category->ebayId}}/1">
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
                        <div class="block-title">
                            <strong>@lang('general.filters')</strong>
                        </div>
                        <div class="block-content">
                            @if($filterData)
                                @foreach($filterData as $filter)
                                    <div class="filter-options-item filter-options-categori">
                                        <div class="filter-options-title">{{ $filter->filterNameHeb }}</div>
                                        <div class="filter-options-content"
                                             data-filter-name="{{ $filter->filterNameEn }}">
                                            <ol class="items">
                                                @for ($i=0;$i<count($filter->filterValueEn);$i++)
                                                    <li class="item
@if($i>=6)
                                                            item-hidden
                                                            hidden
@endif" data-filter-name="{{ $filter->filterNameEn }}">
                                                        <label>
                                                            <input value="{{ $filter->filterValueEn[$i] }}"
                                                                   name="{{ $filter->filterNameEn }}"
                                                                   type="checkbox"
                                                                   @if($checkedInput)
                                                                   @foreach($checkedInput as $checkedFilterTitle=>$arrayChecked)
                                                                   @foreach($arrayChecked as $checkedFilterName)
                                                                   @if($filter->filterValueEn[$i]==$checkedFilterName&$filter->filterNameEn==$checkedFilterTitle)
                                                                   checked
                                                                    @endif
                                                                    @endforeach
                                                                    @endforeach
                                                                    @endif
                                                            >
                                                            <span>{{ $filter->filterValueHeb[$i] }}</span>
                                                        </label>
                                                    </li>
                                                @endfor
                                            </ol>
                                            @if(count($filter->filterValueEn)>6)
                                                <a class="filters" href="#" data-name="{{ $filter->filterNameEn }}">
                                                    @lang('general.apply_filters')
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                {{--<button class="submit" type="submit"--}}
                                {{--name="submit">  @lang('general.apply_filters')</button>--}}
                            @else
                                <span class="filter-options-title">@lang('general.no_filters_available')</span>
                            @endif
                        </div>
                    </div>
                    {{--<div class="category-banner">--}}
                        {{--<!-- code from sekindo - Chipi_Banner_Desk - 160x600 - banner -->--}}
                        {{--<script type="text/javascript" language="javascript" src="http://live.sekindo.com/live/liveView.php?s=88148&cbuster=[CACHE_BUSTER]&pubUrl=[PAGE_URL_ENCODED]"></script>--}}
                        {{--<!-- code from sekindo -->--}}
                    {{--</div>--}}
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
        </div>
    </main>
    <script src="{{ URL::asset('assets/ebay/js/ebay_category.js') }}"></script>
@stop