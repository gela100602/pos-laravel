<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('discount.index');
    }

    public function data()
    {
        $discounts = Discount::orderBy('discount_id', 'desc')->get();

        return datatables()
            ->of($discounts)
            ->addIndexColumn()
            ->addColumn('action', function ($discount) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('discount.update', $discount->discount_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`' . route('discount.destroy', $discount->discount_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
            'discount_type' => 'required',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $discount = new Discount();
        $discount->discount_type = $request->discount_type;
        $discount->percentage = $request->percentage;
        $discount->save();

        return response()->json('Discount saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::find($id);

        return response()->json($discount);
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
            'discount_type' => 'required',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $discount = Discount::find($id);
        $discount->discount_type = $request->discount_type;
        $discount->percentage = $request->percentage;
        $discount->update();

        return response()->json('Discount updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = Discount::find($id);
        $discount->delete();

        return response(null, 204);
    }
}
