@extends('admin.layout.master')
<!--begin::Page subheader-->
@section('subheader')
    <div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-inline align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-0 mr-5">{{ $title }} </h5>
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mb-2 mt-0 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard </a>
                    </li>
                    <li class="breadcrumb-item">
                        <strong>{{ $title }}</strong>
                    </li>
                </ul>
                <!--end::Page Title-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
@endsection
<!--end::Page subheader End-->

<!--begin::Page Content-->
@section('content')
    <div class="container-fluid dashboard">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.password.update') }}" method="POST" id="chnage-password-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="current_password">Current Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control w-100" placeholder="Enter Current Password">
                                        <span class="text-danger errors" id="current_passworderror"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" name="new_password" id="new_password"
                                            class="form-control w-100" placeholder="Enter New Password">
                                        <span class="text-danger errors" id="new_passworderror"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="new_confirm_password">Confirm Password</label>
                                        <input type="password" name="new_confirm_password" id="new_confirm_password"
                                            class="form-control w-100" placeholder="Enter Confirm Password">
                                        <span class="text-danger errors" id="new_confirm_passworderror"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!--end::Page Content End-->

<!--begin::scripts start-->
@push('scripts')
    <script>
        $(document).on("click", "#save-form", function(e) {
            e.preventDefault();
            $("#changepasswordform").trigger('submit');
        })
        $(document).on("submit", "#changepasswordform", async function(e) {
            e.preventDefault();
            var data = await ajaxDynamicMethod($(this).attr('action'), $(this).attr('method'), generateFormData(
                this));
        })
    </script>
@endpush
<!--end::scripts End-->
