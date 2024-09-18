<div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" data-key="t-menu">Menu</li>

            <li>
                <a href="">
                    <i data-feather="home"></i>
                    <span data-key="t-dashboard">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0);" class="has-arrow">
                    <i data-feather="box"></i>
                    <span data-key="t-product-management">Product Management</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{route('brands.index')}}">
                            <i data-feather="grid"></i>
                            <span data-key="t-brands">Brands</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('categories.index')}}">
                            <i data-feather="layers"></i>
                            <span data-key="t-categories">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('subcategories.index')}}">
                            <i data-feather="list"></i>
                            <span data-key="t-subcategories">Subcategories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('products.index')}}">
                            <i data-feather="package"></i>
                            <span data-key="t-products">Products</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
    <!-- Sidebar -->
</div>
