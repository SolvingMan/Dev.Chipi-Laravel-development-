@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="container">
            <div class="container-fluid">
                @if(!$reportInfo->isEmpty())
                    <div class="row chat-container">
                        <div class="col-md-10 col-md-push-1">
                            <table class="table bleach" style="border-style:none;">
                                <tr>
                                    <td>
                                        @lang('general.call_number'): {{$reportInfo[0]->id}}
                                    </td>
                                    <td>
                                        @lang('general.date_of_application'): {{date('d-m-Y',strtotime($reportInfo[0]->date)).
                        ' '.$reportInfo[0]->time}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @lang('general.card_notification'): {{$reportInfo[0]->message}}
                                    </td>
                                    <td>
                                        @lang('general.status'):
                                        @if($reportInfo[0]->status ==5)
                                            @lang('general.her_face_is_closed)
                                        @elseif($reportInfo[0]->status ==2)
                                            @lang('general.waiting_for_customer_reply')
                                        @elseif($reportInfo[0]->status ==0)
                                            @lang('general.waiting_for_customer_service_response')
                                        @elseif($reportInfo[0]->status ==4)
                                            @lang('general.waiting_for_seller_reply')
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-10 current-chat col-md-push-1">
                            <div class="row chat-toolbar-row">
                                <div class="row current-chat-area bleach">
                                    <div class="col-md-12">
                                        <ul class="media-list">
                                            @if(!$conversationsForReport->isEmpty())
                                                @foreach($conversationsForReport as $message)
                                                    <li class="media media-chat">
                                                        <div class="media-body">
                                                            <div class="media ">
                                                                <div class="media-body">
                                                                    <small class="text-muted">
                                                                        @if($message->sender=="User")
                                                                            {{$_SESSION['user']->name.' '.
                                                                            $_SESSION['user']->surname}}
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
                                <form method="POST" id="report-chat-form" action="/user/addMessageToReportService">
                                    <div class="row current-chat-footer">
                                        <div class="panel-footer">
                                            <div class="input-group">
                                                <input name="reportId" type="hidden"
                                                       value="{{$reportInfo[0]->id}}">
                                                <input name="userId" type="hidden"
                                                       value="{{$reportInfo[0]->userId}}">
                                                <input name="orderId" type="hidden"
                                                       value="{{$reportInfo[0]->orderId}}">
                                                <input name="itemId" type="hidden"
                                                       value="{{$reportInfo[0]->itemId}}">
                                                <label for="message">@lang('general.write_your_message')</label>
                                                <input
                                                    <?php if ($conversationsForReport->isEmpty() || $reportInfo[0]->status == 5
                                                        || $reportInfo[0]->status == 4 || $reportInfo[0]->status == 0) {
                                                        echo " disabled ";
                                                    }?> type="text" name="message" class="form-control">
                                                <span class="input-group-btn" style="vertical-align: bottom;"
                                                >
                    <button <?php if ($conversationsForReport->isEmpty() || $reportInfo[0]->status == 5
                        || $reportInfo[0]->status == 4 || $reportInfo[0]->status == 0) {
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