<?php
// PHPセッションを開始します。セッションは、ユーザーごとにサーバー側で一時的にデータを保持する仕組みです。これにより、ユーザーがウェブサイトを移動する際にデータを保持できます。
session_start();

// POSTデータの処理
// $_SERVERは、PHPで事前に定義されているスーパーグローバル変数の一つです。この変数は、サーバーや実行環境に関する情報を格納する連想配列です。
// クライアントから送られてきたリクエストに関する情報も含まれています。
// $_SERVERの連想配列のキーである 'REQUEST_METHOD' は、現在のリクエストのHTTPメソッドを表しています。
// これは、クライアント（通常はブラウザ）がサーバーに送信したリクエストが、どのメソッドを使用しているかを示します。
// POST: データを送信するために使用されます。
// ===は、値と型の両方を比較する厳密な比較演算子
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータを受け取る. リクエストのボディ全体を取得します。これは、JSON形式のデータを含むリクエストボディを読み取るために使用します。
    // HPの関数 file_get_contents:これは、指定したファイルの内容をすべて読み取る関数です。通常はファイルのパスを指定しますが、特殊なストリームも扱えます。
    // php://input:これは、HTTPリクエストの生のデータを読み取るための特殊な入力ストリームです。特に、POSTリクエストやPUTリクエストのボディのデータを取得するのに使います。
    // $_POSTや$_FILESとは異なり、php://inputは解析されていない生のデータを提供します。例えば、JSON形式のデータを受け取った場合などに便利です。
    $input = file_get_contents('php://input');
    // 取得したJSON形式のデータをPHPの連想配列に変換します。
    $data = json_decode($input, true);

    // デコードしたデータからemailフィールドを取得します。フィールドが存在しない場合は空文字列を返します。同様に、nameフィールドも取得します。
    // isset関数は、指定した変数が存在し、かつnullではないかどうかを確認します。ここでは、$data配列に'sub'というキーが存在するかどうかをチェックしています。
    $sub = isset($data['sub']) ? $data['sub'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $name = isset($data['name']) ? $data['name'] : '';

    // POSTデータをセッションに保存. セッションにemailを保存します。同様に、nameもセッションに保存します。
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;

    // ログイン成功レスポンス. レスポンスとしてJSON形式の成功メッセージを返します。
    echo json_encode(['status' => 'success']);
    // スクリプトの実行を終了します。これにより、POSTリクエストに対する処理が完了し、ログイン成功メッセージがクライアントに送信されます。
    exit();
}

// Model
// DBに接続
$dsn = "mysql:host=localhost; dbname=inventory_management; charset=utf8";
$username = "root";
$password = "";
//try ブロック内のコードは、エラーが発生する可能性のある処理を行う
try {
    //DBに接続dbh＝データベースハンドル
    $db = new PDO($dsn, $username, $password);
    //catch ブロックは、try ブロック内で例外（エラー）が発生した場合に実行。PDOException: PDOに関連するエラーをキャッチするための例外クラス（エラーを扱うための特別なクラス）。$e: 発生した例外のインスタンス（生成されたオブジェクト）を保持する変数。
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

// usersテーブル作成のSQL
$sql_create_users = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    mail VARCHAR(50),
    pass VARCHAR(255)
)";
// SQLの実行
$db->exec($sql_create_users);
// google_accountsテーブル作成
$sql_create_google_accounts = "CREATE TABLE IF NOT EXISTS google_accounts (
    user_id INT PRIMARY KEY,
    google_id VARCHAR(255) UNIQUE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->exec($sql_create_google_accounts);

// //フォームに入力されたmailがすでに登録されていないか検索
// $sql_mail = "SELECT * FROM users WHERE mail = :mail";
// // stmt=ステートメント
// //SQLを実行する前の準備。プレースホルダ（データベースクエリの中で、実際の値が代入される前に、仮の値として使用されるもの）
// // を使いSQLインジェクションを防ぐ
// $stmt = $db->prepare($sql_mail);
// // SQLクエリ内のプレースホルダー :mail に、変数 $mail の値を結び付ける（バインドする）
// $stmt->bindValue(':mail', $email);
// // クエリ実行
// $stmt->execute();
// // 結果を取得
// //fetch() メソッドは、実行されたクエリから1行の結果セット(データベースから取得した1行のデータ)を取得します。
// $member = $stmt->fetch();

// if ($member['mail'] === $email) {
//     // そのまま続行
// } else {
//     //登録されていなければinsert 
//     $sql_new_users = "INSERT INTO users(name, mail) VALUES (:name, :mail)";
//     $stmt = $db->prepare($sql_new_users);
//     $stmt->bindValue(':name', $name);
//     $stmt->bindValue(':mail', $email);
//     $stmt->execute();
    
//     // google_accountsテーブルにinsert
//     $sql_google_accounts = "INSERT INTO google_accounts(google_id) VALUES (:google_id)";
//     $stmt_google_accounts = $db->prepare($sql_google_accounts);
//     $stmt_google_accounts->bindValue(':google_id', $sub);
//     $stmt_google_accounts->execute();
// }



// ログイン成功後のページ表示
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
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
<header class="login__header">
</header>
<div class="layout-base__body">
  <h1 class="form__h1-2">Googleアカウントでログインしました</h1>
  <p class="wrapper-internal-link--center"><a href="index.php" class="internal-link">ホームへ</a></p>
  <script>
  // PHPで受け取ったデータをJavaScriptに渡す
  const sub = <?php echo json_encode($sub); ?>;
  const email = <?php echo json_encode($email); ?>;
  const name = <?php echo json_encode($name); ?>;

  // コンソールに出力
  console.log('sub:', sub);
  console.log('Email:', email);
  console.log('Name:', name);
  </script>   
</div>
</body>
</html>
