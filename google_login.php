<?php
// PHPセッションを開始します。セッションは、ユーザーごとにサーバー側で一時的にデータを保持する仕組みです。
session_start();

// クライアントからのPOSTリクエストを受け取る
$input = file_get_contents('php://input');

// 取得したデータを表示（デバッグ用）
echo "Raw input: " . $input . "\n";

$data = json_decode($input, true);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<script>
// ページがロードされたときに実行される関数を定義
window.onload = function() {
  // セッションストレージからデータを取得
  const sub = sessionStorage.getItem('sub');
  const email = sessionStorage.getItem('email');
  const name = sessionStorage.getItem('name');
  console.log(sub);
  console.log(email);
  console.log(name);
  // データをサーバーに送信する関数
  function sendDataToServer(sub, email, name) {
    fetch('google_login.php', {
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
    .then(response => response.json())
    .then(data => {
      console.log('Success:', data);
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }

  // データが存在する場合のみサーバーに送信※後でsubも追加する
  if (email && name) {
    sendDataToServer(email, name);
  }
};
</script>

</body>
</html>
