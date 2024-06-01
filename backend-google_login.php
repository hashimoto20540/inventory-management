<?php
// PHPセッションを開始します
session_start();

// クライアントからのPOSTリクエストを受け取る
$input = file_get_contents('php://input');

// JSONデータをデコード
$data = json_decode($input, true);

// デコードされたデータをセッションに格納
$_SESSION['sub'] = $data['sub'] ?? 'no sub';
$_SESSION['email'] = $data['email'] ?? 'no email';
$_SESSION['name'] = $data['name'] ?? 'no name';


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
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit();
}

//ID初期化
function initializeId($db) {
	$sql = "ALTER TABLE users AUTO_INCREMENT = 1";
	$statement = $db->prepare($sql);
	$statement->execute();
}
initializeId($db);

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


//フォームに入力されたmailがすでに登録されていないか検索
$sql_mail = "SELECT * FROM users WHERE mail = :mail";
// stmt=ステートメント
//SQLを実行する前の準備。プレースホルダ（データベースクエリの中で、実際の値が代入される前に、仮の値として使用されるもの）
// を使いSQLインジェクションを防ぐ
$stmt = $db->prepare($sql_mail);
// SQLクエリ内のプレースホルダー :mail に、変数 $mail の値を結び付ける（バインドする）
$stmt->bindValue(':mail', $data['email']);
// クエリ実行
$stmt->execute();
// 結果を取得
//fetch() メソッドは、実行されたクエリから1行の結果セット(データベースから取得した1行のデータ)を取得します。
$member = $stmt->fetch();

if ($member) {
  // ユーザーが既に存在する場合、user_idを取得
  $user_id = $member['id'];
} else {
  // 登録されていなければ新しいユーザーを挿入
  $sql_new_users = "INSERT INTO users(name, mail) VALUES (:name, :mail)";
  $stmt = $db->prepare($sql_new_users);
  $stmt->bindValue(':name', $_SESSION['name']);
  $stmt->bindValue(':mail', $data['email']);
  $stmt->execute();
  // 挿入されたユーザーのIDを取得
  $user_id = $db->lastInsertId();
}

// google_accountsテーブルにデータを挿入
$sql_google_accounts = "INSERT INTO google_accounts(user_id, google_id) VALUES (:user_id, :google_id)";
$stmt_google_accounts = $db->prepare($sql_google_accounts);
$stmt_google_accounts->bindValue(':user_id', $user_id);
$stmt_google_accounts->bindValue(':google_id', $data['sub']);
$stmt_google_accounts->execute();

// control
// 受け取ったデータをJSON形式で返す
header('Content-Type: application/json');
echo json_encode([
    'id' => $member['id'],
    'sub' => $_SESSION['sub'],
    'email' => $_SESSION['email'],
    'name' => $_SESSION['name']
]);
?>
