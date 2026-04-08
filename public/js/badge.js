function markAsSeen(id) {
    console.log(id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/mark-badge-seen', {
        method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({ id: id })
    }).then(() => {
        document.querySelectorAll(`.row-${id}`).forEach(td => {
            console.log(td.classList);
            td.classList.remove('bg-yellow-200');
        });
    });
}