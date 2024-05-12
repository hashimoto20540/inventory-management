<?php
// セッション開始
session_start();

// ログイン状態の確認 isset() は、変数が存在しているかどうかをチェック
if (!isset($_SESSION['id'])) { // ログインしていない場合
    // ログインページにリダイレクト header() 関数は、HTTPヘッダーの「Location」を設定
    header("Location: login_form.php");
    exit; // リダイレクト後、スクリプトを終了
}

// データベース接続 PDO(PHP Data Objects), PHPが提供するデータベースアクセスのための共通インターフェース
$db = new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');

// テーブル作成のSQL
$sql = "CREATE TABLE IF NOT EXISTS items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    quantity INT
)";

// SQLの実行
$db->exec($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/productList.css">
    <script src="javascript/index.js" defer></script>
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
			<form action="addData.php?id=<?php echo htmlspecialchars(""); ?>&quantity=<?php echo htmlspecialchars(""); ?>&name=<?php echo htmlspecialchars(""); ?>" method="GET">
				<div class="information-bar product-list__information-bar">
					<input class="content__information-box product-list__information-box" placeholder="名前、説明、SKU、GTINのいずれかで検索">
					</input>	
					<div class="flex-grow"></div>
					<input type="submit" value="商品を登録" onclick="getLastID()" class="content__information-box product-list__register-button">
				</div>
			</form>

			<form action="editData.php?id=<?php echo htmlspecialchars(""); ?>&quantity=<?php echo htmlspecialchars(""); ?>&name=<?php echo htmlspecialchars(""); ?>" method="GET">
			<input type="hidden" id="last_id" name="last_id" value="">
				<table border="1">
					<tr>
						<th>ID</th>
						<th>商品名</th>
						<th>カテゴリー</th>
						<th>在庫数</th>
						<th>価格</th>
						<th></th>
					</tr>
					<?php
					// 在庫情報の表示
					$sql = "SELECT * FROM items";
					$statement = $db->prepare($sql);
					$statement->execute();

					while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
					?>
					<tr data-href="editData.php?id=<?php echo htmlspecialchars($row['id']); ?>&quantity=<?php echo htmlspecialchars($row['quantity']); ?>&name=<?php echo htmlspecialchars($row['name']); ?>"  title="商品編集ページに移動します" >
							<td><?php echo htmlspecialchars($row['id']); ?></td>
							<td><?php echo htmlspecialchars($row['name']); ?></td>
							<td></td>
							<td><?php echo htmlspecialchars($row['quantity']); ?></td>
							<td></td>
							<td>
								<img src="image/more_horiz_icon.svg" alt="inventory_icon" width="24" height="24" >
							</td>
					</tr>
					<?php
					}
					?>
				</table>
			</form>
		</main>
	</div>

</body>
</html>





