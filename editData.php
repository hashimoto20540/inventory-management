<?php
// ============================
// Model
// ============================
// データベースに接続
function connectDatabase() {
    try {
        return new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');
    } catch (PDOException $e) {
        echo 'データベース接続失敗: ' . $e->getMessage();
        exit;
    }
}
// テーブル作成のSQL
function createTable($db) {
    $sql = "CREATE TABLE IF NOT EXISTS items(
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50),
        furigana VARCHAR(50),
        item_description TEXT,
        quantity INT,
        price DECIMAL(10, 2),
        image_path VARCHAR(255)
    )";
		//$db->exec($sql) は、構築したSQLクエリをデータベースに対して実行
    $db->exec($sql);
}

//テーブル内の自動増分（AUTO_INCREMENT）値をリセット ※いらないかも
function resetAutoIncrement($db) {
	// items テーブルの主キー列である id の自動増分値を1にリセットするためのSQLステートメント
	$sql = "ALTER TABLE items AUTO_INCREMENT = 1";
	$statement = $db->prepare($sql);
	$statement->execute();
}

// 入力されたIDの行を削除し、関連する画像ファイルも削除
function deleteItem($db, $id) {
	// 削除するアイテムの情報を取得
	$item = getItemById($db, $id);
	
	// アイテムが存在する場合
	if ($item) {
			// データベースからアイテムを削除
			$sql = "DELETE FROM items WHERE id = :id";
			$statement = $db->prepare($sql);
			$statement->execute([':id' => $id]);

			// 関連する画像ファイルを削除 $item['image_path'] はgetItemById() 関数のスコープ内では使用可能
			$imagePath = $item['image_path'];
			if ($imagePath && file_exists($imagePath)) {
					unlink($imagePath); // ファイルを削除
			}
	}
}


//テーブルを更新
function updateItem($db, $data) {
	//更新する各カラムとその新しい値を指定
	//:name, :furigana, :item_description, :quantity, :price, :image_path はプレースホルダーであり、実際の値は後でバインドされます。
	$sql = "UPDATE items SET name = :name, furigana = :furigana, item_description = :item_description, quantity = :quantity, price = :price, image_path = :image_path WHERE id = :id";
	$statement = $db->prepare($sql);
	$statement->execute([
		':name' => $data['name'],
		':furigana' => $data['furigana'],
		':item_description' => $data['item_description'],
		':quantity' => $data['quantity'],
		':price' => $data['price'],
		':image_path' => $data['image_path'],
		':id' => $data['id']
	]);
}

//指定されたIDを持つアイテム（商品）の情報をデータベースから取得.その情報を連想配列として返す
function getItemById($db, $id) {
	$sql = "SELECT * FROM items WHERE id = :id";
	$statement = $db->prepare($sql);
	//:id というプレースホルダーに、指定されたIDの値をバインドする
	$statement->execute([':id' => $id]);
	//実行したクエリから取得した結果を取得します。fetch(PDO::FETCH_ASSOC) は、１行を、連想配列の形式で結果を返す
	//array('id' => 1,'name' => 'Alice','age' => 30)のようなもの
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function handleFileUpload($file) {
	if ($file['error'] === UPLOAD_ERR_NO_FILE) {
		return null;
	}
	if ($file['error'] === UPLOAD_ERR_OK) {
		$uploadDir = 'image/productListThumbnail/';
		if (!file_exists($uploadDir)) {
			mkdir($uploadDir, 0775, true);
		}
		$uploadedFilePath = $uploadDir . basename($file['name']);
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

// ============================
// Controller
// ============================

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login_form.php");
    exit;
}

$db = connectDatabase();
createTable($db);
resetAutoIncrement($db);

//削除ボタンが押されたとき
if (isset($_POST['delete']) && $_POST['delete'] === 'true') {
    deleteItem($db, $_POST['id']);
		//商品一覧画面にリダイレクト
    header("Location: productList.php");
    exit;
}

//画像が更新されたとき
if (isset($_FILES['image'])) {
    $edit_image_path = handleFileUpload($_FILES['image']);
    if (!$edit_image_path) {
        $edit_image_path = isset($_POST['before_edit_image_path']) ? $_POST['before_edit_image_path'] : '';
    }
} else {
    $edit_image_path = isset($_POST['before_edit_image_path']) ? $_POST['before_edit_image_path'] : '';
}

//商品が更新されたとき
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $data = [
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        'furigana' => $_POST['furigana'],
        'item_description' => $_POST['item_description'],
        'quantity' => $_POST['quantity'],
        'price' => $_POST['price'],
        'image_path' => $edit_image_path
    ];
    updateItem($db, $data);
		//商品一覧画面にリダイレクト
    header("Location: productList.php");
    exit;
}

// productListから遷移した際、値を$_GET変数から取得
$item = null;
if (isset($_GET['id'])) {
    $item = getItemById($db, $_GET['id']);
}

// ============================
// View
// ============================

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
<form id="editForm" action="editData.php" method="POST" enctype="multipart/form-data">
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
                <input type="text" name="name" required class="add-data__input" value="<?php echo htmlspecialchars($item['name']); ?>">
            </div>
            <div class="form__wrapper-input">
                <input type="text" name="furigana" required class="add-data__input" value="<?php echo htmlspecialchars($item['furigana']); ?>">
            </div>
        </div>
        <div class="add-data__wrapper-img-edit" id="imageEditButton">
            <div class="add-data__data__wrapper-input-img">
                <img id="imagePreview" class="add-data__input-img" src="<?php echo htmlspecialchars($item['image_path']); ?>" />
            </div>
            <div class="add-data__img-edit-button">編集</div>
        </div>
    </div>
    <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;">
    <input type="hidden" name="before_edit_image_path" value="<?php echo htmlspecialchars($item['image_path']); ?>">

    <div class="form__wrapper-input">
        <textarea type="text" name="item_description" required class="add-data__input add-data__description-item add-data__input--maxwidth"><?php echo htmlspecialchars($item['item_description']); ?></textarea>
    </div>
    <div class="add-data__border"></div>
    <p>在庫</p>
    <div class="form__wrapper-input">
        <input type="text" name="quantity" required class="add-data__input add-data__input--maxwidth" value="<?php echo htmlspecialchars($item['quantity']); ?>">
    </div>
    <div class="add-data__border"></div>
    <p>価格(¥)</p>
    <div class="form__wrapper-input">
        <input type="text" name="price" required class="add-data__input add-data__input--maxwidth" value="<?php echo htmlspecialchars($item['price']); ?>">
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
    <div class="body__wrapper-delete-button">
        <button type="button" id="deleteButton">削除</button>
    </div>
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
    <input type="hidden" name="delete" id="deleteInput" value="false">
</div>

</form>
</body>
</html>
