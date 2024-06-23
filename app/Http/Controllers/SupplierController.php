<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supplier.index');
    }

    public function data()
    {
        $supplier = Supplier::orderBy('supplier_id', 'desc')->get();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('action', function ($supplier) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('supplier.update', $supplier->supplier_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`' . route('supplier.destroy', $supplier->supplier_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required'
        ]);

        $supplier = new Supplier();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->address = $request->address;
        $supplier->email = $request->email;
        $supplier->contact_number = $request->contact_number;
        $supplier->save();

        return response()->json('Supplier saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);

        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required'
        ]);

        $supplier = Supplier::find($id);
        $supplier->supplier_name = $request->supplier_name;
        $supplier->address = $request->address;
        $supplier->email = $request->email;
        $supplier->contact_number = $request->contact_number;
        $supplier->update();

        return response()->json('Supplier updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return response(null, 204);
    }
}