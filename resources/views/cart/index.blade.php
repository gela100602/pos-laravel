@extends('layouts.master')

@section('title')
    Cart
@endsection

<title>POS - Cart</title>

@push('css')
<style>
    .display-payment {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .display-in-words {
        padding: 10px;
        background: #f0f0f0;
    }

    #customer_id {

        background-color: #eee;
    }

    #discount {
   
        background-color: #eee;
    }
    @media(max-width: 768px) {
        .display-payment {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Cart</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                    
                <form class="form-product">
                    @csrf
                    <div class="form-group row">
                        <label for="product_id" class="col-lg-1">Product ID</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $transactionId }}">
                                <input type="text" class="form-control" name="product_id" id="product_id">
                                <span class="input-group-btn">
                                    <button onclick="showProduct()" class="btn btn-success btn-flat" type="button"><i class="fa fa-search-plus"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-striped table-bordered sales-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th width="15%">Quantity</th>
                            <th>Discount</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                </table>
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="display-payment bg-primary"></div>
                        <div class="display-in-words"></div>
                    </div>
                    <div class="col-lg-4">
                        <form class="form-sale" id="form-sale" method="POST">
                            @csrf
                            <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $transactionId }}">
                            
                            <div class="form-group row">
                                <label for="total_display" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="total_display" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="customer_id" class="col-lg-2 control-label">Customer</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="customer_id" value="" readonly>
                                        <span class="input-group-btn">  
                                            <button onclick="showCustomer()" class="btn btn-success btn-flat" type="button"><i class="fa fa-search-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
            
                            <div class="form-group row">
                                <label for="discount" class="col-lg-2 control-label">Discount</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="discount" value="" readonly>
                                        <span class="input-group-btn">
                                            <button onclick="showDiscount()" class="btn btn-success btn-flat" type="button"><i class="fa fa-search-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pay" class="col-lg-2 control-label">Pay</label>
                                <div class="col-lg-8">
                                    <input type="number" id="pay_display" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="received" class="col-lg-2 control-label">Received</label>
                                <div class="col-lg-8">
                                    <input type="number" id="received" class="form-control" name="received" value="{{ $transaction->received ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="return" class="col-lg-2 control-label">Return</label>
                                <div class="col-lg-8">
                                    <input type="text" id="return" name="return" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button class="btn btn-success btn-sm btn-flat pull-right btn-save-transaction"><i class="fa fa-floppy-o"></i> Save Transaction</button>
            </div>
        </div>
    </div>
</div>

@includeIf('cart.product')
@includeIf('cart.customer')
@includeIf('cart.discount')

