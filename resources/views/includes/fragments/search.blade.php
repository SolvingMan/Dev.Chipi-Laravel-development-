<div class="block-content" >
    @if(!isset($siteslug) || $siteslug == "ebay" )
        <form action="/ebay/search" method="get">
            <div class="form-search">
                <div class="box-group">
                    <input id="search-input" type="text" class="form-control" name="searchKeywords"
                           placeholder="@lang('general.searching_for_ebay')@endlang"
                           value="@if(isset($keyword)){{$keyword}}@endif">
                    <div class="ui-widget">
                    </div>
                    <button class="btn btn-search search" data-shop="ebay" style="width:50px" type="submit"><span>search</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif(!isset($siteslug) || $siteslug == "ebay/oneDayDeals")
        <form action="/ebay/search" method="get">
            <div class="form-search">
                <div class="box-group">
                    <input id="search-input" type="text" class="form-control" name="searchKeywords"
                           placeholder="@lang('general.searching_for_ebay')@endlang"
                           value="@if(isset($keyword)){{$keyword}}@endif">
                    <div class="ui-widget">
                    </div>
                    <button class="btn btn-search search" data-shop="ebay" style="width:50px" type="submit"><span>search</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif(!isset($siteslug) || $siteslug == "amazon")
        <form action="/amazon/search" method="get">
            <div class="form-search">
                <div class="box-group">
                    <input id="search-input" type="text" class="form-control" name="searchKeywords"
                           placeholder="@lang('general.searching_for_amazon')@endlang"
                           value="@if(isset($keyword)){{$keyword}}@endif">
                    <div class="ui-widget">
                    </div>
                    <button class="btn btn-search search" data-shop="amazon" style="width:50px" type="submit"><span>search</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif ($siteslug == "aliexpress")
        <form action="/aliexpress/search" method="get">
            <div class="form-search">
                <div class="box-group">
                    <input id="search-input" type="text" class="form-control" name="searchstring"
                           placeholder="@lang('general.searching_for_aliexpress')@endlang"
                           value="@if(isset($keyword)){{$keyword}}@endif">
                    <div class="ui-widget">
                    </div>
                    <button class="btn btn-search search" data-shop="aliexpress" style="width:50px" type="submit">
                        <span>search</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif ($siteslug == "next")
        <form action="/next/search" method="get">
            <div class="form-search">
                <div class="box-group">
                    <input id="search-input" type="text" class="form-control" name="searchKeywords"
                           placeholder="@lang('general.searching_for_next')@endlang"
                           value="@if(isset($keyword)){{$keyword}}@endif">
                    <div class="ui-widget">
                    </div>
                    <button class="btn btn-search search" data-shop="next" style="width:50px" type="submit">
                        <span>search</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif ($siteslug == "asos")
        <form action="/asos/search" method="get">
            <div class="form-search">
                <div class="box-group">
                    <input id="search-input" type="text" class="form-control" name="searchKeywords"
                           placeholder="@lang('general.searching_for_next')@endlang"
                           value="@if(isset($keyword)){{$keyword}}@endif">
                    <div class="ui-widget">
                    </div>
                    <button class="btn btn-search search" data-shop="asos" style="width:50px" type="submit">
                        <span>search</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>