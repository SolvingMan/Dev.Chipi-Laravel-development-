@foreach($summary->products as $key=>$product)
    <div class="cart-item">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="cart-item-img">
                    <a href="/{{$product->site}}/product/{{$product->orderProductTitle}}/{{$product->orderProductId}}">
                        @if($product->site == "next")
                            <img src="{{ $product->orderProductPic}}" alt="Product">
                        @elseif($product->site == "aliexpress")
                            <img src="{{ str_replace("http:","https:",str_replace(' ','-',$product->orderProductPic))}}"
                                 alt="Product">
                        @else
                            <img src="{{ str_replace("http:","https:",$product->orderProductPic)}}" alt="Product">
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-6 col-xs-9">
                <div class="cart-item-description">
                    <p class="cart-prod-name">
                        @if($product->site == "next")
                            <a href="/{{$product->site}}/product/{{str_replace('http://www.next.co.il/he/','',
                                                    str_replace('#','@',$product->urlForNext))}}/{{0}}">
                                @elseif($product->site == "next")
                                    <a href="/{{$product->site}}/product/{{$product->orderProductTitle}}/{{$product->orderProductId}}">
                                        @else
                                            <a href="/{{$product->site}}/product/history/{{$product->orderProductId}}">
                                                @endif
                                                {{ $product->orderProductTitle }}
                                                @if($product->orderProductOptions != null &&$product->orderProductOptions != "null")
                                                    <?php $variations = json_decode($product->orderProductOptions)?>
                                                    <ul>
                                                        @foreach($variations as $variationName=>$variationValue)
                                                            @if($variationName != "numberVariation")
                                                                <li>{{$variationName.' : '.$variationValue}}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </a>
                    </p>

                    @if(isset($product->selectedSku))
                        @foreach($product->selectedSku as $keySku=>$sku)
                            @if($keySku!="numberVariation")
                                <small class="cart_ref">{{ $keySku }} : {{ $sku }}</small>
                                <br>
                            @endif
                        @endforeach
                    @endif

                    {{--@if(isset($product->selectedSku) && $product->selectedSku!="null" && $product->selectedSku!=null)
                        @foreach($product->selectedSku as $keySku=>$sku)
                            @if($keySku!="numberVariation")
                                <span class="cart-prod-character">{{ $keySku }} : {{ $sku }}</span>
                            @endif
                        @endforeach
                    @endif--}}

                </div>
            </div>
            <div class="col-md-2 col-sm-1 col-xs-12">
                <?php
                switch ($product->site):
                case "aliexpress":
                ?>
                <img src="{!! asset('images/icon/index1/ali_logo.png') !!}" class="cart-item-shop">
                <?php break; ?>

                <?php case "ebay": ?>
                <img src="{!! asset('images/icon/index1/ebay_logo.png') !!}" class="cart-item-shop">
                <?php break; ?>

                <?php case "amazon": ?>
                <img src="{!! asset('images/icon/index1/amazon_logo.png') !!}" class="cart-item-shop">
                <?php break; ?>

                <?php case "next": ?>
                <img src="{!! asset('images/icon/index1/next-logo.png') !!}" class="cart-item-shop cart-shop-next">
                <?php break; ?>

                <?php default:
                    break; ?>
                <?php endswitch; ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                <ul class="cart-item-buy">
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.unit_price')</span>
                        <span class="cart-price" style="float:left;">
                            <span>{{ $product->orderProductPrice }}</span>
                                            <i class="fa fa-ils"></i>
                        </span>
                    </li>
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.quantity')</span>
                        <span class="cart-price" style="float:left;">
                             <span>{{$product->orderProductQuantity}}</span>
                        </span>
                    </li>
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.shipping_cost')</span>
                        <span class="cart-price" style="float:left;">
                            <span>{{ $product->orderShippingCost }}</span>
                            <i class="fa fa-ils"></i>
                        </span>
                    </li>
                    <li class="clearfix">
                        <span class="cart-buy-title" style="float:right;">@lang('general.total')</span>
                        <span class="cart-price" style="float:left;">
                            <span class="total-product-price">
                                {{floatval($product->orderProductPrice) * floatval($product->orderProductQuantity)+ $product->orderShippingCost}}
                            </span>
                            <i class="fa fa-ils"></i>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-6">
                <span class="cart-item-message-title">
                    @lang('general.order_delivery_number'):
                </span>
                <span class="cart-item-message">
                    @if($product->orderDeliveryNumber !== null)
                        {{$product->orderDeliveryNumber}}
                    @else
                        @lang('general.seller_did_not_update_tracking_number')
                    @endif
                </span>
                <button data-toggle="modal" @if($product->isSendReport) disabled @endif
                data-target="#modal-report{{$keySum.'-'.$key}}" type="button" class="button"
                        style="@if($product->isSendReport)
                                opacity:0.5;">@lang('general.you_reported')
                    @else">
                    @lang('general.report')
                    @endif
                </button>
            </div>
        </div>
    </div>
@endforeach











<!--<table class="table table-bordered cart_summary">
    <thead>
    <tr>

        <th>@lang('general.order_delivery_number')</th>
        @if(!isset($page))
    <th><i class="glyphicon glyphicon-trash"></i></th>
@endif
        </tr>
        </thead>


        <tbody>
