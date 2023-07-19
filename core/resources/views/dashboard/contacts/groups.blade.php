<!-- column -->
<div class="col-sm-2 col-md-2 w w-auto-sm b-r">
    <div class="row-col">
        <div class="row-row">
            <div class=" hover">
                <div class="row-inner"><br>
                    <div class="nav nav-pills nav-stacked m-t-sm">
                        <div class="row-row">
                            <div class="col-sm-12 p-a-0">
                                <br>
                                <ul class="list">
                                    <li class="marginBottom5">
                                        @if($tableName == "contact")
                                            <a href="{{ route('contacts') }}" style="font-weight:bold;color:#0cc2aa;font-size:18px" !!}> {{ __('backend.allContacts') }} ({{ $AllContactsCount ?? 0 }}) </a>
                                            <br>
                                            <br>
                                            <a href="#" data-url="{{ url('/admin/excel-contacts') }}" class="btn btn-sm white btn-addon info m-b-1" id="import-excel">
                                                <i class="material-icons"> &#xe2c6;</i>&nbsp;<span>{!! __('backend.importContacts') !!}</span>
                                            </a>
                                            <br>
                                            <a href="{!! url('assets/frontend/files/bos-sablon.xlsx') !!}" class="btn btn-sm white btn-addon gray m-b-1" id="empty-contact-file" download>
                                                <i class="material-icons"> &#xe2c4;</i>&nbsp;<span>{!! __('backend.emptyContactFile') !!}</span>
                                            </a>
                                            <br>
                                            <a href="{{ url('/admin/excel-contacts-clear') }}" class="btn btn-sm white btn-addon gray m-b-1" id="import-excel-clear">
                                                <i class="material-icons"> &#xe14c;</i>&nbsp;<span>Excel Verilerini Sıfırla</span>
                                            </a>
                                        @elseif($tableName == "sezon")
                                            <a href="{{ route('sezons') }}" style="font-weight:bold;color:#0cc2aa;font-size:18px" !!}> {{ __('backend.allSezons') }} ({{ $AllSezonsCount ?? 0 }}) </a>
                                        @elseif($tableName == "lig")
                                            <a href="{{ route('ligs') }}" style="font-weight:bold;color:#0cc2aa;font-size:18px" !!}> {{ __('backend.allLigs') }} ({{ $AllLigsCount ?? 0 }}) </a>
                                        @elseif($tableName == "takim")
                                            <a href="{{ route('takims') }}" style="font-weight:bold;color:#0cc2aa;font-size:18px" !!}> {{ __('backend.allTakims') }} ({{ $AllTakimsCount ?? 0 }}) </a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div> </div>
    </div>
</div>
<!-- /column -->

