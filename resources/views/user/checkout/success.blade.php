@extends('layouts.noSidebar')
@section('content')
    <script>
        function inIframe() {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        }

        if (inIframe()) {
            window.top.location.href = window.location.href;
        }

        window.history.pushState("", "", location.pathname + '?usr_id=<?php echo $_GET['usr_id']?>');

    </script>



    <script>

        //Transaction level information is provided via an actionFieldObject.
        ga('ec:setAction', 'purchase', {
            'id': '<?php echo 12;?>',
            'revenue': '<?php echo $totalPaid; ?>'
        });

        ga('send', 'pageview');     // Send transaction data with initial pageview.
    </script>
    @if (env('APP_ENV') == \Config::get('enums.env.LIVE'))
        @if(count($_GET)>2)
            <!-- Google Code for Successful payment Conversion Page -->
            <script type="text/javascript">
                / <![CDATA[ /
                var google_conversion_id = 929435375;
                var google_conversion_language = "en";
                var google_conversion_format = "3";
                var google_conversion_color = "ffffff";
                var google_conversion_label = "02aeCNKzmWUQ752YuwM";
                var google_remarketing_only = false;
                / ]]> /
            </script>

        @endif
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
            <div style="display:inline;">
                <img height="1" width="1" style="border-style:none;" alt=""
                     src="//www.googleadservices.com/pagead/conversion/929435375/?label=02aeCNKzmWUQ752YuwM&amp;guid=ON&amp;script=0"/>
            </div>
        </noscript>

        <script type="text/javascript"
                src="//aff.cashback.co.il/track/script?total={{$totalPaid}}&desc={{"Success payment"}}&aid={{"18711"}}&currency={{"ILS"}}&oid={{$summaryId}}">
        </script>
        <noscript><img
                    src="//aff.cashback.co.il/track/pixel?total={{$totalPaid}}&desc={{"Success payment"}}&aid={{"18711"}}&currency={{"ILS"}}&oid={{$summaryId}}"
                    width="1" height="1"/>
        </noscript>
    @endif
    <main class="site-main">
        <div class="columns container">
            <div class="page-content checkout-page">
                <div class="box-border">
                    <h2 class="" style="color:black">
                        {{$_SESSION['user']->name}}, @lang('general.thank_you_for_purchase')
                    </h2>
                    <h2 class="" style="color:black">
                        @lang('general.success_1') {{$summaryId}} @lang('general.success_2')
                    </h2>
                </div>
            </div>
        @if(isset($similar_products))
            @if(count($similar_products))
                <!-- Block similar products of -->
                    <div class="container ">
                        <div class="block-deals-of-opt2">
                            <div class="block-title ">
                                <span class="title">@lang('general.similar_products')</span>
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
                                    @foreach($similar_products as $item)
                                        {{--@foreach($s_products as $item)--}}
                                        <div class="product-item product-item-opt-2">
                                            <div class="product-item-info">
                                                <div class="product-item-photo">
                                                    {{--@if($item->sitename == "ebay")--}}
                                                    <a class="product-item-img"
                                                       href="/ebay/product/{{ (explode("/", $item->viewItemURL)[4])}}/{{explode("?",explode("/", $item->viewItemURL)[5])[0] }}">
                                                        <img style="min-height: 250px;"
                                                             src='{{"https://galleryplus.ebayimg.com/ws/web/" . $item->itemId . "_1_1_1.jpg"}}'
                                                             alt="{{$item->title}}"
                                                             onerror=this.src='{{str_replace("http:","https:",$item->imageURL)}}'>
                                                    </a>
                                                    {{--@endif--}}
                                                    {{--@if($item->sitename == "aliexpress")--}}
                                                    {{--<a class="product-item-img" href="{{ $item->viewItemURL }}">--}}
                                                    {{--<img style="min-height: 250px;"--}}
                                                    {{--src='{{$item->imageURL}}'--}}
                                                    {{--alt="{{$item->title}}"--}}
                                                    {{--onerror=this.src='{{str_replace("http:","https:",$item->imageURL)}}'>--}}
                                                    {{--</a>--}}
                                                    {{--@endif--}}
                                                </div>
                                                <div class="product-item-detail">
                                                    <strong class="product-item-name">
                                                        {{--@if($item->sitename == "ebay")--}}
                                                        <a href="/ebay/product/{{ (explode("/", $item->viewItemURL)[4])}}/{{explode("?",explode("/", $item->viewItemURL)[5])[0] }}">{{$item->title}}</a>
                                                        {{--@endif--}}
                                                        {{--@if($item->sitename == "aliexpress")--}}
                                                        {{--<a href="{{ $item->viewItemURL }}">{{$item->title}}</a>--}}
                                                        {{--@endif--}}
                                                    </strong>
                                                    <div class="clearfix">
                                                        <div class="product-item-price">
                                                            {{--@if($item->sitename == "ebay")--}}
                                                            @if(isset($item->currentPrice->__value__))
                                                                <span class="price">&#8362;{{$item->currentPrice->__value__}}</span>
                                                            @else({{$item->buyItNowPrice->__value__}})
                                                                <span class="price">&#8362;{{$item->buyItNowPrice->__value__}}</span>
                                                            @endif
                                                            {{--@endif--}}
                                                            {{--@if($item->sitename == "aliexpress")--}}
                                                            {{--@if(isset($item->salePrice))--}}
                                                            {{--<es class="price">&#8362;{{$item->salePrice}}</es>--}}
                                                            {{--@else--}}
                                                            {{--<es class="price">&#8362;0</es>--}}
                                                            {{--@endif--}}
                                                            {{--@endif--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--@endforeach--}}
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div> <!-- Block similar products of -->
                @endif
        </div>
    </main>
    <script>
        var userId ='<?php echo $_GET["usr_id"]?>';
        var delay = 3000;
        setTimeout(function () {
            window.location = '/profile/' + userId + "/history";//?last=true";
        }, delay);
    </script>
@stop
