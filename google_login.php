<?php
// エラー表示をオンにする
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// POSTリクエストのデータを取得
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['name'])) {
    $email = $data['email'];
    $name = $data['name'];

    // データベースに接続してユーザー情報を保存するなどの処理を行う
    // ここでは例として受け取ったデータをそのまま返します
    $response = [
        'status' => 'success',
        'email' => $email,
        'name' => $name,
        'redirect' => 'google_login.php' // 成功した場合にリダイレクトするURLを設定
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid data'
    ];
}

// JSON形式でレスポンスを返す
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Googleログイン</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<header class="login__header">
</header>
<div class="layout-base__body">
  <h1 class="form__h1-2">Googleアカウントでログインしました</h1>
  <p class="wrapper-internal-link--center"><a href="index.php" class="internal-link">ホームへ</a></p>
</div>
</body>
</html>
