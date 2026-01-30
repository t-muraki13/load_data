document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const markBadgeSeenUrl = '/sales/mark-badge-seen';

  fetch(markBadgeSeenUrl, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({
          _token: csrfToken
      })
  }).then(response => {
      if (response.ok) {
          console.log('Badge marked as seen');
      } else {
          console.error('Failed to mark badge as seen');
      }
  }).catch(error => {
      console.error('Error:', error);
  });
});