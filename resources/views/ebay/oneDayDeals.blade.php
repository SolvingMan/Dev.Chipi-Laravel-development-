@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="block-section-top block-section-top1 one-day-products" style="background-color:#f6f6f6">
            <div class="container">
                <div class="block-deals-of-opt1 block-deals-timer">
                    <span class="timer-title">@lang('general.deals_end_another')</span>
                    <div class="clock"></div>
                    <div id="second_date" data-second="{{date('m/d/Y',strtotime("+1 day"))}}"></div>
                </div>
                <div class="products  products-grid">
                    <ol class="product-items row" id="load-data">
                        @foreach($ebayData as $item)
                            <li class="col-sm-3 product-item">
                                <div class="product-item-opt-1">
                                    <div class="product-item-info">
                                        <div class="product-item-photo">
                                            <a href="{{$productBase}}/{{str_replace('/', "", $item->title)}}/{{$item->itemId}}"
                                               class="product-item-img">
                                                <img class="category-fixed-size-image"
                                                     src='{{"https://galleryplus.ebayimg.com/ws/web/" . $item->itemId . "_1_1_1.jpg"}}'
                                                     alt="{{$item->title}}"
                                                     onerror=this.src='{{$item->image}}'>
                                            </a>
                                            <span class="product-item-label label-sale-off">{{$item->discount}}
                                                %-<span>@lang('general.off')</span></span>
                                        </div>
                                        <div class="product-item-detail">
                                            <b class="product-item-name"
                                               style="min-height: 60px; height: 60px; overflow: hidden;">
                                                <a href="{{$productBase}}/{{str_replace('/', "", $item->title)}}/{{$item->itemId}}">
                                                    <span> {{$item->title}}</span>
                                                </a>
                                            </b>
                                            <div class="clearfix">
                                                <div class="product-item-price">
                                                    <span class="price">&#8362;{{ $item->price }}</span>
                                                    <span class="old-price">&#8362;{{$item->originalPrice}}</span>
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
            $(function () {
                FlipClock.Lang.Custom = {hours: 'שעות', minutes: 'דקות', seconds: 'שניות'};
                var opts = {
                    clockFace: 'HourCounter',
                    countdown: true,
                    language: 'Custom'
                };
                var second_day = new Date($('#second_date').attr('data-second')).getTime();
                var countdown = (second_day / 1000) - ((new Date().getTime()) / 1000);
                countdown = Math.max(1, countdown);
                $('.clock').FlipClock(countdown, opts);
            });

            var block = false;
            $(window).scroll(function () {
                var footerHeight = $("footer").height();

                if ($(window).scrollTop() >= $(document).height() - $(window).height() - footerHeight && !block) {
                    var id = $("#more").data('id');
                    //$("#more_message").html("Loading....");
                    block = true;
                    load(id);
                }
            });

            function load(id) {

                if (id !== undefined) {
                    $.ajax({
                        url: '{{ url("/ebay/MoreOneDayDeals") }}',
                        method: "POST",
                        data: {id: id, _token: "{{csrf_token()}}"},
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
        });
    </script>
@stop


