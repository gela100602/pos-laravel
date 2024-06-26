<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form id="product-form" action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                    <!-- Product Name -->
                    <div class="form-group row">
                        <label for="product_name" class="col-lg-2 col-lg-offset-1 control-label">Product Name</label>
                        <div class="col-lg-6">
                            <input type="text" name="product_name" id="product_name" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="form-group row">
                        <label for="category_id" class="col-lg-2 col-lg-offset-1 control-label">Category</label>
                        <div class="col-lg-6">
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $id => $category_name)
                                    <option value="{{ $id }}">{{ $category_name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="form-group row">
                        <label for="supplier_id" class="col-lg-2 col-lg-offset-1 control-label">Supplier</label>
                        <div class="col-lg-6">
                            <select name="supplier_id" id="supplier_id" class="form-control" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $id => $supplier_name)
                                    <option value="{{ $id }}">{{ $supplier_name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Purchase Price -->
                    <div class="form-group row">
                        <label for="purchase_price" class="col-lg-2 col-lg-offset-1 control-label">Purchase Price</label>
                        <div class="col-lg-6">
                            <input type="number" name="purchase_price" id="purchase_price" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Selling Price -->
                    <div class="form-group row">
                        <label for="selling_price" class="col-lg-2 col-lg-offset-1 control-label">Selling Price</label>
                        <div class="col-lg-6">
                            <input type="number" name="selling_price" id="selling_price" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="form-group row">
                        <label for="discount" class="col-lg-2 col-lg-offset-1 control-label">Discount</label>
                        <div class="col-lg-6">
                            <input type="number" name="discount" id="discount" class="form-control" value="0">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="form-group row">
                        <label for="stock" class="col-lg-2 col-lg-offset-1 control-label">Stock</label>
                        <div class="col-lg-6">
                            <input type="number" name="stock" id="stock" class="form-control" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="form-group row">
                        <label for="product_image" class="col-lg-2 col-lg-offset-1 control-label">Product Image</label>
                        <div class="col-lg-6">
                            <input type="file" name="product_image" id="product_image" class="form-control">
                            <span class="help-block with-errors"></span>
                            <img id="image-preview" src="" alt="Image Preview" style="max-height: 150px; display: none;">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-flat">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>