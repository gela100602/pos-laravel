<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function data()
    {
        $sales = Sales::with('member')->orderBy('id_sales', 'desc')->get();

        return datatables()
            ->of($sales)
            ->addIndexColumn()
            ->addColumn('total_item', function ($sales) {
                return format_currency($sales->total_item);
            })
            ->addColumn('total_price', function ($sales) {
                return '$ ' . format_currency($sales->total_price);
            })
            ->addColumn('payment', function ($sales) {
                return '$ ' . format_currency($sales->payment);
            })
            ->addColumn('date', function ($sales) {
                //return format_date($sales->created_at, false);
            })

            ->editColumn('discount', function ($sales) {
                return $sales->discount . '%';
            })
            ->editColumn('cashier', function ($sales) {
                return $sales->user->name ?? '';
            })
            ->addColumn('actions', function ($sales) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('sales.show', $sales->id_sales) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('sales.destroy', $sales->id_sales) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['actions', 'member_code'])
            ->make(true);
    }

    public function create()
    {
        $sales = new Sales();
        $sales->total_item = 0;
        $sales->total_price = 0;
        $sales->discount = 0;
        $sales->payment = 0;
        $sales->received = 0;
        $sales->id_user = auth()->id();
        $sales->save();

        session(['id_sales' => $sales->id_sales]);
        return redirect()->route('transaction.index');
    }

    public function store(Request $request)
    {
        $sales = Sales::findOrFail($request->id_sales);
        $sales->total_item = $request->total_item;
        $sales->total_price = $request->total;
        $sales->discount = $request->discount;
        $sales->payment = $request->payment;
        $sales->received = $request->received;
        $sales->update();

        $details = SalesDetail::where('id_sales', $sales->id_sales)->get();
        foreach ($details as $item) {
            $item->discount = $request->discount;
            $item->update();

            $product = Product::find($item->id_product);
            $product->stock -= $item->quantity;
            $product->update();
        }

        return redirect()->route('transaction.complete');
    }

    public function show($id)
    {
        $details = SalesDetail::with('product')->where('id_sales', $id)->get();

        return datatables()
            ->of($details)
            ->addIndexColumn()
            ->addColumn('product_code', function ($details) {
                return '<span class="label label-success">' . $details->product->product_code . '</span>';
            })
            ->addColumn('product_name', function ($details) {
                return $details->product->product_name;
            })
            ->addColumn('sale_price', function ($details) {
                return '$ ' . format_currency($details->sale_price);
            })
            ->addColumn('quantity', function ($details) {
                return format_currency($details->quantity);
            })
            ->addColumn('subtotal', function ($details) {
                return '$ ' . format_currency($details->subtotal);
            })
            ->rawColumns(['product_code'])
            ->make(true);
    }

    public function destroy($id)
    {
        $sales = Sales::find($id);
        $details = SalesDetail::where('id_sales', $sales->id_sales)->get();
        foreach ($details as $item) {
            $product = Product::find($item->id_product);
            if ($product) {
                $product->stock += $item->quantity;
                $product->update();
            }

            $item->delete();
        }

        $sales->delete();

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
    //     $sales = Sales::find(session('id_sales'));
    //     if (!$sales) {
    //         abort(404);
    //     }
    //     $details = SalesDetail::with('product')
    //         ->where('id_sales', session('id_sales'))
    //         ->get();
        
    //     return view('sales.small_receipt', compact('setting', 'sales', 'details'));
    // }

    // public function largeReceipt()
    // {
    //     $setting = Setting::first();
    //     $sales = Sales::find(session('id_sales'));
    //     if (!$sales) {
    //         abort(404);
    //     }
    //     $details = SalesDetail::with('product')
    //         ->where('id_sales', session('id_sales'))
    //         ->get();

    //     $pdf = PDF::loadView('sales.large_receipt', compact('setting', 'sales', 'details'));
    //     $pdf->setPaper(0, 0, 609, 440, 'portrait');
    //     return $pdf->stream('Transaction-' . date('Y-m-d-his') . '.pdf');
    // }
}
