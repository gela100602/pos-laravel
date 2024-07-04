<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesDetailController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('product_name')->get();
        // $discount = Setting::first()->discount ?? 0;

        // Check whether there are any transactions in progress
        if ($sale_id = session('sale_id')) {
            $sale = Sales::find($sale_id);

            return view('sale_detail.index', compact('products', 'members', 'discount', 'sale_id', 'sale', 'selectedMember'));
        } else {
            if (auth()->user()) {
                return redirect()->route('transaction.new');
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    public function data($id)
    {
        $details = SalesDetail::with('product')
            ->where('sale_id', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_items = 0;

        foreach ($details as $item) {
            $row = array();
            $row['product_code'] = '<span class="label label-success">'. $item->product['product_code'] .'</span';
            $row['product_name'] = $item->product['product_name'];
            $row['sale_price']  = '$ '. format_currency($item->sale_price);
            $row['quantity']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->sale_detail_id .'" value="'. $item->quantity .'">';
            $row['discount']      = $item->discount . '%';
            $row['subtotal']    = '$ '. format_currency($item->subtotal);
            $row['actions']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaction.destroy', $item->sale_detail_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->sale_price * $item->quantity - (($item->discount * $item->quantity) / 100 * $item->sale_price);
            $total_items += $item->quantity;
        }
        $data[] = [
            'product_code' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_items hide">'. $total_items .'</div>',
            'product_name' => '',
            'sale_price'  => '',
            'quantity'      => '',
            'discount'      => '',
            'subtotal'    => '',
            'actions'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['actions', 'product_code', 'quantity'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $product = Product::where('product_id', $request->product_id)->first();
        if (! $product) {
            return response()->json('Data failed to save', 400);
        }

        $detail = new SalesDetail();
        $detail->sale_id = $request->sale_id;
        $detail->product_id = $product->product_id;
        $detail->sale_price = $product->sale_price;
        $detail->quantity = 1;
        $detail->discount = $product->discount;
        $detail->subtotal = $product->sale_price - ($product->discount / 100 * $product->sale_price);
        $detail->save();

        return response()->json('Data saved successfully', 200);
    }
    
    public function update(Request $request, $id)
    {
        $detail = SalesDetail::find($id);
        $detail->quantity = $request->quantity;
        $detail->subtotal = $detail->sale_price * $request->quantity - (($detail->discount * $request->quantity) / 100 * $detail->sale_price);
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = SalesDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    // public function loadForm($discount = 0, $total = 0, $received = 0)
    // {
    //     $pay = $total - ($discount / 100 * $total);
    //     $change = ($received != 0) ? $received - $pay : 0;
    //     $data = [
    //         'totalrp' => format_currency($total),
    //         'pay' => $pay,
    //         'payrp' => format_currency($pay),
    //         'terbilang' => ucwords(toWords($pay). ' Dollars'),
    //         'changerp' => format_currency($change),
    //         'change_in_words' => ucwords(toWords($change). ' Dollars'),
    //     ];

    //     return response()->json($data);
    // }
}
