<?php
session_start();
$username = $_SESSION['name'];
if (isset($_SESSION['id'])) {//ログインしているとき
    $msg = 'こんにちは' . htmlspecialchars($username, \ENT_QUOTES, 'UTF-8') . 'さん';
    $link_productList = '<a href="productList.php">商品管理ページへ</a>';
    $link_logout = '<a href="logout.php">ログアウト</a>';
} else { //ログインしていない時
    $msg = 'ログインしていません';
    $link = '<a href="login.php">ログイン</a>';
}
?>
<h1><?php echo $msg; ?></h1>
<?php echo $link_productList; ?><br>
<?php echo $link_logout; ?>
