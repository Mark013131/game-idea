// フォロー／フォロー中の切り替え（admin-ajax.php に送る）
// ログインしていないときはログインページへ移動します
document.addEventListener('DOMContentLoaded', function () {
  if (typeof GP_FOLLOW === 'undefined') return;

  document.querySelectorAll('.gp-follow-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!GP_FOLLOW.loggedIn) {
        window.location.href = btn.getAttribute('data-login-url');
        return;
      }
      var data = new FormData();
      data.append('action', 'gp_toggle_follow');
      data.append('nonce', GP_FOLLOW.nonce);
      data.append('user_id', btn.getAttribute('data-user-id'));
      btn.disabled = true;

      fetch(GP_FOLLOW.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: data })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          btn.disabled = false;
          if (!res || !res.success) {
            if (res && res.data && res.data.login) {
              window.location.href = res.data.url || btn.getAttribute('data-login-url');
            }
            return;
          }
          var on = !!res.data.following;
          btn.setAttribute('data-following', on ? '1' : '0');
          btn.classList.toggle('is-following', on);
          btn.textContent = on ? 'フォロー中' : 'フォロー';
          var count = document.querySelector('.gp-followers-count');
          if (count && typeof res.data.followers !== 'undefined') {
            count.textContent = res.data.followers;
          }
        })
        .catch(function () { btn.disabled = false; });
    });
  });
});
