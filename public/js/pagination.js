const select = document.getElementById('sort');
  select.addEventListener('change', function() {
    this.form.submit();
  });
  
const pagination = document.getElementById('pagination');
  pagination.addEventListener('change', function() {
    this.form.submit();
  });