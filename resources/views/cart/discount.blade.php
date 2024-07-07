<div class="modal fade" id="modal-discount" tabindex="-1" role="dialog" aria-labelledby="modal-discount">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Discount</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Discount Type</th>
                        <th>Percentage</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $key => $discount)
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td>{{ $discount->discount_type }}</td>
                                <td>{{ $discount->percentage }}%</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="selectDiscount('{{ $discount->discount_id }}', '{{ $discount->percentage }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Select
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
