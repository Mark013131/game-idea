// トップページのスライダー（自動で切り替え＋前へ/次へ＋○をクリック）
document.addEventListener('DOMContentLoaded', function () {
  var slider = document.querySelector('[data-gp-slider]');
  if (!slider) return;

  var slides = slider.querySelectorAll('.gp-hero-slide');
  var dots = slider.querySelectorAll('.gp-dot');
  var current = 0;
  var timer = null;
  var INTERVAL = 5000; // 5秒ごとに切り替え（ミリ秒）

  // n番目のスライドを表示して、○も同じ番号を active にする
  function show(n) {
    current = (n + slides.length) % slides.length; // 端まで行ったらループ
    slides.forEach(function (s, i) { s.classList.toggle('active', i === current); });
    dots.forEach(function (d, i) { d.classList.toggle('active', i === current); });
  }

  function start() { stop(); timer = setInterval(function () { show(current + 1); }, INTERVAL); }
  function stop() { if (timer) clearInterval(timer); }

  slider.querySelector('.gp-hero-next').addEventListener('click', function () { show(current + 1); start(); });
  slider.querySelector('.gp-hero-prev').addEventListener('click', function () { show(current - 1); start(); });
  dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i); start(); }); });

  // マウスを乗せている間は止める
  slider.addEventListener('mouseenter', stop);
  slider.addEventListener('mouseleave', start);

  show(0);
  start();
});
