@extends('frontEnd.layout')

@section('content')
    <?php
    $title_var = "title_" . @Helper::currentLanguage()->code;
    $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
    $details_var = "details_" . @Helper::currentLanguage()->code;
    $details_var2 = "details_" . env('DEFAULT_LANGUAGE');
    if ($Topic->$title_var != "") {
        $title = $Topic->$title_var;
    } else {
        $title = $Topic->$title_var2;
    }
    if ($Topic->$details_var != "") {
        $details = $details_var;
    } else {
        $details = $details_var2;
    }
    $section = "";
    try {
        if ($Topic->section->$title_var != "") {
            $section = $Topic->section->$title_var;
        } else {
            $section = $Topic->section->$title_var2;
        }
    } catch (Exception $e) {
        $section = "";
    }
    ?>
    <section id="inner-headline">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li><a href="{{ route("Home") }}"><i class="fa fa-home"></i></a><i class="icon-angle-right"></i>
                        </li>
                        @if($WebmasterSection->id != 1)
                            <?php
                            $title_var = "title_" . @Helper::currentLanguage()->code;
                            $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
                            if (@$WebmasterSection->$title_var != "") {
                                $WebmasterSectionTitle = @$WebmasterSection->$title_var;
                            } else {
                                $WebmasterSectionTitle = @$WebmasterSection->$title_var2;
                            }
                            ?>
                            <li class="active">{!! $WebmasterSectionTitle !!}</li>
                        @else
                            <li class="active">{{ $title }}</li>
                        @endif
                        @if(!empty($CurrentCategory))
                            <?php
                            $title_var = "title_" . @Helper::currentLanguage()->code;
                            $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
                            if (@$CurrentCategory->$title_var != "") {
                                $CurrentCategoryTitle = @$CurrentCategory->$title_var;
                            } else {
                                $CurrentCategoryTitle = @$CurrentCategory->$title_var2;
                            }
                            ?>
                            <li class="active"><i class="icon-angle-right"></i>{{ $CurrentCategoryTitle }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="content">
    @if($not_container)
        <div class="containerr" style="margin: 50px">
    @else
        <div class="container">
    @endif
            <div class="row">
                <div class="col-lg-{{(count($Categories)>0)? "8":"12"}}">

                    <article>
                        @if($WebmasterSection->type==2 && $Topic->video_file!="")
                            {{--video--}}
                            <div class="post-video">
                                @if($WebmasterSection->title_status)
                                    <div class="post-heading">
                                        <h1>
                                            @if($Topic->icon !="")
                                                <i class="fa {!! $Topic->icon !!} "></i>&nbsp;
                                            @endif
                                            {{ $title }}
                                        </h1>
                                    </div>
                                @endif
                                <div class="video-container">
                                    @if($Topic->video_type ==1)
                                        <?php
                                        $Youtube_id = Helper::Get_youtube_video_id($Topic->video_file);
                                        ?>
                                        @if($Youtube_id !="")
                                            {{-- Youtube Video --}}
                                            <iframe allowfullscreen
                                                    src="https://www.youtube.com/embed/{{ $Youtube_id }}">
                                            </iframe>
                                        @endif
                                    @elseif($Topic->video_type ==2)
                                        <?php
                                        $Vimeo_id = Helper::Get_vimeo_video_id($Topic->video_file);
                                        ?>
                                        @if($Vimeo_id !="")
                                            {{-- Vimeo Video --}}
                                            <iframe allowfullscreen
                                                    src="https://player.vimeo.com/video/{{ $Vimeo_id }}?title=0&amp;byline=0">
                                            </iframe>
                                        @endif

                                    @elseif($Topic->video_type ==3)
                                        @if($Topic->video_file !="")
                                            {{-- Embed Video --}}
                                            {!! $Topic->video_file !!}
                                        @endif

                                    @else
                                        <video width="100%" height="450" controls autoplay>
                                            <source src="{{ URL::to('uploads/topics/'.$Topic->video_file) }}"
                                                    type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif


                                </div>
                            </div>
                        @elseif($WebmasterSection->type==3 && $Topic->audio_file!="")
                            {{--audio--}}
                            <div class="post-video">
                                @if($WebmasterSection->title_status)
                                    <div class="post-heading">
                                        <h1>
                                            @if($Topic->icon !="")
                                                <i class="fa {!! $Topic->icon !!} "></i>&nbsp;
                                            @endif
                                            {{ $title }}
                                        </h1>
                                    </div>
                                @endif
                                @if($Topic->photo_file !="")
                                    <img src="{{ URL::to('uploads/topics/'.$Topic->photo_file) }}"
                                         alt="{{ $title }}"/>
                                @endif
                                <div>
                                    <audio controls autoplay>
                                        <source src="{{ URL::to('uploads/topics/'.$Topic->audio_file) }}"
                                                type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>

                                </div>
                            </div>
                            <br>
                        @elseif(count($Topic->photos)>0)
                            {{--photo slider--}}
                            <div class="post-slider">
                                @if($WebmasterSection->title_status)
                                    <div class="post-heading">
                                        <h1>
                                            @if($Topic->icon !="")
                                                <i class="fa {!! $Topic->icon !!} "></i>&nbsp;
                                            @endif
                                            {{ $title }}
                                        </h1>
                                    </div>
                                @endif
                            <!-- start flexslider -->
                                <div class="p-slider flexslider">
                                    <ul class="slides">
                                        @if($Topic->photo_file !="")
                                            <li>
                                                <img src="{{ URL::to('uploads/topics/'.$Topic->photo_file) }}" alt="{{ $title }}"/>
                                            </li>
                                        @endif
                                        @foreach($Topic->photos as $photo)
                                            <li>
                                                <img src="{{ URL::to('uploads/topics/'.$photo->file) }}" alt="{{ $photo->title  }}"/>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                                <!-- end flexslider -->
                            </div>
                            <br>

                        @else
                            {{--one photo--}}
                            <div class="post-image" style="margin-bottom: 50px">
                                @if($WebmasterSection->title_status)
                                    <div class="post-heading">
                                        <h1>
                                            @if($Topic->icon !="")
                                                <i class="fa {!! $Topic->icon !!} "></i>&nbsp;
                                            @endif
                                            {{ $title }}
                                        </h1>
                                    </div>
                                @endif
                                @if($Topic->photo_file !="")
                                    <img src="{{ URL::to('uploads/topics/'.$Topic->photo_file) }}"
                                         alt="{{ $title }}" title="{{ $title }}"/>
                                    <br>
                                @endif
                            </div>
                        @endif


                        {{--Additional Feilds--}}
                        @if(count($Topic->webmasterSection->customFields->where("in_page",true)) >0)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <?php
                                        $cf_title_var = "title_" . @Helper::currentLanguage()->code;
                                        $cf_title_var2 = "title_" . env('DEFAULT_LANGUAGE');
                                        ?>
                                        @foreach($Topic->webmasterSection->customFields->where("in_page",true) as $customField)
                                            <?php
                                            // check permission
                                            $view_permission_groups = [];
                                            if ($customField->view_permission_groups != "") {
                                                $view_permission_groups = explode(",", $customField->view_permission_groups);
                                            }
                                            if (in_array(0, $view_permission_groups) || $customField->view_permission_groups=="") {
                                            // have permission & continue
                                            ?>
                                            @if($customField->in_page)
                                                <?php
                                                if ($customField->$cf_title_var != "") {
                                                    $cf_title = $customField->$cf_title_var;
                                                } else {
                                                    $cf_title = $customField->$cf_title_var2;
                                                }

                                                $cf_saved_val = "";
                                                $cf_saved_val_array = array();
                                                if (count($Topic->fields) > 0) {
                                                    foreach ($Topic->fields as $t_field) {
                                                        if ($t_field->field_id == $customField->id) {
                                                            if ($customField->type == 7) {
                                                                // if multi check
                                                                $cf_saved_val_array = explode(", ", $t_field->field_value);
                                                            } else {
                                                                $cf_saved_val = $t_field->field_value;
                                                            }
                                                        }
                                                    }
                                                }

                                                ?>

                                                @if(($cf_saved_val!="" || count($cf_saved_val_array) > 0) && ($customField->lang_code == "all" || $customField->lang_code == @Helper::currentLanguage()->code))
                                                    @if($customField->type ==12)
                                                        {{--Vimeo Video Link--}}
                                                        <?php
                                                        $CF_Vimeo_id = Helper::Get_vimeo_video_id($cf_saved_val);
                                                        ?>
                                                        @if($CF_Vimeo_id !="")
                                                            <div class="row field-row">
                                                                <div class="col-lg-3">
                                                                    {!!  $cf_title !!} :
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    {{-- Vimeo Video --}}
                                                                    <iframe allowfullscreen
                                                                            style="height:450px;width: 100%"
                                                                            src="https://player.vimeo.com/video/{{ $CF_Vimeo_id }}?title=0&amp;byline=0">
                                                                    </iframe>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @elseif($customField->type ==11)
                                                        {{--Youtube Video Link--}}

                                                        <?php
                                                        $CF_Youtube_id = Helper::Get_youtube_video_id($cf_saved_val);
                                                        ?>
                                                        @if($CF_Youtube_id !="")
                                                            <div class="row field-row">
                                                                <div class="col-lg-3">
                                                                    {!!  $cf_title !!} :
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    {{-- Youtube Video --}}
                                                                    <iframe allowfullscreen
                                                                            style="height: 450px;width: 100%"
                                                                            src="https://www.youtube.com/embed/{{ $CF_Youtube_id }}">
                                                                    </iframe>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @elseif($customField->type ==10)
                                                        {{--Video File--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <video width="100%" height="450" controls>
                                                                    <source
                                                                        src="{{ URL::to('uploads/topics/'.$cf_saved_val) }}"
                                                                        type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==9)
                                                        {{--Attach File--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <a href="{{ URL::to('uploads/topics/'.$cf_saved_val) }}"
                                                                   target="_blank">
                                                                <span class="badge">
                                                                    {!! Helper::GetIcon(URL::to('uploads/topics/'),$cf_saved_val) !!}
                                                                    {!! $cf_saved_val !!}</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==8)
                                                        {{--Photo File--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <img
                                                                    src="{{ URL::to('uploads/topics/'.$cf_saved_val) }}"
                                                                    alt="{{ $cf_title }} - {{ $title }}"
                                                                    title="{{ $cf_title }} - {{ $title }}">
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==7)
                                                        {{--Multi Check--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <?php
                                                                $cf_details_var = "details_" . @Helper::currentLanguage()->code;
                                                                $cf_details_var2 = "details_en" . env('DEFAULT_LANGUAGE');
                                                                if ($customField->$cf_details_var != "") {
                                                                    $cf_details = $customField->$cf_details_var;
                                                                } else {
                                                                    $cf_details = $customField->$cf_details_var2;
                                                                }
                                                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                                $line_num = 1;
                                                                ?>
                                                                @foreach ($cf_details_lines as $cf_details_line)
                                                                    @if (in_array($line_num,$cf_saved_val_array))
                                                                        <span class="badge">
                                                            {!! $cf_details_line !!}
                                                        </span>
                                                                    @endif
                                                                    <?php
                                                                    $line_num++;
                                                                    ?>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==6)
                                                        {{--Select--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <?php
                                                                $cf_details_var = "details_" . @Helper::currentLanguage()->code;
                                                                $cf_details_var2 = "details_en" . env('DEFAULT_LANGUAGE');
                                                                if ($customField->$cf_details_var != "") {
                                                                    $cf_details = $customField->$cf_details_var;
                                                                } else {
                                                                    $cf_details = $customField->$cf_details_var2;
                                                                }
                                                                $cf_details_lines = preg_split('/\r\n|[\r\n]/', $cf_details);
                                                                $line_num = 1;
                                                                ?>
                                                                @foreach ($cf_details_lines as $cf_details_line)
                                                                    @if ($line_num == $cf_saved_val)
                                                                        {!! $cf_details_line !!}
                                                                    @endif
                                                                    <?php
                                                                    $line_num++;
                                                                    ?>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==5)
                                                        {{--Date & Time--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                {!! Helper::formatDate($cf_saved_val)." ".date("h:i A", strtotime($cf_saved_val)) !!}
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==4)
                                                        {{--Date--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                {!! Helper::formatDate($cf_saved_val) !!}
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==3)
                                                        {{--Email Address--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                {!! $cf_saved_val !!}
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==2)
                                                        {{--Number--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                {!! $cf_saved_val !!}
                                                            </div>
                                                        </div>
                                                    @elseif($customField->type ==1)
                                                        {{--Text Area--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                {!! nl2br($cf_saved_val) !!}
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{--Text Box--}}
                                                        <div class="row field-row">
                                                            <div class="col-lg-3">
                                                                {!!  $cf_title !!} :
                                                            </div>
                                                            <div class="col-lg-9">
                                                                {!! $cf_saved_val !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                            <?php
                                            }
                                            ?>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif
                        {{--End of -- Additional Feilds--}}


                        {!! $Topic->$details !!}

                        @if($Topic->attach_file !="")
                            <?php
                            $file_ext = strrchr($Topic->attach_file, ".");
                            $file_ext = strtolower($file_ext);
                            ?>
                            <div class="bottom-article">
                                @if($file_ext ==".jpg"|| $file_ext ==".jpeg"|| $file_ext ==".png"|| $file_ext ==".gif")
                                    <div class="text-center">
                                        <img src="{{ URL::to('uploads/topics/'.$Topic->attach_file) }}" alt="{{ $title }}"/>
                                    </div>
                                @else
                                    <a href="{{ URL::to('uploads/topics/'.$Topic->attach_file) }}">
                                        <strong>
                                            {!! Helper::GetIcon(URL::to('uploads/topics/'),$Topic->attach_file) !!} &nbsp;{{ __('frontend.downloadAttach') }}
                                        </strong>
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- Show Additional attach files --}}
                        @if(count($Topic->attachFiles)>0)
                            <div style="padding: 10px;border: 1px dashed #ccc;margin-bottom: 10px;">
                                @foreach($Topic->attachFiles as $attachFile)
                                    <?php
                                    if ($attachFile->$title_var != "") {
                                        $file_title = $attachFile->$title_var;
                                    } else {
                                        $file_title = $attachFile->$title_var2;
                                    }
                                    ?>
                                    <div style="margin-bottom: 5px;">
                                        <a href="{{ URL::to('uploads/topics/'.$attachFile->file) }}" target="_blank">
                                            <strong>
                                                {!! Helper::GetIcon(URL::to('uploads/topics/'),$attachFile->file) !!} &nbsp;{{ $file_title }}
                                            </strong>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif


                        @if(count($Topic->maps) >0)
                            <div class="row">
                                <div class="col-lg-12">
                                    <br>
                                    <h4>{{ __('frontend.locationMap') }}</h4>
                                    <div id="google-map"></div>
                                </div>
                            </div>
                        @endif


                        @if($WebmasterSection->comments_status)
                            <div id="comments">
                                @if(count($Topic->approvedComments)>0)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <br>
                                            <h4><i class="fa fa-comments"></i> {{ __('frontend.comments') }}</h4>
                                            <hr>
                                        </div>
                                    </div>
                                    @foreach($Topic->approvedComments as $comment)
                                        <?php
                                        $dtformated = date('d M Y h:i A', strtotime($comment->date));
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <img src="{{ URL::to('uploads/contacts/profile.jpg') }}" class="profile"
                                                     alt="{{$comment->name}}">
                                                <div class="pullquote-left">
                                                    <strong>{{$comment->name}}</strong>
                                                    <div>
                                                        <small>
                                                            <small>{{ $dtformated }}</small>
                                                        </small>
                                                    </div>
                                                    {!! nl2br(strip_tags($comment->comment)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="row">
                                    <div class="col-lg-12">
                                        <br>
                                        <h4><i class="fa fa-plus"></i> {{ __('frontend.newComment') }}</h4>
                                        <div class="bottom-article newcomment">
                                            <div id="sendmessage"><i class="fa fa-check-circle"></i>
                                                &nbsp;{{ __('frontend.youCommentSent') }} &nbsp; <a
                                                    href="{{url()->current()}}"><i
                                                        class="fa fa-refresh"></i> {{ __('frontend.refresh') }}
                                                </a>
                                            </div>
                                            <div id="errormessage">{{ __('frontend.youMessageNotSent') }}</div>

                                            {{Form::open(['route'=>['Home'],'method'=>'POST','class'=>'commentForm'])}}
                                            <div class="form-group">
                                                {!! Form::text('comment_name',@Auth::user()->name, array('placeholder' => __('frontend.yourName'),'class' => 'form-control','id'=>'comment_name', 'data-msg'=> __('frontend.enterYourName'),'data-rule'=>'minlen:4')) !!}
                                                <div class="alert alert-warning validation"></div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::email('comment_email',@Auth::user()->email, array('placeholder' => __('frontend.yourEmail'),'class' => 'form-control','id'=>'comment_email', 'data-msg'=> __('frontend.enterYourEmail'),'data-rule'=>'email')) !!}
                                                <div class="validation"></div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::textarea('comment_message','', array('placeholder' => __('frontend.comment'),'class' => 'form-control','id'=>'comment_message','rows'=>'5', 'data-msg'=> __('frontend.enterYourComment'),'data-rule'=>'required')) !!}
                                                <div class="validation"></div>
                                            </div>

                                            @if(env('NOCAPTCHA_STATUS', false))
                                                <div class="form-group">
                                                    {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                                                    {!! NoCaptcha::display() !!}
                                                </div>
                                            @endif
                                            <div>
                                                <input type="hidden" name="topic_id" value="{{$Topic->id}}">
                                                <button type="submit"
                                                        class="btn btn-theme">{{ __('frontend.sendComment') }}</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($WebmasterSection->order_status)
                            <div id="order">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <br>
                                        <h4><i class="fa fa-cart-plus"></i> {{ __('frontend.orderForm') }}</h4>
                                        <div class="bottom-article newcomment">
                                            <div id="ordersendmessage"><i class="fa fa-check-circle"></i>
                                                &nbsp;{{ __('frontend.youOrderSent') }}
                                            </div>
                                            <div id="ordererrormessage">{{ __('frontend.youMessageNotSent') }}</div>

                                            {{Form::open(['route'=>['Home'],'method'=>'POST','class'=>'orderForm'])}}
                                            <div class="form-group">
                                                {!! Form::text('order_name',@Auth::user()->name, array('placeholder' => __('frontend.yourName'),'class' => 'form-control','id'=>'order_name', 'data-msg'=> __('frontend.enterYourName'),'data-rule'=>'minlen:4')) !!}
                                                <div class="alert alert-warning validation"></div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::text('order_phone',"", array('placeholder' => __('frontend.phone'),'class' => 'form-control','id'=>'order_phone', 'data-msg'=> __('frontend.enterYourPhone'),'data-rule'=>'minlen:4')) !!}
                                                <div class="validation"></div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::email('order_email',@Auth::user()->email, array('placeholder' => __('frontend.yourEmail'),'class' => 'form-control','id'=>'order_email', 'data-msg'=> __('frontend.enterYourEmail'),'data-rule'=>'email')) !!}
                                                <div class="validation"></div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::textarea('order_message','', array('placeholder' => __('frontend.notes'),'class' => 'form-control','id'=>'order_message','rows'=>'5')) !!}
                                                <div class="validation"></div>
                                            </div>

                                            @if(env('NOCAPTCHA_STATUS', false))
                                                <div class="form-group">
                                                    {!! NoCaptcha::renderJs(@Helper::currentLanguage()->code) !!}
                                                    {!! NoCaptcha::display() !!}
                                                </div>
                                            @endif
                                            <div>
                                                <input type="hidden" name="topic_id" value="{{$Topic->id}}">
                                                <button type="submit"
                                                        class="btn btn-theme">{{ __('frontend.sendOrder') }}</button>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        @if($WebmasterSection->related_status)
                            @if(count($Topic->relatedTopics))
                                <div id="Related">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <br>
                                            <h4><i class="fa fa-bookmark"></i> {{ __('backend.relatedTopics') }}
                                            </h4>
                                            <div class="bottom-article newcomment">
                                                <?php
                                                $title_var = "title_" . @Helper::currentLanguage()->code;
                                                $title_var2 = "title_" . env('DEFAULT_LANGUAGE');
                                                $slug_var = "seo_url_slug_" . @Helper::currentLanguage()->code;
                                                $slug_var2 = "seo_url_slug_" . env('DEFAULT_LANGUAGE');
                                                ?>
                                                @foreach($Topic->relatedTopics as $relatedTopic)
                                                    <?php


                                                    if ($relatedTopic->topic->$title_var != "") {
                                                        $relatedTopic_title = $relatedTopic->topic->$title_var;
                                                    } else {
                                                        $relatedTopic_title = $relatedTopic->topic->$title_var2;
                                                    }
                                                    ?>
                                                    <div style="margin-bottom: 5px;">
                                                        <a href="{{ Helper::topicURL($relatedTopic->topic->id) }}"><i
                                                                class="fa fa-bookmark-o"></i>&nbsp; {!! $relatedTopic_title !!}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                    </article>
                </div>
                @if(count($Categories) >0)
                    @include('frontEnd.includes.side')
                @endif
            </div>
        </div>
        <script>
            $(document).ready(function() {
                @if(!empty($not_container) && $not_container)
                    if($('#oyuncu_istatistik_tablosu').length) {
                        let datatable = $('#oyuncu_istatistik_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "{{ route('ajax-oyuncu-istatistik') }}",
                                "data": function (d) {
                                    return $.extend({}, d, {
                                        "select_value1": $('input#adsoyad').val(),
                                        "select_value2": $('select#takimi').val(),
                                        "select_value3": $('select#rakip_takim').val(),
                                        "select_value4": $('input#ilk_tarih').val(),
                                        "select_value5": $('input#son_tarih').val(),
                                        "select_value6": $('input#ilk_sure').val(),
                                        "select_value7": $('input#son_sure').val(),
                                        "select_value8": $('select#sezon').val(),
                                        "select_value9": $('select#lig').val(),
                                        "select_value10": $('select#kapsam').val()
                                    });
                                }
                            },
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": true,
                            "order": [[ 0, "desc" ]]
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-takimlar') }}",
                             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (response) {
                                if (response != 'false') {
                                    let resultsDiv1 = $("select[id='takimi']");
                                    let resultsDiv2 = $("select[id='rakip_takim']");
                                    resultsDiv1.html( `<option value="0">--Takımlar--</option>` );
                                    resultsDiv2.html( `<option value="0">--Rakip Takımlar--</option>` );

                                    let takimlar = JSON.parse(response);
                                    $.each(takimlar, function(key, takim) {
                                        $(resultsDiv1).append( `<option value="`+takim.adi+`">` + takim.adi + `</option>` );
                                        $(resultsDiv2).append( `<option value="`+takim.adi+`">` + takim.adi + `</option>` );
                                    });

                                }
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-sezonlar') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (response) {
                                if (response != 'false') {
                                    let resultsDiv = $("select[id='sezon']");
                                    resultsDiv.html(
                                        `<option value="0">--Sezonlar--</option>`
                                    );

                                    let sezonlar = JSON.parse(response);
                                    $.each(sezonlar, function(key, sezon) {
                                        $(resultsDiv).append(
                                            `<option value="`+sezon.adi+`">` + sezon.adi + `</option>`
                                        );
                                    });
                                }
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-ligler') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (response) {
                                if (response != 'false') {
                                    let resultsDiv = $("select[id='lig']");
                                    resultsDiv.html(
                                        `<option value="0">--Ligler--</option>`
                                    );

                                    let ligler = JSON.parse(response);
                                    $.each(ligler, function(key, lig) {
                                        $(resultsDiv).append(
                                            `<option value="`+lig.adi+`">` + lig.adi + `</option>`
                                        );
                                    });
                                }
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-kapsamlar') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (response) {
                                if (response != 'false') {
                                    let resultsDiv = $("select[id='kapsam']");
                                    resultsDiv.html(
                                        `<option value="0">--Kapsamlar--</option>`
                                    );

                                    let kapsamlar = JSON.parse(response);
                                    $.each(kapsamlar, function(key, kapsam) {
                                        $(resultsDiv).append(
                                            `<option value="`+kapsam.adi+`">` + kapsam.adi + `</option>`
                                        );
                                    });
                                }
                            }
                        });
                        $("button#oyuncu_istatistik_filtre").click(function() {
                            datatable.ajax.reload(null, false);
                        });
                    }


                    if($('#donem_istatistik_tablosu').length) {
                        let datatable = $('#donem_istatistik_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "{{ route('ajax-donem-istatistik') }}",
                                "data": function (d) {
                                    return $.extend({}, d, {
                                        "select_value": $('select#donem').val()
                                    });
                                }
                            },
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": true
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-donem-istatistik-donemler') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (donemler) {
                                if (donemler != 'false') {
                                    $.each(donemler, function(key, donem) {
                                        $("select#donem").append("<option value='" + donem + "'>" + donem + "</option>");
                                    });
                                }
                            }
                        });
                        $("select#donem").change(function() { datatable.ajax.reload(null, false); });

                    }
                    if($('#pozisyon_istatistik_tablosu').length) {
                        let datatable = $('#pozisyon_istatistik_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "{{ route('ajax-pozisyon-istatistik') }}",
                                "data": function (d) {
                                    return $.extend({}, d, {
                                        "select_value1": $('select#ana_pozisyon').val(),
                                        "select_value2": $('select#yan_pozisyon').val(),
                                    });
                                }
                            },
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": true
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-pozisyon-istatistik-pozisyonlar') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (pozisyonlar) {
                                if (pozisyonlar != 'false') {
                                    $.each(pozisyonlar, function(key, data) {
                                        $("select#ana_pozisyon").append("<option value='" + key + "'>" + data + "</option>");
                                        $("select#yan_pozisyon").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                }
                            }
                        });
                        $("select#ana_pozisyon").change(function() { datatable.ajax.reload(null, false); });
                        $("select#yan_pozisyon").change(function() { datatable.ajax.reload(null, false); });
                    }
                    if($('#tbl_mac_sonuclari_tablosu').length) {
                        $('#tbl_mac_sonuclari_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": "{{ route('ajax-tbl-mac-sonuclari') }}",
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": true,
                            "order": [[ 0, "desc" ]]
                        });
                    }
                    if($('#bsl_mac_sonuclari_tablosu').length) {
                        $('#bsl_mac_sonuclari_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": "{{ route('ajax-bsl-mac-sonuclari') }}",
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": true,
                            "order": [[ 0, "desc" ]]
                        });
                    }
                    if($('#mac_analizleri_tablosu').length) {
                        let datatable = $('#mac_analizleri_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "{{ route('ajax-mac-analizleri') }}",
                                "data": function (d) {
                                    return $.extend({}, d, {
                                        "select_value1": $('select#mac_no').val(),
                                        "select_value2": $('select#hafta').val(),
                                        "select_value3": $('select#lig').val(),
                                        "select_value4": $('select#sehir').val(),
                                        "select_value5": $('select#a_takim').val(),
                                        "select_value6": $('select#b_takim').val(),
                                        "select_value7": $('select#grup').val(),
                                        "select_value8": $('select#salon').val(),
                                        "select_value9": $('select#hakem').val(),
                                        "select_value10": $('select#yrd_hakem').val(),
                                        "select_value11": $('select#tv').val(),
                                        "select_value12": $('select#masa_gorevli').val(),
                                    });
                                }
                            },
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": true,
                            "order": [[ 0, "desc" ]]
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-mac-analizleri-filtreler') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (filtreler) {
                                if (filtreler != 'false') {
                                    $.each(filtreler.mac_nolar, function(key, data) {
                                        $("select#mac_no").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.haftalar, function(key, data) {
                                        $("select#hafta").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.ligler, function(key, data) {
                                        $("select#lig").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.sehirler, function(key, data) {
                                        $("select#sehir").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.a_takimlar, function(key, data) {
                                        $("select#a_takim").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.b_takimlar, function(key, data) {
                                        $("select#b_takim").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.gruplar, function(key, data) {
                                        $("select#grup").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.salonlar, function(key, data) {
                                        $("select#salon").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.hakemler, function(key, data) {
                                        $("select#hakem").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.yrd_hakemler, function(key, data) {
                                        $("select#yrd_hakem").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.tvler, function(key, data) {
                                        $("select#tv").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                    $.each(filtreler.masa_gorevliler, function(key, data) {
                                        $("select#masa_gorevli").append("<option value='" + key + "'>" + data + "</option>");
                                    });
                                }
                            }
                        });
                        $("button#mac_analiz_filtre").click(function() {
                            datatable.ajax.reload(null, false);
                        });
                    }
                    if($('#oyuncu_profil_tablosu').length) {
                        $('#oyuncu_profil_tablosu').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": "{{ route('ajax-oyuncu-profil-tablosu') }}",
                            "language": { "url": "{{ asset('/assets/frontend/js/dataTables/tr.js') }}" },
                            "pageLength": 25,
                            "responsive": { "details": { "type": "column", "target": "tr" } },
                            "columnDefs": [{ "className": 'control color-trans ', "orderable": false, "targets": 0 }],
                        });
                    }


                    if($('#player-comparison').length) {

                        let timer1 = null;
                        timer1 = setTimeout(function () {
                            $('.player-comparison__results-tooltip').remove();
                            clearTimeout(timer1);
                        }, 10000);

                        let searchDiv = $(".player-comparison__results-wrap.searchActive");

                        $("#player-comparison_1, #player-comparison_2, #player-comparison_3, #player-comparison_4, #player-comparison_5").click(function (e) {
                            e.preventDefault();

                            if($(this).attr('id') == 'player-comparison_1') {
                                $('#player_1.player-comparison__player').addClass('active').removeClass('inactive');
                                $('#player_2.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_3.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_4.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_5.player-comparison__player').addClass('inactive').removeClass('active');
                            }
                            if($(this).attr('id') == 'player-comparison_2') {
                                $('#player_1.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_2.player-comparison__player').addClass('active').removeClass('inactive');
                                $('#player_3.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_4.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_5.player-comparison__player').addClass('inactive').removeClass('active');
                            }
                            if($(this).attr('id') == 'player-comparison_3') {
                                $('#player_1.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_2.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_3.player-comparison__player').addClass('active').removeClass('inactive');
                                $('#player_4.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_5.player-comparison__player').addClass('inactive').removeClass('active');
                            }
                            if($(this).attr('id') == 'player-comparison_4') {
                                $('#player_1.player-comparison__player').addClass('active').removeClass('active');
                                $('#player_2.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_3.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_4.player-comparison__player').addClass('active').removeClass('inactive');
                                $('#player_5.player-comparison__player').addClass('inactive').removeClass('active');
                            }
                            if($(this).attr('id') == 'player-comparison_5') {
                                $('#player_1.player-comparison__player').addClass('active').removeClass('active');
                                $('#player_2.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_3.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_4.player-comparison__player').addClass('inactive').removeClass('active');
                                $('#player_5.player-comparison__player').addClass('active').removeClass('inactive');
                            }


                            $('.player-comparison').addClass('modalActive');
                        });

                        $(".player-comparison__modal-screen, .player-comparison__box a.close").click(function (e) {
                            e.preventDefault();
                            $('.player-comparison').removeClass('modalActive');
                        });

                        $("li#filtertab").click(function (e) {
                            e.preventDefault();
                            $(this).addClass('active');
                            $("li#searchtab").removeClass('active');
                            $(".player-comparison__tools[data-ui-tab='Filter']").addClass('active');
                            $(".player-comparison__tools[data-ui-tab='Search']").removeClass('active');
                            $(".player-comparison__results-wrap").removeClass('searchActive');
                        });

                        $("li#searchtab").click(function (e) {
                            e.preventDefault();
                            $(this).addClass('active');
                            $("li#filtertab").removeClass('active');
                            $(".player-comparison__tools[data-ui-tab='Search']").addClass('active');
                            $(".player-comparison__tools[data-ui-tab='Filter']").removeClass('active');
                            $(".player-comparison__results-wrap").addClass('searchActive');

                            searchDiv = $(".player-comparison__results-wrap.searchActive .player-comparison__search-results-scroll");
                            $(searchDiv).find(".js-search-results-notice").css('display', 'none');
                            $(searchDiv).find(".loader").css('display', 'none');
                        });

                        $("section .dropDown").click(function (e) {
                            e.preventDefault();
                            $(this).toggleClass('open');
                        });

                        $(".player-comparison__tools").focusout(function (e) {
                            e.preventDefault();
                            $("section .dropDown").removeClass('open');
                        });

                        $("input#player-comparison__search").bind('keyup', function () {
                            $(searchDiv).find(".loader").show();

                            let searchString = $(this).val();
                            let searchCount = 0;

                            $(searchDiv).find("ul.player-comparison__search-results").empty();
                            $(searchDiv).find(".js-search-results-notice").empty();
                            $(searchDiv).find(".loader").css('display', 'none');

                            if (searchString == '') {
                                return;
                            }

                            $("ul.player-comparison__filter-results li.player-comparison__result .player-comparison__result-name").each(function (index, value) {
                                let currentName = $(value).text();
                                let mainList = $("ul.player-comparison__search-results");

                                if (currentName.toUpperCase().indexOf(searchString.toUpperCase()) > -1) {
                                    $(mainList).append($(this).parent("li.player-comparison__result").clone());
                                    searchCount++;
                                }
                            });
                            $(searchDiv).find(".js-search-results-notice").html("<span>" + searchCount.toString() + "</span>Sonuç");
                            $(searchDiv).find(".js-search-results-notice").css('display', 'initial');
                            $(searchDiv).find(".loader").css('display', 'none');
                        });

                        $(document).on('click', 'li.player-comparison__result', function (e) {
                            e.preventDefault();

                            if ($('.player-comparison.modalActive').length <= 0) {
                                return;
                            }

                            let i = parseInt($('.player-comparison__player.active').attr("id").replace('player_', ''));

                            let oyuncu_id = $(this).data('player-id');
                            let oyuncu_image = $(this).data('player-image') ?? '';
                            let oyuncu_adi = $(this).find('.player-comparison__result-name').text().trim();
                            let oyuncu_pozisyon = $(this).find('.player-comparison__result-position').text().trim();

                            $('.player-comparison__player.active').html(`
                                <div class="player-comparison__player-card" data-player-id="` + oyuncu_id + `">
                                    <h3 class="player-comparison__player-name">` + oyuncu_adi + `</h3>
                                    <span class="player-comparison__player-pos">` + oyuncu_pozisyon + `</span>
                                    <div class="player-comparison__player-img-wrap player-comparison-editorial__player-img-wrap player-comparison-editorial__player-img-wrap--crop" style="top:15px;right: 15px">
                                        <img class="player-comparison__player-img" height="140" src="uploads/contacts/` + oyuncu_image + `" alt="` + oyuncu_adi + `" onerror="this.src='assets/frontend/img/Photo-Missing.png'"/>
                                    </div>
                                    <a class="player-comparison__player-switch" href="#" data-add-player="">
                                        <span class="player-comparison__change-icn" style="margin-top: -5px;"></span>
                                        <span class="player-comparison__change-label">Değiştir</span>
                                    </a>
                                </div>
                                <section class="rating-widget">
                                    <div class="rating-stars text-center">
                                        <ul class="stars" title="Verimlilik Puanına Göre">
                                            <li class="star" data-value="0.5"><i class="fa fa-star-half fa-fw"></i></li>
                                            <li class="star" data-value="1"><i class="fa fa-star-half fa-fw mirror"></i></li>
                                            <li class="star" data-value="1.5"><i class="fa fa-star-half fa-fw"></i></li>
                                            <li class="star" data-value="2"><i class="fa fa-star-half fa-fw mirror"></i></li>
                                            <li class="star" data-value="2.5"><i class="fa fa-star-half fa-fw"></i></li>
                                            <li class="star" data-value="3"><i class="fa fa-star-half fa-fw mirror"></i></li>
                                            <li class="star" data-value="3.5"><i class="fa fa-star-half fa-fw"></i></li>
                                            <li class="star" data-value="4"><i class="fa fa-star-half fa-fw mirror"></i></li>
                                            <li class="star" data-value="4.5"><i class="fa fa-star-half fa-fw"></i></li>
                                            <li class="star" data-value="5"><i class="fa fa-star-half fa-fw mirror"></i></li>
                                        </ul>
                                    </div>
                                </section>
                                <div class="player-filtrele">
                                <i class="filtrele-icon fa"></i>
                                <select class="form-control PLAYER_SEASON_FROM">
                                    <option value="-1" selected="">--Sezonlar--</option>
                                </select>
                                <br>
                                <select class="form-control PLAYER_DONEM_FROM">
                                    <option value="-1" selected="">--Dönemler--</option>
                                    <option value="0">Tam Dönem</option>
                                    <option value="1">1.Dönem</option>
                                    <option value="2">2.Dönem</option>
                                </select>
                                <br>
                                <select class="form-control PLAYER_TEAM_FROM">
                                    <option value="-1" selected="">--Takımlar--</option>
                                </select>
                                <br>
                                    <button class="player_getir" style="width:100%" data-index="` + i + `">Getir</button>
                                </div>
                            `);

                            $.ajax({
                                type: 'POST',
                                url: "{{ route('ajax-oyuncular-istatistik') }}",
                                data: "adsoyad=" + encodeURIComponent(oyuncu_adi) + "&ista_type=" + 0,
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function (response) {
                                    if (response != 'false') {
                                        let resultsDiv = $('.player-comparison__filter-results-scroll ul.player-comparison__filter-results');

                                        let oyuncular = JSON.parse(response);
                                        $.each(oyuncular, function(key, oyuncu) {

                                            for(let i=1; i<=5; i++) {
                                                if($('#player_' + i).hasClass('active')) {

                                                    printPlayerStat(oyuncu, i, 1);

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: "{{ route('ajax-oyuncu-listeler') }}",
                                                        data: "adsoyad=" + encodeURIComponent(oyuncu_adi),
                                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                        success: function (response) {
                                                            if (response != 'false') {

                                                                let oyuncuJson = JSON.parse(response);
                                                                let sezonlar = oyuncuJson.sezonlar;
                                                                let takimlar = oyuncuJson.takimlar;

                                                                let resultsDiv1 = $("#player_" + i + " select.PLAYER_SEASON_FROM");
                                                                resultsDiv1.html( `<option value="-1">--Sezonlar--</option>` );
                                                                $.each(sezonlar, function (key, data) {
                                                                    $(resultsDiv1).append(
                                                                        `<option value="` + encodeURIComponent(data.sezon) + `">` + data.sezon + `</option>`
                                                                    );
                                                                });

                                                                let resultsDiv2 = $("#player_" + i + " select.PLAYER_TEAM_FROM");
                                                                resultsDiv2.html( `<option value="-1">--Takımla---</option>` );
                                                                $.each(takimlar, function (key, data) {
                                                                    $(resultsDiv2).append(
                                                                        `<option value="` + encodeURIComponent(data.takimi) + `">` + data.takimi + `</option>`
                                                                    );
                                                                });

                                                            }
                                                        }
                                                    });


                                                    $(".player-comparison__stat-value span.pull-left:contains('-1')").css('margin-left', '5px');

                                                    $('#player-stats_1 .player-comparison__stat').css('backgroundColor', '#FFFFFF');
                                                    $('#player-stats_2 .player-comparison__stat').css('backgroundColor', '#FFFFFF');
                                                    $('#player-stats_3 .player-comparison__stat').css('backgroundColor', '#FFFFFF');
                                                    $('#player-stats_4 .player-comparison__stat').css('backgroundColor', '#FFFFFF');
                                                    $('#player-stats_5 .player-comparison__stat').css('backgroundColor', '#FFFFFF');

                                                    $('.player-comparison').removeClass('modalActive');

                                                    $('.player-comparison__accordion').css('display', 'block');

                                                    $('#player_1.player-comparison__player').addClass('inactive').removeClass('active');
                                                    $('#player_2.player-comparison__player').addClass('inactive').removeClass('active');
                                                    $('#player_3.player-comparison__player').addClass('inactive').removeClass('active');
                                                    $('#player_4.player-comparison__player').addClass('inactive').removeClass('active');
                                                    $('#player_5.player-comparison__player').addClass('inactive').removeClass('active');
                                                    $('#player_' + (i+1) + '.player-comparison__player').addClass('active').removeClass('inactive');


                                                    break;

                                                }
                                            }

                                        });

                                        $('.player-comparison').removeClass('modalActive');

                                    }
                                }
                            });

                        });


                        $(document).on('click', "#player_1 a.player-comparison__player-switch", function (e) {
                            e.preventDefault();

                            $('.player-comparison').addClass('modalActive');

                            $('#player_1').addClass('active').removeClass('inactive');
                            $('#player_2').addClass('inactive').removeClass('active');
                            $('#player_3').addClass('inactive').removeClass('active');
                            $('#player_4').addClass('inactive').removeClass('active');
                            $('#player_5').addClass('inactive').removeClass('active');
                        });
                        $(document).on('click', "#player_2 a.player-comparison__player-switch", function (e) {
                            e.preventDefault();

                            $('.player-comparison').addClass('modalActive');

                            $('#player_1').addClass('inactive').removeClass('active');
                            $('#player_2').addClass('active').removeClass('inactive');
                            $('#player_3').addClass('inactive').removeClass('active');
                            $('#player_4').addClass('inactive').removeClass('active');
                            $('#player_5').addClass('inactive').removeClass('active');
                        });
                        $(document).on('click', "#player_3 a.player-comparison__player-switch", function (e) {
                            e.preventDefault();

                            $('.player-comparison').addClass('modalActive');

                            $('#player_1').addClass('inactive').removeClass('active');
                            $('#player_2').addClass('inactive').removeClass('active');
                            $('#player_3').addClass('active').removeClass('inactive');
                            $('#player_4').addClass('inactive').removeClass('active');
                            $('#player_5').addClass('inactive').removeClass('active');
                        });
                        $(document).on('click', "#player_4 a.player-comparison__player-switch", function (e) {
                            e.preventDefault();

                            $('.player-comparison').addClass('modalActive');

                            $('#player_1').addClass('inactive').removeClass('active');
                            $('#player_2').addClass('inactive').removeClass('active');
                            $('#player_3').addClass('inactive').removeClass('active');
                            $('#player_4').addClass('active').removeClass('inactive');
                            $('#player_5').addClass('inactive').removeClass('active');
                        });
                        $(document).on('click', "#player_5 a.player-comparison__player-switch", function (e) {
                            e.preventDefault();

                            $('.player-comparison').addClass('modalActive');

                            $('#player_1').addClass('inactive').removeClass('active');
                            $('#player_2').addClass('inactive').removeClass('active');
                            $('#player_3').addClass('inactive').removeClass('active');
                            $('#player_4').addClass('inactive').removeClass('active');
                            $('#player_5').addClass('active').removeClass('inactive');
                        });

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('ajax-oyuncular') }}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (response) {
                                if (response != 'false') {
                                    let resultsDiv = $('.player-comparison__filter-results-scroll ul.player-comparison__filter-results');

                                    let oyuncular = JSON.parse(response);
                                    $.each(oyuncular, function(key, oyuncu) {

                                        $(resultsDiv).append(
                                            `<li class="player-comparison__result" data-player-id="` + oyuncu.id + `" data-player-image="` + oyuncu.photo + `">
                                                <span class="player-comparison__plus player-comparison__plus--sm">+</span>
                                                <span class="player-comparison__result-name">` + oyuncu.adsoyad + `</span>
                                                <span class="player-comparison__result-position">` + (oyuncu.ana_pozisyon=='' ? '-' : oyuncu.ana_pozisyon) + ' / ' + (oyuncu.yan_pozisyon=='' ? '-' : oyuncu.yan_pozisyon) + `</span>
                                            </li>`
                                        );
                                    });

                                }
                            }
                        });



                        $(document).on('click', "button.player_getir", function (e) {
                            e.preventDefault();

                            let index = $(this).data('index');
                            let oyuncu_adi = $('#player_' + index + " .player-comparison__player-name").text().trim();
                            let sezonid = $('#player_' + index + " select.PLAYER_SEASON_FROM").val();
                            let donemid = $('#player_' + index + " select.PLAYER_DONEM_FROM").val();
                            let takimid = $('#player_' + index + " select.PLAYER_TEAM_FROM").val();

                            $.ajax({
                                type: 'POST',
                                url: "/ajax-oyuncu-istatistik/takim/" + encodeURIComponent(takimid) + "/sezon/" + encodeURIComponent(sezonid) + "/donem/" + encodeURIComponent(donemid),
                                data: "adsoyad=" + encodeURIComponent(oyuncu_adi),
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function (response) {
                                    if (response != 'false') {
                                        $('#player-stats_' + index).html(`
                                            <div class="player-comparison__stat" row-id="1"> </div>
                                        `);

                                        let oyuncu = JSON.parse(response);
                                        printPlayerStat(oyuncu[0], index, 0);
                                    }
                                    else {
                                        $('#player-stats_' + index).html(`
                                            <div class="player-comparison__stat" row-id="1"> </div>
                                        `);
                                    }
                                }
                            });
                        });


                        $(document).on('click', "button.filtrele", function (e) {
                            e.preventDefault();

                            let sezonid = $("select[id='BASKETBALL_SEASON_FROM']").val();
                            let donemid = $("select[id='BASKETBALL_DONEM_FROM']").val();
                            let takimid = $("select[id='BASKETBALL_TEAM_FROM']").val();
                            let anapozisyonid = $("select[id='BASKETBALL_POSITION_FROM']").val();
                            let yanpozisyonid = $("select[id='BASKETBALL_POSITION2_FROM']").val();


                            $.ajax({
                                type: 'POST',
                                url: "/ajax-oyuncular/takim/" + encodeURIComponent(takimid) + "/sezon/" + encodeURIComponent(sezonid) + "/donem/" + encodeURIComponent(donemid) + "/anapozisyon/" + encodeURIComponent(anapozisyonid) + "/yanpozisyon/" + encodeURIComponent(yanpozisyonid),
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function (response) {
                                    if (response != 'false') {
                                        let resultsDiv = $('.player-comparison__filter-results-scroll ul.player-comparison__filter-results');
                                        resultsDiv.empty();

                                        let oyuncular = JSON.parse(response);
                                        $.each(oyuncular, function(key, oyuncu) {

                                            $(resultsDiv).append(
                                                `<li class="player-comparison__result" data-player-id="` + oyuncu.id + `" data-player-image="` + oyuncu.photo + `">
                                                    <span class="player-comparison__plus player-comparison__plus--sm">+</span>
                                                    <span class="player-comparison__result-name">` + oyuncu.adsoyad + `</span>
                                                    <span class="player-comparison__result-position">` + oyuncu.ana_pozisyon + ' / ' + oyuncu.yan_pozisyon + `</span>
                                                </li>`
                                            );
                                        });

                                    }
                                    else {
                                        $('.player-comparison__filter-results-scroll ul.player-comparison__filter-results').empty();
                                    }
                                }
                            });
                        });


                        $(document)
                            .on('mouseenter', ".player-filtrele", function () {
                                $(this).addClass('active');
                            })
                            .on('mouseleave', ".player-filtrele", function () {
                                $(this).removeClass('active');
                            });

                        $(document)
                            .on('click', "#content", function (e) {
                                e.preventDefault();
                                if($('.player-filtrele:not(.active)').innerHeight() > 50) {
                                    $('.player-filtrele:not(.active)').animate({height: '56px'}, 500);
                                }
                            })
                            .on('click', ".player-filtrele.active i.filtrele-icon", function (e) {
                                e.preventDefault();

                                if($(this).parent('.player-filtrele.active').innerHeight() < 250) {
                                    $(this).parent('.player-filtrele').animate({height: '280px'}, 500);
                                }
                                else {
                                    $(this).parent('.player-filtrele.active').animate({height: '56px'}, 500);
                                }
                            });


                        $(document).on('mouseover', "#player-stats_1 .player-comparison__stat, #player-stats_2 .player-comparison__stat, #player-stats_3 .player-comparison__stat, #player-stats_4 .player-comparison__stat, #player-stats_5 .player-comparison__stat", function () {
                            $("#player-stats_1 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#c8e4e56e');
                            $("#player-stats_2 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#c8e4e56e');
                            $("#player-stats_3 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#c8e4e56e');
                            $("#player-stats_4 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#c8e4e56e');
                            $("#player-stats_5 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#c8e4e56e');
                        });
                        $(document).on('mouseleave', "#player-stats_1 .player-comparison__stat, #player-stats_2 .player-comparison__stat, #player-stats_3 .player-comparison__stat, #player-stats_4 .player-comparison__stat, #player-stats_5 .player-comparison__stat", function () {
                            $("#player-stats_1 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#FFFFFF');
                            $("#player-stats_2 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#FFFFFF');
                            $("#player-stats_3 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#FFFFFF');
                            $("#player-stats_4 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#FFFFFF');
                            $("#player-stats_5 .player-comparison__stat[row-id='" + $(this).attr('row-id') + "']").css('background-color', '#FFFFFF');
                        });


                        $('button#addComparison').click(function (e) {
                            e.preventDefault();

                            if($('#player_1').hasClass('hide')) { $('#player_1').removeClass('hide'); }
                            else if($('#player_2').hasClass('hide')) { $('#player_2').removeClass('hide'); }
                            else if($('#player_3').hasClass('hide')) { $('#player_3').removeClass('hide'); }
                            else if($('#player_4').hasClass('hide')) { $('#player_4').removeClass('hide'); }
                            else if($('#player_5').hasClass('hide')) { $('#player_5').removeClass('hide'); $(this).addClass('hide'); }
                        });

                    }
                @endif
            });

            function printPlayerStat(oyuncu, i, s) {
                $('#player-stats_' + i).html(`
                    <div class="player-comparison__stat" row-id="1">
                        <div class="player-comparison__stat-name">Sezon:</div>
                        <div class="player-comparison__stat-value sezon" style="text-align: center;height: 5.9rem;padding-top: 18px;">
                            <span>` + oyuncu.sezon + `</span>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="2">
                        <div class="player-comparison__stat-name">Süre:</div>
                        <div class="player-comparison__stat-value toplamsure">
                            <span class="pull-right">` + oyuncu.ortalamasure + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamasure.split(':').reduce(function (seconds, v){return +v + seconds * 60;}, 0)/60/(oyuncu.toplamsure.split(':').reduce(function (seconds, v){return +v + seconds * 60;}, 0)/60==0 ? 1 : oyuncu.toplamsure.split(':').reduce(function (seconds, v){return +v + seconds * 60;}, 0)/60)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="3">
                        <div class="player-comparison__stat-name">Sayı:</div>
                        <div class="player-comparison__stat-value toplamsayi">
                            <span class="pull-right">` + (oyuncu.ortalamasayi==0&&s==1 ? '-' : oyuncu.ortalamasayi) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamasayi/(oyuncu.toplamsayi==0 ? 1 : oyuncu.toplamsayi)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="4">
                        <div class="player-comparison__stat-name">Serbest Atış:</div>
                        <div class="player-comparison__stat-value toplamsa">
                            <span class="pull-right">` + (oyuncu.ortalamaSA=='0%'&&s==1 ? '-' : oyuncu.ortalamaSA) + `</span>
                            <div class="bar-container" style="width:` + oyuncu.ortalamaSA + `">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="5">
                        <div class="player-comparison__stat-name">2 Sayı:</div>
                        <div class="player-comparison__stat-value toplams2">
                            <span class="pull-right">` + (oyuncu.ortalamaS2=='0%'&&s==1 ? '-' : oyuncu.ortalamaS2) + `</span>
                            <div class="bar-container" style="width:` + oyuncu.ortalamaS2 + `">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="6">
                        <div class="player-comparison__stat-name">3 Sayı:</div>
                        <div class="player-comparison__stat-value toplams3">
                            <span class="pull-right">` + (oyuncu.ortalamaS3=='0%'&&s==1 ? '-' : oyuncu.ortalamaS3) + `</span>
                            <div class="bar-container" style="width:` + oyuncu.ortalamaS3 + `">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="7">
                        <div class="player-comparison__stat-name">Savunma Ribaundu:</div>
                        <div class="player-comparison__stat-value toplamsr">
                            <span class="pull-right">` + (oyuncu.ortalamaSR==0&&s==1 ? '-' : oyuncu.ortalamaSR) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaSR/(oyuncu.toplamSR==0 ? 1 : oyuncu.toplamSR)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="8">
                        <div class="player-comparison__stat-name">Hücum Ribaundu:</div>
                        <div class="player-comparison__stat-value toplamhr">
                            <span class="pull-right">` + (oyuncu.ortalamaHR==0&&s==1 ? '-' : oyuncu.ortalamaHR) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaHR/(oyuncu.toplamHR==0 ? 1 : oyuncu.toplamHR)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="9">
                        <div class="player-comparison__stat-name">Toplam Ribaund:</div>
                        <div class="player-comparison__stat-value toplamtr">
                            <span class="pull-right">` + (oyuncu.ortalamaTR==0&&s==1 ? '-' : oyuncu.ortalamaTR) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaTR/(oyuncu.toplamTR==0 ? 1 : oyuncu.toplamTR)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="10">
                        <div class="player-comparison__stat-name">Asist:</div>
                        <div class="player-comparison__stat-value toplamas">
                            <span class="pull-right">` + (oyuncu.ortalamaAS==0&&s==1 ? '-' : oyuncu.ortalamaAS) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaAS/(oyuncu.toplamAS==0 ? 1 : oyuncu.toplamAS)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="11">
                        <div class="player-comparison__stat-name">Top Çalma:</div>
                        <div class="player-comparison__stat-value toplamtc">
                            <span class="pull-right">` + (oyuncu.ortalamaTÇ==0&&s==1 ? '-' : oyuncu.ortalamaTÇ) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaTÇ/(oyuncu.toplamTÇ==0 ? 1 : oyuncu.toplamTÇ)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="12">
                        <div class="player-comparison__stat-name">Top Kaybı:</div>
                        <div class="player-comparison__stat-value toplamtk">
                            <span class="pull-right">` + (oyuncu.ortalamaTK==0&&s==1 ? '-' : oyuncu.ortalamaTK) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaTK/(oyuncu.toplamTK==0 ? 1 : oyuncu.toplamTK)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="13">
                        <div class="player-comparison__stat-name">Blok:</div>
                        <div class="player-comparison__stat-value toplambl">
                            <span class="pull-right">` + (oyuncu.ortalamaBL==0&&s==1 ? '-' : oyuncu.ortalamaBL) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaBL/(oyuncu.toplamBL==0 ? 1 : oyuncu.toplamBL)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="14">
                        <div class="player-comparison__stat-name">Faul:</div>
                        <div class="player-comparison__stat-value toplamfa">
                            <span class="pull-right">` + (oyuncu.ortalamaFA==0&&s==1 ? '-' : oyuncu.ortalamaFA) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaFA/(oyuncu.toplamFA==0 ? 1 : oyuncu.toplamFA)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="player-comparison__stat" row-id="15">
                        <div class="player-comparison__stat-name">Verimlilik Puanı:</div>
                        <div class="player-comparison__stat-value toplamvp">
                            <span class="pull-right">` + (oyuncu.ortalamaVP==0&&s==1 ? '-' : oyuncu.ortalamaVP) + `</span>
                            <div class="bar-container" style="width:` + (oyuncu.ortalamaVP/(oyuncu.toplamVP==0 ? 1 : oyuncu.toplamVP)*100) + `%">
                                <span class="bar-inside bar-right bg-c-league text-right">
                                </span>
                            </div>
                        </div>
                    </div>
                `);

                let max_vp = 0;
                let player_vp = [];

                for(let i=1; i<=5; i++) {
                    let vp = parseFloat($('#player-stats_' + i + ' .toplamvp span').text());
                    vp =(vp==-1 || vp==0 || vp=='-' ? 0 : vp);
                    player_vp.push(Number.isNaN(vp) ? 0 : vp);
                }

                max_vp = Math.max(...player_vp);

                for(let i=1; i<=5; i++) {
                    printStar(i, player_vp[i-1],  max_vp);
                }

            }


            function printStar(kolon, value, max) {

                $('#player_' + kolon + ' .stars .star').find('i').css('color', '#CCC');

                value = Math.round(value * 5 / max / 0.5) * 0.5;
                let rate = parseFloat(value).toFixed(1);
                $('#player_' + kolon + ' .stars .star').each(function () {
                    if(parseFloat($(this).data('value')) <= rate) {
                        $(this).find('i').css('color', '#FFCC36');
                    }
                });
            }

        </script>
    </section>

@endsection
@section('footerInclude')
    @if(count($Topic->maps) >0)
        @foreach($Topic->maps->slice(0,1) as $map)
            <?php
            $MapCenter = $map->longitude . "," . $map->latitude;
            ?>
        @endforeach
        <?php
        $map_title_var = "title_" . @Helper::currentLanguage()->code;
        $map_details_var = "details_" . @Helper::currentLanguage()->code;
        ?>
        <script type="text/javascript"
                src="//maps.google.com/maps/api/js?key={{ env("GOOGLE_MAPS_KEY") }}"></script>

        <script type="text/javascript">
            // var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';
            var iconURLPrefix = "{{ asset('assets/dashboard/images/')."/" }}";
            var icons = [
                iconURLPrefix + 'marker_0.png',
                iconURLPrefix + 'marker_1.png',
                iconURLPrefix + 'marker_2.png',
                iconURLPrefix + 'marker_3.png',
                iconURLPrefix + 'marker_4.png',
                iconURLPrefix + 'marker_5.png',
                iconURLPrefix + 'marker_6.png'
            ]

            var locations = [
                    @foreach($Topic->maps as $map)
                ['<?php echo "<strong>" . $map->$map_title_var . "</strong>" . "<br>" . $map->$map_details_var; ?>', <?php echo $map->longitude; ?>, <?php echo $map->latitude; ?>, <?php echo $map->id; ?>, <?php echo $map->icon; ?>],
                @endforeach
            ];

            var map = new google.maps.Map(document.getElementById('google-map'), {
                zoom: 6,
                draggable: false,
                scrollwheel: false,
                center: new google.maps.LatLng(<?php echo $MapCenter; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    icon: icons[locations[i][4]],
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        </script>
    @endif
    <script type="text/javascript">

        jQuery(document).ready(function ($) {
            "use strict";

            @if($WebmasterSection->comments_status)
            //Comment
            $('form.commentForm').submit(function () {

                var f = $(this).find('.form-group'),
                    ferror = false,
                    emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

                f.children('input').each(function () { // run all inputs

                    var i = $(this); // current input
                    var rule = i.attr('data-rule');

                    if (rule !== undefined) {
                        var ierror = false; // error flag for current input
                        var pos = rule.indexOf(':', 0);
                        if (pos >= 0) {
                            var exp = rule.substr(pos + 1, rule.length);
                            rule = rule.substr(0, pos);
                        } else {
                            rule = rule.substr(pos + 1, rule.length);
                        }

                        switch (rule) {
                            case 'required':
                                if (i.val() === '') {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'minlen':
                                if (i.val().length < parseInt(exp)) {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'email':
                                if (!emailExp.test(i.val())) {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'checked':
                                if (!i.attr('checked')) {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'regexp':
                                exp = new RegExp(exp);
                                if (!exp.test(i.val())) {
                                    ferror = ierror = true;
                                }
                                break;
                        }
                        i.next('.validation').html('<i class=\"fa fa-info\"></i> &nbsp;' + (ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show();
                        !ierror ? i.next('.validation').hide() : i.next('.validation').show();
                    }
                });
                f.children('textarea').each(function () { // run all inputs

                    var i = $(this); // current input
                    var rule = i.attr('data-rule');

                    if (rule !== undefined) {
                        var ierror = false; // error flag for current input
                        var pos = rule.indexOf(':', 0);
                        if (pos >= 0) {
                            var exp = rule.substr(pos + 1, rule.length);
                            rule = rule.substr(0, pos);
                        } else {
                            rule = rule.substr(pos + 1, rule.length);
                        }

                        switch (rule) {
                            case 'required':
                                if (i.val() === '') {
                                    ferror = ierror = true;
                                }
                        o        break;

                            case 'minlen':
                                if (i.val().length < parseInt(exp)) {
                                    ferror = ierror = true;
                                }
                                break;
                        }
                        i.next('.validation').html('<i class=\"fa fa-info\"></i> &nbsp;' + (ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show();
                        !ierror ? i.next('.validation').hide() : i.next('.validation').show();
                    }
                });
                if (ferror) return false;
                else var str = $(this).serialize();
                var xhr = $.ajax({
                    type: "POST",
                    url: "{{ route("commentSubmit") }}",
                    data: str,
                    success: function (msg) {
                        if (msg == 'OK') {
                            $("#sendmessage").addClass("show");
                            $("#errormessage").removeClass("show");
                            $("#comment_name").val('');
                            $("#comment_email").val('');
                            $("#comment_message").val('');
                        } else {
                            $("#sendmessage").removeClass("show");
                            $("#errormessage").addClass("show");
                            $('#errormessage').html(msg);
                        }

                    }
                });
                console.log(xhr);
                return false;
            });
            @endif

            @if($WebmasterSection->order_status)

            //Order
            $('form.orderForm').submit(function () {

                var f = $(this).find('.form-group'),
                    ferror = false,
                    emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

                f.children('input').each(function () { // run all inputs

                    var i = $(this); // current input
                    var rule = i.attr('data-rule');

                    if (rule !== undefined) {
                        var ierror = false; // error flag for current input
                        var pos = rule.indexOf(':', 0);
                        if (pos >= 0) {
                            var exp = rule.substr(pos + 1, rule.length);
                            rule = rule.substr(0, pos);
                        } else {
                            rule = rule.substr(pos + 1, rule.length);
                        }

                        switch (rule) {
                            case 'required':
                                if (i.val() === '') {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'minlen':
                                if (i.val().length < parseInt(exp)) {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'email':
                                if (!emailExp.test(i.val())) {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'checked':
                                if (!i.attr('checked')) {
                                    ferror = ierror = true;
                                }
                                break;

                            case 'regexp':
                                exp = new RegExp(exp);
                                if (!exp.test(i.val())) {
                                    ferror = ierror = true;
                                }
                                break;
                        }
                        i.next('.validation').html('<i class=\"fa fa-info\"></i> &nbsp;' + (ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show();
                        !ierror ? i.next('.validation').hide() : i.next('.validation').show();
                    }
                });
                if (ferror) return false;
                else var str = $(this).serialize();
                var xhr = $.ajax({
                    type: "POST",
                    url: "{{ route("orderSubmit") }}",
                    data: str,
                    success: function (msg) {
                        if (msg == 'OK') {
                            $("#ordersendmessage").addClass("show");
                            $("#ordererrormessage").removeClass("show");
                            $("#order_name").val('');
                            $("#order_phone").val('');
                            $("#order_email").val('');
                            $("#order_message").val('');
                        } else {
                            $("#ordersendmessage").removeClass("show");
                            $("#ordererrormessage").addClass("show");
                            $('#ordererrormessage').html(msg);
                        }

                    }
                });
                //console.log(xhr);
                return false;
            });

            @endif
        });
    </script>

@endsection
