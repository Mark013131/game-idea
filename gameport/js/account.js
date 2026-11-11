// ヘッダーのユーザーメニュー開閉＋プロフィール編集フォームの表示切替
document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('gp-user-btn');
  var menu = document.getElementById('gp-user-menu');
  if (btn && menu) {
    function closeMenu() {
      menu.hidden = true;
      btn.setAttribute('aria-expanded', 'false');
    }
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var willOpen = menu.hidden;
      menu.hidden = !willOpen;
      btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    });
    menu.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
  }

  // 自分のプロフィールの「編集」ボタン
  var editBtn = document.getElementById('gp-edit-toggle');
  var form = document.getElementById('gp-profile-form');
  if (editBtn && form) {
    editBtn.addEventListener('click', function () {
      var open = form.hidden;
      form.hidden = !open;
      editBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      editBtn.textContent = open ? '閉じる' : '編集';
      if (open) {
        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    });
  }
});
