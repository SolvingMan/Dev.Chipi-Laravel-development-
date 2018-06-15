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
    @if (env('APP_ENV') == \Config::get('enums.env.LIVE'))
        <script type="text/javascript">
            (function (W, i, s, e, P, o, p) {
                W['WisePopsObject'] = P;
                W[P] = W[P] || function () {
                    (W[P].q = W[P].q || []).push(arguments)
                }, W[P].l = 1 * new Date();
                o = i.createElement(s),
                    p = i.getElementsByTagName(s)[0];
                o.async = 1;
                o.src = e;
                p.parentNode.insertBefore(o, p)
            })(window, document, 'script', '//loader.wisepops.com/get-loader.js?v=1&user_id=32628', 'wisepops');
        </script>
    @endif
</head>
<body class="index-opt-2 catalog-product-view catalog-view_op1 {{ App::getLocale()=="he"?"":"eng-lang" }}" style="background-color:#f6f6f6" >
@if(old('message')!="")
    @include("includes.successLoginModal")
@endif

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
<script src="//assets.pcrl.co/js/jstracker.min.js"></script>
</body>
</html>
