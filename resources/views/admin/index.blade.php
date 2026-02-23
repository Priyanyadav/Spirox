@extends('admin.layout.master')

<!--begin::Page subheader-->
@section('subheader')
    <div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"> {{ isset($title) ? $title : '' }} </h5>
            </div>
        </div>
    </div>
@endsection
<!--end::Page subheader End-->

<!--begin::Page Content-->
@section('content')
    <!-- ===================== TOP STATS ===================== -->
    <div class="row g-4 mb-4">

        <!-- Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Sales</small>
                        <h4 class="fw-bold mb-1">
                            ₹{{ number_format($totalSales, 0) }}
                        </h4>

                        <small class="{{ $growth >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}% from last month
                        </small>
                    </div>
                    <div class="bg-light rounded-circle p-3">
                        <i class="bi bi-currency-rupee fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Active Users</small>
                        <h4 class="fw-bold mb-1">1,234</h4>
                        <small class="text-success">+5.2% from last month</small>
                    </div>
                    <div class="bg-light rounded-circle p-3">
                        <i class="bi bi-people fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Products</small>
                        <h4 class="fw-bold mb-1">456</h4>
                        <small class="text-success">+2.1% from last month</small>
                    </div>
                    <div class="bg-light rounded-circle p-3">
                        <i class="bi bi-box fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Growth</small>
                        <h4 class="fw-bold mb-1">23.5%</h4>
                        <small class="text-success">+4.3% from last month</small>
                    </div>
                    <div class="bg-light rounded-circle p-3">
                        <i class="bi bi-graph-up fs-4 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- ===================== SECOND ROW ===================== -->
    <div class="row g-4 mt-1">

        <!-- Live Tracking -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-body border-bottom d-flex justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-1">Live Sales Executive Tracking</h6>
                        <small class="text-muted">Real-time location monitoring</small><br>
                        <small class="text-success">+12.5% from last month</small>
                    </div>
                    <button class="btn btn-dark btn-sm">
                        <i class="bi bi-geo-alt-fill"></i> View Map
                    </button>
                </div>

                <div class="card-body d-flex justify-content-between">
                    <div>
                        <h6 class="fw-semibold mb-1">Sales Executive</h6>
                        <small>9876543211</small><br>
                        <small class="text-muted">Last updated: 8:44 PM</small>
                    </div>
                    <div class="text-end">
                        <h6 class="fw-bold mb-1">₹15,000</h6>
                        <small>3 active orders</small><br>
                        <button class="btn btn-outline-primary btn-sm mt-2">
                            <i class="bi bi-geo-alt"></i> Track
                        </button>
                    </div>
                </div>

                <div class="card-footer bg-light small text-muted">
                    GPS Tracking: Background location tracking is active. Locations update every 5 minutes.
                </div>

            </div>
        </div>


        <!-- Recent Activities -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="fw-bold">Recent Activities</h6>
                    <small class="text-muted">Latest system activities</small>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <div class="fw-semibold">New order created</div>
                            <small class="text-muted">Sales Executive</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₹5,000</div>
                            <small class="text-muted">2 mins ago</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <div class="fw-semibold">Invoice generated</div>
                            <small class="text-muted">System</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₹3,500</div>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- ===================== QUICK ACTIONS ===================== -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-1">Quick Actions</h6>
                    <small class="text-muted">Frequently used admin functions</small>

                    <div class="row g-3 mt-3">

                        <div class="col-6 col-md-3">
                            <div class="border rounded p-4 text-center bg-light action-box">
                                <i class="bi bi-people fs-3 mb-2"></i>
                                <div class="fw-semibold">Manage Users</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <a href="{{ route('admin.product.index') }}" class="text-decoration-none text-dark">
                                <div class="border rounded p-4 text-center bg-light action-box">
                                    <i class="bi bi-box fs-3 mb-2"></i>
                                    <div class="fw-semibold">Products</div>
                                </div>
                            </a>

                        </div>

                        <div class="col-6 col-md-3">
                            <div class="border rounded p-4 text-center bg-light action-box" style="cursor:pointer;"
                                onclick="window.open('https://www.google.com/maps', '_blank')">
                                <i class="bi bi-geo-alt fs-3 mb-2"></i>
                                <div class="fw-semibold">Live Map</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <a href="#" class="text-decoration-none text-dark">
                                <div class="border rounded p-4 text-center bg-light action-box">
                                    <i class="bi bi-graph-up fs-3 mb-2"></i>
                                    <div class="fw-semibold">Analytics</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!--end::Page Content End-->
