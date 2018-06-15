<!-- btn categori mobile -->
<span data-action="toggle-nav-cat" class="nav-toggle-menu nav-toggle-cat"><span>
        @if($siteslug=="aliexpress")
            @lang('general.now_you_on_aliexpress')
        @elseif($siteslug=="ebay")
            @lang('general.now_on_ebay')
        @elseif($siteslug=="amazon")
            @lang('general.now_on_amazon')
        @elseif($siteslug=="next")
            @lang('general.next_store')
        @elseif($siteslug=="asos")
            @lang('general.asos_shop')
        @endif
    </span></span>

<!-- btn menu mobile -->
<span data-action="toggle-nav" class="nav-toggle-menu"><span>@lang('general.menu')</span></span>

<!-- categori  -->
<div class="block-nav-categori  block-menu-open category-menu-mouseenter">

    <div class="block-title nav-menu-title">
        <span class="nav-categori-title">@lang('general.more_categories') </span>
        <span class="icon-bar"></span>
    </div>

    <div class="block-content">
        <div class="clearfix">
            <span data-action="close-cat" class="close-cate">
                <span>
                    @if($siteslug=="aliexpress")
                        @lang('general.now_you_on_aliexpress')
                    @elseif($siteslug=="ebay")
                        @lang('general.now_on_ebay')
                    @elseif($siteslug=="amazon")
                        @lang('general.now_on_amazon')
                    @elseif($siteslug=="next")
                        @lang('general.next_store')
                    @elseif($siteslug=="asos")
                        @lang('general.asos_shop')
                    @endif
                </span>
            </span>
        </div>
        <ul class="ui-categori">
            @if($siteslug=="aliexpress")
                @include('includes.fragments.aliexpressHeaderFragment')
            @elseif($siteslug=="ebay")
                @include('includes.fragments.ebayHeaderFragment')
            @elseif($siteslug=="amazon")
                @include('includes.fragments.ebayHeaderFragment')
            @elseif($siteslug=="next")
                @include('includes.fragments.nextHeaderFragment')
            @elseif($siteslug=="asos")
                @include('includes.fragments.asosHeaderFragment')
            @endif
        </ul>
    </div>
</div>