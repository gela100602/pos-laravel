@extends('layouts.master')

@section('title', 'Product List')

@section('breadcrumb')
    @parent
    <li class="active">Product List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('products.store') }}')" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New Product</button>
                    <button onclick="deleteSelected('{{ route('products.delete_selected') }}')" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </div>  
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-product">
                    @csrf
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th width="5%">#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Discount</th>
                                <th>Stock</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td class="text-center"><img
                                    src="{{ $user->user_image ? asset('storage/img/user/' . $user->user_image) : 'https://kajabi-storefronts-production.kajabi-cdn.com/kajabi-storefronts-production/themes/774354/settings_images/Xco28EgLSlixesEbQzJx_Avatar3.png' }}"
                                    class="img-fluid" style="border-radius: 50%;" width="70" height="70">
                                </td>
                            </tr>

                            @endforelse
                        </tbody> --}}
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@include('product.form', ['categories' => $categories, 'suppliers' => $suppliers])

@endsection

@push('scripts')
<script>
    $(function () {
        let table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('products.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {
                    data: 'product_image',
                    searchable: false,
                    sortable: false,
                    render: function(data, type, row) {
                        return data ? html(`<img src="{{ asset("storage") }}/' + data + '" alt="Product Image" style="max-height: 50px;">` : `<img src="https://jkfenner.com/wp-content/uploads/2019/11/default.jpg" alt="Default Image" style="max-height: 50px;">`);
                    },
                },
                {data: 'product_name'},
                {data: 'category_name'},
                {data: 'supplier_name'},
                {data: 'purchase_price'},
                {data: 'selling_price'},
                {data: 'discount'},
                {data: 'stock'},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

        // Ensure CSRF token is included in all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#product-form').validator().on('submit', function (e) {
            if (!e.isDefaultPrevented()) {
                let url = $(this).attr('action');
                let method = $(this).find('[name=_method]').val() === 'PUT' ? 'PUT' : 'POST';
                
                // Create FormData object and append necessary fields
                let formData = new FormData(this);
                formData.append('_method', method); // Ensure _method is set correctly
                formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // CSRF token
                
                $.ajax({
                    url: url,
                    type: 'POST', // Always use POST for FormData
                    data: formData,
                    contentType: false, // No need to set contentType when FormData is used
                    processData: false, // No need to process data when FormData is used
                    success: function (response) {
                        $('#modal-form').modal('hide');
                        $('#product-form')[0].reset();
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                let errorMessages = errors[field];
                                alert(field + ": " + errorMessages.join(", "));
                            }
                        } else {
                            alert('Unable to save data');
                        }
                    }
                });

                return false;
            }
        });

        // $('#modal-form').on('hidden.bs.modal', function () {
        //     $('#image-preview').hide().attr('src', '');
        //     $('#product-form')[0].reset();
        //     $('#product-form [name=_method]').val('POST');
        //     $('#product-form').attr('action', '');
        // });
        $('#modal-form').on('hidden.bs.modal', function () {
            $('#image-preview').hide().attr('src', 'https://jkfenner.com/wp-content/uploads/2019/11/default.jpg');
            $('#product-form')[0].reset();
            $('#product-form [name=_method]').val('POST');
            $('#product-form').attr('action', '');
        });


        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Add Product');
        $('#product-form')[0].reset();
        $('#product-form').attr('action', url);
        $('#product-form [name=_method]').val('POST');
        $('#product_name').focus();
        $('#image-preview').hide().attr('src', '');
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Product');
        $('#product-form').attr('action', url);
        $('#product-form [name=_method]').val('PUT'); // Ensure _method is set to PUT

        $.get(url)
            .done(function (response) {
                $('#product_name').val(response.product_name);
                $('#category_id').val(response.category_id);
                $('#supplier_id').val(response.supplier_id);
                $('#purchase_price').val(response.purchase_price);
                $('#selling_price').val(response.selling_price);
                $('#discount').val(response.discount);
                $('#stock').val(response.stock);

                // Display product image if available
                // if (response.product_image) {
                //     $('#image-preview').attr('src', '{{ asset('storage') }}/' + response.product_image).show();
                // } else {
                //     $('#image-preview').hide().attr('src', '');
                // }

                if (response.product_image) {
                    $('#image-preview').attr('src', '{{ asset("storage") }}/' + response.product_image).show();
                } else {
                    $('#image-preview').attr('src', 'https://jkfenner.com/wp-content/uploads/2019/11/default.jpg').show();
                }
            })
            .fail(function (xhr, status, error) {
                console.error('Error fetching data:', error);
                alert('Unable to display data');
            });
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
                    alert('Cannot delete data');
                    return;
                });
        }
    }

    function deleteSelected(url) {
        if ($('input:checked').length > 0) {
            if (confirm('Are you sure you want to delete the selected data?')) {
                $.post(url, $('.form-product').serialize())
                    .done(function(response) {
                        console.log('Delete selected successful:', response);
                        table.ajax.reload(null, false);  // Reload table without resetting pagination
                    })
                    .fail(function(errors) {
                        console.error('Delete selected error:', errors);
                        alert('Unable to delete data');
                    });
            }
        } else {
            alert('Select data to delete');
        }
    }

    $('#product_image').change(function () {
        let input = this;
        let url = URL.createObjectURL(input.files[0]);
        $('#image-preview').attr('src', url).show();
    });

</script>
@endpush