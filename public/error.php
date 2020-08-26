<?php
$errorMsg = "もう一度始めから操作をお願いします。";
if (isset($_SESSION["errorMsg"])) {
    $errorMsg = $_SESSION["errorMsg"];
}
unset($_SESSION["errorMsg"]); ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Hiroki Kondo">
    <title>Error | ScottAdmin Sample</title>
</head>

<body>
    <h1>Error</h1>
    <section>
        <h2>申し訳ございません。障害が発生しました。</h2>
        <p>
            <?= $errorMsg ?> </p>
    </section>
    以下のメッセージご確認ください。<br>
    <p><a href="/ph34/scottadmin/public/">TOPへ戻る</a></p>
</body>

</html>