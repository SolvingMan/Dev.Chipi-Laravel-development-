<!-- FOOTER -->

<!-- Modal popup on click -->
@if(isset($_SESSION['user']))
    <span id="check_user" style="display: none">1</span>
@else
    <span id="check_user" style="display: none">0</span>
@endif
<div id="newsletter-modal" class="modal fade" role="dialog" style="margin-top: 40px">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h3 style="color: #0b0b0b" id="newsletter-text">@lang('general.signed_in_popup_message')</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">@lang("general.back_to_shopping")
                </button>
            </div>
        </div>

    </div>
</div>
@if(isset($_COOKIE['user_sigin']))
    <div id="message" class="modal fade" role="dialog" style="margin-top: 40px">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    @if($_COOKIE['user_sigin'] == '1')
                        <h3 style="color: #000000">@lang('general.successfully_signed_up_chipi')</h3>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endif

{{--modal end--}}
<div id="newsletter-modal_signin" class="modal fade" role="dialog" style="margin-top: 40px">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img class="modal-logo" src="{!! asset('images/icon/index1/main_logo.png') !!}" alt="logo">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <h3 style="color: #000;">@lang('general.join_chipi_discount_website')</h3>
                <img src="{{asset('images/media/popup_family.png')}}">
            </div>
            <div class="modal-footer">
                <div class="row">
                    <form class="form-inline" id="newsletter-sigin_form">
                        <div class="form-group col-md-9">
                            <input id="newsletter-email" class="newsletter-input form-control col-md-9"
                                   placeholder="אימייל" type="email" name="email"
                                   style="height: 39px; width: 100%; border: 1px solid #ccc">
                        </div>
                        <input class="btn btn-default" type="submit" value="הרשם" id="newsletter-sigin"
                               style="background-color: #f78031; color: #fff;">
                        <strong id="msg" style="display: none; margin-right: 20px;"></strong>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
{{--modal end--}}
<div class="container">
    <div class="footer-column">

        <div class="row">
            <div class="col-md-3 col-lg-3 col-xs-6 col-sm-12">
                <strong class="logo-footer">
                    <a href=""><img src="/images/icon/index1/main_logo.png" alt="logo"></a>
                </strong>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-4 col-xs-6">
                <div class="links">
                    <h3 class="title">@lang('general.my_account')</h3>
                    <ul>
                        @if(!isset($_SESSION['user']))
                            <li class="dropdown setting">
                                <a href="/auth"><span>@lang('general.register_login')</span></a>
                            </li>
                        @else
                            <li><a href="/conversation">@lang('general.customer_service')</a></li>
                            <li><a href="/reportConversation">@lang('general.my_product_reports')</a></li>
                            <li><a href="/profile/{{ $_SESSION['user']->id }}">@lang('general.my_account')</a></li>
                            <li><a href="/profile/{{ $_SESSION['user']->id }}/history">@lang('general.my_orders')</a></li>
                           
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-2 col-lg-2  col-sm-4 col-xs-6">
                <div class="links">
                    <h3 class="title">@lang('general.support')</h3>
                    <ul>
                        <li class="dropdown setting"><a href="/main/zipDeterminer">@lang('general.locating_postal_code')</a></li>
                        <li class="dropdown setting"><a href="/main/rules">@lang('general.rules')</a></li>
                        <li class="dropdown setting"><a href="/main/whoWeAre">@lang('general.who_we_are')</a></li>
                        <li class="dropdown setting"><a href="/main/customer-service">@lang('general.customer_service')</a></li>
                        <li class="dropdown setting"><a href="/main/questionanswer">@lang('general.common_questions')</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 col-lg-2  col-sm-4 col-xs-6">
                <div class="links">
                    <h3 class="title">@lang('general.sites')</h3>
                    <ul>
                        <li class="dropdown setting"><a href="/ebay">@Lang('general.searching_for_ebay')</a></li>
                        <li class="dropdown setting"><a href="/aliexpress">@lang('general.searching_for_aliexpress')</a></li>
                        <li class="dropdown setting"><a href="/amazon">@lang('general.searching_for_amazon')</a></li>
                        <li class="dropdown setting"><a href="/next">@lang('general.available_hebrew')</a></li>
                        <li class="dropdown setting"><a href="/aliexpress/oneDayDeals">@lang('general.daily_express')</a></li>
                        <li class="dropdown setting"><a href="/ebay/oneDayDeals">@lang('general.eBay_daily_deal')</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-0 col-sm-1 col-xs-0"></div>
            <div class="col-md-3 col-lg-3 col-sm-10 col-xs-12">
                <div class="block-newletter">
                    <h4 class="widget-title-sm">@lang('general.chipi_club')</h4>
                    <form action="/newsletter" id="newsletter">
                        <div class="form-group">
                            <label class="form-label">@lang('general.join_chipi_club_enjoy_host_benefits')</label>
                            <input id="newsletter-email-footer" class="newsletter-input form-control"
                                   placeholder="@lang('general.email_address')"
                                   type="email" name="email" required="">

                        </div>
                        <input class="btn" type="submit" name="submitNewslaterInSite" value="@lang('general.register')"
                               id="newsletter-button">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-methods">
        <div class="block-title">
            @lang('general.companies_in_collaboration')
        </div>
        <div class="block-content">
            <img alt="payment" src="/images/media/index1/payment1.png">
            <img alt="payment" src="/images/media/index1/payment2.png">
            <img alt="payment" src="/images/media/index1/payment3.png">
            <img alt="payment" src="/images/media/index1/payment4.png">
            <img alt="payment" src="/images/media/index1/payment5.png">
            <img alt="payment" src="/images/media/index1/payment6.png">
            <img alt="payment" src="/images/media/index1/payment7.png">
            <img alt="payment" src="/images/media/index1/payment8.png">
            <img alt="payment" src="/images/media/index1/payment9.png">
            <img alt="payment" src="/images/media/index1/payment10.png">
        </div>
    </div>
    <div class="copyright">@lang('general.copyright')</div>

</div>
<script src="{{asset('assets/footer.js')}}"></script>
