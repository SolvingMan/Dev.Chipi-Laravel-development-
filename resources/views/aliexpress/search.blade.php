@extends('layouts.main')
@section('content')

    <main class="site-main">
        <div class="columns container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">עליאקספרס בעברית </a></li>
                <li class="active"><a href="#">חפש </a></li>

            </ol><!-- Block  Breadcrumb-->
            {{--<div class="row">--}}
            {{--<div class="col-xs-1">Sort:</div>--}}
            {{--<div class="col-xs-1">Original Price</div>--}}
            {{--<div class="row col-xs-2">--}}
            {{--<div class="col-xs-3">--}}
            {{--<form action="">--}}
            {{--<input type="hidden" name="id" value="{{ $categoryData['id'] }}">--}}
            {{--<button type="submit">--}}
            {{--<input type="hidden" name="sort" value="orignalPriceUp">--}}
            {{--<input type="hidden" name="keywords">--}}
            {{--UP--}}
            {{--</button>--}}
            {{--</form>--}}
            {{--</div>--}}
            {{--<div class="col-xs-3">--}}
            {{--<form action="">--}}
            {{--<input type="hidden" name="id" value="{{ $categoryData['id'] }}">--}}
            {{--<button type="submit">--}}
            {{--<input type="hidden" name="sort" value="orignalPriceDown">--}}
            {{--DOWN--}}
            {{--</button>--}}
            {{--</form>--}}
            {{--</div>--}}
            {{--<div class="col-xs-6"></div>--}}
            {{--</div>--}}

            {{--<div class="col-xs-1">Seller rate</div>--}}

            {{--<div class="row col-xs-2">--}}
            {{--<div class="col-xs-3">--}}
            {{--<form action="">--}}
            {{--<input type="hidden" name="id" value="{{ $categoryData['id'] }}">--}}
            {{--<button type="submit">--}}
            {{--<input type="hidden" name="sort" value="sellerRateDown">--}}
            {{--Down--}}
            {{--</button>--}}
            {{--</form>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            <div class="row">
                <div class="col-md-9 col-md-push-3  col-main cat-block">
                    <div class="toolbar-products toolbar-top">
                        <h1 class="cate-title">{{ $categoryData['title'] }}</h1>
                        <div class="modes">
                            <strong class="label">View as:</strong>
                            <strong class="modes-mode active mode-grid" title="Grid">
                                <span>grid</span>
                            </strong>
                            <a href="" id="list" title="List" class="modes-mode mode-list">
                                <span>list</span>
                            </a>
                        </div><!-- View as -->
                    </div>
                    <div class=" toolbar-products toolbar-bottom pagination-sort-direction">
                        <div class="toolbar-option">
                            <div class="toolbar-sorter ">
                                <label class="label">@lang('general.sort_by'):</label>
                                <form href="/aliexpress/searc">
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
                    <div class="products  products-grid" id="grid-block">
                        <ol class="product-items row">
                            @for($i=0;$i<count($aliData);$i+=1)
                                <li class="col-sm-4 product-item ">
                                    <div class="product-item-opt-1">
                                        <div class="product-item-info">
                                            <div class="product-item-photo">
                                                <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}"
                                                   class="product-item-img">
                                                    <img src="{{$aliData[$i]->imageUrl}}"
                                                         alt="{{$aliData[$i]->productTitle}}">
                                                </a>
                                                <button class="btn btn-cart" type="button"><span>Add to Cart</span>
                                                </button>
                                            </div>
                                            <div class="product-item-detail">
                                                <strong class="product-item-name" style="min-height: 60px; height: 60px; overflow: hidden !important;">
                                                    <a href="{{$productBase}}/{{$aliData[$i]->productUrl}}">
                                                        <span>{{$aliData[$i]->productTitle}}</span>
                                                    </a>
                                                </strong>
                                                <div class="clearfix">
                                                    <div class="product-item-price">
                                                        <span class="price">&#8362;{{ $aliData[$i]->salePrice }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endfor
                        </ol><!-- list product -->
                    </div> <!-- List Products -->

                    <!-- Toolbar -->
                    <div class=" toolbar-products toolbar-bottom">
                        <ul class="pagination">
                            @foreach($pagination as $pageNum)
                                <form action="#">
                                    <input type="hidden" name="id" value="{{ $categoryData['id'] }}">
                                    @if($categoryData['sort']!="")
                                        <input type="hidden" name="sort" value="{{$categoryData['sort']}}">
                                    @endif
                                    <input type="hidden" name="page" value="{{$pageNum}}">
                                    <li>
                                        <button type="submit">{{$pageNum}}</button>
                                    </li>
                                </form>
                            @endforeach
                            <li class="action action-next">
                                <a href="{{$nextPageLink}}">
                                    <span><i aria-hidden="true" class="fa fa-angle-double-left"></i></span>
                                    הבא
                                </a>
                            </li>
                        </ul>
                    </div>
@stop
