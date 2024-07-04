@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
{{-- stat boxes --}}
<div class="row">
    {{-- category --}}
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $category }}</h3>
                {{-- <h3>21</h3> --}}
                <p>Total Categories</p>
            </div>
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('category.index') }}" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- product --}}
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ $product }}</h3>
                {{-- <h3>21</h3> --}}
                <p>Total Products</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('products.index') }}" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- customer --}}
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $customer }}</h3>
                {{-- <h3>21</h3> --}}
                <p>Total Customers</p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="{{ route('customer.index') }}" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- supplier --}}
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-olive">
            <div class="inner">
                <h3>{{ $supplier }}</h3>
                {{-- <h3>21</h3> --}}
                <p>Total Suppliers</p>
            </div>
            <div class="icon">
                <i class="fa fa-truck"></i>
            </div>
            <a href="{{ route('supplier.index') }}" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
<script>

</script>
@endpush