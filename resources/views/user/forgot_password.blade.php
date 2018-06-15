@extends('layouts.noSidebar')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="container">

            <?php

            if (old('found') != null) {
                $modalText = old('found');
            } else {
                $modalText = old('not_found');
            }
            ?>
            <script>
                $(document).ready(function () {
                    var show = "<?php echo isset($modalText)?>";
                    if (show) $('#modal-forgot').modal();
                })
            </script>
            <!-- Modal popup on click -->
            <div class="modal fade" id="modal-forgot" role="dialog" style="">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-center">
                                            @if(isset($modalText))
                                                {{$modalText}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn orange-perfect btn-default div-center"
                                        data-dismiss="modal">@lang('general.forgot_pass_modal_button_text')
                                </button>
                        </div>
                    </div>
                </div>
            </div>

            <h1 class="text-center">@lang('general.forgot_pass_title')</h1>
            <p class="text-center">@lang('general.forgot_pass_undertext')</p>
            <p class="text-center">@lang('general.forgot_pass_undertext_2')</p>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-4 col-md-push-4">
                    <form action="/user/forgot_password" id="forgotPassForm" method="post">
                        <div class="form-group center-block">
                            <label for="email" class="center">@lang('general.forgot_pass_label_text'):</label>
                            <input type="email" class="form-control center" id="email" name="email">
                            <br>
                            <div class="g-recaptcha"
                                 data-lang="iw"
                                 data-sitekey="6LccXSAUAAAAAB5jAbip9HdokwMiJTAayflSC5XJ"></div>
                        </div>
                        <button class="btn orange-perfect btn-default col-xs-12 col-md-6 col-md-push-3">@lang('general.forgot_pass_button_text')</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="{!! asset("assets/auth.js")!!}"></script>
@stop
