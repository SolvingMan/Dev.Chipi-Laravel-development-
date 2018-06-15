@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="container">

                <!-- Main Content -->

                    <div class="bleach contact-us-container">
                      <div class="contact-us-main">
                        <h1 class="text-center">
                            @lang('general.customer_service')
                        </h1>

                        @if(old('success')!=null)
                            <div class="alert alert-success" role="alert">
                                @lang("general.success_update")
                            </div>
                        @elseif(old('error')!=null)
                            <div class="alert alert-warning" role="alert">
                                {{old('error')}}
                            </div>
                        @endif
                    <div class="row">
                        <div class="col-md-4 col-md-push-4">

                            <form method="POST" action="/main/contactuspost" id="contactForm">
                                <div class="form-group text-center" >
                                    <label for="department">@lang('general.department')</label>
                                    <select id="department" name="department" class="form-control">
                                        <option value="-">@lang('general.select_department')</option>
                                        <option value="0">@lang('general.customer_service')</option>
                                        <option value="2">@lang('general.help_in_kenya')</option>
                                        <option value="4">@lang('general.feedback_on_the_site')</option>
                                    </select>
                                </div>

                                <div class="hidden-block hidden text-center">
                                    <div class="form-group">
                                        <input id="userID" name="userID" type="hidden"
                                               value="{{$_SESSION['user']->id}}">
                                    </div>
                                    @if(!$orderHistory->isEmpty())

                                        <div class="form-group order-number">
                                            <label for="ordernumber">@lang('general.order_number')</label>
                                            <select id="ordernumber" name="ordernumber" class="form-control">
                                                <option id="default-ordernumber" value="">@lang('general.select_order_number')</option>
                                                @foreach($orderHistory as $order)
                                                    <option value="{{$order->summaryId}}">{{$order->summaryId}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="message">@lang('general.message')</label>
                                        <textarea type="text" class="form-control"
                                                  name="message"></textarea>
                                    </div>
                                    <div class="g-recaptcha"
                                         data-lang="iw"
                                         data-sitekey="6LccXSAUAAAAAB5jAbip9HdokwMiJTAayflSC5XJ" style="margin-left:auto;margin-right:auto;width:304px;"></div>
                                    <div></div>
                                    <button type="submit" class="btn btn-default button-send-contact-us">@lang('general.send')</button>
                                </div>

                                <h1 class="hidden  @if($orderHistory->isEmpty())
                                        history-empty
@endif
                                        order-history-error">@lang('general.you_have_no_orders')</h1>

                            </form>
                        </div>
                       </div>
                    </div>
                </div>
                <!-- Modal popup on click -->
                <div class="modal fade" id="modal-contact-warning" role="dialog" style="">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal">&times;
                                </button>
                                <h4 class="modal-title">@lang('general.warning')</h4>
                            </div>
                            <div class="modal-body">
                                <p>
                                    @lang('general.there_open_invitation_which_you_can_enter')</p>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal popup on click -->
                <div class="modal fade" id="modal-contact-success" role="dialog" style="">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal">&times;
                                </button>
                                <h4 class="modal-title">@lang('general.success')</h4>
                            </div>
                            <div class="modal-body">
                                <p>@lang('general.the_message_was_successfully_sent')</p>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </main>
    <?= $jsData ?>
            {{--{{dd(old('jsDataSuccess'))}}--}}
    <? if (old('jsDataSuccess') != null) {
        echo old('jsDataSuccess');

    } ?>
    <script src="{{ URL::asset('assets/contactUs.js') }}"></script>
@stop