@if($tableName == "contact")

    <script>
        let excel_dosya = null;
        let timer1 = null;
        let timer2 = null;
        let url = null;
        let file = null;

        $("a#import-excel").click(function (e) {
            e.preventDefault();
            clearInterval(timer1);
            clearInterval(timer2);
            let obj = $(this);

            excel_dosya = document.createElement('input');
            excel_dosya.addEventListener("change", () => {

                url = $(obj).attr('data-url');
                let files = excel_dosya.files;

                if (files.length > 0) {
                    file = files[0];
                    if (file.size > 1024 * 1024 * 50) {
                        toastr["error"]("Dosya boyutu 50MB'tan küçük olmalıdır!", "Hata");
                        excel_dosya = null;
                        return;
                    }

                    if (file.type != 'application/vnd.ms-excel' && file.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                        toastr["error"]("XLS yada XLSX türünde dosya olmalıdır!", "Hata");
                        excel_dosya = null;
                        return;
                    }

                    if (excel_dosya == null || file == null) {
                        toastr["error"]("Dosya yüklenemiyor!", "Hata");
                        excel_dosya = null;
                        return;
                    }

                    swal({
                        title: "Soru",
                        text: "Seçilen excel'deki veriler yüklensin mi?",
                        type: "info",
                        showCancelButton: true,
                        cancelButtonText: 'Hayır',
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Evet',
                        closeOnConfirm: true
                    }, function (isConfirm) {

                        if(isConfirm) {
                            import_ajax();
                        }
                        else {
                            excel_dosya = null;
                            return;
                        }
                    });
                }
                else {
                    excel_dosya = null;
                    return;
                }

            }, false);
            excel_dosya.type = 'file';
            excel_dosya.name = 'excel';
            excel_dosya.click();
        });

        function import_ajax() {
            let formData = new FormData();
            formData.append('excel', file, file.name);
            $("#spinner-overlay2").fadeIn(500);

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            let percentComplete = (evt.loaded / evt.total) * 100;
                            if(percentComplete == 100) {
                                setTimeout(function () { $('.percent').html('Sayfalar Okunuyor...'); }, 1000);
                                setTimeout(function () { $('.percent').html('Oyuncular Okunuyor...'); }, 4000);
                                setTimeout(function () { $('.percent').html('Oyuncular Kaydediliyor...'); }, 7000);
                                setTimeout(function () { $('.percent').html('Oyuncular Ayrıştırılıyor...'); }, 10000);
                                setTimeout(function () { $('.percent').html('Oyuncular Kaydediliyor...') }, 13000);

                                timer1 = setInterval(function () {
                                    timer2 = setInterval(function () {
                                        setTimeout(function () { $('.percent').html('İstatistikler Okunuyor'); }, 1000);
                                        setTimeout(function () { $('.percent').html('.İstatistikler Okunuyor.'); }, 2000);
                                        setTimeout(function () { $('.percent').html('..İstatistikler Okunuyor..'); }, 3000);
                                        setTimeout(function () { $('.percent').html('...İstatistikler Okunuyor...'); }, 4000);
                                        setTimeout(function () { $('.percent').html('...İstatistikler Kaydediliyor...'); }, 4000);
                                        setTimeout(function () { $('.percent').html('..İstatistikler Kaydediliyor..'); }, 5000);
                                        setTimeout(function () { $('.percent').html('.İstatistikler Kaydediliyor.'); }, 6000);
                                        setTimeout(function () { $('.percent').html('İstatistikler Kaydediliyor'); }, 7000);
                                    }, 7000);
                                    clearInterval(timer1);
                                }, 16000);
                            }
                            else {
                                setTimeout(function () { $('.percent').html('%' + percentComplete.toFixed(0)); }, 100);
                            }
                        }
                    }, false);
                    return xhr;
                },
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (result) {
                    if (result == 'true') {
                        swal({ title: "Başarılı!",
                                text: "Oyuncu Verileri Yüklendi.",
                                type: "success",
                                timer: 3000 },
                            function () {
                                $("#spinner-overlay2").fadeOut(500);
                                location.reload();
                            });
                        setTimeout(function () {
                            $("#spinner-overlay2").fadeOut(500);
                            location.reload();
                        }, 3000);
                    }
                    else {
                        toastr["error"](result, "Hata");
                        clearInterval(timer1);
                        clearInterval(timer2);
                        $("#spinner-overlay2").fadeOut(500);
                    }
                },
                error: function (response) {
                    if(response.status == 524 || response.status == 500) {
                        toastr["error"]("<b>Excel yükleme süresi aşıldı!</b>" +
                            "<br>Dosya otomatik tekrar yüklenecek!<br>" +
                            "İşlem kalan yerden devam edecek.",
                            "Hata",
                            { timeout: 10000 }
                        );
                        clearInterval(timer1);
                        clearInterval(timer2);
                        $("#spinner-overlay2").fadeOut(500);
                        //import_ajax();
                    }
                    else {
                        toastr["error"]("Excel yükleme işlemi başarısız!", "Hata");
                    }
                },
                complete: function () {
                    setTimeout(function () {
                        clearInterval(timer1);
                        clearInterval(timer2);
                        $("#spinner-overlay2").fadeOut(500);
                    }, 5000);
                }
            });
        }
    </script>
@endif
