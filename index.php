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
    $link_login_out = '<a href="logout.php">ログアウト</a>';
} else { //ログインしていない時
    $msg = 'ログインしていません';
    $link_login_out = '<a href="login_form.php">ログイン</a>';
}

?>
<h1><?php echo $msg; ?></h1>
<?php echo $link_productList; ?><br>
<?php echo $link_login_out; ?>