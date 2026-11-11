<?php
/**
 * header.php（共通ヘッダー）
 * 新しいテンプレートでは get_header(); と書くだけでこのファイルが読み込まれます。
 * ※ front-page.php と page-contact.php は今まで通り、自分の中にヘッダーを持っています。
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'gameport-body' ); ?>>

  <!-- Header -->
  <header class="gp-header">
    <div class="gp-header-left">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gp-logo">GAMEPORT</a>
      <nav class="gp-nav">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
        <!-- 「ゲームを探す」ページ（固定ページ games）へ -->
        <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>">ゲームを探す</a>
        <a href="<?php echo esc_url( home_url( '/post/' ) ); ?>">投稿する</a>
      </nav>
    </div>

    <div class="gp-header-right">
      <!-- 検索：入力してEnterで search.php が表示されます -->
      <form class="gp-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="search" name="s" placeholder="ゲーム名・タグで検索" value="<?php echo get_search_query(); ?>">
      </form>

      <!-- 通知ベルアイコン（押すと通知パネルが開きます：js/notifications.js） -->
      <div class="gp-notify-wrap">
        <button type="button" class="gp-icon-btn" id="gp-notify-btn" aria-label="通知" aria-expanded="false" aria-controls="gp-notify-panel">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
        </button>
        <?php get_template_part( 'parts/notifications' ); ?>
      </div>

      <!-- ユーザーメニュー（ログイン状態で中身が変わります：parts/user-menu.php） -->
      <?php get_template_part( 'parts/user-menu' ); ?>
    </div>
  </header>
