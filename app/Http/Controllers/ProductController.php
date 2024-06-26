<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;




class ProductController extends Controller
{

    public function edit(Product $product){

        $companies = \App\Models\Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function index(){

        $companies = Company::all();
        $products = Product::query();

        $products = Product::query()->paginate(10);

    
        return view('products.index', ['products' => $products,'companies' => $companies,]);
    }
    
    public function show($id){

        $product = Product::findOrFail($id);
        return view('products.detail', compact('product'));
    }
    
    public function create(){

        $companies = Company::all();
        return view('products.create', compact('companies'));

        return view('products.create');
    }

    public function store(CreateProductRequest $request){
        DB::beginTransaction();
    
        try{
            $product = new Product();
            $product->fill($request->validated());
    
            if($request->hasFile('image')){
                $name = $request->file('image')->getClientOriginalName();
                $request->file('image')->move('storage/images', $name);
                $product->img_path = $name;
            }
            $product->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back();
        }
    
        return redirect()->route('products.create');
    }

public function update(UpdateProductRequest $request, Product $product){
        DB::beginTransaction();
    
        try{
            if($request->hasFile('image')){
                $name = $request->file('image')->getClientOriginalName();
                $request->file('image')->move('storage/images', $name);
                $product->img_path = $name;
            }
            
            $product->update($request->validated());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back();
        }

    return redirect()->route('products.edit', ['product' => $product->id]);
}

    public function destroy(Product $product){
        
        DB::beginTransaction();
    
            try{
                $product->delete();
                DB::commit();
                return response()->json(['success' => true], 200);            
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Failed to delete product'], 500);
            }
        }
        
        public function search(Request $request)
        {
            $keyword = $request->input('keyword');
            $companyId = $request->input('company_id');
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $minStock = $request->input('min_stock');
            $maxStock = $request->input('max_stock');
    
            $query = Product::query();
    
            if (!empty($keyword)) {
                $query->where('product_name', 'like', "%{$keyword}%");
            }
    
            if (!empty($companyId)) {
                $query->where('company_id', $companyId);
            }
    
            if ($minPrice && $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }
    
            if ($minStock && $maxStock) {
                $query->whereBetween('stock', [$minStock, $maxStock]);
            }
    
            $products = $query->paginate(10)->toArray();
    
            return response()->json($products);
        }
}

