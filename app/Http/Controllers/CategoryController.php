<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();
        if($user){
            return view('admin.dashboard');
        }else{
            return view('auth\login');
        }
    }

    public function category(){

        return view('admin.new-category');
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
        //
        $categoria = new Category();

        $categoria->name = $request->name;

        $categoria->description = $request->description;

        $categoria->subcategory = $request->subcategory;
        //dd($categoria);

        $categoria->save();

        return redirect()->back()->with('mensaje', 'La categoría ha sido creada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $categories)
    {
        //
        $categories = Category::all();

        return view('admin.show-categories', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update($id){

        $category = Category::find($id);

        return view('admin.update-category', compact('category'));

    }

    public function edit($id, Request $request){

  
        $category = Category::find($id);

        $category->name = $request->name;

        $category->description = $request->description;

        $category->subcategory = $request->subcategory;

        $category->save();

        return redirect()->back()->with('mensaje', 'La categoría ha sido actualizada.');

    }

    public function delete($id){

        $category = Category::find($id);

        $category->delete();

        return redirect()->back()->with('mensaje', 'La categoría ha sido eliminada');

    }
}
