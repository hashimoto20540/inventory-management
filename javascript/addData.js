document.getElementById("imageEditButton").addEventListener("click", function() {
  document.getElementById("imageUpload").click();
});
//imageUploadが書き換わったら実行
document.getElementById("imageUpload").addEventListener("change", function() {
  //選択されたファイルを取得し、変数 file に格納
  var file = this.files[0];
  if (file) {
    //ファイルを読み込むための FileReader オブジェクトを作成
    var reader = new FileReader();
    //ファイルの読み込みが完了したときに実行される関数。読み込んだファイルのデータが e.target.result 
    reader.onload = function(e) {
      //src属性に設定
      document.getElementById("imagePreview").src = e.target.result;
    }
    //ファイルをデータURLとして読み込む
    reader.readAsDataURL(file);
  }
});