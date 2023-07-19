@extends('dashboard.layouts.master')
@section('title', __('backend.newsletter'))
@section('content')
    <div class="padding">
        <div class="app-body-inner">
            <div class="row-col row-col-xs">
            @include('dashboard.contacts.groups')
            <!-- column -->
                <div class="col-sm-4 col-md-3 bg b-r">
                    <div class="row-col">
                        <div class="p-a-xs b-b">
                            @if($tableName == "contact")
                                {{Form::open(['route'=>['contactsSearch'],'method'=>'POST'])}}
                            @elseif($tableName == "sezon")
                                {{Form::open(['route'=>['sezonsSearch'],'method'=>'POST'])}}
                            @elseif($tableName == "lig")
                                {{Form::open(['route'=>['ligsSearch'],'method'=>'POST'])}}
                            @elseif($tableName == "takim")
                                {{Form::open(['route'=>['takimsSearch'],'method'=>'POST'])}}
                            @endif

                            <div class="input-group">
                                @if($tableName == "contact")
                                    <input type="text" style="width: 85%" name="q" value="{{ $search_word }}" class="form-control no-border no-bg"
                                       placeholder="{{ __('backend.searchAllContacts') }}">
                                @elseif($tableName == "sezon")
                                    <input type="text" style="width: 85%" name="q" value="{{ $search_word }}" class="form-control no-border no-bg"
                                       placeholder="{{ __('backend.searchAllSezons') }}">
                                @elseif($tableName == "lig")
                                    <input type="text" style="width: 85%" name="q" value="{{ $search_word }}" class="form-control no-border no-bg"
                                       placeholder="{{ __('backend.searchAllLigs') }}">
                                @elseif($tableName == "takim")
                                    <input type="text" style="width: 85%" name="q" value="{{ $search_word }}" class="form-control no-border no-bg"
                                       placeholder="{{ __('backend.searchAllTakims') }}">
                                @endif

                                <button type="submit" style="padding-top: 10px;"
                                        class="input-group-addon no-border no-shadow no-bg pull-left"><i class="fa fa-search"></i>
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                        <div class="row-row">
                            <div class="row-body scrollable hover">
                                <div class="row-inner">
                                    <div class="list inset">

                                        @if($tableName == "contact")
                                            @foreach($Contacts as $Contact)
                                                <?php $active_cls = ""; ?>
                                                @if(Session::has('ContactToEdit'))
                                                    @if(Session::get('ContactToEdit')->id == $Contact->id)
                                                        <?php $active_cls = "primary"; ?>
                                                    @endif
                                                @endif

                                                <div class="list-item pointer {{$active_cls}}" onclick="location.href='{{ route("contactsEdit",["id"=>$Contact->id]) }}'">
                                                    <div class="list-left">
                                                        <span class="w-40 avatar">
                                                            <a href="{{ route("contactsEdit",["id"=>$Contact->id]) }}">
                                                                @if($Contact->photo!="")
                                                                    <img src="{{ asset('uploads/contacts/'.$Contact->photo) }}" class="img-circle">
                                                                @else
                                                                    <img src="{{ asset('uploads/contacts/bos_profil.png') }}" class="img-circle" style="opacity: 0.5">
                                                                @endif
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="list-body">
                                                        <a href="{{ route("contactsEdit", ["id"=>$Contact->id]) }}">
                                                            {{ $Contact->adsoyad }}
                                                            <small class="block"><i class="fa fa-home m-r-sm text-muted"></i>
                                                                <span dir="ltr">
                                                                    @if(date('m') <= 6)
                                                                        {{ $Contact->{'takim_'.date('y').'_'.(date('y')+1).'_1'} }}
                                                                    @else
                                                                        {{ $Contact->{'takim_'.date('y').'_'.(date('y')+1).'_2'} }}
                                                                    @endif
                                                                </span>
                                                            </small>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($tableName == "sezon")
                                            <?php $rowCounts = 0; ?>
                                            @foreach($Sezons as $Sezon)
                                                <?php $active_cls = ""; ?>
                                                @if(Session::has('ContactToEdit'))
                                                    @if(Session::get('ContactToEdit')->id == $Sezon->id)
                                                        <?php $active_cls = "primary"; ?>
                                                    @endif
                                                @endif

                                                <div class="list-item pointer {{$active_cls}}" onclick="location.href='{{ route("sezonsEdit",["id"=>$Sezon->id]) }}'">
                                                    <div class="list-left">
                                                        <span class="w-40 avatar">{{++$rowCounts}} </span>
                                                    </div>
                                                    <div class="list-body" style="margin-top: 10px;">
                                                        <a href="{{ route("sezonsEdit", ["id"=>$Sezon->id]) }}"> {{ $Sezon->adi }} </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($tableName == "lig")
                                            <?php $rowCounts = 0; ?>
                                            @foreach($Ligs as $Lig)
                                                <?php $active_cls = ""; ?>
                                                @if(Session::has('ContactToEdit'))
                                                    @if(Session::get('ContactToEdit')->id == $Lig->id)
                                                        <?php $active_cls = "primary"; ?>
                                                    @endif
                                                @endif

                                                <div class="list-item pointer {{$active_cls}}" onclick="location.href='{{ route("ligsEdit",["id"=>$Lig->id]) }}'">
                                                    <div class="list-left">
                                                        <span class="w-40 avatar">{{++$rowCounts}} </span>
                                                    </div>
                                                    <div class="list-body" style="margin-top: 10px;">
                                                        <a href="{{ route("ligsEdit", ["id"=>$Lig->id]) }}"> {{ $Lig->adi }} </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($tableName == "takim")
                                            <?php $rowCounts = 0; ?>
                                            @foreach($Takims as $Takim)
                                                <?php $active_cls = ""; ?>
                                                @if(Session::has('ContactToEdit'))
                                                    @if(Session::get('ContactToEdit')->id == $Takim->id)
                                                        <?php $active_cls = "primary"; ?>
                                                    @endif
                                                @endif

                                                <div class="list-item pointer {{$active_cls}}" onclick="location.href='{{ route("takimsEdit",["id"=>$Takim->id]) }}'">
                                                    <div class="list-left">
                                                        <span class="w-40 avatar">{{++$rowCounts}} </span>
                                                    </div>
                                                    <div class="list-body" style="margin-top: 10px;">
                                                        <a href="{{ route("takimsEdit", ["id"=>$Takim->id]) }}"> {{ $Takim->adi }} </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($tableName == "contact")
                            @if($Contacts->total() > env('BACKEND_PAGINATION'))
                                <div class="p-a b-t text-center">
                                    {!! $Contacts->links() !!}
                                </div>
                            @endif

                        @elseif($tableName == "sezon")
                            @if($Sezons->total() > env('BACKEND_PAGINATION'))
                                <div class="p-a b-t text-center">
                                    {!! $Sezons->links() !!}
                                </div>
                            @endif

                        @elseif($tableName == "lig")
                            @if($Ligs->total() > env('BACKEND_PAGINATION'))
                                <div class="p-a b-t text-center">
                                    {!! $Ligs->links() !!}
                                </div>
                            @endif

                        @elseif($tableName == "takim")
                            @if($Takims->total() > env('BACKEND_PAGINATION'))
                                <div class="p-a b-t text-center">
                                    {!! $Takims->links() !!}
                                </div>
                            @endif

                        @endif
                    </div>
                </div>
                <!-- /column -->

                @if(Session::has('ContactToEdit'))
                    @include('dashboard.contacts.edit')
                @else
                    @include('dashboard.contacts.create')
                @endif

            </div>
        </div>
    </div>
    <style>
        .app-footer {
            display: none;
        }
    </style>
@endsection
@push("after-scripts")
    <script type="text/javascript">
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#photo_preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#photo_file").change(function () {
            readURL(this);
            $('#photo_preview').css("opacity", 1);
        });
    </script>
@endpush
