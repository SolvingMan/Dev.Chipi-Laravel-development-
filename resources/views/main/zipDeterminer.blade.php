@extends('layouts.main')
@section('content')

    <!-- MAIN -->
    <main class="site-main profile-page-wrap">
        <div class="columns container">
            <div class="product-info-detailed">
                <!-- Nav tabs -->
                <div class="tab-content bleach">
                    <div role="tabpanel" class="tab-pane active" id="zip-determiner" style="width: 100%;">
                        @if(old('status')!=null)
                            <div class="alert alert-success" role="alert">
                                @lang("general.success_update")
                            </div>
                        @endif
                        <h3 class="zip-determiner-title">@lang('general.find_country-specific_postal_code_targeting')</h3>
                        <div class="page-content checkout-page">
                            <div class="box-border">
                                <form action="" id="zip-determiner-form" method="POST">
                                    <ul class="zip-form">
                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="location" class="required">@lang('general.locality')</label>
                                                <input class="input form-control" name="location" id="location"
                                                       type="text" value="" required>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="street" class="required">@lang('general.street_required')</label>
                                                <input class="input form-control" name="street" id="street"
                                                       type="text" value="" required>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="number" class="required">@lang('general.number')</label>
                                                <input class="input form-control" name="number" id="number" type="text"
                                                       value="" required>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="entrance" class="required">@lang('general.entrance')</label>
                                                <input class="input form-control" name="entrance" id="entrance"
                                                       type="text" value="">
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-sm-6">
                                                <label for="poBox" class="required">@lang('general.po_box')</label>
                                                <input class="input form-control" name="poBox" id="poBox"
                                                       type="text" value="">
                                            </div>
                                        </li>
                                        <li class="panel-body">
                                            <button
                                                    class="button search-button">@lang('general.search)
                                            </button>
                                        </li>
                                        <li class="zip-code-block panel-body">
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

    <script src="{{ URL::asset('assets/zipDeterminer.js') }}"></script>
@stop