@foreach($summary->products as $key=>$product)
    <tr>
        <td class="cart_product">
            <a href="/{{$product->site}}/product/{{$product->orderProductTitle}}/{{$product->orderProductId}}">
                    <img src="{{ str_replace("http:","https:",$product->orderProductPic)}}"
                         alt="Product">
                </a>
            </td>
            <td class="cart_description">
                <p class="product-name">
                    <a href="/{{$product->site}}/product/{{$product->orderProductTitle}}/{{$product->orderProductId}}">
                        {{ $product->orderProductTitle }}
    @if($product->orderProductOptions != null &&$product->orderProductOptions != "null")
        <?php $variations = json_decode($product->orderProductOptions)?>
                <ul>
@foreach($variations as $variationName=>$variationValue)
            @if($variationName != "numberVariation")
                <li>{{$variationName.' : '.$variationValue}}</li>
                                    @endif
        @endforeach
                </ul>
@endif
            </a>
        </p>
@if(isset($product->selectedSku))
        @foreach($product->selectedSku as $keySku=>$sku)
            @if($keySku!="numberVariation")
                <small class="cart_ref">{{ $keySku }} : {{ $sku }}</small>
                            <br>
                        @endif
        @endforeach
    @endif
            </td>
             <td>
                <?php
    switch ($product->site):
    case "aliexpress":
    ?>
            <img src="{!! asset('images/icon/index1/ali_logo.png') !!}">
                <?php break; ?>

    <?php case "ebay": ?>
            <img src="{!! asset('images/icon/index1/ebay_logo.png') !!}">
                <?php break; ?>

    <?php case "amazon": ?>
            <img src="{!! asset('images/icon/index1/amazon_logo.png') !!}">
                <?php break; ?>

    <?php default:
        break; ?>
            <?php endswitch; ?>
            </td>
            <td class="cart-price">
                <span>{{ $product->orderProductPrice }}</span>
                <i class="fa fa-ils"></i>
            </td>
            <td class="qty">
                <p>{{$product->orderProductQuantity}}</p>
            </td>
            <td class="cart-price">
                <span>{{ $product->orderShippingCost }}</span>
                <i class="fa fa-ils"></i>
            </td>
            <td class="cart-price">
                <span class="total-product-price">
                    {{floatval($product->orderProductPrice) * floatval($product->orderProductQuantity)+ $product->orderShippingCost}}
            </span>
            <i class="fa fa-ils"></i>
        </td>




        <td>
@if($product->orderDeliveryNumber !== null)
        {{$product->orderDeliveryNumber}}
    @else
        <span>המוכר לא עדכן מספר מעקב</span>
@endif
            </td>
            <td class="cart_product">
                <button data-toggle="modal" @if($product->isSendReport) disabled @endif
            data-target="#modal-report{{$keySum.'-'.$key}}" type="button" class="button"
                        style="width: 100px; font-size: 18px; @if($product->isSendReport)
        opacity:0.5;">דיווחת
@else
        ">
        דווח
@endif
            </button>
        </td>
    </tr>

@endforeach

        </tbody>
    </table> -->




@foreach($summary->products as $key=>$product)
    <!-- Modal popup on click -->
    <div class="modal fade" id="modal-report{{$keySum.'-'.$key}}" role="dialog" style="">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">@lang('general.product_reporting')</h4>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div class="row">
                            <div class="col-md-4 col-md-push-3">
                                <?php
                                $date = str_replace('/', '-', $summary->summaryEsitmatedDate);
                                ?>
                                @if(strtotime(Date("Y-m-d"))>=strtotime($date)
                                && strtotime(Date("Y-m-d"))<=strtotime("+8 day", strtotime($date)))
                                    <form method="POST" enctype="multipart/form-data" action="/user/reportitem"
                                          id="reportForm{{$keySum.'-'.$key}}">
                                        <div class="form-group">
                                            <input name="orderID" type="hidden"
                                                   value="{{$product->orderId}}">
                                        </div>
                                        <div class="form-group">
                                            <input name="userID" type="hidden"
                                                   value="{{$product->orderUserId}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="orderSummaryID">@lang('general.order_number')</label>
                                            <input class="form-control" id="orderSummaryID" name="orderSummaryID"
                                                   type="text" readonly="readonly"
                                                   value="{{$product->orderSummaryId}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="productID">@lang('general.product_code')</label>
                                            <input class="form-control" id="productID" name="productID" type="text"
                                                   readonly="readonly"
                                                   value="{{$product->orderProductId}}">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('general.product_did_not_arrive')</label>
                                            <input class="reason" name="reason" id="itemNotReceived" type="radio"
                                                   value="1">
                                            <label>@lang('general.the_product_came_damaged')</label>
                                            <input class="reason" name="reason" id="damageItem" type="radio"
                                                   value="2">
                                        </div>
                                        <div class="form-group photo-block hidden">
                                            <label for="photo">@lang('general.image')</label>
                                            <input disabled="disabled" class="photo" type="file" name="photo"
                                                   multiple accept="image/*">
                                        </div>
                                        <div class="form-group">
                                            <label for="message">@lang('general.notice_please_specify_much_possible')</label>
                                            <textarea class="form-control" class="message"
                                                      name="message"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-default button-send-contact-us">@lang('general.send_report')</button>
                                    </form>
                                @elseif(strtotime($date)>strtotime(Date('Y-m-d')))
                                    <p>@lang('general.you_can_not_open')</p>
                                @else
                                    <p>@lang('general.you_can_report_up_to_7_days')</p>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
                {{--<div class="modal-footer">--}}
                {{--<button type="button" class="btn btn-default"--}}
                {{--data-dismiss="modal">בסדר--}}
                {{--</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endforeach