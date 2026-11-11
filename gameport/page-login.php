<?php
/**
 * Template Name: ログイン
 * カスタムのログインページ（見た目は GAMEPORT 流）。
 * WordPress標準の wp-login.php もそのまま残してあります。
 *
 * 送信はその場で処理して、成功したら redirect_to（またはホーム）へ移動します。
 */
$gp_redirect = gameport_safe_redirect_to();
$gp_errors   = array();

// すでにログインしている人はそのまま戻す
if ( is_user_logged_in() && 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
    wp_safe_redirect( $gp_redirect ? $gp_redirect : home_url( '/' ) );
    exit;
}

if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_POST['gp_login_nonce'] ) ) {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gp_login_nonce'] ) ), 'gp_login' ) ) {
        $gp_errors[] = 'もう一度ページを開き直してから送信してね。';
    } else {
        $login = isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '';
        $pass  = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : '';
        $remember = ! empty( $_POST['rememberme'] );

        if ( '' === $login || '' === $pass ) {
            $gp_errors[] = 'ユーザー名（またはメール）とパスワードを入力してね。';
        } else {
            // メールアドレスでログインできるように、先にユーザー名へ変換
            if ( is_email( $login ) ) {
                $user_by_email = get_user_by( 'email', $login );
                if ( $user_by_email ) {
                    $login = $user_by_email->user_login;
                }
            }
            $user = wp_signon(
                array(
                    'user_login'    => $login,
                    'user_password' => $pass,
                    'remember'      => $remember,
                ),
                is_ssl()
            );
            if ( is_wp_error( $user ) ) {
                $gp_errors[] = 'ユーザー名またはパスワードが違います。';
            } else {
                wp_safe_redirect( $gp_redirect ? $gp_redirect : home_url( '/' ) );
                exit;
            }
        }
    }
}

get_header();
?>

<main class="gp-auth-container">
  <section class="gp-auth-card">
    <h1 class="gp-auth-title">ログイン</h1>

    <?php if ( $gp_errors ) : ?>
      <div class="gp-post-notice gp-post-notice-error">
        <ul>
          <?php foreach ( $gp_errors as $msg ) : ?>
            <li><?php echo esc_html( $msg ); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form class="gp-auth-form" method="post" action="<?php echo esc_url( get_permalink() ); ?>">
      <?php wp_nonce_field( 'gp_login', 'gp_login_nonce' ); ?>
      <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $gp_redirect ); ?>">

      <label for="gp-log">ユーザー名またはメールアドレス<span class="gp-required">*</span></label>
      <input type="text" id="gp-log" name="log" class="gp-form-input" value="<?php echo isset( $_POST['log'] ) ? esc_attr( wp_unslash( $_POST['log'] ) ) : ''; ?>" required autofocus>

      <label for="gp-pwd">パスワード<span class="gp-required">*</span></label>
      <input type="password" id="gp-pwd" name="pwd" class="gp-form-input" required>

      <label class="gp-auth-remember">
        <input type="checkbox" name="rememberme" value="1">
        ログインしたままにする
      </label>

      <button type="submit" class="gp-submit-btn">ログイン</button>
    </form>

    <p class="gp-auth-switch">アカウントを持っていない？ <a href="<?php echo esc_url( gameport_register_url( $gp_redirect ) ); ?>">新規登録</a></p>
  </section>
</main>

<?php get_footer(); ?>
