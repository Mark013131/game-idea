<?php
/**
 * Template Name: お問い合わせ
 * お問い合わせページ。CSS（css/contact.css）は functions.php で読み込んでいます。
 */

// よくある質問（ここを書き換えるだけで項目を増やせます）
$faq_items = array(
    'ゲームの投稿方法がわかりません',
    '投稿したゲームの内容を変更したいです',
    '不適切なコンテンツを見つけた場合の対処は？',
    '返信などはどのくらいで届きますか？',
);

// お問い合わせ種別（セレクトボックスの選択肢）
$contact_types = array(
    'ご質問',
    'ご提案・ご要望',
    '不具合の報告',
    '不適切なコンテンツの報告',
    'その他',
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'gameport-body' ); ?>>

  <!-- Header（トップページと同じ） -->
  <header class="gp-header">
    <div class="gp-header-left">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gp-logo">GAMEPORT</a>
      <nav class="gp-nav">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
        <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>">ゲームを探す</a>
        <a href="<?php echo esc_url( home_url( '/post/' ) ); ?>">投稿する</a>
      </nav>
    </div>

    <div class="gp-header-right">
      <form class="gp-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="search" name="s" placeholder="ゲーム名・タグで検索" value="<?php echo get_search_query(); ?>">
      </form>

      <!-- 通知ベルアイコン（押すと通知パネルが開きます） -->
      <div class="gp-notify-wrap">
        <button type="button" class="gp-icon-btn" id="gp-notify-btn" aria-label="通知" aria-expanded="false" aria-controls="gp-notify-panel">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
      </button>
        <?php get_template_part( 'parts/notifications' ); ?>
      </div>

      <!-- ユーザーメニュー（ログイン状態で中身が変わります） -->
      <?php get_template_part( 'parts/user-menu' ); ?>
    </div>
  </header>

  <!-- Main Content -->
  <main class="gp-contact-container">
    <div class="gp-contact-layout">

      <!-- 左カラム：タイトル・その他の方法・FAQ -->
      <div class="gp-contact-side">
        <div class="gp-contact-heading">
          <h1 class="gp-contact-title">お問い合わせ</h1>
          <p class="gp-contact-lead">ご質問・ご提案・不具合の報告など<br>お気軽にお問い合わせください</p>
        </div>

        <section class="gp-contact-card">
          <h2 class="gp-contact-card-title">その他の問い合わせ方法</h2>
          <ul class="gp-contact-methods">
            <li>
              <span class="gp-contact-method-label">メール</span>
              <a href="mailto:sample@example.com">sample@example.com</a>
            </li>
            <li>
              <span class="gp-contact-method-label">Discordサーバー</span>
              <!-- Discord：サーバーの招待URLが決まったら href を書き換えてね（いまはまだ無いので #） -->
              <a href="#">コミュニティでサポート中！</a>
            </li>
            <li>
              <span class="gp-contact-method-label">GitHub</span>
              <!-- GitHub：リポジトリURLが決まったら href を書き換えてね（いまはまだ無いので #） -->
              <a href="#">github.com/gameport</a>
            </li>
          </ul>
        </section>

        <section class="gp-contact-card" id="faq">
          <h2 class="gp-contact-card-title">よくある質問</h2>
          <ul class="gp-faq-list">
            <?php foreach ( $faq_items as $i => $faq ) : ?>
              <li id="faq-<?php echo (int) ( $i + 1 ); ?>"><a href="#faq-<?php echo (int) ( $i + 1 ); ?>"><span class="gp-faq-q">Q.</span><?php echo esc_html( $faq ); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </section>
      </div>

      <!-- 右カラム：フォーム -->
      <section class="gp-contact-card gp-contact-form-card">
        <h2 class="gp-contact-card-title">お問い合わせフォーム</h2>

        <?php
        /*
         * ▼ いまはただのHTMLフォームです（見た目の確認用。送信しても届きません）
         * Contact Form 7 を使う場合は、下の <form>〜</form> をまるごと消して
         *   echo do_shortcode( '[contact-form-7 id="xxxx" title="お問い合わせ"]' );
         * （PHPタグで囲む）に置き換えてください。id は CF7 の管理画面で確認できます。CF7 側のフォームに class="gp-form-input" などを
         * 付ければ、このCSSのデザインがそのまま使えます。
         */
        ?>
        <form class="gp-contact-form" method="post" action="#">
          <div class="gp-form-row">
            <label for="gp-name">お名前<span class="gp-required">*</span></label>
            <input type="text" id="gp-name" name="your-name" class="gp-form-input" required>
          </div>

          <div class="gp-form-row">
            <label for="gp-email">メールアドレス<span class="gp-required">*</span></label>
            <input type="email" id="gp-email" name="your-email" class="gp-form-input" required>
          </div>

          <div class="gp-form-row">
            <label for="gp-type">お問い合わせ種別<span class="gp-required">*</span></label>
            <select id="gp-type" name="your-type" class="gp-form-input" required>
              <option value="">選択してください</option>
              <?php foreach ( $contact_types as $type ) : ?>
                <option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $type ); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="gp-form-row">
            <label for="gp-subject">件名<span class="gp-required">*</span></label>
            <input type="text" id="gp-subject" name="your-subject" class="gp-form-input" required>
          </div>

          <div class="gp-form-row">
            <label for="gp-message">メッセージ<span class="gp-required">*</span></label>
            <textarea id="gp-message" name="your-message" class="gp-form-input gp-form-textarea" rows="7" required></textarea>
          </div>

          <button type="submit" class="gp-submit-btn">送信する</button>
        </form>
      </section>

    </div>
  </main>

  <!-- Footer（トップページと同じ） -->
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
            <!-- 固定ページのスラッグを contact にするとこのリンクで開けます -->
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
