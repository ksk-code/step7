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
        url: "search",
        type: 'GET',
        data: formData,
        dataType: 'json',
        /*headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },*/
            success: function(data) {
                let tableBody = $('.table tbody');
                tableBody.empty(); // テーブルの内容をクリア
            
                // 各商品の行を生成して追加
                if (data && data.data && Array.isArray(data.data)) {
                    $.each(data.data, function(index, product) {
                    let imgSrc = product.img_path ? `storage/images/${product.img_path}` : ''; // 画像がある場合はそのパスを設定、ない場合は空文字列を設定
                    let row = `
                        <tr id="productId-${product.id}" class="productId">
                            <td>${product.id}</td>
                            <td>${product.product_name}</td>
                            <td>￥${product.price}</td>
                            <td>${product.stock}</td>
                            <td><img src="${imgSrc}" alt="${product.name}" width="100" style="display: ${imgSrc ? 'block' : 'none'}"></td> <!-- 画像がある場合は表示、それ以外は非表示 -->
                            <td>${product.comment}</td>
                            <td>${product.company.company_name}</td>
                            <td>
                                <form id="deleteForm-${product.id}" action="products/${product.id}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="btn btn-danger delete-btn" data-id="${product.id}">削除</button>
                                </form>
                            </td>
                            <td>
                                <a href="products/${product.id}" class="btn btn-info ml-2">詳細</a>
                            </td>
                        </tr>`;
                    tableBody.append(row);
                });
                // ページネーションリンクを更新
                $('.pagination').html(data.links);
                // ソート機能を再適用
                $("#productTable").trigger("update");
            } else {
                console.error('Unexpected response format:', data);
            }
            

            },
        error: function() {
            alert('Search failed.');
        }
    });
});

    //削除機能
    $(document).on("click", ".delete-btn", function(e) {
        e.preventDefault();
        var clickedButton = $(this); // クリックされたボタンを追跡
        var productId = $(this).data('id'); // data-productId属性からIDを取得
        console.log(productId);
        var deleteConfirm = confirm('削除してもよろしいですか？');
    
        if (deleteConfirm == true) {
            $.ajax({
                url: "products/" + productId,
                type: 'POST',
                dataType: 'json',
                data: {'id':productId,'_method':'DELETE'},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                success: function(response) {
                    if (response.success) {
                        clickedButton.closest('tr').remove();
                        console.log('削除成功');
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
            url: 'api/purchase',
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
