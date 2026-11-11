// 通知パネルの開け閉め（ふつうのJavaScript、ビルド不要）
document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('gp-notify-btn');
  var panel = document.getElementById('gp-notify-panel');
  if (!btn || !panel) return;

  var list = panel.querySelector('.gp-notify-list-view');
  var details = panel.querySelectorAll('[data-gp-detail]');

  // 一覧ビューに戻す
  function showList() {
    list.hidden = false;
    details.forEach(function (d) { d.hidden = true; });
  }
  function close() {
    panel.hidden = true;
    btn.setAttribute('aria-expanded', 'false');
    showList();
  }

  // ベルを押したら開く／閉じる
  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    if (panel.hidden) {
      panel.hidden = false;
      btn.setAttribute('aria-expanded', 'true');
    } else {
      close();
    }
  });

  // パネルの中のボタン
  panel.addEventListener('click', function (e) {
    e.stopPropagation();
    var open = e.target.closest('[data-gp-open]');
    if (open) {
      list.hidden = true;
      panel.querySelector('[data-gp-detail="' + open.dataset.gpOpen + '"]').hidden = false;
      return;
    }
    if (e.target.closest('[data-gp-back]')) showList();
    if (e.target.closest('[data-gp-close]')) close();
  });

  // パネルの外をクリック、またはEscキーで閉じる
  document.addEventListener('click', function () { if (!panel.hidden) close(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
});
