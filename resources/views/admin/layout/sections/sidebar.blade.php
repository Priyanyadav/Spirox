<div class="aside aside-left  aside-fixed  d-flex flex-column flex-row-auto" id="kt_aside">
    <div class="brand flex-column-auto " id="kt_brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <img alt="Logo" src="{{ asset('assets/admin/media/logos/Logo1_login.png') }}" class="w-75"style="height: 80px; object-fit: contain; align:center;" />
        </a>

        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                    </g>
                </svg>
            </span>
        </button>
    </div>

    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="aside-menu my-4 " data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">
            <ul class="menu-nav">
                <li class="menu-item {{ Route::currentRouteName() === 'admin.dashboard' ? 'menu-item-open' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-gauge"></i>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                {{-- <li class="menu-item {{ Route::currentRouteName() === 'admin.developer.index' || Route::currentRouteName() == 'admin.articles.create' ? 'menu-item-open' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.developer.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-code"></i>
                        </span>
                        <span class="menu-text">Developer</span>
                    </a>
                </li> --}}
                @if (auth()->user()->role === 'Admin')
                    <li class="menu-item {{ Route::currentRouteName() === 'admin.admin.index' ? 'menu-item-open' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.admin.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="fa-solid fa-user-shield"></i>
                            </span>
                            <span class="menu-text">Admin</span>
                        </a>
                    </li>
                @endif
                <li class="menu-item {{ Route::currentRouteName() === 'admin.sales_executive.index' || Route::currentRouteName() == 'admin.sales_executive.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.sales_executive.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-handshake"></i>
                        </span>
                        <span class="menu-text">Sales Executive</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.store.index' || Route::currentRouteName() == 'admin.store.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.store.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-store"></i>
                        </span>
                        <span class="menu-text">Store's</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.category.index' || Route::currentRouteName() == 'admin.category.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.category.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-store"></i>
                        </span>
                        <span class="menu-text">category</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.product.index' || Route::currentRouteName() == 'admin.product.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.product.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-box"></i>
                        </span>
                        <span class="menu-text">Product</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.inventory.index' || Route::currentRouteName() == 'admin.inventory.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.inventory.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-box-open"></i>
                        </span>
                        <span class="menu-text">Inventory</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.salesOrder.index' || Route::currentRouteName() == 'admin.salesOrder.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.salesOrder.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </span>
                        <span class="menu-text">Sales Order</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.salesOrderItem.index' || Route::currentRouteName() == 'admin.salesOrderItem.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.salesOrderItem.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </span>
                        <span class="menu-text">Sales Order Item</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.salesExe.index' || Route::currentRouteName() == 'admin.salesExe.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.salesExe.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </span>
                        <span class="menu-text">Sales Executive Histroy</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.livelocationtracking.index' || Route::currentRouteName() == 'admin.livelocationtracking.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.livelocationtracking.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-location-crosshairs"></i>
                        </span>
                        <span class="menu-text">Live Location Tracking</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::currentRouteName() === 'admin.BillingPDF.index' || Route::currentRouteName() == 'admin.BillingPDF.index' ? 'menu-item-open' : 'admin.dashboard' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.BillingPDF.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </span>
                        <span class="menu-text">Billing PDF</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
