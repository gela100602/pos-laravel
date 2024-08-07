<div class="modal fade" id="modal-customer" tabindex="-1" role="dialog" aria-labelledby="modal-customer">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Customers</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-customer table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($customers as $key => $item)  
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->contact_number }}</td>
                                <td>{{ $item->address }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="selectCustomer('{{ $item->customer_id }}')">
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