@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">商品一覧画面</div>

                    <div class="card-body">
                    <div>
                        <form id="searchForm" >
                            @csrf
                            <input type="text" name="keyword" placeholder="検索キーワード">

                            <select name="company_id">
                                <option value="">メーカー名</option>
                                @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                            </select>
                            <br>
                            <input type="number" name="min_price" placeholder="最低価格">
                            <input type="number" name="max_price" placeholder="最高価格">
                            <br>
                            <input type="number" name="min_stock" placeholder="最小在庫">
                            <input type="number" name="max_stock" placeholder="最大在庫">

                            <button type="button" id="searchBtn" >検索</button>
                        </form>
                    </div>

                        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規登録</a>

                        <table class="table" id="productTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>名前</th>
                                    <th>価格</th>
                                    <th>在庫</th>
                                    <th>商品画像</th>
                                    <th>コメント</th>
                                    <th>メーカー名</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="tbody" id="results">
                                @foreach ($products as $product)
                                    <tr class = "productId">
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>￥{{ $product->price }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td><img src="{{ asset('storage/images/'. $product->img_path) }}" alt="{{ $product->name }}" width="100"></td>
                                        <td>{{ $product->comment }}</td>
                                        <td>{{ $product->company->company_name }}</td>
                                        <td>
                                            <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button data-productId="{{ $product->id }}" type="button" class="btn btn-danger delete-btn">削除</button>
                                            </form>
                                        </td>
                                        <td>
                                        <a href="{{ route('products.detail', ['id' => $product->id]) }}" class="btn btn-info ml-2">詳細</a> 
                                        </td>
                                        <td>
                                            <form action="/api/purchase" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button id="purchaseButton" type="submit" class="btn btn-success">購入</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                {{--  --}}
                                {{ $products->links() }}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

