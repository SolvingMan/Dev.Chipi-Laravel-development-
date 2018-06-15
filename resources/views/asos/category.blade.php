@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">נקסט בעברית</a></li>
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
                                            value >התאמה הטובה ביותר
                                    </option>
                                    <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="freshness")
                                            selected="selected"
                                            @endif
                                            value="freshness">החדשים
                                    </option>
                                    <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="pricedesc")
                                            selected="selected"
                                            @endif
                                            value="pricedesc">מחיר מהגבוה לנמוך
                                    </option>

                                    <option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="priceasc")
                                            selected="selected"
                                            @endif
                                            value="priceasc">מחיר מנמוך לגבוה
                                    </option>
                                </select>
                            </form>
                        </div><!-- Short by -->
                    </div>
                </div>
                <div class="products  products-grid">
                    <ol class="product-items row">
                        @for($i=0;$i<count($asosData);$i+=1)
                            <li class="col-sm-3 product-item ">
                                <div class="product-item-opt-1">
                                    <div class="product-item-info">
                                        <a href="{{$productBase}}/{{$asosData[$i]['title']}}/{{$asosData[$i]['productId']}}?cid={{$categoryData['id']}}">
                                            <div class="product-item-photo">
                                                <div class="loader-wrap"></div>
                                                <div class="product-item-img">
                                                    <img class="category-fixed-size-image"
                                                         src='{{ $asosData[$i]['imageUrl'] }}'
                                                         alt="{{$asosData[$i]['title']}}">
                                                </div>
                                            </div>
                                        </a>
                                        <div class="product-item-detail">
                                            <strong class="product-item-name"
                                                    style="min-height: 60px; height: 60px; overflow: hidden;">
                                                <a href="{{$productBase}}/{{$asosData[$i]['title']}}/{{$asosData[$i]['productId']}}?cid={{$categoryData['id']}}">
                                                    <span>{{$asosData[$i]['title']}}</span>
                                                </a>
                                            </strong>
                                            <div class="clearfix">
                                                <div class="product-item-price">
                                                    <span class="price">{{ $asosData[$i]['price'] }}</span>
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
                                        <span><i aria-hidden="true" class="fa fa-angle-double-left"></i></span>הבא
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