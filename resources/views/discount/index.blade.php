@extends('layouts.master')

@section('title')
    Discount List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Discount List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('discount.store') }}')" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New Discount</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Discount Type</th>
                        <th>Percentage</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('discount.form')
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
                url: '{{ route('discount.data') }}',
                error: function (xhr, error, thrown) {
                    alert('Unable to display data');
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'discount_type'},
                {data: 'percentage'},
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
                        if (errors && errors.discount_type) {
                            alert('Unable to save data: ' + errors.discount_type.join(', '));
                        } else if (errors && errors.percentage) {
                            alert('Unable to save data: ' + errors.percentage.join(', '));
                        } else {
                            alert('Unable to save data');
                        }
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Discount');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=discount_type]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Discount');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=discount_type]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=discount_type]').val(response.discount_type);
                $('#modal-form [name=percentage]').val(response.percentage);
            })
            .fail((xhr) => {
                alert('Unable to display data: ' + xhr.responseText);
            });
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete this discount?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((xhr) => {
                    alert('Cannot delete discount: ' + xhr.responseText);
                });
        }
    }
</script>
@endpush