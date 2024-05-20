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