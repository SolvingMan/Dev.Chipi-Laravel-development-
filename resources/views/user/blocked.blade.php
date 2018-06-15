@extends('layouts.noSidebar')
@section('content')
    <main class="site-main">
        <div class="columns container">
            <h1 class="errorpage-error" style="margin-right: 50px">@lang('general.blocked_message')</h1>

            <a href="{{$previous_page}}"><h3>@lang('general.go_back')</h3></a>
        </div>

    </main>
@stop

