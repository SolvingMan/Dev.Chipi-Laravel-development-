@foreach($aliData as $item)
    <li class="col-sm-3 product-item">
        <div class="product-item-opt-1">
            <div class="product-item-info">
                <div class="product-item-photo">
                    <div class="loader-wrap"></div>
                    <a href="{{$productBase}}/{{$item->productDetailUrl}}" class="product-item-img">
                        <img class="category-fixed-size-image"
                             src='{{ $item->productImage }}'
                             alt="{{$item->productTitle}}">
                    </a>
                    @if(isset($item->discount))

                        <span class="product-item-label label-sale-off">{{$item->discount}}%-<span>@lang('general.off')</span></span>
                    @endif
                </div>
                <div class="product-item-detail">
                    <b class="product-item-name" style="min-height: 60px; height: 60px; overflow: hidden;">
                        <a href="{{$productBase}}/{{$item->productDetailUrl}}">
                            <span>{{$item->productTitle}}</span>
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

<span class="col-md-12" id="more_message"
      @if($currentSelectedTab <= $currentTab)
      data-time="<?php
              if(isset($aliData[0]->endTime)){
                  $aliData[0]->endTime;
              }
      ?>"
      data-text="הדילים מסתיימים בעוד"
      @else
      data-time="<?php
      if(isset($aliData[0]->endTime)){
          $aliData[0]->startTime;
      }
      ?>"
      data-text="הדילים מתחילים בעוד"
      @endif
      style="text-align: center"></span>
@if(isset($id))
    <input id="more" style="display: none" type="hidden" data-id="{{$id}}">
@endif
