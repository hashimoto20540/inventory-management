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
<title>在庫管理システム</title>
</head>
<body>

    <h2>在庫管理システム</h2>
    <form action="addData.php?id=<?php echo htmlspecialchars(""); ?>&quantity=<?php echo htmlspecialchars(""); ?>&name=<?php echo htmlspecialchars(""); ?>" method="GET">
    <input type="hidden" id="last_id" name="last_id" value="">
        <input type="submit" value="商品登録" onclick="getLastID()">
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>商品名</th>
            <th>在庫数</th>
            <th></th>
        </tr>
        <?php
        // 在庫情報の表示
        $sql = "SELECT * FROM items";
        $statement = $db->prepare($sql);
        $statement->execute();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            <td><a href="editData.php?id=<?php echo htmlspecialchars($row['id']); ?>&quantity=<?php echo htmlspecialchars($row['quantity']); ?>&name=<?php echo htmlspecialchars($row['name']); ?>" class="button">編集</a></td>
        </tr>
        <?php
        }
        ?>
    </table>
    
    <script>
        function getLastID() {
            // データベースの最後のIDを取得
            <?php
            $last_id_sql = "SELECT MAX(id) AS last_id FROM items";
            $last_id_statement = $db->prepare($last_id_sql);
            $last_id_statement->execute();
            $last_id_row = $last_id_statement->fetch(PDO::FETCH_ASSOC);
            $last_id = $last_id_row['last_id'];
            ?>
            
            // 隠しフィールドに最後のIDを設定
            document.getElementById("last_id").value = <?php echo $last_id; ?>;
        }
    </script>

</body>
</html>