@extends('layouts.master')

@section('title')
    Transaction List
@endsection

<title>POS - Transaction</title>

@section('breadcrumb')
    @parent
    <li class="active">Transaction List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-sales table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Discount</th>
                        <th>Total Pay</th>
                        <th>Cashier</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('payment_transaction.detail')

{{-- @include('transaction.form', ['discounts' => $discounts, 'users' => $users]) --}}

@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-sales').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('payment_transaction.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'date'},
                {data: 'customer_id'},
                {data: 'total_item'},
                {data: 'total_price'},
                {
                    data: 'percentage',
                    render: function (data, type, row) {
                        return parseFloat(data).toFixed(0) + '%'; // Ensure data is parsed as float and rounded to 0 decimal places
                    }
                },
                {data: 'payment'},
                {data: 'username'},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'product_name'},
                {data: 'selling_price'},
                {data: 'quantity'},
                {data: 'subtotal'},
            ]
        })
    });

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete selected data?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Unable to delete data');
                    return;
                });
        }
    }
</script>
@endpush