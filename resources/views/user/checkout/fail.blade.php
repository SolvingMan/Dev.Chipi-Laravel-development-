@extends('layouts.noSidebar')
@section('content')
    <script>
        function inIframe() {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        }
        if (inIframe()) {
            window.top.location.href = window.location.href;
        }
    </script>
    <?php
    $userId = $_GET['usr_id'];
    $_SESSION['user'] = Db::table('users')->where('id', "=", $userId)->first();
    ?>
    <main class="site-main">
        <div class="columns container">

            <div class="page-content checkout-page">
                <div class="box-border">
                    <h2 class="" style="color:black">
                        {{$_SESSION['user']->name}}, @lang('general.fail_1')
                    </h2>
                </div>
            </div>
        </div>
    </main>
@stop
