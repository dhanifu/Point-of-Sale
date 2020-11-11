<nav class="sidebar-nav shadow">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="@role('manager') {{ route('manager.home') }} @elserole('admin') {{ route('admin.home') }} @elserole('kasir') {{ route('kasir.home') }} @endrole">
                <i class="nav-icon icon-speedometer"></i> Dashboard
            </a>
        </li>

        @role('manager')
        <li class="nav-title">REPORT</li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="nav-icon icon-drop"></i> Product Report
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="nav-icon icon-drop"></i> Transaction Report
            </a>
        </li>
        @elserole('admin')
        <li class="nav-title">PRODUCT MANAGEMENT</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.brand.index') }}">
                <i class="nav-icon icon-drop"></i> Brands
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('admin.product.*')) active @endif" href="{{ route('admin.product.index') }}">
                <i class="nav-icon icon-drop"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.distributor.index') }}">
                <i class="nav-icon icon-drop"></i> Distributors
            </a>
        </li>
        @elserole('kasir')
        <li class="nav-title">TRANSACTION</li>
        <li class="nav-item">
            <a class="nav-link @if(request()->routeIs('kasir.transaction.*')) active @endif" href="{{ route('kasir.transaction.index') }}">
                <i class="nav-icon icon-drop"></i> Transactions
            </a>
        </li>
        @endrole
    </ul>
</nav>