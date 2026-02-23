<div id="kt_quick_user" class="offcanvas offcanvas-right">
    <div class="offcanvas-header d-flex align-items-center justify-content-between p-7 border-bottom bg-secondary">
        <h4 class="font-weight-bold text-white m-0">
            {{ Auth::guard('admin')->user()->name }}
        </h4>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-danger" id="kt_quick_user_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>

    <div class="offcanvas-content">
        <div class="row">
            <!-- My Profile Section -->
            <div class="col-lg-12">
                <a href="{{ route('admin.profile.view') }}" class="btn btn-profile w-100 text-left py-3">
                    <i class="far fa-user text-primary px-5"></i> My Profile
                </a>
            </div>

            <!-- Change Password Section -->
            <div class="col-lg-12 border-top border-bottom-0 border-left-0 border-right-0">
                <a href="{{ route('admin.change.password') }}" class="btn btn-profile w-100 text-left py-3">
                    <i class="fas fa-unlock-alt text-primary px-5"></i> Change Password
                </a>
            </div>

            

            <!-- Logout Section -->
            <div class="col-lg-12 text-right border-top border-left-0 border-right-0">
                <a href="{{ route('admin.logout') }}" class="btn btn-light-danger btn-sm m-5"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Log Out
                </a>

                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
