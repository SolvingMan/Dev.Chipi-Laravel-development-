@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div id="fb-root"></div>

        <div class="columns container">
            <!-- Block  Breadcrumb-->

            <ol class="breadcrumb no-hide">
                <li><a href="/">@lang("general.auth_bread_1")</a></li>
                <li class="active">@lang("general.auth_bread_2")</li>
            </ol><!-- Block  Breadcrumb-->

            <h2 class="page-heading">
                <span class="page-heading-title2">{{$title}}</span>
            </h2>

            <div class="page-content">
                <div class="row">
                    <div class="col-sm-6">
                        <form action="/auth/register" method="post" class="cmxform cat-block" id="commentForm">
                            {{ csrf_field() }}
                            <div class="box-authentication">
                                <h3>@lang('general.create_an_account')</h3>
                                @if(isset($_GET["method"]))
                                    @if($_GET["method"]=="register")
                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li><?php echo $error ?></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                @endif


                                <div class="form-group">
                                    <label for="email_register">@lang('general.email_address')</label>
                                    <input type="email" class="form-control register-input" name="email"
                                           id="email_register" required>
                                </div>

                                <div class="form-group">
                                    <label for="name_register">@lang('general.name')</label>
                                    <input type="text" class="form-control register-input" name="name"
                                           id="name_register"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="password_register">@lang('general.password')</label>
                                    <input type="password" class="form-control register-input" name="password"
                                           id="password_register" required>
                                </div>

                                <div class="form-group">
                                    <div class="rules-agree-block">
                                        <div class="checkbox checkbox-primary">
                                            <label>
                                                <input type="checkbox"
                                                       title="rules"
                                                       name="check_rules"
                                                       id="check_rules"></label>
                                            <label>
                                                @lang("general.i_agree_with")
                                                <a href="#" id="rules-link" style="color:red" data-toggle="modal"
                                                   data-target="#modal-rules">
                                                    @lang("general.rules")
                                                </a>
                                            </label>
                                        </div>
                                    </div>
                                    {{--<div class="g-recaptcha"--}}
                                         {{--data-lang="iw"--}}
                                         {{--data-sitekey="6LccXSAUAAAAAB5jAbip9HdokwMiJTAayflSC5XJ"></div>--}}
                                    <button id="register_button" class="button">
                                        <i class="fa fa-user"></i>
                                        @lang('general.create_an_account')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="col-sm-6">
                        <form action="/auth/login" method="post" id="loginForm" class="cat-block">
                            {{ csrf_field() }}
                            <div class="box-authentication">
                                @if(old("error")!=null)
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{{old("error")}}</li>
                                        </ul>
                                    </div>
                                @endif
                                <h3>@lang('general.already_registered')</h3>
                                @if(isset($_GET["method"]))
                                    @if($_GET["method"]=="login")
                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                <div class="form-cotrol">
                                    <label for="email_login">@lang('general.email_address')</label>
                                    <input type="text" class="form-control" name="email" id="email_login">
                                </div>

                                <div class="form-cotrol">
                                    <label for="password_login">@lang('general.password')</label>
                                    <input type="password" class="form-control" name="password" id="password_login">
                                </div>


                                <div class="clearfix">
                                    <button class="login-button button col-xs-6">
                                        <i class="fa fa-lock"></i>
                                        @lang('general.sign_in')
                                    </button>
                                </div>
                                <div class="clearfix">
                                    <a href="/user/forgot_password_index" id="forgotPass"
                                       class="button text-center col-xs-6">
                                        @lang('general.forgot_pass')
                                    </a>
                                </div>

                                <div class="form-group">
                                    <div class="fb-login-button" data-max-rows="1" data-size="large"
                                         data-button-type="login_with"
                                         data-show-faces="false" data-auto-logout-link="false"
                                         onlogin="checkLoginState();"
                                         data-scope="email"></div>
                                </div>
                            </div>
                        </form>
                        <!-- Modal popup on click -->
                        <div class="modal fade" id="modal-rules" role="dialog" style="">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close"
                                                data-dismiss="modal">&times;
                                        </button>
                                        <h4 class="modal-title"> @lang('general.rules')</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>
                                                        <?= $rules ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                                data-dismiss="modal">@lang('general.okay')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{!! asset("assets/auth.js")!!}"></script>
    </main><!-- end MAIN -->
@stop
