<?php
/**
 * footer.php（共通フッター）
 * 新しいテンプレートでは get_footer(); と書くだけでこのファイルが読み込まれます。
 */
?>
  <!-- Footer -->
  <footer class="gp-footer">
    <div class="gp-footer-inner">
      <div class="gp-footer-logo">GAMEPORT</div>

      <div class="gp-footer-links">
        <div class="gp-footer-col">
          <h4>サイト</h4>
          <ul>
            <li><a href="<?php echo esc_url( home_url( '/post/' ) ); ?>">投稿する</a></li>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a></li>
            <li><a href="<?php echo esc_url( home_url( '/games/' ) ); ?>">ゲームを探す</a></li>
            <li><a href="<?php echo esc_url( home_url( '/ranking/' ) ); ?>">ランキング</a></li>
          </ul>
        </div>

        <div class="gp-footer-col">
          <h4>サポート</h4>
          <ul>
            <li><a href="<?php echo esc_url( home_url( '/contact/#faq' ) ); ?>">よくある質問</a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></li>
          </ul>
        </div>
      </div>

      <div class="gp-footer-socials">
        <!-- YouTube：チャンネルURLが決まったら href を書き換えてね（いまはまだ無いので #） -->
        <a href="#" aria-label="YouTube">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#ff0000">
            <rect x="2" y="4" width="20" height="16" rx="4" fill="#ff0000"/>
            <polygon points="10,8 16,12 10,16" fill="#ffffff"/>
          </svg>
        </a>
        <!-- Discord：サーバーの招待URLが決まったら href を書き換えてね（いまはまだ無いので #） -->
        <a href="#" aria-label="Discord">
          <svg width="22" height="22" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" fill="#1e1e1e"/>
            <circle cx="9.5" cy="11.5" r="1.5" fill="#ffffff"/>
            <circle cx="14.5" cy="11.5" r="1.5" fill="#ffffff"/>
          </svg>
        </a>
        <!-- GitHub：リポジトリURLが決まったら href を書き換えてね（いまはまだ無いので #） -->
        <a href="#" aria-label="GitHub">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#111111">
            <path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56 0-.28-.01-1.02-.02-2-3.2.7-3.88-1.54-3.88-1.54-.52-1.33-1.28-1.68-1.28-1.68-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.25.73-1.54-2.55-.29-5.24-1.28-5.24-5.68 0-1.26.45-2.28 1.18-3.09-.12-.29-.51-1.46.11-3.05 0 0 .97-.31 3.18 1.18a11.1 11.1 0 0 1 5.8 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.24 2.76.12 3.05.74.81 1.18 1.83 1.18 3.09 0 4.41-2.69 5.38-5.26 5.67.41.36.78 1.06.78 2.14 0 1.55-.01 2.79-.01 3.17 0 .31.21.68.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5z"/>
          </svg>
        </a>
      </div>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>