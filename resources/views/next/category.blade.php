@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.available_hebrew')</a></li>
                <li>
                    <a href="/{{$siteslug}}/categoryMap/{{ $breadCramb['category_id']}}"><span>{{ $breadCramb['category'] }} </span></a>
                </li>
                <li><span>{{ $breadCramb['subCategory'] }} </span></li>
                <li class="active"><a href="#">{{ $breadCramb['subSubCategory'] }} </a></li>
            </ol><!-- Block  Breadcrumb-->

            <div class="next-category-wrap">
                <div class="toolbar-products toolbar-top"><h1 class="cate-title">{{ $categoryData['title'] }}</h1>
                </div>
                <div class=" toolbar-products pagination-sort-direction">
                    <div class="toolbar-option">
                        <div class="toolbar-sorter ">
                            <label class="label">Short by:</label>
                            <form href="{{$_SERVER["REQUEST_URI"]}}">
                                <select id="sort_select" name="sortOrder"
                                        class="category-sort sorter-options form-control">
                                    <option @if(!isset($_GET['sortOrder']))
                                            selected="selected"
                                            @endif
                                            value="id|asc">@lang('general.best_match')
                                    </option>
                                    <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="price|asc")
                                            selected="selected"
                                            @endif
                                            value="price|asc">@lang('general.price_low_to_high')
                                    </option>
                                    <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="price|desc")
                                            selected="selected"
                                            @endif
                                            value="price|desc">@lang('general.price_high_to_low')
                                    </option>

                                </select>
                            </form>
                        </div><!-- Short by -->
                    </div>
                </div>
                <div class="products  products-grid">
                    <ol class="product-items row">
                        @for($i=0;$i<count($nextData);$i+=1)
                            <li class="col-sm-3 product-item ">
                                <div class="product-item-opt-1">
                                    <div class="product-item-info">
                                        <a href="{{$productBase}}/{{str_replace('/','-',$nextData[$i]->title)}}/{{$nextData[$i]->id}}">
                                            <div class="product-item-photo">
                                                <div class="loader-wrap"></div>
                                                <div class="product-item-img">
                                                    <img class="category-fixed-size-image"
                                                         src='{{ $nextData[$i]->imageUrl }}'
                                                         alt="{{$nextData[$i]->title}}"
                                                            {{--onerror=this.src='{{str_replace("http:","https:",$ebayData[$i]->imageUrl)}}'--}}
                                                    >
                                                </div>
                                            </div>
                                        </a>
                                        <div class="product-item-detail">
                                            <strong class="product-item-name"
                                                    style="min-height: 60px; height: 60px; overflow: hidden;">
                                                <a href="{{$productBase}}/{{str_replace('/','-',$nextData[$i]->title)}}/{{$nextData[$i]->id}}"><span>{{$nextData[$i]->title}}</span></a>
                                            </strong>
                                            <div class="clearfix">
                                                <div class="product-item-price">
                                                    <span class="price">{{ $nextData[$i]->price }}</span>
                                                    <span class="price">&#8362;</span>
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

        </div>
    </main>

    <script src="{{ URL::asset('assets/ebay/js/ebay_category.js') }}"></script>
@stop