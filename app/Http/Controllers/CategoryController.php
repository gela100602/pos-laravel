<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }

    public function data()
    {
        $categories = Category::orderBy('category_id', 'desc')->get();

        return datatables()
            ->of($categories)
            ->addIndexColumn()
            ->addColumn('actions', function ($category) {
                return '
            <div class="btn-group">
                <button onclick="editForm(`' . route('category.update', $category->category_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                <button onclick="deleteData(`' . route('category.destroy', $category->category_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
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
            'category_name' => 'required|string|max:255',
        ]);

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();

        return response()->json('Category saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json($category);
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
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->category_name = $request->category_name;
        $category->save();

        return response()->json('Category updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response(null, 204);
    }
}
