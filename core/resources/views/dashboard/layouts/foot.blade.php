<script type="text/javascript">
    var public_lang = "{{ @Helper::currentLanguage()->code }}";
    var public_folder_path = "{{ asset('') }}";
    var first_day_of_week = "{{ env("FIRST_DAY_OF_WEEK",0) }}";

</script>
@stack('before-scripts')
<!-- Bootstrap -->
<script src="{{ asset('assets/dashboard/js/tether/dist/js/tether.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/bootstrap/dist/js/bootstrap.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/moment/moment.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/moment/moment.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/moment/locale/'.@Helper::currentLanguage()->code.'.js') }}" defer></script>
<!-- core -->
<script src="{{ asset('assets/dashboard/js/underscore/underscore-min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/jQuery-Storage-API/jquery.storageapi.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/pace/pace.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/config.lazyload.js') }}" defer></script>

<script src="{{ asset('assets/dashboard/js/scripts/palette.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-load.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-jp.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-include.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-device.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-form.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-nav.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-screenfull.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-scroll-to.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-toggle-class.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/toastr.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/sweet-alert.min.js') }}" defer></script>

<script src="{{ asset('assets/dashboard/js/scripts/app.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scrollbooster.min.js') }}" defer></script>

<style scoped>
    #spinner-overlay{
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        background: #f5f5f5d6;
        z-index: 9999;
    }
    #spinner-overlay2 {
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        background: rgba(0,0,0,0.6);
    }
    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .percent {
        z-index: 9999;
        color: white;
        font-size: 20px;
        margin-top: 100px;
    }
    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }
    .swal-overlay {
        background-color: rgba(43, 165, 137, 0.45);
    }
</style>

{!! Helper::SaveVisitorInfo("Dashboard &raquo; ".trim($__env->yieldContent('title'))) !!}
@stack('after-scripts')
