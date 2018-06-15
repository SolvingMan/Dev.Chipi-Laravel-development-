@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="container qa-container">
            <!--div class="row"-->
            <!--div class="col-md-12 "-->
            <div class="panel-group">
                <h2>@lang('general.you_have_been_removed_from_the_mailing_list')</h2>
            </div>
            <!--/iv-->
            <!--/div-->
        </div>
    </main>
    <script>
        $(document).ready(function(){
            setTimeout(function(){
                location.href = location.origin;
            },4000);
        });
    </script>
@stop
