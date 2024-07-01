$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
});

$(document).ready(function() {
    //ソート機能
    $("#productTable").tablesorter({
        // ソート方法を指定
        headers: {
            0: { sorter: "integer" }, // ID列を整数ソートにする
            1: { sorter: "text" }, // 名前列、コメント、メーカーを文字列ソートにする
            2: { sorter: "numeric" } // 在庫列、価格を数値ソートにする
        },
    });

    // 検索機能
$('#searchBtn').on('click', function(e) {
    e.preventDefault();
    let formData = $('#searchForm').serialize(); // クエリパラメータ形式に変換

    $.ajax({
        url: "/products/search",
        type: 'GET',
        data: formData,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                let tableBody = $('.table tbody');
                tableBody.empty(); // テーブルの内容をクリア
            
                // 各商品の行を生成して追加
                $.each(data.data, function(index, product) {
                    let row = `
                        <tr class="productId">
                            <td>${product.id}</td>
                            <td>${product.product_name}</td>
                            <td>￥${product.price}</td>
                            <td>${product.stock}</td>
                            <td><img src="${product.img_path}" alt="${product.name}" width="100"></td>
                            <td>${product.comment}</td>
                            <td>${product.company_name}</td>
                            <td>
                                <!-- 削除ボタンのフォーム -->
                            </td>
                            <td>
                                <a href="/products/detail/${product.id}" class="btn btn-info ml-2">詳細</a>
                            </td>
                            <td>
                                <!-- 購入ボタンのフォーム -->
                            </td>
                        </tr>`;
                    tableBody.append(row);
                });
            
                // ページネーションリンクを更新
                $('.pagination').html(data.links);
            
                // ソート機能を再適用
                $("#productTable").trigger("update");
            },
        error: function() {
            alert('Search failed.');
        }
    });
});

    //削除機能
    $(".delete-btn").on("click", function(e) {
        e.preventDefault();
        var productId = $(this).data('productId'); // data-productId属性からIDを取得
        var deleteConfirm = confirm('削除してもよろしいですか？');
    
        if (deleteConfirm) {
            $.ajax({
                url: '/products/' + productId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                success: function(response) {
                    if (response.success) {
                        $(this).closest('tr').remove(); // このボタンが属する行を削除
                    } else {
                        alert('商品の削除に失敗しました');
                    }
                },
                error: function() {
                    alert('サーバーとの通信に失敗しました');
                }
            });
        }
    });

    //購入処理
    $('#purchaseButton').on('click', function(e) {
        e.preventDefault(); // フォームのデフォルト動作を防ぐ
        const form = $(this).closest('form'); // 最近のフォーム要素を取得
        const formData = form.serializeArray().reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {}); 
    
        $.ajax({
            url: '/api/purchase',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(error) {
                console.error(error);
                alert('購入処理中にエラーが発生しました');
            }
        });
    });
});
