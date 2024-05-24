<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン</title>
<link rel="stylesheet" type="text/css" href="css/login.css">

<!-- Googleログイン用 : Google Identity Services（GIS）ライブラリをHTMLにインクルードします。-->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script src="javascript/login_form.js" defer></script>
<!-- jwt-decodeライブラリをCDNから読み込む -->
<script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>

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




	<p>Googleアカウントをお持ちの方</p>
	<!-- ページがロードされたときにGoogleの認証サービスを初期化します。data-client_idにはGoogle Cloud Consoleで取得したクライアントIDを設定します。
data-callbackには、ユーザーが認証した後に呼び出されるJavaScript関数名を指定します。data-callbackには、ユーザーが認証した後に呼び出されるJavaScript関数名を指定します。 -->
	<div id="g_id_onload"
				data-client_id="67399827703-oqqaicvrrvg0je78cu8hh43fn6rd7rhd.apps.googleusercontent.com"
				data-callback="handleCredentialResponse">
	</div>
	<!-- oogleでログインボタンを表示します。
data-type="standard"は標準のGoogleログインボタンを表示するための設定です。他にもiconやbuttonなどのタイプがあります。 -->
	<div class="g_id_signin" data-type="standard"></div>

	<script>
			// ユーザーがログインした後、Googleが提供するJWT（JSON Web Token）を処理するコールバック関数です。
			// ここでresponse.credentialにエンコードされたJWT IDトークンが含まれています。このトークンをバックエンドに送信してユーザーの認証を行います。
			function handleCredentialResponse(response) {
						console.log("Encoded JWT ID token: " + response.credential);
					// ここにトークンをバックエンドに送信して検証するコードを追加できます。
						const token = response.credential;
            const decodedToken = jwt_decode(token);
            console.log("Decoded JWT: ", decodedToken);

            const email = decodedToken.email;
            const name = decodedToken.name;

            console.log("Email: ", email);
            console.log("Name: ", name);

						// EmailとNameをサーバーに送信する
						sendLoginData(email, name);
			}
			function sendLoginData(email, name) {
				fetch('google_login.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({ email: email, name: name })
				})
				.then(response => response.json())
				.then(data => {
					console.log('Success:', data);
					if (data.status === 'success' && data.redirect) {
						window.location.href = data.redirect; // サーバーからのレスポンスに基づいてリダイレクト
					}
				})
				.catch((error) => {
					console.error('Error:', error);
				});
			}
	</script>
</div>

</form>
</body>
</html>