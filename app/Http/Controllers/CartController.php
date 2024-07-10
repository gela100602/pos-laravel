<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Discount;
use App\Models\Cart;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if transactionId already exists in session
        if (session()->has('transactionId')) {
            $transactionId = session('transactionId');
        } else {
            // Check for an existing PaymentTransaction where received is 0 this will load the last transaction that has not been save
            $paymentTransaction = PaymentTransaction::where('received', 0.00)->orderBy('created_at', 'desc')->first();

            if ($paymentTransaction) {
                $transactionId = $paymentTransaction->transaction_id;
            } else {
                // Create a new PaymentTransaction
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->total_items = 0;
                $paymentTransaction->total_price = 0;
                $paymentTransaction->customer_id = null;
                $paymentTransaction->discount_id = null; // default
                $paymentTransaction->payment = 0.00;
                $paymentTransaction->received = 0.00;
                $paymentTransaction->user_id = auth()->id();
                $paymentTransaction->save();

                $transactionId = $paymentTransaction->transaction_id;
            }

            // Store transactionId in session
            session(['transactionId' => $transactionId]);
        }

        // Fetch necessary data
        $products = Product::all();
        $customers = Customer::all();
        $discounts = Discount::all();

        return view('cart.index', compact('transactionId', 'products', 'customers', 'discounts'));
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
                                        <button onclick="deleteData('. route('selectedCartProduct.destroy', $cart->cart_id) .')" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
           
            // Retrieve or create the payment transaction
            $paymentTransaction = PaymentTransaction::findOrFail($request->transaction_id);

            // Retrieve product details
            $product = Product::find($request->product_id);
            $selling_price = $product ? $product->selling_price : 0;

            // Check if the product already exists in the cart for this transaction
            $existingCartItem = Cart::where('transaction_id', $request->transaction_id)
                                    ->where('product_id', $request->product_id)
                                    ->first();

            if ($existingCartItem) {
                // Increment quantity and update subtotal
                $existingCartItem->quantity += 1;
                $existingCartItem->subtotal = $selling_price * $existingCartItem->quantity;
                $existingCartItem->save();
            } else {
                // Create new cart item
                $cartItem = new Cart();
                $cartItem->transaction_id = $request->transaction_id;
                $cartItem->product_id = $request->product_id;
                $cartItem->selling_price = $selling_price;
                $cartItem->quantity = $request->quantity ?? 1;
                $cartItem->subtotal = $selling_price * ($request->quantity ?? 1);
                $cartItem->save();
            }

            // Update payment transaction details
            $paymentTransaction->total_items = Cart::where('transaction_id', $request->transaction_id)->count();
            $paymentTransaction->total_price = Cart::where('transaction_id', $request->transaction_id)->sum('subtotal');
            $paymentTransaction->discount_id = $request->discount_id;
            $paymentTransaction->payment = $request->pay ?? 0;
            $paymentTransaction->received = $request->received ?? 0;
            $paymentTransaction->save();

            // Update product stock
            if ($product) {
                $product->stock -= 1; 
                $product->save();
            }

            return response()->json(['message' => 'Product added successfully'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Transaction not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add product', 'error' => $e->getMessage()], 500);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function updateCart(Request $request, $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->quantity = $request->quantity;
            $cart->subtotal = $cart->quantity * $cart->selling_price;
            $cart->save();

            return response()->json(['success' => 'Cart updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception Message: ' . $e->getMessage()], 500);
        }
    }

    public function destroyCartItem($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();

            return response()->json(['success' => 'Item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception Message: ' . $e->getMessage()], 500);
        }
    }



    public function saveTransaction(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'transaction_id' => 'required|exists:payment_transactions,transaction_id',
                'pay_display' => 'required|numeric',
                'received' => 'required|numeric|min:1',
                'discount_id' => 'nullable|exists:discounts,discount_id',
                'customer_id' => 'nullable|exists:customers,customer_id',
            ]);

            // Calculate total_items from carts associated with transaction_id
            $totalItems = Cart::where('transaction_id', $validatedData['transaction_id'])->sum('quantity');

            // Update the payment transaction
            $updateData = [
                'total_items' => $totalItems,
                'payment' => $validatedData['pay_display'],
                'received' => $validatedData['received'],
            ];

            // Handle nullable fields conditionally
            if (isset($validatedData['customer_id'])) {
                $updateData['customer_id'] = $validatedData['customer_id']; // not yet fetchable
            }

            if (isset($validatedData['discount_id'])) {
                $updateData['discount_id'] = $validatedData['discount_id']; // not yet fetchable
            }

            PaymentTransaction::where('transaction_id', $validatedData['transaction_id'])->update($updateData);

            // Destroy current session of transaction_id
            $request->session()->forget('transactionId');

            // Log success message
            Log::info('Transaction updated successfully');

            // Return a response (redirect or stay on page)
            return redirect()->route('cart.index')->with('success', 'Transaction updated successfully');
            
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Failed to update transaction: ' . $e->getMessage());

            // Return an error response
            return redirect()->back()->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

}
