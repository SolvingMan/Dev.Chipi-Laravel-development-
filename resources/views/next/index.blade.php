@extends('layouts.main')
@section('content')
    <main class="site-main next-category-wrap">
        <div class="block-section-top block-section-top1" style="min-height: 450px; background-color:#f6f6f6;">
            <div class="container">
                <div class="box-section-top row">
                    <!--categori  -->
                    <div class="block-nav-categori">
                        <div class="block-title nav-menu-title">
                            <span>Categories</span>
                        </div>
                        <div class="block-content">
                            <div class="clearfix"><span data-action="close-cat"
                                                        class="close-cate"><span>Categories</span></span></div>
                            <ul class="ui-categori">
                                @foreach($categories as $category)
                                    @if(count($category['sub'])==0)
                                        @continue
                                    @endif
                                    <li class="parent">
                                        <a href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                                            <span class="icon">
                                                <i class="fa {{$category['catIcon']}}"></i>
                                            </span>
                                            {{ $category->catName }}
                                        </a>
                                        <span class="toggle-submenu"></span>
                                        <div class="submenu"
                                             style='background: white url({!! asset("images/icon/ebay/".$category['catPic']) !!}) no-repeat left center;
                                             @if($category['catPicWidth']!="")
                                             {{"background-size: ".$category['catPicWidth']." ".$category['catPicHeight'].";"}}
                                             @endif'>
                                            <ul class="categori-list clearfix">

                                                @for($i=0;$i<count($category['sub']);$i+=3)
                                                    <div class="row">
                                                        <?php $rows = 0; ?>
                                                        @for($j=$i;$j<$i+3;$j++)
                                                            {{--break if there is no more subcategories to show--}}
                                                            @if($j==count($category['sub']))
                                                                @break(1)
                                                            @endif
                                                            {{--continue if there is already 3 columns--}}
                                                            <div class="col-sm-3">
                                                                <strong class="title">
                                                                    <a href=""
                                                                       style="pointer-events: none">{{ $category['sub'][$j]->subcatName }} </a>
                                                                </strong>
                                                                <ul>
                                                                    <?php $k = 0;?>
                                                                    @foreach($category['sub'][$j]->sub as $key=>$subSub)
                                                                        @if($key<=14 && $k<5)
                                                                            <?php $k++;?>
                                                                            <li>
                                                                                <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->subSubcatId}}/1">
                                                                                    {{ $subSub->subSubcatName }}
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                    <li>
                                                                        <a class="submenu-all-category"
                                                                           href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                                                                            @lang('general.more_categories')
                                                                        </a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                            {{--@if(count($category['sub'][$j]->sub)>15)--}}
                                                                {{--<div class="col-sm-3">--}}
                                                                    {{--<strong class="title second-col"></strong>--}}
                                                                    {{--<ul>--}}
                                                                        {{--@foreach($category['sub'][$j]->sub as $key=>$subSub)--}}
                                                                            {{--@if($key>14)--}}
                                                                                {{--<li>--}}
                                                                                    {{--<a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->subSubcatId}}/1">--}}
                                                                                        {{--{{ $subSub->subSubcatName }}--}}
                                                                                    {{--</a>--}}
                                                                                {{--</li>--}}
                                                                            {{--@endif--}}
                                                                        {{--@endforeach--}}
                                                                    {{--</ul>--}}
                                                                {{--</div>--}}
                                                            {{--@endif--}}
                                                        @endfor
                                                    </div>
                                                @endfor
                                            </ul>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="parent">
                                    <a href="/next/categoryList" class="all-category">
    <span class="icon">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </span>
                                        @lang('general.all_categories')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- block slide top -->
                    <div class="block-slide-main slide-opt-1 col-xs-1">
                        <!-- slide -->
                        <div class="owl-carousel dotsData"
                             data-nav="true"
                             data-dots="true"
                             data-margin="0"
                             data-items='1'
                             data-autoplayTimeout="700"
                             data-autoplay="true"
                             data-loop="true">

                            <div class="item item2" style="background-image: url(images/media/index1/ch1New.png);"
                                 data-dot="1">
                            </div>
                            <a href="/aliexpress/oneDayDeals">
                                <div class="item item1" style="background-image: url(images/media/index1/alideals.png);"
                                     data-dot="2">
                                </div>
                            </a>
                            <a href="/ebay/oneDayDeals">
                                <div class="item item3"
                                     style="background-image: url(images/media/index1/ebayDeals.jpg);"
                                     data-dot="3">
                                </div>
                            </a>
                            <div class="item item3" style="background-image: url(images/media/index1/ch2New.png);"
                                 data-dot="4">

                            </div>

                            <div class="item item3" style="background-image: url(images/media/index1/ch3New.png);"
                                 data-dot="5">
                            </div>

                        </div> <!-- slide -->
                    </div><!-- block slide top -->

                    <div class="banner-slide latest-deal col-xs-6 block-service-mobile">
                        <div class="block-deals-of block-deals-of-opt1">
                            <div class="block-title">
                                <span class="icon"></span>
                                <div class="heading-title">@lang('general.product_today')</div>
                            </div>
                            <div class="block-content">
                                <div class="owl-carousel"
                                     data-nav="false"
                                     data-dots="false"
                                     data-margin="30"
                                     data-responsive='{
                                    "0":{"items":1},
                                    "480":{"items":1},
                                    "768":{"items":1},
                                    "992":{"items":1},
                                    "1200":{"items":1}
                                    }'>
                                    @foreach($recommendItems as $item)
                                        <div class="product-item  product-item-opt-1 ">
                                            <div class="deals-of-countdown">
                                                <div class="count-down-time"
                                                     data-countdown="{{date('Y-m-d',strtotime("+1 day"))}}"></div>
                                            </div>
                                            <div class="product-item-info">
                                                <div class="product-item-photo">
                                                    <div class="loader-wrap"></div>
                                                    <a class="product-item-img"
                                                       href="/ebay/product/{{$item->title}}/{{$item->itemNumber}}"><img
                                                                alt="product name" src="{{$item->pic}}"></a>
                                                </div>
                                                <div class="product-item-detail">
                                                    <strong class="product-item-name"
                                                            style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                                href="/ebay/product/{{$item->title}}/{{$item->itemNumber}}">{{$item->title}}</a></strong>
                                                    <div class="clearfix">
                                                        <div class="product-item-price">
                                                            <span class="price">&#8362;{{$item->price}}</span>
                                                            <span class="old-price">&#8362;{{$item->oldPrice}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- block  service-->
        <div class="container">
            @include('includes.fragments.services')
        </div>


      <!-- Block deals of -->
        <div class="container ">
            <div class="block-deals-of-opt2">
                <div class="block-title ">
                    <a class="title" href="ebay/oneDayDeals">@lang('general.latest_deals')</a>
                </div>
                <div class="block-content">
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
                        @if(!empty($latestDeals))
                            @foreach($latestDeals as $deal)
                                <div class="product-item product-item-opt-2">
                                    <div class="product-item-info">
                                        <div class="product-item-photo">
                                            <div class="loader-wrap"></div>
                                            <a class="product-item-img"
                                               href="{{$productBase}}/{{str_replace('/','-',$deal->title)}}/{{$deal->id}}">
                                                <img style="min-height: 250px;" class="category-fixed-size-image"
                                                     src='{{$deal->imageUrl}}'
                                                     alt="{{$deal->title}}">
                                            </a>
                                            <span class="product-item-label label-sale-off">{{$deal->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                        </div>
                                        <div class="product-item-detail">
                                            <strong class="product-item-name"
                                                    style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                        href="{{$productBase}}/{{str_replace('/','-',$deal->title)}}/{{$deal->id}}">
                                                    <span>{{$deal->title}}</span></a></strong>
                                            <div class="clearfix">
                                                <div class="product-item-price">
                                                    <span class="price">{{$deal->salePrice}}</span>
                                                    <span class="price">&#8362;</span>
                                                    <span class="old-price">{{$deal->originalPrice}}</span>
                                                    <span class="old-price">&#8362;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                       @endif
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
        <!-- Block deals of -->
        <div class="container ">
            <div class="block-deals-of-opt2">
                <div class="block-title ">
                    <span class="title">
                        <a href="{{$siteslug}}/categoryMap/4">
                            @lang('general.womens_fashion')
                        </a>
                    </span>
                </div>
                <div class="block-content">
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
                        @if(!empty($womenFashion))
                        @foreach($womenFashion as $fashion)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{str_replace('/','-',$fashion->title)}}/{{$fashion->id}}">
                                            <img style="min-height: 250px;"
                                                 class="category-fixed-size-image"
                                                 src='{{ $fashion->imageUrl }}'
                                                 alt="{{$fashion->title}}">
                                        </a>
                                    </div>
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                    href="{{$productBase}}/{{str_replace('/','-',$fashion->title)}}/{{$fashion->id}}"><span>
                                                    {{$fashion->title}}
                                                </span></a></strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">{{$fashion->price}}</span>
                                                <span class="price">&#8362;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Block deals of -->
        <!-- Block deals of -->
        <div class="container ">
            <div class="block-deals-of-opt2">
                <div class="block-title ">
                    <span class="title">
                        <a href="{{$siteslug}}/categoryMap/5">
                            @lang('general.mens_fashion')
                        </a>
                    </span>
                </div>
                <div class="block-content">
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
                        @if(!empty($menFashion))
                        @foreach($menFashion as $menFash)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{str_replace('/','-',$menFash->title)}}/{{$menFash->id}}">
                                            <img style="min-height: 250px;" class="category-fixed-size-image"
                                                 src='{{ $menFash->imageUrl }}'
                                                 alt="{{$menFash->title}}">
                                        </a>
                                    </div>
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                    href="{{$productBase}}/{{str_replace('/','-',$menFash->title)}}/{{$menFash->id}}"><span>{{$menFash->title}}
                                                </span></a></strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">{{$menFash->price}}</span>
                                                <span class="price">&#8362;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
        <!-- Block deals of -->
        <div class="container ">
            <div class="block-deals-of-opt2">
                <div class="block-title ">
                    <span class="title">
                        <a href="{{$siteslug}}/categoryMap/1">
                            @lang('general.infants_0_to_2_years_of_age')
                        </a>
                    </span>
                </div>
                <div class="block-content">
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
                        @if(!empty($babyClothes))
                        @foreach($babyClothes as $babyclothes)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{str_replace('/','-',$babyclothes->title)}}/{{$babyclothes->id}}">
                                            <img style="min-height: 250px;" class="category-fixed-size-image"
                                                 src='{{$babyclothes->imageUrl}}'
                                                 alt="{{$babyclothes->title}}">
                                        </a>
                                    </div>
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;">
                                            <a href="{{$productBase}}/{{str_replace('/','-',$babyclothes->title)}}/{{$babyclothes->id}}">
                                                <span>{{$babyclothes->title}}</span>
                                            </a>
                                        </strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">{{$babyclothes->price}}</span>
                                                <span class="price">&#8362;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
    </main>
@stop
