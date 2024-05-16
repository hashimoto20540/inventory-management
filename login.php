<?php
//Control

//Model
session_start();
$mail = $_POST['mail'];
$dsn = "mysql:host=localhost; dbname=inventory_management; charset=utf8";
$username = "root";
$password = "";
//try ブロック内のコードは、エラーが発生する可能性のある処理を行う
try {
    //DBに接続dbh＝データベースハンドル
    $dbh = new PDO($dsn, $username, $password);
    //catch ブロックは、try ブロック内で例外（エラー）が発生した場合に実行。PDOException: PDOに関連するエラーをキャッチするための例外クラス（エラーを扱うための特別なクラス）。$e: 発生した例外のインスタンス（生成されたオブジェクト）を保持する変数。
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$sql = "SELECT * FROM users WHERE mail = :mail";
//SQLを実行する前の準備。プレースホルダ（データベースクエリの中で、実際の値が代入される前に、仮の値として使用されるもの）
// を使いSQLインジェクションを防ぐ
$stmt = $dbh->prepare($sql);
//PDOStatementオブジェクト ($stmt) の bindValue メソッドを使って、名前付きプレースホルダ :mail に具体的な値 $mail をバインド、必ず必要な代入みたいなもの
$stmt->bindValue(':mail', $mail);
//PDOStatementオブジェクト ($stmt) に対して、準備されたSQL文を実行
$stmt->execute();
//fetch() メソッドは、実行されたクエリから1行の結果セット(データベースから取得した1行のデータ)を取得します。
$member = $stmt->fetch();

//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $member['pass'])) {
    //DBのユーザー情報をセッションに保存
    $_SESSION['id'] = $member['id'];
    $_SESSION['name'] = $member['name'];
    $msg = 'ログインしました。';
    $link = '<a href="index.php" class="internal-link">ホーム</a>';
} else {
    $msg = 'メールアドレスもしくはパスワードが間違っています。';
    $link = '<a href="login_form.php" class="internal-link">戻る</a>';
}

//View
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン完了</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<header class="login__header">
</header>
<div class="layout-base__body">
	<h1 class="form__h1-2"><?php echo $msg; ?></h1>
	<p class="wrapper-internal-link--center"><?php echo $link; ?></p>
</div>
</body>
</html>