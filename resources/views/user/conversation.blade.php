@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main container">
                @if(old('status')!=null)
                    <div class="alert alert-success" role="alert">
                        @lang("general.success_update")
                    </div>
            @endif
            <!-- Main Content -->
            <div class="bleach contact-us-container">
                        <h1 class="page-title">
                            @lang('general.my_references')
                        </h1>
                @foreach($tickets as $ticket)
                    <div class="contact-us-item-wrap">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="contact-us-item first-item">
                                    <span class="contact-us-item-title">@lang('general.call_number'): </span>
                                    <span class="contact-us-item-content">

                                        <a href="/user/ticketChat/{{$ticket->id}}">
                                             <p>{{$ticket->id}}</p>
                                        </a>

                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="contact-us-item">
                                    <span class="contact-us-item-title">@lang('general.date_of_application'): </span>
                                    <span class="contact-us-item-content">

                                        <a href="/user/ticketChat/{{$ticket->id}}">
                                             <p>{{date('d-m-Y',strtotime($ticket->date))}}</p>
                                        </a>

                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="contact-us-item">
                                    <span class="contact-us-item-title">@lang('general.call_status'): </span>
                                    <span class="contact-us-item-content">

                                        <a href="/user/ticketChat/{{$ticket->id}}">
                                             @if($ticket->status ==5)
                                                 <p>@lang('general.her_face_is_closed')</p>
                                             @elseif($ticket->status ==2)
                                                 <p>@lang('general.waiting_for_customer_reply')</p>
                                             @elseif($ticket->status ==0)
                                                  <p>@lang('general.waiting_for_customer_service_response')</p>
                                             @endif
                                        </a>

                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="contact-us-item last-item">
                                    <div class="contact-us-item-content">
                                        <a class="btn btn-default" href="/user/ticketChat/{{$ticket->id}}">@lang('general.insert_in_front_of_her')</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

    </main>
@stop