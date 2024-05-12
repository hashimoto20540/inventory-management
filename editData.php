<?php
// セッション開始
session_start();

// ログイン状態の確認
if (!isset($_SESSION['id'])) { // ログインしていない場合
    // ログインページにリダイレクト
    header("Location: login_form.php");
    exit; // リダイレクト後、スクリプトを終了
}

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










<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>商品編集</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
<link rel="stylesheet" type="text/css" href="css/addData.css">
<link rel="stylesheet" type="text/css" href="css/editData.css">
</head>
<body>
<form action="addData.php" method="post">

<header class="addData__header">
	<a href="productList.php" class="button">
		<div class="header__wrapper-close">
			<img src="image/close_icon.svg" alt="close_icon" width="24" height="24" fill="rgb(0, 0, 0)">
		</div>
	</a>
	<div class="flex-grow"></div>
	<div class="header__wrapper-save-button" onclick="registeredRecrd()">
		<input type="submit" value="保存" onclick="registeredRecrd()">
	</div>
</header>

<div class="layout-base__body">
	<h1>商品を編集</h1>
	<h2 class="h2_subtitle">詳細</h2>
	<div class="add-data__wrapper-name-img">
		<div class="add-data__wrapper-name">
			<div class="form__wrapper-input">
				<input type="text" name="name" required class="add-data__input" placeholder="名前">
			</div>
			<div class="form__wrapper-input">
				<input type="text" name="furigana" class="add-data__input" placeholder="フリガナ">
			</div>
		</div>
		<div class="add-data__wrapper-img-edit">
			<div class="add-data__data__wrapper-input-img">
				<img class="add-data__input-img" src="https://placehold.jp/300x200.png" />
			</div>
			<div class="add-data__img-edit-button">編集</div>
		</div>
	</div>
	<div class="form__wrapper-input">
		<textarea type="text" name="description_item" class="add-data__input add-data__description-item add-data__input--maxwidth " placeholder="商品の説明"></textarea>
	</div>
	<div class="add-data__border"></div>
	<p>在庫</p>
	<div class="form__wrapper-input">
		<input type="text" name="quantity" required class="add-data__input add-data__input--maxwidth " placeholder="在庫">
	</div>
	<div class="add-data__border"></div>
	<p>価格(¥)</p>
	<div class="form__wrapper-input">
		<input type="text" name="price" class="add-data__input add-data__input--maxwidth " placeholder="価格">
	</div>
	<div class="add-data__border"></div>
	<p>カテゴリー</p>
	<div class="wrapper-contents--flex">
		<img src="image/folder_icon.svg" alt="folder_icon" width="24" height="24" fill="rgb(0, 0, 0)">
		<div class="select-box">
			<select>
				<option value="1">フルーツ</option>
				<option value="2">野菜</option>
				<option value="3">その他</option>
			</select>
		</div>
	</div>
</div>

</form>
</body>
</html>