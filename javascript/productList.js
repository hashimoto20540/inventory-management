document.addEventListener('DOMContentLoaded', function() {
  const table = document.getElementById('table_items');
  table.addEventListener('click', function(event) {
    const row = event.target.closest('tr');
    if (row && row.hasAttribute('data-id')) {
      const id = row.getAttribute('data-id');
      const name = row.getAttribute('data-name');
      const quantity = row.getAttribute('data-quantity');
      const furigana = row.getAttribute('data-furigana');
      const description = row.getAttribute('data-description');
      const price = row.getAttribute('data-price');
      const image = row.getAttribute('data-image');
      // クエリパラメータとして情報を含めたURLに遷移
      window.location.href = `editData.php?id=${id}&quantity=${quantity}&name=${name}&furigana=${furigana}&item_description=${description}&price=${price}&image_path=${image}`;
    }
  });
});

// (document).ready(function(){ ... }); は、HTMLドキュメントが完全に読み込まれた後に内部のコードを実行するための関数です。
$(document).ready(function(){
  // .search-box内のテキスト入力ボックスに対して、「keyup」および「input」イベントが発生したときに実行されます。「keyup」はキーを離したとき、「input」は入力内容が変わったときにトリガーされます。
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        // 現在の入力ボックスの値を取得します。$(this) は、jQueryのセレクタです。イベントハンドラーの中で使うと、そのイベントが発生した要素を指します。
        // .val() は、jQueryのメソッドで、フォーム要素（<input>、<select>、<textarea>など）の現在の値を取得。検索ボックスで検索したものです。
        var inputVal = $(this).val();
        
        //  クラスが「table-items__tbody--search-result」であるものを取得します。以下のクラスのオブジェクト情報を取得
        var resultDropdown = $(".table-items__tbody--search-result");
        // console.log(resultDropdown);
        // 入力値がある場合はAJAXリクエストを送信し、入力値が空の場合は結果表示エリアをクリアします。
        if(inputVal.length){
          //$.get:jQueryのショートカットメソッドで、HTTP GETリクエストを送信します。サーバーからデータを取得するために使用します。
          //"backend-item-search.php": リクエストを送信するURLです。この場合、backend-item-search.phpというファイルにリクエストを送信します。このファイルは、サーバー側で検索処理を行い、結果を返すPHPスクリプトです。
          //{term: inputVal}: サーバーに送信するデータを指定します。このオブジェクトはキーと値のペアで構成されており、キーはterm、値はinputValです。inputValは検索ボックスに入力されたテキストです。
          //.done: jQueryのメソッドで、リクエストが成功したときに実行されるコールバック関数を指定します。サーバーからのレスポンスを処理するために使用されます。
          //function(data): コールバック関数です。サーバーから返されたデータは、この関数の引数dataに渡されます。
            $.get("backend-item-search.php", {term: inputVal}).done(function(data){
                // dataには、羅列されたHTMLが入っている
                // console.log(data);
                // resultDropdown.html(data); は、jQueryのメソッドを使ってHTML要素の内容を動的に更新する部分です。
                // resultDropdownは変数。jQueryの .html() メソッドは、指定した要素のHTML内容を取得または設定するために使われます。引数を与えると、その内容で要素の内部HTMLを置き換えます。
                resultDropdown.html(data);
            });
        } else{
          // 検索ボックスが空の場合、すべての商品を再表示する
          //$.get は jQuery の関数で、HTTP GET リクエストを送信します。
          //第一引数 "backend-item-search.php" はリクエストを送信する URL を指定
          //第二引数 { term: "" } は送信するデータをオブジェクト形式で指定しています。この場合、term というキーに空の文字列 "" を渡しています。これにより、サーバー側では検索文字列が空であることがわかります。
          $.get("backend-item-search.php", { term: "" }).done(function(data) {
            resultDropdown.html(data);
          });

        }
    });
    
    // Set search input value on click of table-items__tbody--search-result item
    // ドキュメント全体に対して、クラスが「table-items__tbody--search-result」である要素内の<p>タグがクリックされたときのイベントハンドラーを設定します。
    // $(document).on("click", ".table-items__tbody--search-result p", function(){
    //   // クリックされた<p>タグのテキストを検索ボックスの入力フィールドに設定します。
    //     $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
    //     // 検索結果をクリアします。
    //     $(this).parent(".table-items__tbody--search-result").empty();
    // });
});

//検索ボックスに入力があるかどうかを検知し、入力がある場合は商品一覧を非表示にし、入力がない場合は表示する
$(document).ready(function(){
  $('.search-box input[type="text"]').on("keyup", function() {
      var searchText = $(this).val().trim();
      if(searchText !== '') {
          $('.table-items__tbody--search-result .table-items__tr--list').hide(); // 入力がある場合は商品一覧を非表示にする
      } else {
          $('.table-items__tbody--search-result .table-items__tr--list').show(); // 入力がない場合は商品一覧を表示する
      }
  });
});