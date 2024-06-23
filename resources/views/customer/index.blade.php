@extends('layouts.master')

@section('title')
    Customer List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Customer List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('customer.store') }}')" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New Customer</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        <!-- Customer data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('customer.form')
@endsection

@push('scripts')
<script>
    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('customer.data') }}',
                error: function (xhr, error, thrown) {
                    alert('Unable to display data');
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'name'},
                {data: 'address'},
                {data: 'email'},
                {data: 'contact_number'},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (!e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((xhr) => {
                        let errors = xhr.responseJSON.errors;
                        if (errors && errors.name) {
                            alert('Unable to save data: ' + errors.name.join(', '));
                        } else {
                            alert('Unable to save data');
                        }
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Customer');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Customer');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=name]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=name]').val(response.name);
                $('#modal-form [name=address]').val(response.address);
                $('#modal-form [name=email]').val(response.email);
                $('#modal-form [name=contact_number]').val(response.contact_number);
            })
            .fail((xhr) => {
                alert('Unable to display data: ' + xhr.responseText);
            });
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete this customer?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((xhr) => {
                    alert('Cannot delete customer: ' + xhr.responseText);
                });
        }
    }
</script>
@endpush
