<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<form action="login.php" method="post">
<div class="layout-base__body">
	<h1>ログイン</h1>
	<p>アカウントをお持ちでない方 <a href="signup.php" class="internal-link"><span >アカウントを作成</span></a></p>
	<div class="form__wrapper-input">
		<input type="text" name="mail" required class="form__input-text" placeholder="メールアドレス">
	</div>
	<div class="form__wrapper-input">
		<input type="password" name="pass" required class="form__input-text" placeholder="パスワード">
	</div>
	<input type="submit" value="ログイン" class="form__botton">
</div>

</form>
</body>
</html>