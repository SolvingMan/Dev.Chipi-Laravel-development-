@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="container">
            <ol class="breadcrumb no-hide">
                <li><a href="/{{$siteslug}}">@lang('general.available_hebrew')</a></li>
                <li class="active"><a href="#">@lang('general.search')</a></li>
                <li><a>{{$keywordEnglish}}</a></li>
            </ol><!-- Block  Breadcrumb-->
            <div class="next-category-wrap">
                <!-- Этот див не нужен !!!!!!-->
                {{--  <div--}}
                {{--@if($totalEntries>0)
                         col-md-9 col-md-push-3
                         @else
                         col-md-12
                         @endif
                         {{--col-main cat-block"--}}{{-->--}}
                <div class="toolbar-products toolbar-top"><h1 class="cate-title">{{ $keyword}}</h1>
                </div>
                <div class=" toolbar-products pagination-sort-direction">

                    <div class="toolbar-option">
                        {{--@if($totalEntries>0)--}}
                        {{--<div class="toolbar-sorter ">--}}
                        {{--<label class="label">Short by:</label>--}}
                        {{--<form href="{{$_SERVER["REQUEST_URI"]}}">--}}
                        {{--<select id="sort_select" name="sortOrder"--}}
                        {{--class="category-sort sorter-options form-control">--}}
                        {{--<option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="BestMatch")--}}
                        {{--selected="selected"--}}
                        {{--@endif--}}
                        {{--value="BestMatch">התאמה טובה ביותר--}}
                        {{--</option>--}}
                        {{--<option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="PricePlusShippingHighest")--}}
                        {{--selected="selected"--}}
                        {{--@endif--}}
                        {{--value="PricePlusShippingHighest">מחיר : מגבוה לנמוך--}}
                        {{--</option>--}}
                        {{--<option @if(isset($_GET['sortOrder'])&& $_GET['sortOrder']=="PricePlusShippingLowest")--}}
                        {{--selected="selected"--}}
                        {{--@endif--}}
                        {{--value="PricePlusShippingLowest">מחיר : מנמוך לגבוה--}}
                        {{--</option>--}}
                        {{--</select>--}}
                        {{--<button type="submit" class="sorter-action"></button>--}}
                        {{--</form>--}}
                        {{--</div><!-- Short by -->--}}
                        {{--@endif--}}

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

                @if($totalEntries>0)
                    <div class="products  products-grid">
                        <ol class="product-items row">
                            @for($i=0;$i<count($nextData);$i+=1)
                                <li class="col-sm-3 product-item ">
                                    <div class="product-item-opt-1">
                                        <div class="product-item-info">
                                            <a href="{{$productBase}}/{{str_replace('http://www.next.co.il/he/','',
                                                    str_replace('#','@',$nextData[$i]->productUrl))}}/{{0}}">
                                                <div class="product-item-photo">
                                                    <div class="loader-wrap"></div>
                                                    <div class="product-item-img">
                                                        <img class="category-fixed-size-image"
                                                             src='{{ $nextData[$i]->imageUrl }}'
                                                             alt="{{$nextData[$i]->title}}">
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="product-item-detail">
                                                <strong class="product-item-name"
                                                        style="min-height: 60px; height: 60px; overflow: hidden;">
                                                    <a href="{{$productBase}}/{{str_replace('http://www.next.co.il/he/','',
                                                    str_replace('#','@',$nextData[$i]->productUrl))}}/{{0}}">
                                                        <span>{{$nextData[$i]->title}}</span>
                                                    </a>
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
                                        הבא
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        {{--</div>--}}
    </main>
    <script src="{{ URL::asset('assets/') }}"></script>
@stop