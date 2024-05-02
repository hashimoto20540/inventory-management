<?php
// データベース接続
$db = new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 値を$_POST変数から取得
    $registered_name = $_POST['name'];
    $registered_quantity = $_POST['quantity'];

    // 商品情報の登録
    $sql = "INSERT INTO items (name, quantity) VALUES (:name, :quantity)";
    $statement = $db->prepare($sql);
    $statement->execute([':name' => $registered_name, ':quantity' => $registered_quantity]);

    // 登録後に商品一覧画面にリダイレクト
    header("Location: productList.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>在庫管理システム 商品追加画面</title>
</head>
<body>

    <h2>在庫管理システム 商品追加画面</h2>
    <form action="addData.php" method="POST">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>商品名</th>
                <th>在庫数</th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" name="name" value=""></td>
                <td><input type="text" name="quantity" value=""></td>
            </tr>
        </table>
        <input type="submit" value="登録" onclick="registeredRecrd()">
    </form>
    <a href="productList.php" class="button">戻る</a>
</body>
</html>