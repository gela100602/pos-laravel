<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('storage/user_image/default-user.png') }}" class="img-circle img-profile" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            
            <li class="header">MASTER</li>
            <li>
                <a href="{{ route('customer.index') }}">
                    <i class="fa fa-users"></i> <span>Customer</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Supplier</span>
                </a>
            </li>
            <li>
                <a href="{{ route('category.index') }}">
                    <i class="fa fa-cube"></i> <span>Category</span>
                </a>
            </li>
            <li>
                <a href="{{ route('discount.index') }}">
                    <i class="fa fa-percent"></i> <span>Discount</span>
                </a>
            </li>
            <li>
                <a href="{{ route('payment-method.index') }}">
                    <i class="fa fa-truck"></i> <span>Payment Method</span>
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}">
                    <i class="fa fa-cubes"></i> <span>Product</span>
                </a>
            </li>
            <li class="header">TRANSACTION</li>
            <li>
                <a href="{{ route('transaction.index') }}">
                    <i class="fa fa-exchange"></i> <span>Payment Transaction</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaction.index') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Cart</span>
                </a>
            </li>
            
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>