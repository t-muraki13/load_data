function deletePost(e) {
  'use strict';
  if(confirm('完了にしてもよろしいですか？')) {
    document.getElementById('delete_' + e.dataset.id).submit();
  }
}