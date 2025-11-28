<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p class="seller-name">{{ $trade->seller->name }} 様</p>

    <p>以下の取引が完了しました。</p>

    <p>商品名：{{ $trade->item->name }}</p>
    <p>購入者：{{ $trade->buyer->name }}</p>

    <p>取引画面から評価をお願いします。</p>
</body>

</html>