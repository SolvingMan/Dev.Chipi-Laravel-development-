@extends('layouts.noSidebar')
@section('content')
    <!-- MAIN -->
    <main class="site-main">

        <div class="columns container">
            <!-- Block  Breadcrumb-->

            <ol class="breadcrumb no-hide">
                <li><a href="#">Home </a></li>
                <li class="active"> Checkout</li>
            </ol><!-- Block  Breadcrumb-->

            <h2 class="page-heading">
                <span class="page-heading-title2"> Checkout </span>
            </h2>

            <form action="/checkout" method="post" id="checkout_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="page-content checkout-page">
                    <h3 class="checkout-sep">Billing Infomations</h3>
                    @if(isset($errors))
                        <p>
                            @foreach($errors as $errorField)
                                field {{ $errorField }} is required<br>
                            @endforeach
                        </p>
                    @endif
                    <div class="box-border">
                        <ul>
                            <li class="row">
                                <div class="col-sm-6">
                                    <label for="first_name" class="required">First Name</label>
                                    <input class="input form-control" name="name" id="name" type="text"
                                           value="@if(isset($_SESSION['user']->name)){{$_SESSION['user']->name}}@endif">
                                </div>
                                <div class="col-sm-6">
                                    <label for="last_name" class="required">Last Name</label>
                                    <input name="surname" class="input form-control" id="surname" type="text"
                                           value="@if(isset($_SESSION['user']->surname)){{$_SESSION['user']->surname}}@endif">
                                </div>
                            </li>

                            <li class="row">
                                <div class="col-sm-12">
                                    <label for="email" class="required">Email Address</label>
                                    <input class="input form-control" name="email" id="email" type="text"
                                           value="@if(isset($_SESSION['user']->email)){{$_SESSION['user']->email}}@endif">
                                </div>
                            </li>

                            <li class="row">
                                <div class="col-sm-3">
                                    <label for="city" class="required">City</label>
                                    <input class="input form-control" name="city" id="city" type="text"
                                           value="@if(isset($_SESSION['user']->city)){{$_SESSION['user']->city}}@endif">
                                </div>
                                <div class="col-xs-3">
                                    <label for="street" class="required">Street</label>
                                    <input class="input form-control" name="street" id="street" type="text"
                                           value="@if(isset($_SESSION['user']->street)){{$_SESSION['user']->street}}@endif">
                                </div>
                                <div class="col-xs-3">
                                    <label for="building_number" class="required">Building Number</label>
                                    <input class="input form-control" name="building_number" id="building_number"
                                           type="text"
                                           value="@if(isset($_SESSION['user']->building_number)){{$_SESSION['user']->building_number}}@endif">
                                </div>
                                <div class="col-xs-3">
                                    <label for="apartment_number" class="required">Apartment Number</label>
                                    <input class="input form-control" name="apartment_number" id="apartment_number"
                                           type="text"
                                           value="@if(isset($_SESSION['user']->apartment_number)){{$_SESSION['user']->apartment_number}}@endif">
                                </div>
                            </li>


                            <li class="row">
                                <div class="col-sm-6">
                                    <label for="telephone" class="required">Telephone</label>
                                    <input class="input form-control" name="telephone" id="telephone" type="text"
                                           value="@if(isset($_SESSION['user']->phone)){{$_SESSION['user']->phone}}@endif">
                                </div>
                                <div class="col-sm-6">

                                    <label for="postal_code" class="required">Zip/Postal Code</label>
                                    <input class="input form-control" name="postal_code" id="postal_code" type="text"
                                           value="@if(isset($_SESSION['user']->zip_code)){{$_SESSION['user']->zip_code}}@endif">
                                </div>

                            </li>
                            <li>
                                <button type="submit" class="button">Continue</button>
                            </li>
                        </ul>
                    </div>

                    <h3 class="checkout-sep">Order Review</h3>
                    {{--<div class="page-content checkout-page">--}}
                    @include('user.fragments.productsTable')
                    {{--</div>--}}
                </div>
            </form>

        </div>

        <script src="{!! asset("assets/auth.js")!!}"></script>

    </main><!-- end MAIN -->
@endsection
