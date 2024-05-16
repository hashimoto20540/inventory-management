<?php
// Model
// セッション開始
session_start();
// ログイン状態の確認
function isLoggedIn() {
	// ログインしていない場合、ログインページにリダイレクト
	return isset($_SESSION['id']);
}

// データベース接続
function connectDatabase() {
	//データベース接続はtryを入れる
	try {
		return new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');
	} catch (PDOException $e) {
		echo 'データベース接続失敗: ' . $e->getMessage();
		exit;
	}
}

//商品を保存
function saveItem($db, $data, $imagePath = null) {
	//$sql は実行したいSQL文の文字列を記載
	//items テーブルに、挿入するカラムを指定
	//VALUES句で、挿入する値を指定。:で始まる名前（プレースホルダー）は、後で実際の値に置換。
	$sql = "INSERT INTO items (name, furigana, item_description, quantity, price, image_path) 
					VALUES (:name, :furigana, :item_description, :quantity, :price, :image_path)";
	//SQL文をプリペアドステートメント(実行するSQL文を事前に解析し、効率的かつ安全に実行するための仕組み)に変換
	$statement = $db->prepare($sql);
	//プレースホルダを具体的な値に置換
	//$statement->execute() は、プリペアドステートメントを実行するためのメソッド
	$statement->execute([
			':name' => $data['name'],
			':furigana' => $data['furigana'],
			':item_description' => $data['item_description'],
			':quantity' => $data['quantity'],
			':price' => $data['price'],
			':image_path' => $imagePath
	]);
}

function handleFileUpload($file) {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        // ファイルがアップロードされなかった場合
        return null;
    }
		//UPLOAD_ERR_OK：アップロードされたファイルにエラーがないことを示す
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'image/productListThumbnail/';
				//basename($file['name'])：パスからファイル名部分を取得するためのPHPの組み込み関数。「.」は結合
        $uploadedFilePath = $uploadDir . basename($file['name']);
				//move_uploaded_file() 関数は、一時的なアップロードされたファイルを新しい場所に移動させるためのPHPの関数
				//$file['tmp_name']: アップロードされたファイルが一時的に保存されている場所のパス。
				//$_FILES スーパーグローバル配列内のエントリ $file['tmp_name'] によって提供
        if (move_uploaded_file($file['tmp_name'], $uploadedFilePath)) {
            return $uploadedFilePath;
        } else {
            echo "ファイルのアップロードに失敗しました。";
        }
    } else {
        echo "ファイルのアップロードエラーが発生しました: " . $file['error'];
    }
    return null;
}

// Controller

if (!isLoggedIn()) {
    header("Location: login_form.php");
    exit;
}

$db = connectDatabase();

//POSTのリクエストが来た時
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//POSTから受け取ったデータを$dataの連想配列に格納。'name' => $_POST['name'],の左がキー（フィールド名）、右が値（フィールドの値）。
	$data = [
		'name' => $_POST['name'],
		'furigana' => $_POST['furigana'],
		'item_description' => $_POST['item_description'],
		'quantity' => $_POST['quantity'],
		'price' => $_POST['price']
	];
	//POSTで送った画像ファイルは$_FILES['image']で取得
	$imagePath = handleFileUpload($_FILES['image']);
	saveItem($db, $data, $imagePath);
	//productList.phpに遷移
	header("Location: productList.php");
	exit;
}

// View

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
<form action="addData.php" method="post" enctype="multipart/form-data">

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
