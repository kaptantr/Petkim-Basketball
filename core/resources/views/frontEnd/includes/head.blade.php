<meta charset="utf-8">
<title>{{$PageTitle}} {{($PageTitle !="")? "|":""}} {{ Helper::GeneralSiteSettings("site_title_" . @Helper::currentLanguage()->code) }}</title>
<meta name="description" content="{{$PageDescription}}"/>
<meta name="keywords" content="{{$PageKeywords}}"/>
<meta name="author" content="{{ URL::to('') }}"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="{{ URL::asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/frontend/css/jcarousel.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/flexslider.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/style.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/color.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('assets/frontend/css/colors.css') }}" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/toastr.min.css') }}" type="text/css"/>

<link rel="stylesheet" href="{{ URL::asset('assets/frontend/js/owl-carousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/frontend/js/owl-carousel/assets/owl.theme.default.min.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&display=swap" rel="stylesheet">

@if( @Helper::currentLanguage()->direction=="rtl")
<link href="{{ URL::asset('assets/frontend/css/rtl.css') }}" rel="stylesheet"/>
@endif

<!-- Favicon and Touch Icons -->
@if(Helper::GeneralSiteSettings("style_fav") !="")
    <link href="{{ URL::asset('uploads/settings/'.Helper::GeneralSiteSettings("style_fav")) }}" rel="shortcut icon"
          type="image/png">
@else
    <link href="{{ URL::asset('uploads/settings/nofav.png') }}" rel="shortcut icon" type="image/png">
@endif

@if(Request::path('/oyuncu-karsilastirma'))
    <link href="{{ URL::asset('assets/frontend/css/pl-screen.css?v=2') }}" rel="stylesheet"/>
@endif

@if(Helper::GeneralSiteSettings("style_apple") !="")
    <link href="{{ URL::asset('uploads/settings/'.Helper::GeneralSiteSettings("style_apple")) }}" rel="apple-touch-icon">
    <link href="{{ URL::asset('uploads/settings/'.Helper::GeneralSiteSettings("style_apple")) }}" rel="apple-touch-icon"
          sizes="72x72">
    <link href="{{ URL::asset('uploads/settings/'.Helper::GeneralSiteSettings("style_apple")) }}" rel="apple-touch-icon"
          sizes="114x114">
    <link href="{{ URL::asset('uploads/settings/'.Helper::GeneralSiteSettings("style_apple")) }}" rel="apple-touch-icon"
          sizes="144x144">
@else
    <link href="{{ URL::asset('uploads/settings/nofav.png') }}" rel="apple-touch-icon">
    <link href="{{ URL::asset('uploads/settings/nofav.png') }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ URL::asset('uploads/settings/nofav.png') }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ URL::asset('uploads/settings/nofav.png') }}" rel="apple-touch-icon" sizes="144x144">
@endif

    <script src="{{ URL::asset('assets/frontend/js/jquery.js') }}"></script>
    <link href="{{ URL::asset('assets/frontend/css/jquery.dataTables.min.css') }}" rel="stylesheet"/>
    <script src="{{ URL::asset('assets/frontend/js/jquery.dataTables.min.js') }}"></script>
    <link href="{{ URL::asset('assets/frontend/css/responsive.dataTables.min.css') }}" rel="stylesheet"/>
    <script src="{{ URL::asset('assets/frontend/js/dataTables.responsive.min.js') }}"></script>

    <style>
        #pozisyon_istatistik_tablosu_processing {
            position: absolute;
            background: none;
            background-color: #00000033;
            left: 0;
            top: 0;
            margin: auto auto;
            color: #000;
            font-weight: bold;
            width: 100%;
            height: 110px;
            z-index: 9000;
        }
        section.rating-widget {
            padding-top: 10px;
        }
        .player-comparison__player-empty section.rating-widget {
            padding-top: 0px;
        }
        .rating-stars ul {
            list-style-type:none;
            margin:0;
            padding:0;
            -moz-user-select:none;
            -webkit-user-select:none;
        }
        .rating-stars ul > li.star {
            display:initial;
            margin-left: -3px;

        }
        .rating-stars ul > li.star > i.fa {
            font-size:2em;
            color:#ccc;
        }
        .rating-stars ul > li.star > i.fa.mirror {
            transform: scalex(-1);
            display: inline-block;
            width: 0px;
        }
        section.content-row-no-bg.player-comparison__widgets header h4 {
            font-size: 30px;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 1px 1px 0px #e31b23;
            background-image: linear-gradient(315deg, #000000 0%, #ff2882 74%);
            font-family: 'Akaya Kanadaka', cursive;
        }
        .bar-container {
            background-color: #3494c8;
            /*width: 100%;*/
            margin: 2.8rem 5px 0 auto;
            height: 3rem;
        }
        .bg-c-league {
            /*background-color: #3494c8 !important;*/
        }
        .bar-right {
            margin: 0 0 0 auto;
        }
        .bar-inside {
            display: block;
        }
        .player-comparison__stat-value span.pull-left {
            margin-left: 10px;
        }
        .player-comparison__stat-value span.pull-right {
            margin-right: 10px;
        }
        .player-filtrele {
            box-shadow: 1px 1px 10px;
            padding: 20px 10px;
            border: 1px solid #00000045;
            border-radius: 5px;
            margin-top: 20px;
            height: 56px;
            overflow: hidden;
        }
        .player-filtrele i.filtrele-icon {
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
            display: block;
            cursor: pointer;
        }
    </style>
<meta property='og:title'
      content='{{$PageTitle}} {{($PageTitle =="")? Helper::GeneralSiteSettings("site_title_" . trans('backLang.boxCode')):""}}'/>
@if(@$Topic->photo_file !="")
    <meta property='og:image' content='{{ URL::asset('uploads/topics/'.@$Topic->photo_file) }}'/>
@elseif(Helper::GeneralSiteSettings("style_apple") !="")
    <meta property='og:image'
          content='{{ URL::asset('uploads/settings/'.Helper::GeneralSiteSettings("style_apple")) }}'/>
@else
    <meta property='og:image'
          content='{{ URL::asset('uploads/settings/nofav.png') }}'/>
@endif
<meta property="og:site_name" content="{{ Helper::GeneralSiteSettings("site_title_" . trans('backLang.boxCode')) }}">
<meta property="og:description" content="{{$PageDescription}}"/>
<meta property="og:url" content="{{ url()->full()  }}"/>
<meta property="og:type" content="website"/>

@if(Helper::GeneralSiteSettings("css")!="")
    <style type="text/css">
        {!! Helper::GeneralSiteSettings("css") !!}
    </style>
@endif
{{-- Google Tags and google analytics --}}
@if($WebmasterSettings->google_tags_status && $WebmasterSettings->google_tags_id !="")
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{!! $WebmasterSettings->google_tags_id !!}');</script>
    <!-- End Google Tag Manager -->
@endif
