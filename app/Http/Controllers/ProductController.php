<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

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
            $products->where('name', 'LIKE', "%{$keyword}%") // 商品名に基づく部分一致検索
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $product = new Product;
        $product->fill($validatedData);

        if(request('image')){
            $name=request()->file('image')->getClientOriginalName();
            request()->file('image')->move('storage/images',$name);
            $product->img_path=$name;
        }

    $product->save();

    return redirect()->route('products.create');
    }

    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('image')) {
            // 既存の画像ファイルを削除（必要に応じて）
            // Storage::delete($product->img_path);
    
            // アップロードされたファイルを取得
            $image = $request->file('image');
    
            // 新しいファイル名を生成（ここでは現在の日時を使用）
            $filename = time(). '.'. $image->getClientOriginalExtension();
    
            // ファイルを指定したディレクトリに保存
            $path = $image->storeAs('images', $filename, 'public');
    
            // データベースのimg_pathを更新
            $product->img_path = basename($path);
            $product->save();
        }
        $product->update($request->all());
        return redirect()->route('products.edit', ['product' => $product->id]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index');
    }
}
