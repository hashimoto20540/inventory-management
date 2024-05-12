<?php
session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログアウト完了</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<header class="login__header">
</header>
<div class="layout-base__body">
	<h1 class="form__h1-2">ログアウトしました</h1>
	<p class="wrapper-internal-link--center"><a href="login_form.php">ログイン</a></p>
</div>
</body>
</html>