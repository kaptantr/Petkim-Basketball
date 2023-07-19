<!-- column -->
<div class="col-sm-6 col-md-7">
    <div class="row-col">
        <br>
        <div class="p-a-sm">
            <div>
                @if($tableName == "contact")
                    <h6 class="m-b-0 m-t-sm"><i class="material-icons"> &#xe3c9;</i> {{ __('backend.editContacts') }}</h6>
                @elseif($tableName == "sezon")
                    <h6 class="m-b-0 m-t-sm"><i class="material-icons"> &#xe3c9;</i> {{ __('backend.editSezons') }}</h6>
                @elseif($tableName == "lig")
                    <h6 class="m-b-0 m-t-sm"><i class="material-icons"> &#xe3c9;</i> {{ __('backend.editLigs') }}</h6>
                @elseif($tableName == "takim")
                    <h6 class="m-b-0 m-t-sm"><i class="material-icons"> &#xe3c9;</i> {{ __('backend.editTakims') }}</h6>
                @endif
            </div>
        </div>
        <br><br>
        <div class="row-row">
            <div class="row-body">
                <div class="row-inner">
                    <div class="padding p-y-sm">
                        @if(Session::has('doneMessage2'))
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        {{ Session::get('doneMessage2') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($tableName == "contact")
                            {{Form::open(['route'=>['contactsUpdate',Session::get('ContactToEdit')->id],'method'=>'POST', 'files' => true])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="col-sm-3">
                                    <div class="avatar w-64 inline">
                                        @if(Session::get('ContactToEdit')->photo !="")
                                            <img id="photo_preview" src="{{ asset('uploads/contacts/'.Session::get('ContactToEdit')->photo) }}">
                                        @else
                                            <img id="photo_preview" src="{{ asset('uploads/contacts/bos_profil.png') }}" style="opacity: 0.2">
                                        @endif
                                    </div>
                                    <div class="form-file inline">
                                        <input id="photo_file" type="file" name="file" accept="image/*">
                                        <button class="btn white btn-sm">
                                            <small>
                                                <small>{{ __('backend.selectFile') }} ..</small>
                                            </small>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-9 v-m h2 _300">
                                    <div class="p-l-xs" style="display:flex">
                                        {!! Form::text('adsoyad',Session::get('ContactToEdit')->adsoyad, array('placeholder' =>__('backend.adsoyad'), 'class' => 'form-control inline', 'id'=>'adsoyad', 'required'=>'')) !!}
                                    </div>
                                </div>
                            </div>
                            <!-- fields -->
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.pozisyon') !!}:</label>
                                    <div class="col-sm-9">
                                        <select name="ana_pozisyon" id="ana_pozisyon" class="form-control select2 select2-hidden-accessible"
                                                ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value=""> - </option>
                                            @foreach ($pozisyonlar as $pozisyon)
                                                <option value="{{ $pozisyon->adi }}" {{ Session::get('ContactToEdit')->ana_pozisyon==$pozisyon->adi ? 'selected' : '' }}>
                                                    {{ $pozisyon->adi }} ({{ $pozisyon->title }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.yanpozisyon') !!}:</label>
                                    <div class="col-sm-9">
                                        <select name="yan_pozisyon" id="yan_pozisyon" class="form-control select2 select2-hidden-accessible"
                                                ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value=""> - </option>
                                            @foreach ($pozisyonlar as $pozisyon)
                                                <option value="{{ $pozisyon->adi }}" {{ Session::get('ContactToEdit')->yan_pozisyon==$pozisyon->adi ? 'selected' : '' }}>
                                                    {{ $pozisyon->adi }} ({{ $pozisyon->title }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.alt_kimlik') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('alt_kimlik', Session::get('ContactToEdit')->alt_kimlik, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'alt_kimlik')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.boy') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('boy', Session::get('ContactToEdit')->boy, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'boy')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.dogum_tarihi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::date('dogum_tarihi', Session::get('ContactToEdit')->dogum_tarihi, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'dogum_tarihi')) !!}
                                    </div>
                                </div>

                                @for($i=16; $i<=34; $i++)
                                    <?php
                                    $name1 = 'takim_'.$i.'_'.($i+1).'_1';
                                    $name2 = 'takim_'.$i.'_'.($i+1).'_2';
                                    ?>
                                    @if(!empty(Session::get('ContactToEdit')->{$name1}) || $i==date('y')-1 || $i==date('y') || $i<date('y'))
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">{!!  __('backend.takim') !!} ({{'20'.$i.'-'.($i+1)}}):</label>
                                            <div class="col-sm-4">
                                                <select name="{{ $name1 }}" id="{{ $name1 }}" class="form-control select2 select2-hidden-accessible"
                                                        ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                                    <option value=""> - </option>
                                                    @foreach ($takimlar as $takim)
                                                        <option value="{{ $takim->adi }}" {{ Session::get('ContactToEdit')->{$name1}==$takim->adi ? 'selected' : '' }}>{{ $takim->adi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-1" style="text-align:center;margin-top:5px"><b>-</b></div>
                                            <div class="col-sm-4">
                                                <select name="{{ $name2 }}" id="{{ $name2 }}" class="form-control select2 select2-hidden-accessible"
                                                        ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                                    <option value=""> - </option>
                                                    @if(empty(Session::get('ContactToEdit')->{$name2}))
                                                    @else
                                                        @foreach ($takimlar as $takim)
                                                            <option value="{{ $takim->adi }}" {{ Session::get('ContactToEdit')->{$name2}==$takim->adi ? 'selected' : '' }}>{{ $takim->adi }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @endfor

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.menajer') !!}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('menajer', Session::get('ContactToEdit')->menajer, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'menajer')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.menajer_email') !!}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('menajer_email', Session::get('ContactToEdit')->menajer_email, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'menajer_email')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.menajer_telefon') !!}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('menajer_telefon', Session::get('ContactToEdit')->menajer_telefon, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'menajer_telefon')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.oyuncu_ozellikleri') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::textarea('oyuncu_ozellikleri', Session::get('ContactToEdit')->oyuncu_ozellikleri, array('placeholder' => '', 'class' => 'form-control', 'rows'=>'6')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.aktif') }}:</label>
                                    <div class="col-sm-9">
                                        <div class="checkbox">
                                            <label class="ui-check" style="transform:scale(1.5);padding-left:25px">
                                                {!! Form::checkbox('status', Session::get('ContactToEdit')->status, '1', array('id' => 'status')) !!}
                                                <i class="dark-white"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button class="btn warning pull-right" data-toggle="modal"
                                                    data-target="#mc-{{ Session::get('ContactToEdit')->id }}"
                                                    ui-toggle-class="bounce"
                                                    ui-target="#animate">
                                                <small><i class="material-icons"> &#xe872;</i> {{ __('backend.deleteContacts') }} </small>
                                            </button>
                                    @endif
                                    <!-- .modal -->
                                        <div id="mc-{{ Session::get('ContactToEdit')->id }}"
                                             class="modal fade"
                                             data-backdrop="true">
                                            <div class="modal-dialog" id="animate">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                    </div>
                                                    <div class="modal-body text-center p-lg">
                                                        <p>
                                                            {{ __('backend.confirmationDeleteMsg') }}
                                                            <br>
                                                            <strong>[ {{ Session::get('ContactToEdit')->first_name }}  {{ Session::get('ContactToEdit')->last_name }}
                                                                ]</strong>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">{{ __('backend.no') }}</button>
                                                        <a href="{{ route("contactsDestroy",["id"=>Session::get('ContactToEdit')->id]) }}"
                                                           class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <!-- / .modal -->

                                        <button type="submit" class="btn btn-primary"><i class="material-icons"> &#xe31b;</i> {!! __('backend.save') !!}</button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif

                        @if($tableName == "sezon")
                            {{Form::open(['route'=>['sezonsUpdate',Session::get('ContactToEdit')->id],'method'=>'POST'])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.sezonAdi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('adi', Session::get('ContactToEdit')->adi, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'adi')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button class="btn warning pull-right" data-toggle="modal"
                                                    data-target="#mc-{{ Session::get('ContactToEdit')->id }}"
                                                    ui-toggle-class="bounce"
                                                    ui-target="#animate">
                                                <small> <i class="material-icons"> &#xe872;</i> {{ __('backend.deleteSezons') }} </small>
                                            </button>
                                        @endif
                                        <!-- .modal -->
                                        <div id="mc-{{ Session::get('ContactToEdit')->id }}" class="modal fade" data-backdrop="true">
                                            <div class="modal-dialog" id="animate">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                    </div>
                                                    <div class="modal-body text-center p-lg">
                                                        <p>
                                                            {{ __('backend.confirmationDeleteMsg') }}
                                                            <br>
                                                            <strong>[ {{ Session::get('ContactToEdit')->adi }} ]</strong>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">{{ __('backend.no') }}</button>
                                                        <a href="{{ route("sezonsDestroy",["id"=>Session::get('ContactToEdit')->id]) }}" class="btn danger p-x-md">
                                                            {{ __('backend.yes') }}
                                                        </a>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <!-- / .modal -->

                                        <button type="submit" class="btn btn-primary"><i class="material-icons"> &#xe31b;</i> {!! __('backend.save') !!}</button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif

                        @if($tableName == "lig")
                            {{Form::open(['route'=>['ligsUpdate',Session::get('ContactToEdit')->id],'method'=>'POST'])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.ligAdi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('adi', Session::get('ContactToEdit')->adi, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'adi')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button class="btn warning pull-right" data-toggle="modal"
                                                    data-target="#mc-{{ Session::get('ContactToEdit')->id }}"
                                                    ui-toggle-class="bounce"
                                                    ui-target="#animate">
                                                <small> <i class="material-icons"> &#xe872;</i> {{ __('backend.deleteLigs') }} </small>
                                            </button>
                                    @endif
                                    <!-- .modal -->
                                        <div id="mc-{{ Session::get('ContactToEdit')->id }}" class="modal fade" data-backdrop="true">
                                            <div class="modal-dialog" id="animate">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                    </div>
                                                    <div class="modal-body text-center p-lg">
                                                        <p>
                                                            {{ __('backend.confirmationDeleteMsg') }}
                                                            <br>
                                                            <strong>[ {{ Session::get('ContactToEdit')->adi }} ]</strong>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">{{ __('backend.no') }}</button>
                                                        <a href="{{ route("ligsDestroy",["id"=>Session::get('ContactToEdit')->id]) }}" class="btn danger p-x-md">
                                                            {{ __('backend.yes') }}
                                                        </a>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <!-- / .modal -->

                                        <button type="submit" class="btn btn-primary"><i class="material-icons"> &#xe31b;</i> {!! __('backend.save') !!}</button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif

                        @if($tableName == "takim")
                            {{Form::open(['route'=>['takimsUpdate',Session::get('ContactToEdit')->id],'method'=>'POST'])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.takimAdi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('adi', Session::get('ContactToEdit')->adi, array('placeholder' =>'', 'class' => 'form-control', 'id'=>'adi')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        @if(@Auth::user()->permissionsGroup->delete_status)
                                            <button class="btn warning pull-right" data-toggle="modal"
                                                    data-target="#mc-{{ Session::get('ContactToEdit')->id }}"
                                                    ui-toggle-class="bounce"
                                                    ui-target="#animate">
                                                <small> <i class="material-icons"> &#xe872;</i> {{ __('backend.deleteTakims') }} </small>
                                            </button>
                                    @endif
                                    <!-- .modal -->
                                        <div id="mc-{{ Session::get('ContactToEdit')->id }}" class="modal fade" data-backdrop="true">
                                            <div class="modal-dialog" id="animate">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                                                    </div>
                                                    <div class="modal-body text-center p-lg">
                                                        <p>
                                                            {{ __('backend.confirmationDeleteMsg') }}
                                                            <br>
                                                            <strong>[ {{ Session::get('ContactToEdit')->adi }} ]</strong>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">{{ __('backend.no') }}</button>
                                                        <a href="{{ route("ligsDestroy",["id"=>Session::get('ContactToEdit')->id]) }}" class="btn danger p-x-md">
                                                            {{ __('backend.yes') }}
                                                        </a>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div>
                                        </div>
                                        <!-- / .modal -->

                                        <button type="submit" class="btn btn-primary"><i class="material-icons"> &#xe31b;</i> {!! __('backend.save') !!}</button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /column -->
