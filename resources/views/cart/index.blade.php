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

    .sales-table tbody tr:last-child {
        display: none;
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
                        <label for="product_id" class="col-lg-2">Product ID</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="transaction_id" id="transaction_id" value="{{ $transaction_id }}">
                                {{-- <input type="hidden" name="product_id" id="product_id"> --}}
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
                        <th width="5%">#</th>
                        <th>Code</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th width="15%">Quantity</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="display-payment bg-primary"></div>
                        <div class="display-in-words"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transaction.save') }}" class="form-sale" method="post">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_items" id="total_items">
                            <input type="hidden" name="pay" id="pay">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ $selectedCustomer->customer_id }}">
                            <input type="hidden" name="discount_id" id="discount_id" value="{{ $selectedDiscount->discount_id }}">
                            
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
                                        <input type="text" class="form-control" id="customer_id" value="{{ $selectedMember->customer_id }}">
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
                                        <input type="number" class="form-control" id="discount" value="{{ $selectedDiscount->percentage }}">
                                        <span class="input-group-btn">
                                            <button onclick="showDiscount()" class="btn btn-success btn-flat" type="button"><i class="fa fa-search-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label for="discount" class="col-lg-2 control-label">Discount</label>
                                <div class="col-lg-8">
                                    <input type="number" name="discount" id="discount" class="form-control" 
                                        value="{{ ! empty($selectedMember->member_id) ? $discount : 0 }}" 
                                        readonly>
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <label for="pay" class="col-lg-2 control-label">Pay</label>
                                <div class="col-lg-8">
                                    <input type="text" id="pay_display" class="form-control" readonly>
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
                <button type="submit" class="btn btn-success btn-sm btn-flat pull-right btn-save"><i class="fa fa-floppy-o"></i> Save Transaction</button>
            </div>
        </div>
    </div>
</div>

{{-- individual select forms --}}
@includeIf('cart.product')
@includeIf('cart.customer')
@includeIf('cart.discount')

@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.sales-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaction.data', $transaction_id) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'product_id'},
                {data: 'product_name'},
                {data: 'selling_price'},
                {data: 'quantity'},
                {data: 'discount'},
                {data: 'subtotal'},
                {data: 'action', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#discount').val());
            setTimeout(() => {
                $('#received').trigger('input');
            }, 300);
        });

        table2 = $('.product-table').DataTable();

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

            $.post(`{{ url('/transaction') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'quantity': quantity
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#discount').val()));
                    });
                })
                .fail(errors => {
                    alert('Unable to save data');
                    return;
                });
        });

        $(document).on('input', '#discount', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#received').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#discount').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-save').on('click', function () {
            $('.form-sale').submit();
        });
    });

    // Product
    function showProduct() {
        $('#modal-product').modal('show');
    }

    function hideProduct() {
        $('#modal-product').modal('hide');
    }

    function selectProduct(product_id) {
        $('#product_id').val(product_id);
        // $('#product_code').val(code);
        hideProduct();
        addProduct();
    }

    function addProduct() {
        $.post('{{ route('transaction.store') }}', $('.form-product').serialize())
            .done(response => {
                $('#product_id').focus();
                table.ajax.reload(() => loadForm($('#discount').val()));
            })
            .fail(errors => {
                alert('Unable to save data');
                return;
            });
    }

    // Customer
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

    // Discount
    function showDiscount() {
        $('#modal-discount').modal('show');
    }

    function selectDiscount(discount_id, percentage) {
        $('#discount_id').val(discount_id);
        $('#percentage').val(percentage);
        $('#received').val(0).focus().select();
        hideDiscount();
    }

    function hideDiscount() {
        $('#modal-discount').modal('hide');
    }

    // Delete Data
    function deleteData(url) {
        if (confirm('Are you sure you want to delete selected data?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail((errors) => {
                    alert('Unable to delete data');
                    return;
                });
        }
    }

    // Load Form
    function loadForm(discount = 0, received = 0) {
        $('#total').val($('.total').text());
        $('#total_items').val($('.total_items').text());

        $.get(`{{ url('/transaction/loadform') }}/${discount}/${$('.total').text()}/${received}`)
        // $.get(`{{ url('/transaction/loadform') }}₱{discount}₱{$('.total').text()}₱{received}`)
            .done(response => {
                $('#total_display').val('₱ '+ response.total_display);
                $('#pay_display').val('₱ '+ response.pay_display);
                $('#pay').val(response.pay);
                $('.display-payment').text('Pay: ₱ '+ response.pay_display);
                $('.display-in-words').text(response.in_words);

                $('#return').val('₱'+ response.return_display);
                if ($('#received').val() != 0) {
                    $('.display-payment').text('Return: ₱ '+ response.return_display);
                    $('.display-in-words').text(response.return_in_words);
                }
            })
            .fail(errors => {
                alert('Unable to display data');
                return;
            })
    }
</script>
@endpush
