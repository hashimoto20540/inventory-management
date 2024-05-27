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
  <p>アカウントをお持ちでない方 <a href="signup.php" class="internal-link"><span>アカウントを作成</span></a></p>
  <div class="form__wrapper-input">
    <input type="text" name="mail" required class="form__input-text" placeholder="メールアドレス">
  </div>
  <div class="form__wrapper-input">
    <input type="password" name="pass" required class="form__input-text" placeholder="パスワード">
  </div>
  <input type="submit" value="ログイン" class="form__botton">

  <p>Googleアカウントをお持ちの方</p>
  <!-- ページがロードされたときにGoogleの認証サービスを初期化します。data-client_idにはGoogle Cloud Consoleで取得したクライアントIDを設定します。data-callbackは関数名 -->
  <div id="g_id_onload"
       data-client_id="67399827703-oqqaicvrrvg0je78cu8hh43fn6rd7rhd.apps.googleusercontent.com"
       data-callback="handleCredentialResponse">
  </div>
  <!-- Googleでログインボタンを表示します。 -->
  <div class="g_id_signin" data-type="standard"></div>

  <script>
    // ユーザーがログインした後、Googleが提供するJWT（JSON Web Token）を処理するコールバック関数です。
    // JWT（JSON Web Token）は、3つの部分で構成されています：ヘッダー、ペイロード、シグネチャ。それぞれの部分はドット（.）で区切られています。
    // ヘッダーには、トークンのタイプ（JWTであること）と使用される署名アルゴリズムが含まれる
    // ペイロードには、クレームと呼ばれる情報が含まれます。クレームは、トークンの発行者、発行対象、発行時間などの情報を含むキーとバリューのペアです。
    // シグネチャは、ヘッダーとペイロードをエンコードした後、それらに秘密鍵や公開鍵で署名したもの
    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: " + response.credential);
      const token = response.credential;
      // jwt_decodeを使ってデコードします。（JSON形式に変換）
      const decodedToken = jwt_decode(token);
      console.log("Decoded JWT: ", decodedToken);

      // sub（Subject）：トークンの主題（例：ユーザーID）
			const sub = decodedToken.sub;
			// console.log("Subject (sub):", sub);
      // GoogleアカウントのGmailと氏名（フル）
      const email = decodedToken.email;
      const name = decodedToken.name;

      // Sub、EmailとNameをサーバーに送信する
      sendLoginData(sub, email, name);
    }
    function sendLoginData(sub, email, name) {
      // Ajaxを使用
      var xhr = new XMLHttpRequest();
      // POSTメソッドでgoogle_login.phpにリクエストを送信
      xhr.open('POST', 'google_login.php', true);
      // setRequestHeaderメソッドは、リクエストヘッダーに指定した名前と値のペアを追加
      // Content-Typeは、HTTPヘッダーの一つで、リクエストボディのメディアタイプ（MIMEタイプ）を指定します。これにより、サーバーは受け取るデータの形式を理解できる
      // 'application/json'は、データがJSON（JavaScript Object Notation）形式で送信されることを示します。
      xhr.setRequestHeader('Content-Type', 'application/json');
      // onload は、XHRリクエストが完了したときに実行されるイベントハンドラです。このハンドラはリクエストが正常に完了（成功または失敗）した後に呼び出されます。
      xhr.onload = function() {
        // リクエストの完了後に返されるHTTPステータスコードです。200は、リクエストが成功したことを示します。
        if (xhr.status === 200) {
          // xhr.responseText には、サーバーからのレスポンスデータが含まれています。
          console.log('Login successful: ' + xhr.responseText);
          // ログイン成功後にgoogle_login_display.phpにリダイレクト
          // window.location.href を設定することで、現在のページから新しいページに移動します。
          window.location.href = 'google_login.php';
        } else {
          console.error('Login failed: ' + xhr.responseText);
        }
      };
      // emailとnameをJSON形式にエンコードしてリクエストボディに含める
      var data = JSON.stringify({ sub: sub, email: email, name: name });
      xhr.send(data);
    }
  </script>
</div>
</form>
</body>
</html>
