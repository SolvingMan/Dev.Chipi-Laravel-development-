<?php
if (env('APP_ENV') == \Config::get('enums.env.LIVE')) {
    if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

?>
        <!doctype html>
<html class="cms-rtl">
<head>
    @include('includes.head')
</head>
<body class="index-opt-2 catalog-product-view catalog-view_op1 {{ App::getLocale()=="he"?"":"eng-lang" }}" style="background-color:#f6f6f6" >
<div class="wrapper">

    @include('includes.headerGeneral')

    {{--<div id="main" class="row">--}}
    {{--<div class="col-xs-2"></div>--}}

    {{--<!-- main content -->--}}
    {{--<div id="content" class="col-xs-10">--}}
    <div style="min-height: 450px">
        @include('includes.popup')
        @yield('content')
    </div>
    {{--</div>--}}


    {{--</div>--}}

    <footer class="main-footer footer-opt-1">
        @include('includes.footer')
    </footer>

</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59354d98e8093222"></script>
</body>
</html>
