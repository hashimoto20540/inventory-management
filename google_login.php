<?php
session_start();

// セッションからデータを取得
$id = $_SESSION['id'] ?? 'default_id';
$sub = $_SESSION['sub'] ?? 'default_sub';
$email = $_SESSION['email'] ?? 'default_email';
$name = $_SESSION['name'] ?? 'default_name';

$_SESSION['id'] = $id;
$_SESSION['name'] = $name;

$msg = htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "さん、Googleアカウントでログインしました";
$link = '<a href="index.php" class="internal-link">ホーム</a>'; // 適宜変更
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Googleアカウントでログイン</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<header class="login__header">
</header>
<div class="layout-base__body">
	<h1 class="form__h1-2"><?php echo $msg; ?></h1>
	<p class="wrapper-internal-link--center"><?php echo $link; ?></p>
</div>
<script>
// ページがロードされたときに実行される関数を定義
window.onload = function() {
  // セッションストレージからデータを取得
  const sub = sessionStorage.getItem('sub');
  const email = sessionStorage.getItem('email');
  const name = sessionStorage.getItem('name');

  console.log('sub:', sub);
  console.log('email:', email);
  console.log('name:', name);

  // データをサーバーに送信する関数
  function sendDataToServer(sub, email, name) {
    fetch('backend-google_login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ 
        sub: sub,        
        email: email,
        name: name 
      })
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json(); // JSON形式でレスポンスを取得
    })
    .then(data => {
      console.log('Success:', data);
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }

  // データが存在する場合のみサーバーに送信
  if (sub && email && name) {
    sendDataToServer(sub, email, name);
  } else {
    console.log('Data missing:', { sub, email, name });
  }
};
</script>
</body>
</html>
