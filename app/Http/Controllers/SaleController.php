<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;

class SaleController extends Controller
{
    /**
     * 購入処理を行う
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    // リクエストから必要なデータを取得
    $productId = $request->input('product_id');

    // 在庫があるかチェック
    $product = Product::findOrFail($productId);
    if ($product->stock <= 0) {
        return response()->json(['error' => '在庫不足'], 400);
    }

    // 在庫を減算
    $product->stock--;
    $product->save();

    // 購入処理としてsaleテーブルにレコードを追加
    $sale = new Sale([
        'product_id' => $productId,
        'quantity' => 1,
        'total_price' => $product->price,
    ]);
    $sale->save();

    return response()->json(['message' => '購入成功'], 201);
    }
}