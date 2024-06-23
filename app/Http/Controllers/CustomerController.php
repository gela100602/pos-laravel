<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function data()
    {
        $customers = Customer::orderBy('customer_id', 'desc')->get();

        return datatables()
            ->of($customers)
            ->addIndexColumn()
            ->addColumn('action', function ($customer) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('customer.update', $customer->customer_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`' . route('customer.destroy', $customer->customer_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->email = $request->email;
        $customer->contact_number = $request->contact_number;
        $customer->save();

        return response()->json('Customer saved successfully', 200);
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        return response()->json($customer);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
        ]);

        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->email = $request->email;
        $customer->contact_number = $request->contact_number;
        $customer->update();

        return response()->json('Customer updated successfully', 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return response(null, 204);
    }
}