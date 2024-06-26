$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN':'{{csrf_token()}}'
    }
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

    //検索機能
    $('#searchBtn').on('click', function(e) {
        e.preventDefault();
        let formData = new FormData($('#searchForm')[0]);

        $.ajax({
            url: '/products/search',
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: function(data) {
                let resultsContainer = $('#results');
                resultsContainer.empty(); // 既存の内容をクリア
                data.data.forEach(product => {
                    // ここで各製品情報をHTML要素として生成し、resultsContainerに追加
                    let productHtml = `
                        <div class="product">
                            <h3>${product.product_name}</h3>
                            <p>価格: ${product.price}</p>
                            <p>在庫: ${product.stock}</p>
                        </div>
                    `;
                    resultsContainer.append(productHtml);
                });
            },
            error: function() {
                alert('Search failed.');
            }
        });
    });

    //削除機能
    $(".delete-btn").on("click", function() {
        var productId = $(this).closest('tr').find('.productId').val(); //.productIdは商品IDを保持している要素のクラス名
        var deleteConfirm = confirm('削除してもよろしいですか？');
    
        if (deleteConfirm) {
            $.ajax({
                url: '/products/' + productId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
    $('#purchaseButton').on('click', function() {
        const productId = $('#productIdInput').val();
        const quantity = $('#quantityInput').val();
    
        $.ajax({
            url: '/api/purchase',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
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
