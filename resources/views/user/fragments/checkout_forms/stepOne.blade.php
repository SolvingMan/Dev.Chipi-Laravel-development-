<form action="/checkout" method="post" id="billingForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="page-content checkout-page">
        <h2 style="text-align: right">@lang('general.delivery_details')</h2>
        <div class="box-border" style="margin-bottom: 0">

            <ul class="no-bullets-list">
                <li class="row form-group">
                    <div class="col-sm-6">
                        <label for="name">@lang('general.name')</label>
                        <input name="name" class="input form-control"
                               data-field-name="name"
                               data-required="true"
                               id="name" type="text"
                               value="@if(isset($user->name)){{$user->name}}@endif">
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="surname">@lang('general.surname')</label>
                        <input name="surname" class="input form-control"
                               data-field-name="surname"
                               data-required="false"
                               id="surname" type="text"
                               value="@if(isset($user->surname)){{$user->surname}}@endif">
                        <br>
                    </div>
                    <br>
                </li>

                <li class="row">
                    <div class="col-sm-12">
                        <label for="email">@lang('general.email')</label>
                        <input class="input form-control" name="email"
                               data-field-name="email"
                               data-required="true"
                               id="email" type="email"
                               disabled
                               value="@if(isset($user->email)){{$user->email}}@endif">
                        <br>
                    </div>
                    <br>
                </li>

                <li class="row">
                    <div class="col-sm-2">
                        <label for="city">@lang('general.city')</label>
                        <input class="input form-control" name="city" id="city"
                               type="text"
                               data-field-name="city"
                               data-required="true"
                               value="@if(isset($user->city)){{$user->city}}@endif">
                        <br>
                    </div>
                    <div class="col-sm-2">
                        <label for="street">@lang('general.street')</label>
                        <input class="input form-control" name="street" id="street"
                               type="text"
                               data-field-name="street"
                               data-required="true"
                               value="@if(isset($user->street)){{$user->street}}@endif">
                        <br>
                    </div>
                    <div class="col-sm-2">
                        <label for="building_number">@lang('general.building_number')</label>
                        <input class="input form-control" name="building_number"
                               id="building_number"
                               type="text"
                               data-field-name="building_numer"
                               data-required="true"
                               value="@if(isset($user->building_number)){{$user->building_number}}@endif">
                        <br>
                    </div>
                    <div class="col-sm-3">
                        <label for="apartment_number">@lang('general.apartment_number')</label>
                        <input class="input form-control" name="apartment_number"
                               id="apartment_number"
                               type="text"
                               data-field-name="apartment_number"
                               data-required="false"
                               value="@if(isset($user->apartment_number)){{$user->apartment_number}}@endif">
                        <br>
                    </div>
                    <div class="col-sm-3">
                        <label for="num_enter">@lang('general.num_enter')</label>
                        <input class="input form-control" name="num_enter"
                               id="num_enter"
                               type="text"
                               data-field-name="num_enter"
                               data-required="false"
                               value="@if(isset($user->num_enter)){{$user->num_enter}}@endif">
                        <br>
                    </div>
                    <br>
                </li>

                <li class="row">
                    <div class="col-sm-6">
                        <label for="telephone">@lang('general.telephone')</label>
                        <input class="input form-control" name="telephone"
                               id="telephone"
                               type="text"
                               value="@if(isset($user->telephone)){{$user->telephone}}@endif">
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <label for="postal_code">@lang('general.postal_code')</label>
                        <input class="input form-control" name="postal_code"
                               id="postal_code"
                               type="text"
                               data-field-name="postal_code"
                               data-required="true"
                               required
                               value="@if(isset($user->postal_code)){{$user->postal_code}}@endif">
                        <br>
                    </div>
                </li>
                <li class="row">
                    <div class="col-sm-2 col-sm-push-6">
                        <button class="btn-sm zip-code-button">@lang('general.find_zip_code_click_here')</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</form>
