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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// 値を$_POST変数から取得
	$registered_name = $_POST['name'];
	$registered_furigana = $_POST['furigana'];
	$registered_item_description = $_POST['item_description'];
	$registered_quantity = $_POST['quantity'];
	$registered_price = $_POST['price'];
	// $_FILESから取得
	// 画像のアップロードと保存
	$imageFile = $_FILES['image'];

	if ($imageFile['error'] === UPLOAD_ERR_OK) {
		$uploadDir = 'image/productListThumbnail/';
		$uploadedFilePath = $uploadDir . basename($imageFile['name']);

		if (move_uploaded_file($imageFile['tmp_name'], $uploadedFilePath)) {
			// echo "ファイルがアップロードされました。";
		} else {
			echo "ファイルのアップロードに失敗しました。";
		}
	} else {
		echo "ファイルのアップロードエラーが発生しました。";
		echo $imageFile["error"];
	}

	// データベースへのパスの保存
	$sql = "INSERT INTO items (name, furigana, item_description, quantity, price, image_path) 
		VALUES (:name, :furigana, :item_description, :quantity, :price, :image_path)";
	$statement = $db->prepare($sql);
	$statement->execute([
	':name' => $registered_name,
	':furigana' => $registered_furigana,
	':item_description' => $registered_item_description,
	':quantity' => $registered_quantity,
	':price' => $registered_price,
	':image_path' => $uploadedFilePath
	]);

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
<title>商品登録</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
<link rel="stylesheet" type="text/css" href="css/addData.css">
<script src="javascript/addData.js" defer></script>
</head>
<body>
<form action="addData.php" method="post"  enctype="multipart/form-data">

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
	<h1>商品を登録</h1>
	<h2 class="h2_subtitle">詳細</h2>
	<div class="add-data__wrapper-name-img">
		<div class="add-data__wrapper-name">
			<div class="form__wrapper-input">
				<input type="text" name="name" required class="add-data__input" placeholder="名前">
			</div>
			<div class="form__wrapper-input">
				<input type="text" name="furigana" required class="add-data__input" placeholder="フリガナ">
			</div>
		</div>
		<div class="add-data__wrapper-img-edit" id="imageEditButton">
			<div class="add-data__data__wrapper-input-img">
				<img id="imagePreview" class="add-data__input-img" src="https://placehold.jp/300x200.png" />
			</div>
			<div class="add-data__img-edit-button">編集</div>
		</div>
	</div>
	<input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;">
	<div class="form__wrapper-input">
		<textarea type="text" name="item_description" required class="add-data__input add-data__description-item add-data__input--maxwidth " placeholder="商品の説明"></textarea>
	</div>
	<div class="add-data__border"></div>
	<p>在庫</p>
	<div class="form__wrapper-input">
		<input type="text" name="quantity" required class="add-data__input add-data__input--maxwidth " placeholder="在庫">
	</div>
	<div class="add-data__border"></div>
	<p>価格(¥)</p>
	<div class="form__wrapper-input">
		<input type="text" name="price" required class="add-data__input add-data__input--maxwidth " placeholder="価格">
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