<!doctype html>
<html class="cms-rtl">
<head>
    @include('includes.head')
</head>
<body class="index-opt-2 catalog-product-view catalog-view_op1 {{ App::getLocale()=="he"?"":"eng-lang" }}">
<div class="wrapper">
    @include('includes.headerGeneral')
    <?php
    \DB::table("errors")->insert(['date' => date("Y-m-d"), 'time' => date("H:i:s"), "url" => $_SERVER['REDIRECT_URL'],
        "user_id" => isset($_SESSION['user']->id) ? $_SESSION['user']->id : "", "referer" => isset($_SERVER['HTTP_REFERER']) ?
            $_SERVER['HTTP_REFERER'] : "",'browser'=>$_SERVER['HTTP_USER_AGENT'],"method"=>$_SERVER['REQUEST_METHOD']]);
    ?>
    <div class="site-main error-page-wrapper text-center container">
        <div class="error-page-content">
            <h1 class="errorpage-error">500 שְׁגִיאָה</h1>
            <h2 class="errorpage-error">שגיאת שרת פנימית</h2>
        </div>
    </div>
    <footer class="main-footer footer-opt-1">
        @include('includes.footer')
    </footer>
    <script>
        var delay = 2000;
        setTimeout(function () {
            window.location = location.origin;
        }, delay);
    </script>
</div>
</body>
</html>
