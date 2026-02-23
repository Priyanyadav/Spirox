<script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
</script>
<!--end::Global Config-->

<!-- jQuery -->

<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{ asset('assets/admin/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
<!--end::Global Theme Bundle-->

<!--begin::Page Vendors(used by this page)-->
<script src="{{ asset('assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<!--end::Page Vendors-->

<!--begin::Page Scripts(used by this page)-->
<script src="{{ asset('assets/admin/js/pages/widgets.js') }}"></script>
<script src="{{ asset('assets/admin/js/pages/crud/forms/widgets/select2.js') }}"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/admin/js/toastr.js') }}"></script>
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script src="{{ asset('assets/admin/js/crypto-js.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/admin/socket.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap4-toggle.min.js') }}"></script>

{{--
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script> --}}
{{--
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> --}}

{{--
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9hAX1RWEuNEE-yQ7tq5pYcogkv6aKuZs&callback=initMap"> --}}
</script>
<script>
    

    $(document).ready(function () {
        $(".load-time-prevent").remove();
    });

    setTimeout(function () {
        $('.alert-block').fadeOut('fast');
    }, 3000);

    $(document).ready(function () {
        $(".clock-in-out").show();
    });

    $('.select2').select2({
        placeholder: "Select option",
    });

    $('.date').datepicker();

    $(document).on("submit", "#chnage-password-form", async function (e) {
        e.preventDefault();
        var data = await ajaxDynamicMethod($(this).attr('action'), $(this).attr('method'), generateFormData(
            this));
        if (data.success) {
            toastrsuccess(data.msg);
            $('#common-modal').modal('hide');
            var table = $('#table').DataTable();
            table.ajax.reload(null, false);
        }
    });
</script>

@yield('script')
@stack('script-push')
<!--end::Page Scripts-->
