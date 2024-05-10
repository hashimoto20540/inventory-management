document.getElementById("menu-toggle").addEventListener("click", function() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("hidden"); // hiddenクラスの付け外しで表示を切り替える
});

// 日付を取得し、HTMLに表示する関数
function displayCurrentDate() {
  const today = new Date(); // 現在の日付を取得
  const month = String(today.getMonth() + 1).padStart(2, '0'); // 月を取得（0始まりなので+1）
  const day = String(today.getDate()).padStart(2, '0'); // 日を取得

  // "YYYY/MM/DD" の形式で日付をフォーマット
  const formattedDate = `${month}月${day}日`;

  // 日付をHTMLに表示
  const dateElement = document.getElementById("today-date");
  dateElement.textContent = formattedDate; // テキストを設定
}

// ページの読み込み時に日付を表示
window.addEventListener("DOMContentLoaded", displayCurrentDate);