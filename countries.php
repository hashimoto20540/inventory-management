<?php
//DB接続
$db = new PDO('mysql:host=localhost;dbname=inventory_management;charset=utf8', 'root', '');
//countriesテーブルを作成
$sql_countries = "CREATE TABLE IF NOT EXISTS countries (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL
)";
//IDを初期化
$sql = "ALTER TABLE countries AUTO_INCREMENT = 1";
$statement = $db->prepare($sql);
$statement->execute();
// sqlを実行
$db->exec($sql_countries);
//countryを入れていく
$sql = "INSERT INTO countries (name) VALUES (:name)";

$statement = $db->prepare($sql);
//入れる国名を追加
$statement->execute([
  ':name' => "England"
]);
?>

