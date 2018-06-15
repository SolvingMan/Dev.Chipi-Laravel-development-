@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="block-section-top block-section-top1 one-day-products" style="background-color:#f6f6f6">
            <div class="loader-wrap"></div>
            <div id="menu-timer-time" data-timer="{{$timerTime}}"></div>
            <div class="container">
                <ul class="ali-one-day-tabs nav nav-tabs">
                    <li @if($currentTab==1)
                            class="active"

                        @endif
                    >
                        <a href="#" class="deals" data-toggle="tab" data-number="1" data-widget="{{$widgetArray[0]}}"
                           data-phase="{{$phaseArray[0]}}">
                            @if($currentTab==1)
                                @lang('general.selling_right_now')
                            @else
                                @lang('general.sale_was_over')
                            @endif
                            <span class="ali-tabs-time">10:00</span></a>
                    </li>
                    <li @if($currentTab==2)
                        class="active"
                            @endif
                    >
                        <a href="#" class="deals" data-toggle="tab" data-number="2" data-widget="{{$widgetArray[1]}}"
                           data-phase="{{$phaseArray[1]}}">
                            @if($currentTab==2)
                                @lang('general.selling_right_now')
                            @elseif($currentTab>2)
                                @lang('general.sale_was_over')
                            @else
                                @lang('general.new_deals_at')
                            @endif
                            <span class="ali-tabs-time">16:00</span></a>
                    </li>
                    <li @if($currentTab==3)
                        class="active"
                            @endif
                    >
                        <a href="#" class="deals" data-toggle="tab" data-number="3" data-widget="{{$widgetArray[2]}}"
                           data-phase="{{$phaseArray[2]}}">
                            @if($currentTab==3)
                                @lang('general.selling_right_now')
                            @elseif($currentTab>3)
                                @lang('general.sale_was_over')
                            @else
                                @lang('general.new_deals_at')
                            @endif
                            <span class="ali-tabs-time">22:00</span></a>
                    </li>
                    <li @if($currentTab==4)
                        class="active"
                            @endif
                    >
                        <a href="#" class="deals" data-toggle="tab" data-number="4" data-widget="{{$widgetArray[3]}}"
                           data-phase="{{$phaseArray[3]}}">
                            @if($currentTab==4)
                                @lang('general.selling_right_now')
                            @else
                                @lang('general.new_deals_at')
                            @endif
                            <span class="ali-tabs-time">04:00</span></a>
                    </li>
                </ul>

                <div class="block-deals-of-opt1 block-deals-timer">
                    <span class="timer-title">@lang('general.deals_end_another')</span>
                    <div class="clock"></div>
                    <div id="second_date" data-second="{{$endTime}}"></div>
                </div>

                <div class="products  products-grid">
                    <ol class="product-items row" id="load-data">
                        @foreach($aliData as $item)
                            <li class="col-sm-3 product-item">
                                <div class="product-item-opt-1">
                                    <div class="product-item-info">
                                        <div class="product-item-photo">
                                            <div class="loader-wrap"></div>
                                            <a href="{{$productBase}}/{{$item->productDetailUrl}}"
                                               class="product-item-img">
                                                <img class="category-fixed-size-image"
                                                     src='{{$item->productImage}}' alt="{{$item->productTitle}}">
                                            </a>
                                            @if(isset($item->discount))
                                                <span class="product-item-label label-sale-off">{{$item->discount}}%-<span>
                                                        @lang('general.off')</span></span>
                                            @endif
                                        </div>
                                        <div class="product-item-detail">
                                            <b class="product-item-name"
                                               style="min-height: 60px; height: 60px; overflow: hidden;">
                                                <a href="{{$productBase}}/{{$item->productDetailUrl}}">
                                                    <span> {{$item->productTitle}}</span>
                                                </a>
                                            </b>
                                            <div class="clearfix">
                                                <div class="product-item-price">
                                                    @if($item->maxPrice==$item->minPrice)
                                                        <span class="price">&#8362;{{ $item->maxPrice }}</span>
                                                    @else
                                                        <span class="price">{{ $item->minPrice." - ".$item->maxPrice }}</span>
                                                        <span class="price">&#8362;</span>
                                                    @endif
                                                    @if(isset($item->discount))
                                                        <span class="old-price">&#8362;{{$item->oriMaxPrice}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        <span class="col-md-12" style="text-align: center" id="more_message"></span>
                        <input id="more" style="display: none" type="hidden" data-id="30">
                    </ol>
                </div> <!-- List Products -->
            </div>
        </div>
    </main>
    <div class="load"></div>
    <script type="text/javascript">
        $(document).ready(function () {
            var widgetId = $(".ali-one-day-tabs .active a").data("widget");
            var phase = $(".ali-one-day-tabs .active a").data("phase");
            var currentSelectedTab = $(".ali-one-day-tabs .active a").data("number");
            //console.log(saleWidgetId);
            $(function () {
                FlipClock.Lang.Custom = {hours: 'שעות', minutes: 'דקות', seconds: 'שניות'};
                var opts = {
                    clockFace: 'HourCounter',
                    countdown: true,
                    language: 'Custom'
                };
                var second_day = $('#second_date').attr('data-second');
                var currentDate = ((new Date().getTime()) / 1000);
                var countdown = second_day - currentDate;
                countdown = Math.max(1, countdown);
                $('.clock').FlipClock(countdown, opts);
                var timeForTimer = $("#menu-timer-time").data("timer");
                $('.count-down-time').countdown(timeForTimer);
            });

            var block = false;
            $(window).scroll(function () {
                var footerHeight = $("footer").height() * 2;

                if ($(window).scrollTop() >= $(document).height() - $(window).height() - footerHeight && !block) {
                    block = true;
                    var id = $("#more").data('id');
                    $("#more_message").html("טוען....");
                    load(id);
                }
            });

            function load(id) {

                if (id !== undefined) {
                    $.ajax({
                        url: '{{ url("/aliexpress/MoreOneDayDeals") }}',
                        method: "POST",
                        data: {
                            id: id,
                            widgetId: widgetId,
                            phase: phase,
                            currentSelectedTab: currentSelectedTab,
                            _token: "{{csrf_token()}}"
                        },
                        dataType: "text",
                        success: function (data) {
                            $('#more').remove();
                            $("#more_message").remove();
                            $('#load-data').append(data);
                            block = false;
                        }
                    });
                } else {
                    $('#more_message').html("No Data");
                }
            }

            $('.deals').on('click', function (e) {
                $('.container').css("opacity", 0.5);
                $('.loader-wrap').addClass('loader-product').css("opacity", 1);
                widgetId = $(this).data("widget");
                phase = $(this).data("phase");
                currentSelectedTab = $(this).data("number");

                $.ajax({
                    url: '{{ url("/aliexpress/MoreOneDayDeals") }}',
                    method: "POST",
                    data: {
                        id: -12,
                        widgetId: widgetId,
                        phase: phase,
                        currentSelectedTab: currentSelectedTab,
                        _token: "{{csrf_token()}}"
                    },
                    dataType: "text",
                    success: function (data) {
                        $('#more').remove();
                        $("#more_message").remove();
                        $('#load-data').html("");
                        $('#load-data').append(data);
                        var textForTime = $("#more_message").data("text");
                        $(".timer-title").text(textForTime);
                        var endTime = $("#more_message").data("time");
                        var currentDate = ((new Date().getTime()) / 1000);
                        var countdown = endTime - currentDate;
                        countdown = Math.max(1, countdown);
                        var opts = {
                            clockFace: 'HourCounter',
                            countdown: true,
                            language: 'Custom'
                        };
                        $('.clock').FlipClock(countdown, opts);

                        $('.loader-wrap').removeClass('loader-product');
                        $('.container').css("opacity", 1);
                    }
                });
            });
        });
    </script>
@stop


