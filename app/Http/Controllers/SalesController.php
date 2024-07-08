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

    public function data()
    {
        $payment_transactions = PaymentTransaction::with('customer', 'discount', 'user')->orderBy('transaction_id', 'desc')->get();

        return datatables()
            ->of($payment_transactions)
            ->addIndexColumn()
            ->addColumn('date', function ($payment_transactions) {
                return format_date($payment_transactions->created_at, 'Y-m-d');
            })
            ->addColumn('customer_id', function ($payment_transactions) {
                $customer = $payment_transactions->customer ? $payment_transactions->customer->customer_id : '';
                return '<span class="label label-success">'. $customer .'</span>';
            })
            ->addColumn('total_item', function ($payment_transactions) {
                return format_currency($payment_transactions->total_items);
            })
            ->addColumn('total_price', function ($payment_transactions) {
                return '₱ ' . format_currency($payment_transactions->total_price);
            })
            // ->editColumn('percentage', function ($payment_transactions) {
            //     return $payment_transactions->discount->percentage . '%';
            // })
            // ->editColumn('percentage', function ($payment_transactions) {
            //     return optional($payment_transactions->discount)->percentage . '%';
            // })
            ->editColumn('percentage', function ($payment_transactions) {
                $percentage = $payment_transactions->discount ? $payment_transactions->discount->percentage . '%' : '';
                return $percentage;
            })
            ->addColumn('payment', function ($payment_transactions) {
                return '₱ ' . format_currency($payment_transactions->payment);
            })
            ->editColumn('username', function ($payment_transactions) {
                return $payment_transactions->user->username ?? '';
            })
            ->addColumn('action', function ($payment_transactions) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('payment_transaction.show', $payment_transactions->transaction_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('payment_transaction.destroy', $payment_transactions->transaction_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'customer_id', 'percentage', 'username'])
            ->make(true);
    }

    public function create()
    {
        $payment_transactions = new PaymentTransaction();
        $payment_transactions->total_items = 0;
        $payment_transactions->total_price = 0;
        $payment_transactions->discount_id = 0;
        $payment_transactions->payment = 0;
        $payment_transactions->received = 0;
        $payment_transactions->user_id = auth()->id();
        $payment_transactions->save();

        session(['transaction_id' => $payment_transactions->transaction_id]);
        return view('cart.index');
        
    }

    public function store(Request $request)
    {
        $payment_transactions = PaymentTransaction::findOrFail($request->transaction_id);
        $payment_transactions->total_item = $request->total_item;
        $payment_transactions->total_price = $request->total;
        $payment_transactions->discount = $request->discount;
        $payment_transactions->pay = $request->pay;
        $payment_transactions->accepted = $request->accepted;
        $payment_transactions->update();

        $details = Cart::where('transaction_id', $payment_transactions->transaction_id)->get();
        foreach ($details as $item) {
            $item->discount = $request->discount;
            $item->update();

            $product = Product::find($item->product_id);
            $product->stock -= $item->quantity;
            $product->update();
        }

        return redirect()->route('transaction.complete');
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
