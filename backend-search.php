<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "inventory_management");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 //$_REQUEST["term"]がセットされているかどうかを確認し、セットされている場合は検索を実行
if(isset($_REQUEST["term"])){
    // Prepare a select statement
    //LIKE ?を使用して、プレースホルダー?に対するパラメータバインディングを行うSQL文を準備します。
    $sql = "SELECT * FROM countries WHERE name LIKE ?";
    //mysqli_prepare関数でSQL文を準備します。
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        //mysqli_stmt_bind_param関数で、SQL文のプレースホルダーに実際の値（ここでは、ユーザーが入力した検索文字列）をバインドします。"s"はパラメータの型（文字列）を示します。
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        
        // Set parameters
        // パラメータ$param_termに、ユーザーが入力した検索文字列に続けてワイルドカード文字%を追加します。これにより、部分一致検索が可能になります。
        $param_term = $_REQUEST["term"] . '%';
        
        // Attempt to execute the prepared statement
        // mysqli_stmt_execute関数で準備されたSQL文を実行します。
        if(mysqli_stmt_execute($stmt)){
          // mysqli_stmt_get_result関数で結果セットを取得します。
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            //mysqli_num_rows関数で結果セットの行数を確認し、1行以上の場合は結果を表示します。該当する行がない場合は「No matches found」というメッセージを表示します。
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                // mysqli_fetch_array関数で結果セットから各行を連想配列として取得し、国名を表示します。MYSQLI_ASSOCは、mysqli_fetch_array関数の引数として使用されます。この関数は、データベースクエリの結果セットから1行分のデータを取得し、指定された形式で返します。MYSQLI_ASSOCを使用すると、結果が連想配列として返され、カラム名がキーとして使われます。
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo "<p>" . $row["name"] . "</p>";
                }
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
     
    // Close statement
    // 準備済みステートメントを閉じるための関数(準備済みステートメントのリソースを解放し、メモリを節約)
    mysqli_stmt_close($stmt);
}
 
// close connection
// MySQLデータベースへの接続を閉じるための関数(サーバー側のリソースを解放し、メモリを節約)
mysqli_close($link);
?>