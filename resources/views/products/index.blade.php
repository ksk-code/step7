@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">商品一覧画面</div>

                    <div class="card-body">
                    <div>
                        <form action="{{ route('products.index') }}" method="GET">
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

                            <input type="submit" value="検索">
                        </form>
                    </div>

                        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規登録</a>

                        <table class="table">
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
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
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
                                            <button type="submit" class="btn btn-danger">削除</button>
                                            </form>
                                        </td>
                                        <td>
                                            <button onclick="event.preventDefault(); window.location.href='{{ route('products.detail', ['id' => $product->id]) }}';" class="btn btn-info ml-2">詳細</button> 
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