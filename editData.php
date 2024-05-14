<?php
var_dump($_FILES);
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

// 値を$_GET変数から取得
$select_id = $_GET['id'];
$edit_name = $_GET['name'];
$edit_furigana = $_GET['furigana'];
$edit_item_description = $_GET['item_description'];
$edit_quantity = $_GET['quantity'];
$edit_price = $_GET['price'];

if(isset($_GET['image_path'])) {
	$edit_image_path = $_GET['image_path'];
}

// echo  $_GET['id'];
// echo $_FILES['image']['name'];

if(isset($_GET['image_path'])) {
	echo $edit_image_path;
}



// $imageFile = $_FILES['image'];
if(isset($_FILES['image'])) {
	
	if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
		$imageFile = $_FILES['image'];
		$uploadDir = 'image/productListThumbnail/';
		$uploadedFilePath = $uploadDir . basename($imageFile['name']);
		echo "FILES[image]は登録されました";
		if (move_uploaded_file($imageFile['tmp_name'], $uploadedFilePath)) {
			echo "ファイルがアップロードされました。";
		} else {
			echo "ファイルのアップロードに失敗しました。";
		}
	} else {
		echo "ファイルのアップロードエラーが発生しました。";
		// echo $imageFile["error"];
	}
}












// 在庫数の登録・更新
$sql = "UPDATE items SET :name, :furigana, :item_description, :quantity, :price, :image_path WHERE id = :id";
$sql = "UPDATE items SET name = :name, furigana = :furigana, item_description = :item_description, quantity = :quantity, price = :price, image_path = :image_path WHERE id = :id";
$statement = $db->prepare($sql);

if(isset($_GET['image_path'])) {
	$statement->execute([
		':name' => $edit_name,
		':furigana' => $edit_furigana,
		':item_description' => $edit_item_description,
		':quantity' => $edit_quantity,
		':price' => $edit_price,
		':image_path' => $edit_image_path,
		':id' => $select_id
	]);
}

if(isset($_FILES['image'])) {
	$statement->execute([
		':name' => $edit_name,
		':furigana' => $edit_furigana,
		':item_description' => $edit_item_description,
		':quantity' => $edit_quantity,
		':price' => $edit_price,
		':image_path' => $uploadedFilePath,
		':id' => $select_id
	]);	
	// 登録後に商品一覧画面にリダイレクト
	// header("Location: productList.php");
	// exit;
}

// 更新後のデータを取得
$sql = "SELECT * FROM items WHERE id = :id";
$statement = $db->prepare($sql);
$statement->execute([':id' => $select_id]);
$row = $statement->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>商品編集</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
<link rel="stylesheet" type="text/css" href="css/addData.css">
<link rel="stylesheet" type="text/css" href="css/editData.css">
<script src="javascript/editData.js" defer></script>
</head>
<body>
<form action="editData.php" method="GET" enctype="multipart/form-data">
<header class="addData__header">
	<a href="productList.php" class="button">
		<div class="header__wrapper-close">
			<img src="image/close_icon.svg" alt="close_icon" width="24" height="24" fill="rgb(0, 0, 0)">
		</div>
	</a>
	<div class="flex-grow"></div>
	<div class="header__wrapper-save-button">
		<input type="submit" value="保存">
	</div>
</header>

<div class="layout-base__body">
	<h1>商品を編集</h1>
	<h2 class="h2_subtitle">詳細</h2>
	<div class="add-data__wrapper-name-img">
		<div class="add-data__wrapper-name">
			<div class="form__wrapper-input">
				<input type="text" name="name" required class="add-data__input" value="<?php echo htmlspecialchars($row['name']); ?>">
			</div>
			<div class="form__wrapper-input">
				<input type="text" name="furigana" required class="add-data__input" value="<?php echo htmlspecialchars($row['furigana']); ?>">
			</div>
		</div>









		<div class="add-data__wrapper-img-edit" id="imageEditButton">
			<div class="add-data__data__wrapper-input-img">
				<img id="imagePreview" class="add-data__input-img" src="<?php echo htmlspecialchars($row['image_path']); ?>" />
			</div>
			<div class="add-data__img-edit-button">編集</div>
		</div>
	</div>
	<!-- display: none;を一旦削除 -->
	<input type="file" id="imageUpload" name="image" accept="image/*" style="">



	<div class="form__wrapper-input">
		<textarea type="text" name="item_description" required class="add-data__input add-data__description-item add-data__input--maxwidth"><?php echo htmlspecialchars($row['item_description']); ?></textarea>
	</div>
	<div class="add-data__border"></div>
	<p>在庫</p>
	<div class="form__wrapper-input">
		<input type="text" name="quantity" required class="add-data__input add-data__input--maxwidth " value="<?php echo htmlspecialchars($row['quantity']); ?>">
	</div>
	<div class="add-data__border"></div>
	<p>価格(¥)</p>
	<div class="form__wrapper-input">
		<input type="text" name="price" required class="add-data__input add-data__input--maxwidth "  value="<?php echo htmlspecialchars($row['price']); ?>">
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
	<input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
</div>

</form>
</body>
</html>







