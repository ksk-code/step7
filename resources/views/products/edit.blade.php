@extends('layouts.app')

@section('content')
    <h1>商品編集</h1>

<form action="{{ route('products.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">商品名</label>
        @include('partials.require')
        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
    </div>
    <div class="form-group">
        <label for="company_id">メーカー名</label>
        @include('partials.require')
        <select class="form-control" id="company_id" name="company_id" required>
            <option value="">選択してください</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $product->company_id == $company->id? 'selected' : '' }}>{{ $company->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="price">価格</label>
        @include('partials.require')
        <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
    </div>
    <div class="form-group">
        <label for="stock">在庫数</label>
        @include('partials.require')
        <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
    </div>
    <div class="form-group">
        <label for="comment">コメント</label>
        <textarea class="form-control" id="comment" name="comment" rows="3">{{ $product->comment }}</textarea>
    </div>
    <div class="form-group">
        <label for="image">画像</label>
        <input type="file" class="form-control-file" id="image" name="image">
    </div>
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
</form>
@endsection