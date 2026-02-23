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
    <div class="container-fluid profile-view">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    {{-- <img src="..." class="card-img-top" alt="Profile Image"> --}}
                    <div class="card-body">
                        <h5 class="card-title">
                            <strong>Name :</strong> {{ $Admin->name }}
                        </h5>
                        <p class="card-text">
                            <strong>Email Id :</strong> {{ $Admin->email }}
                        </p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Go Back...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<!--end::Page Content End-->

<!--begin::scripts start-->
@push('scripts')
    <script></script>
@endpush
<!--end::scripts End-->
