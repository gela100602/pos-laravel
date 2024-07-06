@extends('layouts.master')

@section('title')
    Payment Methods List
@endsection

<title>POS - Payment Method</title>


@section('breadcrumb')
    @parent
    <li class="active">Payment Method List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('payment-method.store') }}')" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New Payment Method</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Method</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('payment-method.form')
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
                url: '{{ route('payment-method.data') }}',
                error: function (xhr, error, thrown) {
                    alert('Unable to display data');
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'method'},
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
                        if (errors && errors.method) {
                            alert('Unable to save data: ' + errors.method.join(', '));
                        } else {
                            alert('Unable to save data');
                        }
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Payment Method');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=method]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Payment Method');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=method]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=method]').val(response.method);
            })
            .fail((xhr) => {
                alert('Unable to display data: ' + xhr.responseText);
            });
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete this payment method?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((xhr) => {
                    alert('Cannot delete payment method: ' + xhr.responseText);
                });
        }
    }
</script>
@endpush