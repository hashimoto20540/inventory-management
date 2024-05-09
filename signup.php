<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>新規アカウント登録</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<!-- //処理を行う宛先を指定 -->
<header class="login__header">
</header>
<form action="register.php" method="post">
	<div class="layout-base__body">
    <h1 class="form__h1-2">アカウントを作成しましょう</h1>
    <div class="form__wrapper-input">
			<input type="text" name="name" required class="form__input-text" placeholder="名前">
    </div>
    <div class="form__wrapper-input">
			<input type="text" name="mail" required class="form__input-text" placeholder="メールアドレス">
    </div>
    <div class="form__wrapper-input">
			<input type="password" name="pass" required class="form__input-text" placeholder="パスワード">
    </div>
    <input type="submit" value="新規登録" class="form__botton2">
    <p class="form__text--center">アカウントをお持ちの方は<a href="login_form.php" class="internal-link">こちら</a></p>
	</div>
</form>
</body>
</html>