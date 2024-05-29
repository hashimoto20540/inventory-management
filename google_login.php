<?php
// PHPセッションを開始します。セッションは、ユーザーごとにサーバー側で一時的にデータを保持する仕組みです。
session_start();

// クライアントからのPOSTリクエストを受け取る
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// データが存在する場合、セッションに保存
if (isset($data['email']) && isset($data['name'])) {
	$_SESSION['sub'] = $data['sub'];
  $_SESSION['email'] = $data['email'];
  $_SESSION['name'] = $data['name'];

  // レスポンスとしてJSON形式のデータを返す
  echo json_encode([
    'status' => 'success',
		'sub' => $_SESSION['sub'],
    'email' => $_SESSION['email'],
    'name' => $_SESSION['name']
  ]);
} else {
  // エラーレスポンスを返す
  echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
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

  // データをサーバーに送信する関数
  function sendDataToServer(sub, email, name) {
    fetch('receiver.php', {
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
      // データをHTMLに表示（必要に応じて）
    //   document.getElementById('email').textContent = data.email;
    //   document.getElementById('name').textContent = data.name;
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
