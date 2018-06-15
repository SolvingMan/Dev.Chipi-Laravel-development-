@extends('layouts.main')
@section('content')
    <main class="site-main">
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
                                    <li class="parent index-parent">
                                        <a href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                                            <span class="icon">
                                                <i class="fa {{$category['catIcon']}}"></i>
                                            </span>
                                            @if(\Lang::getLocale()=='he')
                                                {{ $category->catName }}
                                            @elseif(\Lang::getLocale()=="en")
                                                {{ $category->catEnglish }}
                                            @endif
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
                                                                       style="pointer-events: none">
                                                                        @if(\Lang::getLocale()=='he')
                                                                            {{ $category['sub'][$j]->subcatName }}
                                                                        @elseif(\Lang::getLocale()=="en")
                                                                            {{ $category['sub'][$j]->subcatEnglish }}
                                                                        @elseif(\Lang::getLocale()=="es")
                                                                            {{ $category['sub'][$j]->subcatSpanish }}
                                                                        @endif
                                                                    </a>
                                                                </strong>
                                                                <ul>
                                                                    <?php $k = 0;?>
                                                                    @foreach($category['sub'][$j]->sub as $subSub)
                                                                        @if(++$k>5)
                                                                            <li>
                                                                                <a class="submenu-all-category"
                                                                                   href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                                                                                    @lang('general.more_categories')
                                                                                </a>
                                                                            </li>
                                                                            @break(1)
                                                                        @endif
                                                                        @if($subSub->type == 0)
                                                                            <li>
                                                                                <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->ebayId}}/1">
                                                                                    @if(\Lang::getLocale()=='he')
                                                                                        {{ $subSub->subSubcatName }}
                                                                                    @elseif(\Lang::getLocale()=="en")
                                                                                        {{ $subSub->subSubcatEnglish }}
                                                                                    @endif
                                                                                </a>
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <a href="/{{$siteslug}}/search/{{$subSub->search}}/1">
                                                                                    @if(\Lang::getLocale()=='he')
                                                                                        {{ $subSub->subSubcatName }}
                                                                                    @elseif(\Lang::getLocale()=="en")
                                                                                        {{ $subSub->subSubcatEnglish }}
                                                                                    @endif
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach

                                                                </ul>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                @endfor
                                            </ul>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="parent index-parent">
                                    <a href="/ebay/categoryList" class="all-category">
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

                    <div class="banner-slide latest-deal col-xs-1">
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
        <div class="container ">
            @include('includes.fragments.services')
        </div><!-- block  service-->

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
                        @foreach($latestDeals as $deal)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{$deal->title}}/{{$deal->itemId}}">
                                            <img style="min-height: 250px;" class="category-fixed-size-image"
                                                 src='{{"https://galleryplus.ebayimg.com/ws/web/" . $deal->itemId . "_1_1_1.jpg"}}'
                                                 alt="{{$deal->title}}"
                                                 onerror=this.src='{{str_replace("http:","https:",$deal->image)}}'>
                                        </a>
                                        <span class="product-item-label label-sale-off">{{$deal->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                    </div>
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                    href="{{$productBase}}/{{$deal->title}}/{{$deal->itemId}}"><span>{{$deal->title}}</span></a></strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">&#8362;{{$deal->price}}</span>
                                                <span class="old-price">&#8362;{{$deal->originalPrice}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
        <!-- Block deals of -->
        <div class="container ">
            <div class="block-deals-of-opt2">
                <div class="block-title ">
                    <span class="title">
                        <a href="{{$siteslug}}/categoryMap/5">
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
                        @foreach($womenFashion as $fashion)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{ (explode("/", $fashion->productUrl)[4])}}/{{explode("?",explode("/", $fashion->productUrl)[5])[0] }}">
                                            <img style="min-height: 250px;"
                                                 class="category-fixed-size-image"
                                                 src='{{"https://galleryplus.ebayimg.com/ws/web/" . $fashion->productId . "_1_1_1.jpg"}}'
                                                 alt="{{$fashion->productTitle}}"
                                                 onerror=this.src='{{str_replace("http:","https:",$fashion->imageUrl)}}'>
                                        </a>
                                    </div>
                                    @if(isset($fashion->discount))
                                        <span class="product-item-label label-sale-off">{{$fashion->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                    @endif
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                    href="{{$productBase}}/{{ (explode("/", $fashion->productUrl)[4])}}/{{explode("?",explode("/", $fashion->productUrl)[5])[0] }}"><span>{{$fashion->productTitle}}</span></a></strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">&#8362;{{$fashion->price}}</span>
                                                @if(isset($fashion->oldPrice))
                                                    <span class="old-price">&#8362;{{$fashion->oldPrice}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
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
                        @foreach($menFashion as $menFash)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{ (explode("/", $menFash->productUrl)[4])}}/{{explode("?",explode("/", $menFash->productUrl)[5])[0] }}">
                                            <img style="min-height: 250px;" class="category-fixed-size-image"
                                                 src='{{"https://galleryplus.ebayimg.com/ws/web/" . $menFash->productId . "_1_1_1.jpg"}}'
                                                 alt="{{$menFash->productTitle}}"
                                                 onerror=this.src='{{str_replace("http:","https:",$menFash->imageUrl)}}'>
                                        </a>
                                    </div>
                                    @if(isset($menFash->discount))
                                        <span class="product-item-label label-sale-off">{{$menFash->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                    @endif
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;"><a
                                                    href="{{$productBase}}/{{ (explode("/", $menFash->productUrl)[4])}}/{{explode("?",explode("/", $menFash->productUrl)[5])[0] }}"><span>{{$menFash->productTitle}}</span></a></strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">&#8362;{{$menFash->price}}</span>
                                                @if(isset($menFash->oldPrice))
                                                    <span class="old-price">&#8362;{{$menFash->oldPrice}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
        <!-- Block deals of -->
        <div class="container ">
            <div class="block-deals-of-opt2">
                <div class="block-title ">
                    <span class="title">
                        <a href="{{$siteslug}}/categoryMap/11">
                            @lang('general.electronics')
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
                        @foreach($electronic as $electr)
                            <div class="product-item product-item-opt-2">
                                <div class="product-item-info">
                                    <div class="product-item-photo">
                                        <div class="loader-wrap"></div>
                                        <a class="product-item-img"
                                           href="{{$productBase}}/{{ (explode("/", $electr->productUrl)[4])}}/{{explode("?",explode("/", $electr->productUrl)[5])[0] }}">
                                            <img style="min-height: 250px;" class="category-fixed-size-image"
                                                 src='{{"https://galleryplus.ebayimg.com/ws/web/" . $electr->productId . "_1_1_1.jpg"}}'
                                                 alt="{{$electr->productTitle}}"
                                                 onerror=this.src='{{str_replace("http:","https:",$electr->imageUrl)}}'>
                                        </a>
                                    </div>
                                    @if(isset($electr->discount))
                                        <span class="product-item-label label-sale-off">{{$electr->discount}}%-
                                             <span>@lang('general.off')</span></span>
                                    @endif
                                    <div class="product-item-detail">
                                        <strong class="product-item-name"
                                                style="min-height: 60px; height: 60px; overflow: hidden;">
                                            <a href="{{$productBase}}/{{ (explode("/", $electr->productUrl)[4])}}/{{explode("?",explode("/", $electr->productUrl)[5])[0] }}">
                                                <span>{{$electr->productTitle}}</span>
                                            </a>
                                        </strong>
                                        <div class="clearfix">
                                            <div class="product-item-price">
                                                <span class="price">&#8362;{{$electr->price}}</span>
                                                @if(isset($electr->oldPrice))
                                                    <span class="old-price">&#8362;{{$electr->oldPrice}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> <!-- Block deals of -->
    </main>
@stop
