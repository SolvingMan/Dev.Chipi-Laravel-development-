    @foreach($history as $order)
        <div class="contact-us-item-wrap">
            <div class="row">

                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="contact-us-item first-item">
                                    <span class="contact-us-item-title">@lang('general.order_number')</span>

                                    <span class="contact-us-item-content">
                                        <p>{{$order->summaryId}}</p>
                                    </span>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="contact-us-item">
                                    <span class="contact-us-item-title">@lang('general.date_of_order')</span>

                                    <span class="contact-us-item-content">
                                        <p>{{$order->summaryDate}}</p>
                                    </span>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="contact-us-item">
                                    <span class="contact-us-item-title">@lang('general.estimated_date')</span>

                                    <span class="contact-us-item-content">
                                        <p>{{$order->summaryEsitmatedDate}}</p>
                                    </span>

                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-6">
                                 <div class="contact-us-item">
                                      <span class="contact-us-item-title">@lang('general.order_price')</span>

                                      <span class="contact-us-item-content">
                                          <p>{{$order->summaryTotalPriceOrder}} &#8362;</p>
                                      </span>

                                 </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                 <div class="contact-us-item">
                                      <span class="contact-us-item-title">@lang('general.order_status')</span>

                                      <span class="contact-us-item-content">
                                           <p>{{$order->statusText}}</p>
                                      </span>

                                 </div>
                            </div>


                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="contact-us-item">
                                <span class="contact-us-item-title last-title"></span>

                                    <div class="contact-us-item-content last-item">
                                        <button data-id="{{$order->summaryId}}"
                                                                class="btn btn-default order-details">@lang("general.view_order")</button>
                                    </div>
                                </div>

                            </div>
            </div>
        </div>
    @endforeach

