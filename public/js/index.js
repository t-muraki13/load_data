function toggleComplete(id) {
    const rows = document.querySelectorAll(`.row-${id}`);
    const button = document.getElementById('toggle-button-' + id);
    console.log(rows, button); 
  
    let isCompleted = localStorage.getItem('completed-' + id) === 'true';
  
    for (let i = 0; i < rows.length; i++) {
        // if (!isCompleted) {
        //     rows[i].classList.add('bg-gray-400'); // グレーに変更
        // } else {
        //     rows[i].classList.remove('bg-gray-400'); // 背景色をリセット
        // }
        if (!isCompleted) {
            // 背景色をグレーに変更 
            rows[i].style.setProperty('background-color', '#9CA3AF', 'important');
        } else {
            // 背景色をリセット
            rows[i].style.setProperty('background-color', '', 'important');
        }
    }
  
    if (!isCompleted) {
        button.innerText = '戻す'; // ボタンのテキストを「戻す」に変更
        button.classList.remove('bg-red-500'); // 背景色を変更
        button.classList.add('bg-gray-500'); // グレーに変更
        localStorage.setItem('completed-' + id, 'true');
    } else {
        button.innerText = '完了'; // ボタンのテキストを「完了」に変更
        button.classList.remove('bg-gray-500'); // 背景色を変更
        button.classList.add('bg-red-500'); // 元の色に戻す
        localStorage.setItem('completed-' + id, 'false');
    }
  }
  
  // ページ読み込み時に状態を復元
  document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('[id^=toggle-button-]');
  
    buttons.forEach(button => {
      const id = button.id.replace('toggle-button-', '');
      const isCompleted = localStorage.getItem('completed-' + id) === 'true';
      const rows = document.querySelectorAll(`.row-${id}`);
  
      if (isCompleted) {
        button.innerText = '戻す';
        button.classList.remove('bg-red-500');
        button.classList.add('bg-gray-500');
  
        for (let i = 0; i < rows.length; i++) {
            //rows[i].classList.add('bg-gray-400');
            rows[i].style.setProperty('background-color', '#9CA3AF', 'important');
        }
      }
    });
  });