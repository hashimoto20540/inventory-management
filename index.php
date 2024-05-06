<?php
// session_start();
$cookie_lifetime = 300;
//セッションの有効期限を指定する
session_start([
    'cookie_lifetime' => $cookie_lifetime,
]);

$username = $_SESSION['name'];
if (isset($_SESSION['id'])) {//ログインしているとき
    $msg = 'こんにちは' . htmlspecialchars($username, \ENT_QUOTES, 'UTF-8') . 'さん';
    $link_productList = '<a href="productList.php">商品管理ページへ</a>';
    $link_logout = '<a href="logout.php">ログアウト</a>';
} else { //ログインしていない時
    $msg = 'ログインしていません';
    $link = '<a href="login.php">ログイン</a>';
}

//isset()は関数がセットされているか返す関数
if (!isset($_SESSION['count'])) {
    // キー'count'が登録されていなければ、1を設定
    $_SESSION['count'] = 1;
} else {
    //  キー'count'が登録されていれば、その値をインクリメント
    $_SESSION['count']++;
}

?>
<h1><?php echo $msg; ?></h1>
<?php echo $_SESSION['count']."回目の訪問です。"; ?><br>
<?php echo $link_productList; ?><br>
<?php echo $link_logout; ?>

