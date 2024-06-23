<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::pluck('category_name', 'category_id');
        $suppliers = Supplier::pluck('supplier_name', 'supplier_id');

        return view('product.index', compact('categories', 'suppliers'));
    }

    public function data()
    {
        $products = Product::with('category', 'supplier')
            ->select(['product_id', 'product_name', 'supplier_id', 'category_id', 'purchase_price', 'selling_price', 'discount', 'stock', 'product_image'])
            ->get();

        return datatables()
            ->of($products)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '<input type="checkbox" name="product_id[]" value="' . $product->product_id . '">';
            })
            ->addColumn('category_name', function ($product) {
                return $product->category->category_name;
            })
            ->addColumn('supplier_name', function ($product) {
                return $product->supplier->supplier_name;
            })
            ->addColumn('product_image', function ($product) {
                return '<img src="' . asset('storage/' . $product->product_image) . '" height="50">';
            })
            ->addColumn('action', function ($product) {
                return '<div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('products.update', $product->product_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`' . route('products.destroy', $product->product_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['select_all', 'category_name', 'supplier_name', 'product_image', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,category_id',
            'supplier_id' => 'required|integer|exists:suppliers,supplier_id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'required|integer',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['discount'] = $request->input('discount', 0);

        if ($request->hasFile('product_image')) {
            $filenameWithExtension = $request->file('product_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('product_image')->storeAs('public/img/product', $filenameToStore);
            $validated['product_image'] = $filenameToStore;
        } else {
            $validated['product_image'] = 'img/product.png';
        }
    
        Product::create($validated);

        // return response()->json('Product saved successfully', 200);
        return redirect('/products');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());

        // return response()->json('Product updated successfully', 200);
        return redirect('/products');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->product_id as $id) {
            $product = Product::find($id);
            $product->delete();
        }

        return response(null, 204);
    }

}