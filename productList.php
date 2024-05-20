<?php
// Model
// セッション開始
session_start();

//ログイン状態を確認 isset() は、変数が存在しているかどうかをチェック
function isLoggedIn() {
  return isset($_SESSION['id']);
}

// データベース接続：PDOはPHP Data Objects, PHPが提供するデータベースアクセスのための共通インターフェース
function connectDatabase() {
	// エラーが起こる可能性のあるものはtryに入れる。エラーが起きたらcatchの処理を行う
	try {
		$db = new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');
		return $db;
	} catch (PDOException $e) {
		echo 'データベース接続失敗: ' . $e->getMessage();
		exit;
	}
}

function createTable($db) {
	//テーブルを作成するSQL
	//商品テーブル
	$sql_items = "CREATE TABLE IF NOT EXISTS items(
		id INT AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50),
		furigana VARCHAR(255),
		item_description VARCHAR(255),
		price INT,
		image_path VARCHAR(255)
	)";

	//在庫テーブル items:quantities = 1:n
	$sql_quantities = "CREATE TABLE IF NOT EXISTS quantities(
		id INT AUTO_INCREMENT PRIMARY KEY,
		item_id INT,
		quantity INT,
		-- CONSTRAINT fk_item: 外部キーの制約に名前を付けます(任意)
		CONSTRAINT fk_item
			-- FOREIGN KEY (item_id) REFERENCES items(id): item_id 列が items テーブルの id 列を参照する外部キーであることを示す
			FOREIGN KEY (item_id) REFERENCES items(id)
			-- items テーブルの行が削除されたときに、その id を参照する quantities テーブルの行も自動的に削除されるようにします。
			ON DELETE CASCADE
	)";

	// カテゴリーテーブル items:categories = n:n
	$sql_categories = "CREATE TABLE IF NOT EXISTS categories (
			id INT AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(50) NOT NULL
	)";

	// 商品テーブルと、カテゴリーテーブルの中間テーブル
	$sql_item_categories = "CREATE TABLE IF NOT EXISTS item_categories (
			item_id INT,
			category_id INT,
			-- (item_id, category_id): 複合主キーを設定しています。これにより、item_idとcategory_idの組み合わせが一意であることを保証します。つまり、同じ商品が同じカテゴリーに複数回関連付けられることはありません。
			PRIMARY KEY (item_id, category_id),
			FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
			FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
	)";

	//PHPのPDO (PHP Data Objects) クラスを使用して、データベースに対してSQLコマンドを実行するためのメソッドです。具体的には、exec メソッドはSQL文を実行し、その影響を受けた行数を返します。
	$db->exec($sql_items);
	$db->exec($sql_quantities);
	$db->exec($sql_categories);
	$db->exec($sql_item_categories);
}

//ID初期化
function initializeId($db) {
	$sql = "ALTER TABLE items AUTO_INCREMENT = 1";
	$statement = $db->prepare($sql);
	$statement->execute();
}

// Controller

if (!isLoggedIn()) {
	// ログインページにリダイレクト header() 関数は、HTTPヘッダーの「Location」を設定
	header("Location: login_form.php");
	exit;
}

$db = connectDatabase();
createTable($db);
initializeId($db);

// View
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/productList.css">
  	<script src="javascript/productList.js" defer></script>
</head>
<body>
	<header class="dashboard__header">
		<div class="menu-toggle" id="menu-toggle">
			<!-- ハンバーガーメニューアイコン -->
			<span class="bar"></span>
			<span class="bar"></span>
			<span class="bar"></span>
		</div>
		<h1>商品</h1>
	</header>

	<div class="dashboard__container">
		<nav class="dashboard__sidebar" id="sidebar">
			<ul>
				<li>
					<img src="image/home_icon.svg" alt="inventory_icon" width="24" height="24" fill="rgb(0, 0, 0)">						
					<a href="index.php">ホーム</a>
				</li>
				<li>
					<img src="image/inventory_icon.svg" alt="inventory_icon" width="24" height="24" fill="rgb(0, 0, 0)">
					<a href="productList.php">商品</a>
				</li>
			</ul>
		</nav>

		<main class="content">
			<form action="" method="GET">
				<div class="information-bar product-list__information-bar">
					<input class="content__information-box product-list__information-box" placeholder="名前、説明、SKU、GTINのいずれかで検索">
					</input>	
					<div class="flex-grow"></div>
					<a href="addData.php" class="content__information-box product-list__register-button"><div>商品を登録</div></a>
				</div>
			</form>
			<table border="1" id="table_items">
				<tr class="product-table__th">
					<th>ID</th>
					<th class="productList__table--th-image"></th>
					<th>商品名</th>
					<th>フリガナ</th>
					<th>カテゴリー</th>
					<th>在庫数</th>
					<th>価格</th>
					<th></th>
				</tr>
				<?php
				// 結合して全行を出力するSQL文
				$sql = "SELECT 
					items.id AS item_id,
					items.name,
					items.furigana,
					items.item_description,
					items.price,
					items.image_path,
					quantities.id AS quantity_id,
					quantities.quantity
				FROM 
					items
				INNER JOIN 
					quantities ON items.id = quantities.item_id";
				$statement = $db->prepare($sql);
				$statement->execute();

				while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr data-id="<?php echo htmlspecialchars($row['item_id']); ?>"
					data-name="<?php echo htmlspecialchars($row['name']); ?>"
					data-quantity="<?php echo htmlspecialchars($row['quantity']); ?>"
					data-furigana="<?php echo htmlspecialchars($row['furigana']); ?>"
					data-description="<?php echo htmlspecialchars($row['item_description']); ?>"
					data-price="<?php echo htmlspecialchars($row['price']); ?>"
					data-image="<?php echo htmlspecialchars($row['image_path']); ?>"
					title="<?php echo htmlspecialchars($row['name']); ?>の商品編集ページに移動します"
					enctype="multipart/form-data">
						<td><?php echo htmlspecialchars($row['item_id']); ?></td>
						<td>
							<div class="productList__table--wapper-image">
								<?php if (!empty($row['image_path'])) : ?>
									<img class="productList__table--image" src="<?php echo htmlspecialchars($row['image_path']); ?>" />
								<?php else : ?>
									<img class="productList__table--image" src="https://placehold.jp/300x200.png" />
								<?php endif; ?>
							</div>
						</td>
						<td><?php echo htmlspecialchars($row['name']); ?></td>
						<td><?php echo htmlspecialchars($row['furigana']); ?></td>
						<td></td>
						<td><?php echo htmlspecialchars($row['quantity']); ?></td>
						<td><?php echo htmlspecialchars($row['price']); ?></td>
					</tr>
				<?php
				}
				?>
			</table>
		</main>
	</div>

</body>
</html>





