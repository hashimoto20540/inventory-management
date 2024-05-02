<?php
// データベース接続
$db = new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');

// テーブル作成のSQL
$sql = "CREATE TABLE IF NOT EXISTS items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    quantity INT
)";

$id_initialization = "ALTER TABLE items AUTO_INCREMENT = 1";
$statement = $db->prepare($id_initialization);
$statement->execute();

// SQLの実行
$db->exec($sql);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>在庫管理システム 在庫編集画面</title>
</head>
<body>
    <h2>在庫管理システム 在庫編集画面</h2>
    <form action="editData.php" method="GET">
        <table border="1">
            <tr>
                <th>ID</th>
                <th>商品名</th>
                <th>在庫数</th>
            </tr>
            <?php
            // 値を$_GET変数から取得
            $select_id = $_GET['id'];
            $edit_name = $_GET['name'];
            $edit_quantity = $_GET['quantity'];

            // 在庫数の登録・更新
            $sql = "UPDATE items SET name = :name, quantity = :quantity WHERE id = :id";
            $statement = $db->prepare($sql);
            $statement->execute([':name' => $edit_name, ':quantity' => $edit_quantity, ':id' => $select_id]);

            // 更新後のデータを取得
            $sql = "SELECT * FROM items WHERE id = :id";
            $statement = $db->prepare($sql);
            $statement->execute([':id' => $select_id]);
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
                <td><input type="text" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>"></td>
            </tr>
        </table>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <input type="submit" value="登録">
    </form>
    <a href="productList.php" class="button">戻る</a>
</body>
</html>
