@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main container">
        <div class="bleach contact-us-container conversation-container">

                @if(old('status')!=null)
                    <div class="alert alert-success" role="alert">
                        @lang("general.success_update")
                    </div>
            @endif
            <!-- Main Content -->
            <h1 class="page-title">
                                            @lang('general.product_reports')
            </h1>
            @foreach($reports as $report)
            <div class="contact-us-item-wrap">
                 <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact-us-item first-item">
                                <span class="contact-us-item-title">@lang('general.call_number'): </span>
                                <div class="contact-us-item-content">

                                    <a href="/user/reportChat/{{$report->id}}">
                                         <p>{{$report->id}}</p>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact-us-item">
                                <span class="contact-us-item-title">@lang('general.order_number'): </span>
                                <div class="contact-us-item-content">

                                    <a href="/user/reportChat/{{$report->id}}">
                                        <p>{{$report->orderId}}</p>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact-us-item">
                                <span class="contact-us-item-title">@lang('general.product_code'): </span>
                                <div class="contact-us-item-content">

                                    <a href="/user/reportChat/{{$report->id}}">
                                        <p>{{$report->itemId}}</p>
                                    </a>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact-us-item">
                                <span class="contact-us-item-title">@lang('general.date_of_application'): </span>
                                <div class="contact-us-item-content">

                                    <a href="/user/reportChat/{{$report->id}}">
                                          <p>{{date('d-m-Y',strtotime($report->date))}}</p>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact-us-item">
                                <span class="contact-us-item-title">@lang('general.call_status'): </span>
                                <div class="contact-us-item-content">

                                    <a href="/user/reportChat/{{$report->id}}">
                                         @if($report->status ==5)
                                              <p>@lang('general.her_face_is_closed')</p>
                                         @elseif($report->status ==2)
                                              <p>@lang('general.waiting_for_customer_reply')</p>
                                         @elseif($report->status ==0)
                                               <p>@lang('general.waiting_for_customer_service_response')</p>
                                         @endif
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="contact-us-item">
                            <span class="contact-us-item-title last-title"></span>

                                  <div class="contact-us-item-content last-item">
                                       <a class="btn btn-default" href="/user/reportChat/{{$report->id}}">@lang('general.insert_in_front_of_her')</a>
                                  </div>

                            </div>
                        </div>
                 </div>
            </div>
            @endforeach
        </div>
    </main>
@stop