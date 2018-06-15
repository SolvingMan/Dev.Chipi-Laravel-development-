<?php
if (env('APP_ENV') == \Config::get('enums.env.LIVE')) {
    if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
}
?>
        <!DOCTYPE html>
<html class="cms-rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="robots" content="noindex,follow">

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="{!! asset('assets/checkout_data_files/bootstrap.min.css')!!}">
    <!-- Optional Bootstrap theme -->
    <link rel="stylesheet" href="{!! asset('assets/checkout_data_files/bootstrap-theme.min.css') !!}">

    <!-- Include SmartWizard CSS -->
    <link href="{!! asset('assets/checkout_data_files/smart_wizard.min.css')!!}" rel="stylesheet" type="text/css">


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>@lang('general.general_title')</title>

    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!-- Style CSS -->
<!--link rel="stylesheet" type="text/css" href="{!! asset('css/style.css')!!}"-->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/styleTem.min.css')!!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/myStyle.css')!!}">
    <link rel="stylesheet" href="{!! asset('assets/checkout_data_files/style.css')!!}">
    <script type="text/javascript" src="{!! asset('js/jquery.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/cardValidator.js') !!}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Optional SmartWizard theme -->
    <link href="{!! asset('assets/checkout_data_files/smart_wizard_theme_circles.min.css') !!}" rel="stylesheet"
          type="text/css">
    <link href="{!! asset('assets/checkout_data_files/smart_wizard_theme_arrows.min.css') !!}" rel="stylesheet"
          type="text/css">
    <link href="{!! asset('assets/checkout_data_files/smart_wizard_theme_dots.min.css') !!}" rel="stylesheet"
          type="text/css">


    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>

    <!-- sticky -->
    <script type="text/javascript" src="{!! asset('js/jquery.sticky.js') !!}"></script>

    <!-- OWL CAROUSEL Slider -->
    <script type="text/javascript" src="{!! asset('js/owl.carousel.min.js') !!}"></script>

    <!-- Countdown -->
    <script type="text/javascript" src="{!! asset('js/jquery.countdown.min.js') !!}"></script>

    <!--jquery Bxslider  -->
    <script type="text/javascript" src="{!! asset('js/jquery.bxslider.min.js') !!}"></script>

    <!-- actual -->
    <script type="text/javascript" src="{!! asset('js/jquery.actual.min.js') !!}"></script>

    <!-- Chosen jquery-->
    <script type="text/javascript" src="{!! asset('js/chosen.jquery.min.js') !!}"></script>

    <!-- elevatezoom -->
    <script type="text/javascript" src="{!! asset('js/jquery.elevateZoom.min.js') !!}"></script>

    <!-- fancybox -->
    <script src="{!! asset('js/fancybox/source/jquery.fancybox.pack.js') !!}"></script>
    <script src="{!! asset('js/fancybox/source/helpers/jquery.fancybox-media.js') !!}"></script>
    <script src="{!! asset('js/fancybox/source/helpers/jquery.fancybox-thumbs.js') !!}"></script>

    {{--put all php language strings to js variable i18n--}}
    <script>
        $(document).ready(function () {
            window.i18n = {};
            i18n.general = [];
            @foreach(Lang::get('general') as $key => $str)
                i18n.general["{{$key}}"] = "{{$str}}";
            @endforeach
        });
    </script>

    <script src="{!! asset("assets/auth.js")!!}"></script>

    <!-- Include SmartWizard JavaScript source -->
    <script type="text/javascript"
            src="{!! asset('assets/checkout_data_files/jquery.smartWizard.min.js')!!}"></script>

    <!-- arcticmodal -->
    <script src="{!! asset('js/arcticmodal/jquery.arcticmodal.js') !!}"></script>

    <!-- Main -->
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js'></script>
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js'></script>

    <script type="text/javascript" src="{!! asset('js/main.js') !!}"></script>
    {{--<script src="/js/lang.js"></script>--}}
    {{--@if (env('APP_ENV') == \Config::get('enums.env.LIVE'))--}}
    {{--<!-- analytice code -->--}}

        {{--<script>--}}
            {{--(function (i, s, o, g, r, a, m) {--}}
                {{--i['GoogleAnalyticsObject'] = r;--}}
                {{--i[r] = i[r] || function () {--}}
                    {{--(i[r].q = i[r].q || []).push(arguments)--}}
                {{--}, i[r].l = 1 * new Date();--}}
                {{--a = s.createElement(o),--}}
                    {{--m = s.getElementsByTagName(o)[0];--}}
                {{--a.async = 1;--}}
                {{--a.src = g;--}}
                {{--m.parentNode.insertBefore(a, m)--}}
            {{--})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');--}}

            {{--ga('create', 'UA-57155240-1', 'auto');--}}
            {{--ga("require", "ec");--}}
            {{--ga('send', 'pageview');--}}
        {{--</script>--}}
        {{--<script type="text/javascript">--}}
            {{--(function (W, i, s, e, P, o, p) {--}}
                {{--W['WisePopsObject'] = P;--}}
                {{--W[P] = W[P] || function () {--}}
                    {{--(W[P].q = W[P].q || []).push(arguments)--}}
                {{--}, W[P].l = 1 * new Date();--}}
                {{--o = i.createElement(s),--}}
                    {{--p = i.getElementsByTagName(s)[0];--}}
                {{--o.async = 1;--}}
                {{--o.src = e;--}}
                {{--p.parentNode.insertBefore(o, p)--}}
            {{--})(window, document, 'script', '//loader.wisepops.com/get-loader.js?v=1&user_id=32628', 'wisepops');--}}
        {{--</script>--}}
        {{--<!-- Begin Inspectlet Embed Code -->--}}
        {{--<script type="text/javascript" id="inspectletjs">--}}
            {{--window.__insp = window.__insp || [];--}}
            {{--__insp.push(['wid', 1340669591]);--}}
            {{--(function () {--}}
                {{--function ldinsp() {--}}
                    {{--if (typeof window.__inspld != "undefined") return;--}}
                    {{--window.__inspld = 1;--}}
                    {{--var insp = document.createElement('script');--}}
                    {{--insp.type = 'text/javascript';--}}
                    {{--insp.async = true;--}}
                    {{--insp.id = "inspsync";--}}
                    {{--insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js';--}}
                    {{--var x = document.getElementsByTagName('script')[0];--}}
                    {{--x.parentNode.insertBefore(insp, x);--}}
                {{--};--}}
                {{--setTimeout(ldinsp, 500);--}}
                {{--document.readyState != "complete" ? (window.attachEvent ? window.attachEvent('onload', ldinsp) : window.addEventListener('load', ldinsp, false)) : ldinsp();--}}
            {{--})();--}}
        {{--</script>--}}
        {{--<!-- End Inspectlet Embed Code -->--}}
        {{--<script src="//assets.pcrl.co/js/jstracker.min.js"></script>--}}
    {{--@endif--}}
</head>

<body class="index-opt-2 catalog-product-view catalog-view_op1 {{ App::getLocale()=="he"?"":"eng-lang" }}" >
<div>
    {{--@if (env('APP_ENV') == \Config::get('enums.env.LIVE'))--}}
        {{--<script type="text/javascript">--}}
            {{--/ <![CDATA[ /--}}
            {{--var google_conversion_id = 929435375;--}}
            {{--var google_conversion_language = "en";--}}
            {{--var google_conversion_format = "3";--}}
            {{--var google_conversion_color = "ffffff";--}}
            {{--var google_conversion_label = "yfoSCLOdkmUQ752YuwM";--}}
            {{--var google_remarketing_only = false;--}}
            {{--/ ]]> /--}}
        {{--</script>--}}
        {{--<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">--}}
        {{--</script>--}}
        {{--<noscript>--}}
            {{--<div style="display:inline;">--}}
                {{--<img height="1" width="1" style="border-style:none;" alt=""--}}
                     {{--src="//www.googleadservices.com/pagead/conversion/929435375/?label=yfoSCLOdkmUQ752YuwM&amp;guid=ON&amp;script=0"/>--}}
            {{--</div>--}}
        {{--</noscript>--}}
    {{--@endif--}}
    @if(old('message')!="")
        @include("includes.successLoginModal")
    @endif
    <div id="step-1" class="container checkout-page-wrap" style="position: relative">
        <br>
        <a href="/">
            <img class="checkout-page-logo" src="{!! asset('images/icon/index1/main_logo.png') !!}">
        </a>

        <h1>@lang('general.security_payment_page')</h1>

        <div class="page-content checkout-services">

            @include('includes.fragments.services')
        </div>

        <!-- SmartWizard html -->
        <div id="smartwizard" class="sw-main sw-theme-arrows">
            <ul class="nav nav-tabs step-anchor">
                <li class="active"><a href="#step-1">@lang('general.delivery_details')</a></li>
                <li><a href="#step-2">@lang('general.secure_payment')</a></li>
            </ul>

            <div class="sw-container tab-content" style="background-color: white">
                {{--step 1 page--}}
                <div id="step-1" class="step-content">
                    {{--<h2>@lang('general.delivery_details')</h2>--}}
                    @include("user.fragments.checkout_forms.stepOne")
                    {{--<h2>@lang('general.order_confirmation')</h2>--}}
                    <div style="height:20px;background-color: #f6f6f6"></div>
                    @include("user.fragments.checkout_forms.stepTwo")
                </div>

                {{--step 2 page --}}
                {{--<div id="step-2" class="step-content" style="display: none;">--}}
                {{--@include("user.fragments.checkout_forms.stepTwo")--}}
                {{--</div>--}}

                {{--step 3 page--}}
                <div id="step-2" class="step-content" style="display: none; padding: 0;">
                    @include("user.fragments.checkout_forms.stepThree")
                </div>
            </div>
        </div>
    </div>
</div>

{{--this page's script--}}
<script src="{{ URL::asset('js/cart.js') }}"></script>
<script src={{ asset('assets/checkout_data_files/checkout.js') }}></script>

</body>
</html>
