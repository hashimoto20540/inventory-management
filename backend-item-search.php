<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "inventory_management");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// 文字セットをUTF-8に設定（英語だけでなく、日本語も検索できるようにするため）
mysqli_set_charset($link, "utf8mb4");

 //$_REQUEST["term"]がセットされているかどうかを確認し、セットされている場合は検索を実行. $_REQUEST["term"]は、PHPでWebフォームから送信されたデータを取得するためのスーパーグローバル変数です。具体的には、HTTP POST メソッドや GET メソッドで送信されたデータを取得します。
if(isset($_REQUEST["term"])){
    // Prepare a select statement
    //LIKE ?を使用して、プレースホルダー?に対するパラメータバインディングを行うSQL文を準備します。COLLATE utf8mb4_general_ciで照合順序を行う。

    $sql = "SELECT 
        items.id AS item_id,
        items.name AS item_name,
        items.furigana,
        items.item_description,
        items.price,
        items.image_path,
        quantities.id AS quantity_id,
        quantities.quantity,
        categories.name AS category_name
    FROM 
        items   
    INNER JOIN 
        quantities ON items.id = quantities.item_id
    INNER JOIN 
        item_categories ON items.id = item_categories.item_id
    INNER JOIN 
        categories ON item_categories.category_id = categories.id
    WHERE items.name 
    COLLATE utf8mb4_general_ci 
    LIKE ?";

    //mysqli_prepare関数でSQL文を準備します。
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        //mysqli_stmt_bind_param関数で、SQL文のプレースホルダーに実際の値（ここでは、ユーザーが入力した検索文字列）をバインドします。"s"はパラメータの型（文字列）を示します。
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        
        // Set parameters
        // パラメータ$param_termに、ユーザーが入力した検索文字列に続けてワイルドカード文字%を追加します。これにより、部分一致検索が可能になります。
        $param_term =  $_REQUEST["term"] . '%';
        
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
                    echo "<td>" . $row["item_id"] . "</td>";
                    // ヒアドキュメントを使用して複数行のHTMLを出力. <<<HTMLで開始、HTML;で終了
                    echo <<<HTML
                    <td>
                        <div class="productList__table--wapper-image">
                HTML;
                    if (!empty($row['image_path'])) {
                        echo "<img class='productList__table--image' src='" . htmlspecialchars($row['image_path']) . "' />";
                    } else {
                        echo "<img class='productList__table--image' src='https://placehold.jp/300x200.png' />";
                    }
                    echo <<<HTML
                        </div>
                    </td>
                HTML;
                    echo "<td>" . $row["item_name"] . "</td>";
                    echo "<td>" . $row["furigana"] . "</td>";
                    echo "<td>" . $row["category_name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                }
            } else{
                echo "<p>当てはまる商品が見つかりません。</p>";
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