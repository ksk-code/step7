<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;



class ProductController extends Controller
{

    public function edit(Product $product)
    {
        $companies = \App\Models\Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function index(Request $request)
    {
        $companies = Company::all();
        $products = Product::query();
    
        $keyword = $request->input('keyword');
        $companyId = $request->input('company_id');

        if (!empty($keyword)) {
            $products->where('product_name', 'LIKE', "%{$keyword}%") // 商品名に基づく部分一致検索
                    ->orWhere('comment', 'LIKE', "%{$keyword}%"); // 商品説明に基づく部分一致検索
        }

        if (!empty($companyId)) {
            $products->where('company_id', $companyId);
        }
    
        $products = $products->paginate(10); // ページネーション
    
        return view('products.index', ['products' => $products,'companies' => $companies,]);
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.detail', compact('product'));
    }
    
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));

        return view('products.create');
    }

    public function store(CreateProductRequest $request)
    {
        DB::transaction(function () use ($request) {

        $product = new Product;
        $product->fill($request->validated());

        if($request->hasFile('image')){
            $name = $request->file('image')->getClientOriginalName();
            $request->file('image')->move('storage/images', $name);
            $product->img_path = $name;
        }

        $product->save();
    });

    return redirect()->route('products.create');
}

public function update(UpdateProductRequest $request, Product $product)
{
    DB::transaction(function () use ($request, $product) {

        if($request->hasFile('image')){
            $name = $request->file('image')->getClientOriginalName();
            $request->file('image')->move('storage/images', $name);
            $product->img_path = $name;
        }

        $product->update($request->validated());
    }); 

    return redirect()->route('products.edit', ['product' => $product->id]);
}

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $product->delete();
        });
    
        return redirect()->route('products.index');
    }
}
