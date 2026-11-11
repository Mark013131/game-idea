// 投稿ページ：タグのチップと画像プレビュー
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('gp-post-form');
  if (!form) return; // ログインしていないときはフォームが無い

  // ---- タグ ----
  var tags = [];
  var input = document.getElementById('gp-tag-input');
  var chips = document.getElementById('gp-tag-chips');
  var hidden = document.getElementById('gp-tags-hidden');

  function renderTags() {
    chips.innerHTML = '';
    tags.forEach(function (t, i) {
      var li = document.createElement('li');
      li.className = 'gp-new-tag gp-post-chip';
      li.textContent = t;
      var x = document.createElement('button');
      x.type = 'button';
      x.setAttribute('aria-label', t + ' を削除');
      x.textContent = '×';
      x.addEventListener('click', function () { tags.splice(i, 1); renderTags(); });
      li.appendChild(x);
      chips.appendChild(li);
    });
    hidden.value = tags.join(','); // サーバーにはカンマ区切りで送る
  }

  function addTag() {
    var v = input.value.replace(/,/g, '').trim();
    if (v && tags.indexOf(v) === -1 && tags.length < 10) tags.push(v);
    input.value = '';
    renderTags();
  }

  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.isComposing) { e.preventDefault(); addTag(); } // 日本語変換中のEnterは無視
  });
  document.getElementById('gp-tag-add').addEventListener('click', addTag);

  // ---- サムネイルのプレビュー ----
  var thumbInput = document.getElementById('gp-thumb-input');
  var thumbPrev = document.getElementById('gp-thumb-preview');
  var thumbBox = document.getElementById('gp-thumb-box');
  thumbInput.addEventListener('change', function () {
    var f = thumbInput.files[0];
    if (!f) return;
    thumbPrev.src = URL.createObjectURL(f);
    thumbPrev.hidden = false;
    thumbBox.classList.add('has-image');
  });

  // ---- スクリーンショットのプレビュー ----
  var shotsInput = document.getElementById('gp-shots-input');
  var shotsPrev = document.getElementById('gp-shots-preview');
  shotsInput.addEventListener('change', function () {
    shotsPrev.innerHTML = '';
    Array.prototype.slice.call(shotsInput.files, 0, 6).forEach(function (f) {
      var img = document.createElement('img');
      img.src = URL.createObjectURL(f);
      img.alt = '';
      shotsPrev.appendChild(img);
    });
  });

  // 送信前に入力途中のタグも追加しておく
  form.addEventListener('submit', function () { if (input.value.trim()) addTag(); });
});
