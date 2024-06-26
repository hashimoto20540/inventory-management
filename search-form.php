<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PHP Live MySQL Database Search</title>
<style>
    body{
        font-family: Arail, sans-serif;
    }
    /* Formatting search box */
    .search-box{
        width: 300px;
        position: relative;
        display: inline-block;
        font-size: 14px;
    }
    .search-box input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }
    .result{
        position: absolute;        
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
    }
    .result p:hover{
        background: #f2f2f2;
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
  // (document).ready(function(){ ... }); は、HTMLドキュメントが完全に読み込まれた後に内部のコードを実行するための関数です。
$(document).ready(function(){
  // .search-box内のテキスト入力ボックスに対して、「keyup」および「input」イベントが発生したときに実行されます。「keyup」はキーを離したとき、「input」は入力内容が変わったときにトリガーされます。
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        // 現在の入力ボックスの値を取得します。$(this) は、jQueryのセレクタです。イベントハンドラーの中で使うと、そのイベントが発生した要素を指します。.val() は、jQueryのメソッドで、フォーム要素（<input>、<select>、<textarea>など）の現在の値を取得
        var inputVal = $(this).val();
        //  入力ボックスと同じ親要素を持つ要素で、クラスが「result」であるものを取得します。.siblings() は、jQueryのメソッドで、現在の要素の兄弟要素をすべて選択します。
        var resultDropdown = $(this).siblings(".result");
        // 入力値がある場合はAJAXリクエストを送信し、入力値が空の場合は結果表示エリアをクリアします。
        if(inputVal.length){
          // $.get関数を使用して「backend-search.php」に対してGETリクエストを送信します。リクエストが成功すると、返されたデータ（検索結果）を結果表示エリアに表示します。
          //  $.get:jQueryのショートカットメソッドで、HTTP GETリクエストを送信します。サーバーからデータを取得するために使用します。
          //"backend-search.php": リクエストを送信するURLです。この場合、backend-search.phpというファイルにリクエストを送信します。このファイルは、サーバー側で検索処理を行い、結果を返すPHPスクリプトです。
          //{term: inputVal}: サーバーに送信するデータを指定します。このオブジェクトはキーと値のペアで構成されており、キーはterm、値はinputValです。inputValは検索ボックスに入力されたテキストです。
          //.done: jQueryのメソッドで、リクエストが成功したときに実行されるコールバック関数を指定します。サーバーからのレスポンスを処理するために使用されます。
          //function(data): コールバック関数です。サーバーから返されたデータは、この関数の引数dataに渡されます。
            $.get("backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                // resultDropdown.html(data); は、jQueryのメソッドを使ってHTML要素の内容を動的に更新する部分です。
                // resultDropdownは変数。jQueryの .html() メソッドは、指定した要素のHTML内容を取得または設定するために使われます。引数を与えると、その内容で要素の内部HTMLを置き換えます。
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    
    // Set search input value on click of result item
    // ドキュメント全体に対して、クラスが「result」である要素内の<p>タグがクリックされたときのイベントハンドラーを設定します。
    $(document).on("click", ".result p", function(){
      // クリックされた<p>タグのテキストを検索ボックスの入力フィールドに設定します。
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        // 検索結果をクリアします。
        $(this).parent(".result").empty();
    });
});
</script>
</head>
<body>
    <div class="search-box">
        <input type="text" autocomplete="off" placeholder="Search country..." />
        <div class="result"></div>
    </div>
</body>
</html>