@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="container qa-container">
            <!--div class="row"-->
                <!--div class="col-md-12 "-->
                    <div class="panel-group" id="accordion">
                    <h2 class="qa-title"> @lang('general.common_questions')</h2>
                        @foreach($qa as $key=>$item)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse{{$key+1}}">

                                                <span class="fa fa-book"></span>

                                           <span class="qa">{{$item->question}}</span>
                                           <span class="fa fa-angle-down"></span>
                                           </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$key+1}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php echo $item->answer ?>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a style="line-height: 50px;" href="/main/contactus"><span>@lang('general.if_you_have_not_found_an_answer')</span></a>
                    </div>


                <!--/div-->
            <!--/div-->
        </div>
    </main>
@stop