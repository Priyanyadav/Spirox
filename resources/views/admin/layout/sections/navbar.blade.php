<div id="kt_header" class="header  header-fixed ">
    <!--begin::Container-->
    <div class=" container-fluid  d-flex align-items-center justify-content-between">
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
            <!--begin::Header Menu-->
            <div id="kt_header_menu" class="header-menu header-menu-mobile  header-menu-layout-default ">
                <!--begin::Header Nav-->
                <ul class="menu-nav ">
                    <li class="menu-item">
                        <h3 class="mb-0"><strong>{{ isset($header_title) ? $header_title : '' }}</strong></h3>
                    </li>
                </ul>
                <!--end::Header Nav-->
             
            </div>
            <!--end::Header Menu-->
        </div>
        <!--begin::Topbar-->

        <div class="topbar">
            <!--begin::User-->
            <div class="topbar-item">

                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2"
                    id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">
                        {{ Auth::guard('admin')->user()->name }}</span>
                    <span class="symbol symbol-lg-35 symbol-25 symbol-primary">
                        <span
                            class="symbol-label font-size-h5 font-weight-bold">{{ Str::substr(Auth::guard('admin')->user()->name, 0, 1) }}</span>
                    </span>
                </div>
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
