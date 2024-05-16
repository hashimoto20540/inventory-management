<?php

$cookie_lifetime = 300;
//PHPセッション開始：PHPのセッションは、ユーザーがウェブサイトを訪問している間に、サーバー側でデータを保存し、複数のページにわたってそのデータを共有するための仕組み
//セッションの有効期限を指定する
session_start([
    'cookie_lifetime' => $cookie_lifetime,
]);

// ログイン状態の確認 isset() は、変数が存在しているかどうかをチェック
if (!isset($_SESSION['id'])) { // ログインしていない場合
	// ログインページにリダイレクト header() 関数は、HTTPヘッダーの「Location」を設定
	header("Location: login_form.php");
	exit; // リダイレクト後、スクリプトを終了
}

$username = $_SESSION['name'];
//htmlspecialchars 関数を使って、ユーザー名をHTMLエスケープします。これにより、ユーザー名に特殊文字（例: <, >, & など）が含まれていても、HTMLとして解釈されずに表示。XSS攻撃を防ぐ
//\ENT_QUOTES: シングルクォート（'）とダブルクォート（"）の両方をエスケープ。
$msg = 'こんにちは' . htmlspecialchars($username, \ENT_QUOTES, 'UTF-8') . 'さん';
$link_productList = '<a href="productList.php">商品管理ページへ</a>';
$link_logout = '<a href="logout.php">ログアウト</a>';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>ホーム</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script src="javascript/index.js" defer></script>
</head>
<body>
	<header class="dashboard__header">
		<div class="menu-toggle" id="menu-toggle">
			<!-- ハンバーガーメニューアイコン -->
			<span class="bar"></span>
			<span class="bar"></span>
			<span class="bar"></span>
		</div>
		<h1>ホーム</h1>
		<div class="flex-grow"></div>
		<div class="header--logout"><?php echo $link_logout; ?></div>
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
			<div class="information-bar">
				<div class="content__information-box">名前 <span class="content__information-box--bold"><?php echo $username; ?>さん</span></div>
				<div class="content__information-box">日付 <span id="today-date" class="content__information-box--bold"></span></div>
			</div>
			<h2><?php echo $msg; ?></h2>
			<p>さっそくはじめましょう</p>
			
		</main>
	</div>
</body>
</html>
