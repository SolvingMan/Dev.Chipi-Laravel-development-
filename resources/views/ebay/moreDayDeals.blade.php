    @foreach($ebayData as $item)
        <li class="col-sm-3 product-item">
            <div class="product-item-opt-1">
                <div class="product-item-info">
                    <div class="product-item-photo">
                        <a href="{{$productBase}}/{{str_replace('/', "", $item->title)}}/{{$item->itemId}}" class="product-item-img">
                            <img class="category-fixed-size-image"
                                 src='{{"https://galleryplus.ebayimg.com/ws/web/" . $item->itemId . "_1_1_1.jpg"}}'
                                 alt="{{$item->title}}"
                                 onerror=this.src='{{$item->picture}}'>
                        </a>
                        <span class="product-item-label label-sale-off">{{$item->discount}}%-<span>@lang('general.off')</span></span>
                    </div>
                    <div class="product-item-detail">
                        <b class="product-item-name" style="min-height: 60px; height: 60px; overflow: hidden;">
                            <a href="{{$productBase}}/{{str_replace('/', "", $item->title)}}/{{$item->itemId}}">
                                <span>{{$item->title}}</span>
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

<span class="col-md-12" id="more_message" style="text-align: center"></span>
@if(isset($id))
    <input id="more" style="display: none" type="hidden" data-id="{{$id}}">
@endif