@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.sales-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaction.data', $transactionId) }}',
                dataSrc: 'data'
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: true },
                { data: 'product_id' },
                { data: 'product_name' },
                { data: 'selling_price' },
                { data: 'quantity', render: function (data, type, row) {
                    return '<input type="number" class="form-control input-sm quantity" data-id="'+ row.cart_id +'" value="'+ data +'">';
                }},
                { data: 'discount' },
                { data: 'subtotal' },
                { data: 'action', searchable: false, sortable: false },
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false,
        });

        table.on('draw.dt', function () {
            calculateTotal();
            loadForm($('#discount').val(), $('#received').val());
            setTimeout(() => {
                $('#received').trigger('input');
            }, 0);
        });

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let quantity = parseInt($(this).val());

            if (quantity < 1) {
                $(this).val(1);
                alert('The number cannot be less than 1');
                return;
            }
            if (quantity > 10000) {
                $(this).val(10000);
                alert('The number cannot exceed 10000');
                return;
            }

            $.ajax({
                type: 'PUT',
                url: `{{ url('/transaction') }}/cart/${id}`,
                data: {
                    '_token': $('[name=csrf-token]').attr('content'),
                    'quantity': quantity
                },
                success: function (response) {
                    table.ajax.reload(() => loadForm($('#discount').val()));
                },
                error: function (errors) {
                    alert('Unable to save data');
                    return;
                }
            });
        });

        $(document).on('input', '#discount', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#received').on('input', function () {
            let received = parseFloat($(this).val()) || 0;
            let pay = parseFloat($('#pay_display').val().replace(/[^\d.-]/g, '')) || 0.00;

            if (!isNaN(pay) && !isNaN(received)) {
                let returnAmount = received - pay;
                $('#return').val(returnAmount.toFixed(2));
            } else {
                $('#return').val(0.00);
            }

            loadForm($('#discount').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.btn-save-transaction').on('click', function (e) {
            e.preventDefault();

            // Check if there are any rows in the DataTable
            if (table.data().count() === 0) {
                alert('Please add at least one product to the cart before saving.');
                return;
            }

            // Proceed with saving the transaction
            let received = parseFloat($('#received').val()) || 0;
            let pay = parseFloat($('#pay_display').val().replace(/[^\d.-]/g, '')) || 0;

            if (received < pay) {
                alert('Received amount must be equal to or greater than Pay amount.');
                return;
            }

            // Serialize form data
            let formData = $('#form-sale').serialize();

            formData += '&pay_display=' + pay;

            $.ajax({
                type: 'POST',
                url: '{{ route('transactionCart.cartSave') }}',
                data: formData,
                success: function (response) {
                    console.log('Transaction saved successfully:', response);
                    alert('Transaction saved successfully');
                },
                error: function (xhr, status, error) {
                    alert('Failed to save transaction. Please try again.');
                }
            });

        });



    });

    function calculateTotal() {
        let total = 0;
        let discountValue = $('#discount').val().replace('%', '');
        let discount = parseInt(discountValue) || 0;

        $('.sales-table tbody tr').each(function () {
            let subtotalText = $(this).find('td:eq(6)').text().replace(/[^0-9.-]+/g, "");
            let subtotal = parseFloat(subtotalText);
            if (!isNaN(subtotal)) {
                total += subtotal;
            }
        });

        $('#total_display').val('₱ ' + total.toFixed(2));
        let pay = total - (total * (discount / 100));
        $('#pay_display').val(pay.toFixed(2));
        $('.display-payment').text('Pay: ₱ ' + pay.toFixed(2));
    }

    function loadForm(discount = 0, received = 0) {
        var transaction_id = $('#transaction_id').val();

        $.get(`{{ route('transaction.load_form') }}`, {
                discount: discount,
                transaction_id: transaction_id,
                received: received
            })
            .done(function (response) {
                $('#total_display').text('₱ ' + (response.total_display || 0));
                $('#pay_display').text('₱ ' + (response.pay_display || 0));
                $('#pay').val(response.pay || 0);
                $('.display-in-words').text(response.in_words);

                if (received != 0) {
                    $('#received').val(received);
                    $('.display-in-words').text(response.return_in_words);
                }

                $('#return').text('₱ ' + (response.return_display || 0));
            })
            .fail(function (xhr, status, error) {
                alert('Unable to display data');
                console.error(xhr, status, error);
            });
    }

    function showProduct() {
        $('#modal-product').modal('show');
    }

    function hideProduct() {
        $('#modal-product').modal('hide');
    }

    function selectProduct(product_id) {
        $('#product_id').val(product_id);
        hideProduct();
        addProduct();
    }

    function addProduct() {
        $.ajax({
            type: 'POST',
            url: '{{ route('transaction.store') }}',
            data: $('.form-product').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#product_id').val('');
                if (table) {
                    table.ajax.reload(null, false);
                } else {
                    console.error('DataTable `table` is not initialized correctly.');
                }
                loadForm($('#discount').val());
            },
            error: function (xhr, status, error) {
                var errorMessage = 'Unable to add product.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += ' ' + xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage += ' ' + xhr.statusText;
                } else {
                    errorMessage += ' Error occurred.';
                }
                console.error(errorMessage);
                alert(errorMessage);
            }
        });
    }

    function showCustomer() {
        $('#modal-customer').modal('show');
    }

    function selectCustomer(customer_id) {
        $('#customer_id').val(customer_id);
        $('#received').val(0).focus().select();
        hideCustomer();
    }

    function hideCustomer() {
        $('#modal-customer').modal('hide');
    }

    function showDiscount() {
        $('#modal-discount').modal('show');
    }

    function selectDiscount(discount_id, percentage) {
        $('#discount_id').val(discount_id);
        $('#discount').val(percentage + '%');
        $('#received').val(0).focus().select();
        hideDiscount();
        loadForm(percentage);
        calculateTotal();
    }

    function hideDiscount() {
        $('#modal-discount').modal('hide');
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete selected data?')) {
            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    '_token': $('[name=csrf-token]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.success);
                        table.ajax.reload(() => loadForm($('#discount').val()));
                    } else {
                        alert(response.error || 'Unable to delete data');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Unable to delete data');
                    console.error(xhr, status, error);
                }
            });
        }
    }
</script>
@endpush