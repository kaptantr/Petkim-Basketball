
<!-- column -->
<div class="col-sm-6 col-md-7">
    <div class="row-col">
        <br>
        <div class="p-a-sm">
            <button onclick="$('#newContactForm').slideToggle('fast');" class="btn btn-sm white btn-addon primary m-b-1" style="position:absolute">
                @if($tableName == "contact")
                    <i class="material-icons"> &#xe02e;</i>&nbsp;<span>{!! __('backend.newContacts') !!}</span>
                @elseif($tableName == "sezon")
                    <i class="material-icons"> &#xe02e;</i>&nbsp;<span>{!! __('backend.newSezons') !!}</span>
                @elseif($tableName == "lig")
                    <i class="material-icons"> &#xe02e;</i>&nbsp;<span>{!! __('backend.newLigs') !!}</span>
                @elseif($tableName == "takim")
                    <i class="material-icons"> &#xe02e;</i>&nbsp;<span>{!! __('backend.newTakims') !!}</span>
                @endif
            </button>
        </div>
        <br><br><br>
        <div id="newContactForm" class="row-row" style="display: none">
            <div class="row-body">
                <div class="row-inner">
                    <div class="padding p-y-sm ">
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
                            {{Form::open(['route'=>['contactsStore'],'method'=>'POST', 'files' => true ])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="col-sm-3">
                                    <div class="avatar w-64 inline">
                                        <img id="photo_preview" src="{{ asset('uploads/contacts/profile.jpg') }}" style="opacity: 0.2">
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
                                        {!! Form::text('adsoyad','', array('placeholder' =>__('backend.adsoyad'), 'class' => 'form-control inline', 'id'=>'adsoyad', 'required'=>'')) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- fields -->
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.pozisyon') !!}:</label>
                                    <div class="col-sm-9">
                                        <select name="ana_pozisyon" id="ana_pozisyon" class="form-control select2 select2-hidden-accessible" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value=""> - </option>
                                            @foreach ($pozisyonlar as $pozisyon)
                                                <option value="{{ $pozisyon->adi }}">
                                                    {{ $pozisyon->adi }} ({{ $pozisyon->title }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.yanpozisyon') !!}:</label>
                                    <div class="col-sm-9">
                                        <select name="yan_pozisyon" id="yan_pozisyon" class="form-control select2 select2-hidden-accessible" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value=""> - </option>
                                            @foreach ($pozisyonlar as $pozisyon)
                                                <option value="{{ $pozisyon->adi }}">
                                                    {{ $pozisyon->adi }} ({{ $pozisyon->title }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.alt_kimlik') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('alt_kimlik', '', array('placeholder' =>'', 'class' => 'form-control', 'id'=>'alt_kimlik')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.boy') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('boy', '', array('placeholder' =>'', 'class' => 'form-control', 'id'=>'boy')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.dogum_tarihi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::date('dogum_tarihi', '', array('placeholder' =>'', 'class' => 'form-control', 'id'=>'dogum_tarihi')) !!}
                                    </div>
                                </div>

                                @for($i=16; $i<=34; $i++)
                                    @if($i > date('y')+1) @continue @endif
                                    <?php
                                        $name1 = 'takim_'.$i.'_'.($i+1).'_1';
                                        $name2 = 'takim_'.$i.'_'.($i+1).'_2';
                                    ?>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">{!!  __('backend.takim') !!} ({{'20'.$i.'-'.($i+1)}}):</label>
                                        <div class="col-sm-4">
                                            <select name="{{ $name1 }}" id="{{ $name1 }}" class="form-control select2 select2-hidden-accessible"
                                                    ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                                <option value=""> - </option>
                                                @foreach ($takimlar as $takim)
                                                    <option value="{{ $takim->adi }}">{{ $takim->adi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-1" style="text-align:center;margin-top:5px"><b>-</b></div>
                                        <div class="col-sm-4">
                                            <select name="{{ $name2 }}" id="{{ $name2 }}" class="form-control select2 select2-hidden-accessible"
                                                    ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                                <option value=""> - </option>
                                                @foreach ($takimlar as $takim)
                                                    <option value="{{ $takim->adi }}">{{ $takim->adi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endfor

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{!!  __('backend.menajer') !!}:</label>
                                    <div class="col-sm-9">
                                        <select name="menajer" id="menajer"
                                                class="form-control select2 select2-hidden-accessible" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                            <option value=""> - </option>
                                            @foreach ($menajerler as $menajer)
                                                <option value="{{ $menajer->adi }}">{{ $menajer->adi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.oyuncu_ozellikleri') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::textarea('oyuncu_ozellikleri', '', array('placeholder' => '', 'class' => 'form-control', 'rows'=>'2')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.aktif') }}:</label>
                                    <div class="col-sm-9">
                                        <div class="checkbox">
                                            <label class="ui-check" style="transform:scale(1.5);padding-left:25px">
                                                {!! Form::checkbox('status', '1', '1', array('id' => 'status')) !!}
                                                <i class="dark-white"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-icons"> &#xe31b;</i> {!! __('backend.add') !!}
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif

                        @if($tableName == "sezon")
                            {{Form::open(['route'=>['sezonsStore'],'method'=>'POST'])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.sezonAdi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('adi', '', array('placeholder' =>'', 'class' => 'form-control', 'id'=>'adi')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-icons"> &#xe31b;</i> {!! __('backend.add') !!}
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif

                        @if($tableName == "lig")
                            {{Form::open(['route'=>['ligsStore'],'method'=>'POST'])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.ligAdi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('adi', '', array('placeholder' =>'', 'class' => 'form-control', 'id'=>'adi')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-icons"> &#xe31b;</i> {!! __('backend.add') !!}
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <!-- / fields -->
                            {{ Form::close() }}
                        @endif

                        @if($tableName == "takim")
                            {{Form::open(['route'=>['takimsStore'],'method'=>'POST'])}}
                            <div class="row-col h-auto m-b-1">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">{{ __('backend.takimAdi') }}:</label>
                                    <div class="col-sm-9">
                                        {!! Form::text('adi', '', array('placeholder' =>'', 'class' => 'form-control', 'id'=>'adi')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-icons"> &#xe31b;</i> {!! __('backend.add') !!}
                                        </button>
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
