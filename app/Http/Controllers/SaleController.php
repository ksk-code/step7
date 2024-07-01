<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
{
    DB::beginTransaction();

    try {
    $productId = $request->input('product_id');

    // 商品情報が存在しない場合の処理
    if (!$product = Product::findOrFail($productId)) {
        return response()->json(['error' => '商品情報が見つかりません'], 404);
    }

    // 在庫があるかチェック
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
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Transaction failed'], 500);
    }
}
}