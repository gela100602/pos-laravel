<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\PaymentTransaction;
use App\Models\Cart;
use NumberToWords\NumberToWords;

use Illuminate\Http\Request;

class SalesDetailController extends Controller
{
    // public function index()
    // {
    //     $products = Product::orderBy('product_name')->get();
    //     $customer = Customer::orderBy('name')->get();
    //     $discount = Discount::first()->discount ?? 0;

    //     // $discount = Discount::first()->discount ?? 0;

    //     // Check whether there are any transactions in progress
    //     if ($transaction_id = session('transaction_id')) {
    //         $transaction = PaymentTransaction::find($transaction_id);
    //         $selectedCustomer = $transaction->customer ?? new Customer();
    //         $selectedDiscount = $transaction->discount ?? 0;
        
    //         return view('cart.index', compact('products', 'customer', 'discount', 'transaction_id', 'transaction', 'selectedCustomer', 'selectedDiscount'));
    //     } else {
    //         if (auth()->user()) {
    //             return redirect()->route('transaction.new');
    //         } else {
    //             return redirect()->route('dashboard');
    //         }
    //     }
    // }

    // public function data($id)
    // {
    //     $details = Cart::with('product')
    //         ->where('transaction_id', $id)
    //         ->get();

    //     $data = array();
    //     $total = 0;
    //     $total_items = 0;

    //     foreach ($details as $item) {
    //         $row = array();
    //         $row['product_id'] = '<span class="label label-success">'. $item->product['product_id'] .'</span';
    //         $row['product_name'] = $item->product['product_name'];
    //         $row['selling_price']  = '₱ '. format_currency($item->selling_price);
    //         $row['quantity']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->cart_id .'" value="'. $item->quantity .'">';
    //         $row['discount']      = $item->discount . '%';
    //         $row['subtotal']    = '₱ '. format_currency($item->subtotal);
    //         $row['actions']        = '<div class="btn-group">
    //                                 <button onclick="deleteData(`'. route('transaction.destroy', $item->cart_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
    //                             </div>';
    //         $data[] = $row;

    //         $total += $item->selling_price * $item->quantity - (($item->discount * $item->quantity) / 100 * $item->selling_price);
    //         $total_items += $item->quantity;
    //     }
    //     $data[] = [
    //         'product_id' => '
    //             <div class="total hide">'. $total .'</div>
    //             <div class="total_items hide">'. $total_items .'</div>',
    //         'product_name' => '',
    //         'selling_price'  => '',
    //         'quantity'      => '',
    //         'discount'      => '',
    //         'subtotal'    => '',
    //         'actions'        => '',
    //     ];

    //     return datatables()
    //         ->of($data)
    //         ->addIndexColumn()
    //         ->rawColumns(['actions', 'product_id', 'quantity'])
    //         ->make(true);
    // }

    // public function store(Request $request)
    // {
    //     $product = Product::where('product_id', $request->product_id)->first();
    //     if (! $product) {
    //         return response()->json('Data failed to save', 400);
    //     }

    //     $detail = new Cart();
    //     $detail->transaction_id = $request->transaction_id;
    //     $detail->product_id = $product->product_id;
    //     $detail->selling_price = $product->selling_price;
    //     $detail->quantity = 1;
    //     $detail->discount = $product->discount;
    //     $detail->subtotal = $product->selling_price - ($product->discount / 100 * $product->selling_price);
    //     $detail->save();

    //     return response()->json('Data saved successfully', 200);
    // }
    
    // public function update(Request $request, $id)
    // {
    //     $detail = Cart::find($id);
    //     $detail->quantity = $request->quantity;
    //     $detail->subtotal = $detail->sale_price * $request->quantity - (($detail->discount * $request->quantity) / 100 * $detail->selling_price);
    //     $detail->update();
    // }

    // public function destroy($id)
    // {
    //     $detail = Cart::find($id);
    //     $detail->delete();

    //     return response(null, 204);
    // }

    // public function loadForm($discount = 0, $total = 0, $received = 0)
    // {
    //     $pay = $total - ($discount / 100 * $total);
    //     $change = ($received != 0) ? $received - $pay : 0;

    //     // Initialize the number to words converter
    //     $numberToWords = new NumberToWords();
    //     $numberTransformer = $numberToWords->getNumberTransformer('en');

    //     $data = [
    //         'total_display' => format_currency($total),
    //         'pay' => $pay,
    //         'pay_display' => format_currency($pay),
    //         'toWords' => ucwords($numberTransformer->toWords($pay) . ' Pesos'),
    //         'return_display' => format_currency($change),
    //         'return_in_words' => ucwords($numberTransformer->toWords($change) . ' Pesos'),
    //     ];

    //     return response()->json($data);
    // }
}
