document.querySelectorAll("tr[data-href]").forEach((row) => {
  row.addEventListener("click", () => {
      const url = row.getAttribute("data-href"); // data-hrefからURLを取得
      window.location.href = url; // URLに遷移
  });
});