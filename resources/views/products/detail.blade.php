@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">商品詳細</div>

                <div class="card-body">
                    <p>ID:{{ $product->id }}</p>
                    <p>画像:</p>
                    <img src="{{ asset('storage/images/'. $product->img_path) }}" alt="{{ $product->product_name }}" width="200">
                    <p>商品名:{{ $product->product_name }}</p>
                    <p>メーカー: {{ $product->company->company_name?? '未知' }}</p>
                    <p>価格: ￥{{ $product->price }}</p>
                    <p>在庫数: {{ $product->stock }}</p>
                    <p>コメント: {{ $product->comment }}</p>
                    <button type="submit" class="btn btn-primary" onclick="location.href='{{ route('products.index') }}'">戻る</button>
                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-warning">編集</a>        </div>
    </div>
</div>
@endsection