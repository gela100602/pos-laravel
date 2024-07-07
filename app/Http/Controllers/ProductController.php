<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::pluck('category_name', 'category_id');
        $suppliers = Supplier::pluck('supplier_name', 'supplier_id');

        $products = Product::where('is_deleted', 0)->get();

        return view('product.index', compact('products', 'categories', 'suppliers'));
    }

    public function data()
    {
        $products = Product::with('category', 'supplier')
            ->where('is_deleted', 0)
            ->select([
                'product_id',
                'product_name',
                'supplier_id',
                'category_id',
                'purchase_price',
                'selling_price',
                'discount',
                'stock',
                'product_image'
            ])
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
                // return optional($product->supplier)->supplier_name ?? '';
            })
            ->addColumn('product_image', function ($product) {

                $productImagePath = public_path('storage/product_image/' . $product->product_image);
                
                // Check if the file exists in the public disk
                if (file_exists($productImagePath)) {
                    // File exists, return the image path
                    return $product->product_image;
                } else {
                    // File does not exist, return the default image path or filename
                    return 'default-item.png';
                }                

            })
            ->addColumn('action', function ($product) {
                return '<div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('products.update', $product->product_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`' . route('products.markAsDeleted', $product->product_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
            $request->file('product_image')->storeAs('public/product_image', $filenameToStore);
            $validated['product_image'] = $filenameToStore;
        } else {
            $validated['product_image'] = 'default-item.png';
        }

        Product::create($validated);

        return redirect('/products');
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product->product_image_url = asset('storage/product_image/' . $product->product_image);

        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,category_id',
            'supplier_id' => 'required|integer|exists:suppliers,supplier_id',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'required|integer',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ensure discount is set to 0 if not provided
        $validatedData['discount'] = $request->input('discount', 0);

        if ($request->hasFile('product_image')) {
            $filenameWithExtension = $request->file('product_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            
            $request->file('product_image')->storeAs('public/product_image', $filenameToStore);
            
            $validatedData['product_image'] = $filenameToStore;
        }
        else {
            $validatedData['product_image'] = $product->product_image;
        }

        // Update the product with validated data
        $product->update($validatedData);

        // Return a JSON response for API or redirect to index page
        return response()->json(['message' => 'Product updated successfully']);
        return redirect()->route('products.index');
    }

    public function markAsDeleted($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->is_deleted = 1;
            if ($product->save()) {
                return response()->json(['message' => 'Product marked as deleted successfully']);
            } else {
                return response()->json(['error' => 'Failed to update product'], 500);
            }
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
  
    
    // public function deleteSelected(Request $request)
    // {
    //     $selectedIds = $request->product_id;
    
    //     if (!empty($selectedIds)) {
    //         Product::whereIn('product_id', $selectedIds)->update(['is_deleted' => 1]);
    //         return response()->json(['message' => 'Selected products marked as deleted successfully'], 200);
    //     } else {
    //         return response()->json(['error' => 'No products selected'], 400);
    //     }
    // }
    
}