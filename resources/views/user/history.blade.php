@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">

        <div class="columns container">
            <br>
            {{--tabz--}}
            <h3 class="history-title"> @lang('general.my_orders'):</h3>
            <div class="product-info-detailed notranslate">
                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#history" role="tab"
                           data-toggle="tab">@lang('general.order_history')</a>
                    </li>

                    <li role="presentation" >
                        <a href="#order" role="tab"
                           data-toggle="tab">@lang('general.order_details')</a>
                    </li>
                </ul>

                {{--content of tabs--}}
                <div class="tab-content bleach">

                    {{-- ORDER HISTORY--}}
                    <div role="tabpanel" class="tab-pane active" id="history" style="width: 100%;">
                        <div class="checkout-page history-container">

                            @if(count($history)>0)
                                @include('user.fragments.historyTable')
                            @else
                                <h1>@lang('general.order_history_is_empty')@endlang</h1>
                            @endif

                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane " id="order">

                        @foreach($history as $keySum=>$summary)
                            <div class="order_detail_wrapper {{ $summaryId==null?"hidden":"" }} box-border"
                                 data-summary-id="{{$summary->summaryId}}">
                                @include('user.fragments.orderDetailTable')
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </main><!-- end MAIN -->

    <?= $jsData?>
    <script src="{{ URL::asset('assets/profile.js') }}"></script>
@endsection
