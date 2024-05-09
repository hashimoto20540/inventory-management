<?php
//フォームからの値をそれぞれ変数に代入 dsn=データソースネーム
$name = $_POST['name'];
$mail = $_POST['mail'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$username = "root";
$password = "";

$db = new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');

// テーブル作成のSQL
$sql = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    mail VARCHAR(50),
    pass VARCHAR(100)
)";

// SQLの実行
$db->exec($sql);

//フォームに入力されたmailがすでに登録されていないかチェック

$sql_mail = "SELECT * FROM users WHERE mail = :mail";
// stmt=ステートメント
$stmt = $db->prepare($sql_mail);
// SQLクエリ内のプレースホルダー :mail に、変数 $mail の値を結び付ける（バインドする）ためのコード
$stmt->bindValue(':mail', $mail);
// クエリ実行
$stmt->execute();
// 結果を取得
$member = $stmt->fetch();

if ($member['mail'] === $mail) {
    $msg = '同じメールアドレスが存在します。';
    $link = '<a href="signup.php" class="internal-link">戻る</a>';
} else {
    //登録されていなければinsert 
    $sql_mail = "INSERT INTO users(name, mail, pass) VALUES (:name, :mail, :pass)";
    $stmt = $db->prepare($sql_mail);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':mail', $mail);
    $stmt->bindValue(':pass', $pass);
    $stmt->execute();
    $msg = '会員登録が完了しました';
    $link = '<a href="login_form.php" class="internal-link">ログインページ</a>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>新規アカウント登録</title>
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