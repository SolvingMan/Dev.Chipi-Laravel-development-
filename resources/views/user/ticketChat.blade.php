@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="container">
            <div class="container-fluid">
                @if(!$ticketInfo->isEmpty())
                    <div class="row bleach chat-container">
                        <div class="col-md-10 col-md-push-1">
                            {{--<div class="container bleach" style="border-style:none;">--}}
                            <div class="row">
                                <div class="col-md-4">
                                    @lang('general.call_number'): {{$ticketInfo[0]->id}}
                                </div>
                                <div class="col-md-4">
                                    @lang('general.date_of_application'): {{date('d-m-Y',strtotime($ticketInfo[0]->date)).
                        ' '.$ticketInfo[0]->time}}
                                </div>
                                <div class="col-md-4">
                                    @lang('general.status'):
                                    @if($ticketInfo[0]->status ==5)
                                        @lang('general.her_face_is_closed')
                                    @elseif($ticketInfo[0]->status ==2)
                                        @lang('general.waiting_for_customer_reply')
                                    @elseif($ticketInfo[0]->status ==0)
                                        @lang('general.waiting_for_customer_service_response')
                                    @elseif($ticketInfo[0]->status ==4)
                                        @lang('general.waiting_for_seller_reply')
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    @lang('general.card_notification'): {{$ticketInfo[0]->message}}
                                </div>
                                <div class="col-md-3">
                                    @lang('general.department'):
                                    @if($ticketInfo[0]->department==0)
                                        @lang('general.customer_service')
                                    @elseif($ticketInfo[0]->department==2)
                                        @lang('general.help_in_kenya')
                                    @elseif($ticketInfo[0]->department==4)
                                        @lang('general.feedback_on_the_site')
                                    @endif
                                </div>
                            </div>
                            {{--</div>--}}
                        </div>
                        <div class="col-md-10 current-chat col-md-push-1">
                            <div class="row chat-toolbar-row">
                                <div class="row current-chat-area bleach">
                                    <div class="col-md-12">
                                        <ul class="media-list">
                                            @if(!$conversationsForTicket->isEmpty())
                                                @foreach($conversationsForTicket as $message)
                                                    <li class="media media-chat">
                                                        <div class="media-body">
                                                            <div class="media ">
                                                                <div class="media-body">
                                                                    <small class="text-muted">
                                                                        @if($message->sender=="User")
                                                                            @if (env('APP_ENV') == \Config::get('enums.env.LOCAL'))
                                                                                {{$_SESSION['user']['attributes']['name'].' '.
                                                                                $_SESSION['user']['attributes']['surname']}}
                                                                            @else
                                                                                {{$_SESSION['user']->name.' '.
                                                                                    $_SESSION['user']->surname}}
                                                                            @endif
                                                                        @else
                                                                            {{$message->sender}}
                                                                        @endif
                                                                        | {{date('d-m-Y',strtotime($message->date)).' '.$message->time}}
                                                                    </small>

                                                                    <p class="chat-paragraph"> <?php echo $message->message?></p>
                                                                    <hr>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <p>@lang('general.waiting_for_reply')</p>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                                <form method="POST" id="chat-form" action="/user/addMessageToCustomerService">
                                    <div class="row current-chat-footer">
                                        <div class="panel-footer bleach">
                                            <div class="input-group">
                                                <input name="ticketId" type="hidden"
                                                       value="
                                                   <?php if (!$conversationsForTicket->isEmpty()) {
                                                           echo $conversationsForTicket[0]->ticketId;
                                                       }?>">
                                                <input name="userId" type="hidden"
                                                       value=" <?php if (!$conversationsForTicket->isEmpty()) {
                                                           echo $conversationsForTicket[0]->userId;
                                                       }?>">
                                                <label for="message">@lang('general.write_your_message')</label>
                                                <input
                                                    <?php if ($conversationsForTicket->isEmpty() || $ticketInfo[0]->status == 5
                                                        || $ticketInfo[0]->status == 4 || $ticketInfo[0]->status == 0) {
                                                        echo " disabled ";
                                                    }?> type="text" name="message" class="form-control">
                                                <span class="input-group-btn" style="vertical-align: bottom;"
                                                >
                    <button <?php if ($conversationsForTicket->isEmpty() || $ticketInfo[0]->status == 5
                        || $ticketInfo[0]->status == 4 || $ticketInfo[0]->status == 0) {
                        echo " disabled ";
                    }?> class="btn btn-default button-send-ticket-chat" type="submit">@lang('general.send_a_message')</button>
                  </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row bleach chat-container" style="height: 450px;">
                        <span>@lang('general.you_dont_have_access_to_this_card')</span>
                    </div>
                @endif
            </div>
        </div>
    </main>
    <script src="{{ URL::asset('assets/chat.js') }}"></script>
@stop