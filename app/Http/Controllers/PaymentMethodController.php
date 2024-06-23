<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethods;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment-method.index');
    }

    public function data()
    {
        $methods = PaymentMethods::orderBy('method_id', 'desc')->get();

        return datatables()
            ->of($methods)
            ->addIndexColumn()
            ->addColumn('action', function ($method) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('payment-method.update', $method->method_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`' . route('payment-method.destroy', $method->method_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
            'method' => 'required',
        ]);

        $method = new PaymentMethods();
        $method->method = $request->method;
        $method->save();

        return response()->json('Payment method saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $method = PaymentMethods::find($id);

        return response()->json($method);
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
            'method' => 'required',
        ]);

        $method = PaymentMethods::find($id);
        $method->method = $request->method;
        $method->update();

        return response()->json('Payment method updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $method = PaymentMethods::find($id);
        $method->delete();

        return response(null, 204);
    }
}