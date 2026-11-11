<?php
/**
 * ヘッダー右端のユーザーメニュー
 * ・ログイン前：ログイン／新規登録
 * ・ログイン後：マイページ／投稿する／ログアウト
 * アバターはプロフィールで設定した画像（gp_avatar）を使います。
 */
$gp_redirect = gameport_current_url();
?>
<div class="gp-user-wrap">
  <button type="button" class="gp-icon-btn" id="gp-user-btn" aria-label="アカウントメニュー" aria-expanded="false" aria-controls="gp-user-menu">
    <?php
    if ( is_user_logged_in() ) {
        gameport_the_avatar( get_current_user_id(), 32, 'header' );
    } else {
        gameport_the_avatar( 0, 32, 'header' );
    }
    ?>
  </button>
  <div class="gp-user-menu" id="gp-user-menu" hidden>
    <?php if ( is_user_logged_in() ) : ?>
      <a href="<?php echo esc_url( get_author_posts_url( get_current_user_id() ) ); ?>">マイページ</a>
      <a href="<?php echo esc_url( home_url( '/post/' ) ); ?>">投稿する</a>
      <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">ログアウト</a>
    <?php else : ?>
      <a href="<?php echo esc_url( gameport_login_url( $gp_redirect ) ); ?>">ログイン</a>
      <a href="<?php echo esc_url( gameport_register_url( $gp_redirect ) ); ?>">新規登録</a>
    <?php endif; ?>
  </div>
</div>
