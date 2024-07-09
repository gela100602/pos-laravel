<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\User;
use App\Models\Product;

// use App\Models\Setting;
use Illuminate\Http\Request;
// use PDF;

class SalesController extends Controller
{
    public function index()
    {
        $customers = Customer::pluck('customer_id', 'customer_id');
        $discounts = Discount::pluck('percentage', 'discount_id');
        $users = User::pluck('username', 'user_id');

        return view('payment_transaction.index', compact('customers', 'discounts', 'users'));
    }

    public function data($transactionId)
    {
        try {
            $payment_transactions = PaymentTransaction::where('transaction_id', $transactionId)
                ->with(['carts.product', 'carts.discount'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Prepare DataTables response
            $data = [];
            foreach ($payment_transactions as $payment_transaction) {
                foreach ($payment_transaction->carts as $cart) {
                    $discountPercentage = optional($cart->discount)->percentage ?? 0;

                    $data[] = [
                        'DT_RowIndex' => '',
                        'cart_id' => $cart->cart_id,
                        'product_id' => optional($cart->product)->product_id,
                        'product_name' => optional($cart->product)->product_name,
                        'selling_price' => $cart->selling_price,
                        'quantity' => $cart->quantity,
                        'discount' => $discountPercentage,
                        'subtotal' => $cart->subtotal,
                        'action' => '<div class="btn-group">
                                        <button onclick="deleteData(`'. route('selectedCartProduct.destroy', $cart->cart_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                    </div>',
                    ];
                }
            }

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            return response()->json([
                'error' => 'Exception Message: ' . $e->getMessage()
            ], 500);
        }
    }


    public function create()
    {
        $paymentTransaction = new PaymentTransaction();
        $paymentTransaction->total_items = 0;
        $paymentTransaction->total_price = 0;
        $paymentTransaction->discount_id = 0;
        $paymentTransaction->payment = 0;
        $paymentTransaction->received = 0;
        $paymentTransaction->user_id = auth()->id();
        $paymentTransaction->save();

        $transactionId = $paymentTransaction->transaction_id;

        session(['transaction_id' => $transactionId]);
        return view('cart.index');
    }


    public function store(Request $request)
    {
        try {
            $payment_transaction = PaymentTransaction::findOrFail($request->transaction_id);

            // Update payment transaction details
            $payment_transaction->total_items = $request->total_items;
            $payment_transaction->total_price = $request->total;
            $payment_transaction->discount_id = $request->discount_id;
            $payment_transaction->payment = $request->pay;
            $payment_transaction->received = $request->received;
            $payment_transaction->update();

            // Update cart details and product stock
            $details = Cart::where('transaction_id', $payment_transaction->transaction_id)->get();
            foreach ($details as $item) {
                $item->discount = $request->discount_id;
                $item->update();

                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock -= $item->quantity;
                    $product->update();
                }
            }

            // Optionally, you might return a JSON response indicating success
            return response()->json(['message' => 'Transaction updated successfully'], 200);
            
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['message' => 'Failed to update transaction', 'error' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        $details = Cart::with('product')->where('transaction_id', $id)->get();

        return datatables()
            ->of($details)
            ->addIndexColumn()
            ->addColumn('product_id', function ($details) {
                return '<span class="label label-success">' . $details->product->product_id . '</span>';
            })
            ->addColumn('product_name', function ($details) {
                return $details->product->product_name;
            })
            ->addColumn('selling_price', function ($details) {
                return '₱ ' . format_currency($details->selling_price);
            })
            ->addColumn('quantity', function ($details) {
                return format_currency($details->quantity);
            })
            ->addColumn('subtotal', function ($details) {
                return '₱ ' . format_currency($details->subtotal);
            })
            ->rawColumns(['product_id'])
            ->make(true);
    }

    public function destroy($id)
    {
        $payment_transactions = PaymentTransaction::find($id);
        $details = Cart::where('transaction_id', $payment_transactions->transaction_id)->get();
        foreach ($details as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock += $item->quantity;
                $product->update();
            }

            $item->delete();
        }

        $payment_transactions->delete();

        return response(null, 204);
    }

    // public function complete()
    // {
    //     $setting = Setting::first();

    //     return view('sales.complete', compact('setting'));
    // }

    // public function smallReceipt()
    // {
    //     $setting = Setting::first();
    //     $sales = Sales::find(session('sales_id'));
    //     if (!$sales) {
    //         abort(404);
    //     }
    //     $details = SalesDetail::with('product')
    //         ->where('sales_id', session('sales_id'))
    //         ->get();
        
    //     return view('sales.small_receipt', compact('setting', 'sales', 'details'));
    // }

    // public function largeReceipt()
    // {
    //     $setting = Setting::first();
    //     $sales = Sales::find(session('sales_id'));
    //     if (!$sales) {
    //         abort(404);
    //     }
    //     $details = SalesDetail::with('product')
    //         ->where('sales_id', session('sales_id'))
    //         ->get();

    //     $pdf = PDF::loadView('sales.large_receipt', compact('setting', 'sales', 'details'));
    //     $pdf->setPaper(0, 0, 609, 440, 'portrait');
    //     return $pdf->stream('Transaction-' . date('Y-m-d-his') . '.pdf');
    // }
}
