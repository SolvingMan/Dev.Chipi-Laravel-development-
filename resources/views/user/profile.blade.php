@extends('layouts.main')

@section('content')
    <!-- MAIN -->
    <main class="site-main profile-page-wrap">

        <div class="columns container">
            <br>
            {{--tabz--}}
            <div class="product-info-detailed notranslate">
                <!-- Nav tabs -->
                {{--content of tabs--}}
                <div class="tab-content bleach">
                    {{--PROFILE PAGE--}}
                    <div role="tabpanel" class="tab-pane active" id="profile" style="width: 100%;">
                        @if(old('status')!=null)
                            <div class="alert alert-success" role="alert">
                                @lang("general.success_update")
                            </div>
                        @endif
                        <h3 class="user-profile-title"> @lang('general.my_account')</h3>
                        <div class="page-content checkout-page">
                            <div class="box-border">
                                <form action="/../user/update/{{$_SESSION['user']->id}}" method="POST">
                                    <ul>
                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="first_name" class="required">@lang('general.name')</label>
                                                <input class="input form-control" name="first_name" id="first_name"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->name)){{$_SESSION['user']->name}}@endif">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="last_name" class="required">@lang('general.surname')</label>
                                                <input name="last_name" class="input form-control" id="last_name"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->surname)){{$_SESSION['user']->surname}}@endif">
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-sm-12">
                                                <label for="email" class="required">@lang('general.email')</label>
                                                <input class="input form-control" name="email" id="email"
                                                       type="text"
                                                       disabled
                                                       value="@if(isset($_SESSION['user']->email)){{$_SESSION['user']->email}}@endif">
                                            </div>

                                        </li>
                                        <li class="row">
                                            <div class="col-md-2 col-sm-6">
                                                <label for="city" class="required">@lang('general.city')</label>
                                                <input class="input form-control" name="city" id="city" type="text"
                                                       value="@if(isset($_SESSION['user']->city)){{$_SESSION['user']->city}}@endif">
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <label for="street" class="required">@lang('general.street')</label>
                                                <input class="input form-control" name="street" id="street"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->street)){{$_SESSION['user']->street}}@endif">
                                            </div>
                                            <div class="col-md-2 col-sm-6">
                                                <label for="building_number"
                                                       class="required">@lang('general.building_number')</label>
                                                <input class="input form-control" name="building_number"
                                                       id="building_number"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->building_number)){{$_SESSION['user']->building_number}}@endif">
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <label for="apartment_number"
                                                       class="required">@lang('general.apartment_number')</label>
                                                <input class="input form-control" name="apartment_number"
                                                       id="apartment_number"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->apartment_number)){{$_SESSION['user']->apartment_number}}@endif">
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <label for="num_enter">@lang('general.num_enter')</label>
                                                <input class="input form-control" name="num_enter"
                                                       id="num_enter"
                                                       type="text"
                                                       data-field-name="num_enter"
                                                       data-required="false"
                                                       value="@if(isset($_SESSION['user']->num_enter)){{$_SESSION['user']->num_enter}}@endif">
                                                <br>
                                            </div>
                                        </li>

                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="telephone"
                                                       class="required">@lang('general.telephone')</label>
                                                <input class="input form-control" name="telephone" id="telephone"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->telephone)){{$_SESSION['user']->telephone}}@endif">
                                            </div>
                                            <div class="col-sm-6">

                                                <label for="postal_code"
                                                       class="required">@lang('general.postal_code')</label>
                                                <input class="input form-control" name="postal_code" id="postal_code"
                                                       type="text"
                                                       value="@if(isset($_SESSION['user']->postal_code)){{$_SESSION['user']->postal_code}}@endif">
                                            </div>
                                        </li>
                                        <li>
                                            <button type="submit" name="submit"
                                                    class="button">@lang('general.update_account')
                                            </button>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main><!-- end MAIN -->

    {{--<script src="{{ URL::asset('assets/profile.js') }}"></script>--}}
@stop
