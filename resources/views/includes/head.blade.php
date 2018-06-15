<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
{{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta name="description" content="{{isset($description)?$description:Lang::get('general.general_description')}}">

<title>{{isset($title)?$title:Lang::get('general.general_title')}}</title>

{{--<meta http-equiv="Cache-Control" content="no-cache" />--}}
{{--<meta http-equiv="Expires" content="Wed, 26 Feb 1999 08:21:57 GMT" />--}}
{{--<meta http-equiv="Pragma" content="no-cache" />--}}

<link rel="stylesheet" type="text/css" href="{!! asset('css/styleTem.min.css')!!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/myStyle.css')!!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/flipclock.css')!!}">

<script type="text/javascript" src="{!! asset('js/jquery.min.js') !!}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>

<!-- sticky -->
<script type="text/javascript" src="{!! asset('js/jquery.sticky.min.js') !!}"></script>

<!-- OWL CAROUSEL Slider -->
<script type="text/javascript" src="{!! asset('js/owl.carousel.min.js') !!}"></script>

<!-- Countdown -->
<script type="text/javascript" src="{!! asset('js/jquery.countdown.min.js') !!}"></script>

<!--jquery Bxslider  -->
{{--<script type="text/javascript" src="{!! asset('js/jquery.bxslider.min.js') !!}"></script>--}}

<!-- actual -->
<script type="text/javascript" src="{!! asset('js/jquery.actual.min.js') !!}"></script>

<!-- Chosen jquery-->
{{--<script type="text/javascript" src="{!! asset('js/chosen.jquery.min.js') !!}"></script>--}}

<!-- elevatezoom -->
<script type="text/javascript" src="{!! asset('js/jquery.elevateZoom.min.js') !!}"></script>

{{--<!-- fancybox -->--}}
{{--<script src="{!! asset('js/fancybox/source/jquery.fancybox.pack.js') !!}"></script>--}}
{{--<script src="{!! asset('js/fancybox/source/helpers/jquery.fancybox-media.min.js') !!}"></script>--}}
{{--<script src="{!! asset('js/fancybox/source/helpers/jquery.fancybox-thumbs.min.js') !!}"></script>--}}

<script src="{!! asset('assets/facebook.js') !!}"></script>
<!-- Main -->
<script type="text/javascript" src="{!! asset('js/main.js') !!}"></script>
@if(!isset($zipDeterminer))
    <script type="text/javascript" src="{!! asset('js/category-menu.js') !!}"></script>
@endif
<script type="text/javascript" src="{!! asset('js/flipclock.min.js') !!}"></script>
<script>
    $(document).ready(function () {
        window.i18n = {};
        i18n.general = [];
        @foreach(Lang::get('general') as $key => $str)
            i18n.general["{{$key}}"] = "{{$str}}";
        @endforeach
    });
</script>

<script src='https://www.google.com/recaptcha/api.js?hl=iw'></script>

<script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js'></script>
<script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js'></script>
@if (env('APP_ENV') == \Config::get('enums.env.LIVE'))
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-57155240-1', 'auto');
        ga("require", "ec");
        ga('send', 'pageview');

    </script>
    <script data-obct type="text/javascript">
        /** DO NOT MODIFY THIS CODE**/
        !function (_window, _document) {
            var OB_ADV_ID = '00ad7ea5b7ef423f8dac456fb32c382212';
            if (_window.obApi) {
                return;
            }
            var api = window.obApi = function () {
                api.dispatch ? api.dispatch.apply(api, arguments) : api.queue.push(arguments);
            };
            api.version = '1.0';
            api.loaded = true;
            api.marketerId = OB_ADV_ID;
            api.queue = [];
            var tag = document.createElement('script');
            tag.async = true;
            tag.src = '//amplify.outbrain.com/cp/obtp.js';
            tag.type = 'text/javascript';
            var script = _document.getElementsByTagName('script')[0];
            script.parentNode.insertBefore(tag, script);
        }(window, document);
        obApi('track', 'PAGE_VIEW');
    </script>
    <script type="text/javascript">
        (function () {
            var elem = document.createElement('script');
            elem.src = (document.location.protocol == "https:" ?
                "https://" : "http://") +
                "aff.cashback.co.il/track/cbtrack.v1.min.js";
            elem.async = true;
            elem.type = "text/javascript";
            var scpt = document.getElementsByTagName('script')[0];
            scpt.parentNode.insertBefore(elem, scpt);
        })();
    </script>
    <script src="//assets.pcrl.co/js/jstracker.min.js"></script>
@endif

