<?php
/**
 * Template Name: 新規登録
 * カスタムの登録ページ。権限は「投稿者（author）」で作ります。
 *
 * 設定 → 一般の「だれでもユーザー登録ができるようにする」がオフでも、
 * このページからの登録は動きます（wp_insert_user を直接使っているため）。
 */
$gp_redirect = gameport_safe_redirect_to();
$gp_errors   = array();
$gp_values   = array(
    'user_login' => '',
    'user_email' => '',
);

if ( is_user_logged_in() && 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
    wp_safe_redirect( $gp_redirect ? $gp_redirect : get_author_posts_url( get_current_user_id() ) );
    exit;
}

if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_POST['gp_register_nonce'] ) ) {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gp_register_nonce'] ) ), 'gp_register' ) ) {
        $gp_errors[] = 'もう一度ページを開き直してから送信してね。';
    } else {
        $login   = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ), true ) : '';
        $email   = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
        $pass    = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : '';
        $pass2   = isset( $_POST['pass2'] ) ? (string) wp_unslash( $_POST['pass2'] ) : '';
        $gp_values['user_login'] = $login;
        $gp_values['user_email'] = $email;

        if ( '' === $login || ! validate_username( $login ) ) {
            $gp_errors[] = '使えるユーザー名を入力してね（半角英数と一部の記号）。';
        } elseif ( username_exists( $login ) ) {
            $gp_errors[] = 'このユーザー名はすでに使われています。';
        }
        if ( ! is_email( $email ) ) {
            $gp_errors[] = '正しいメールアドレスを入力してね。';
        } elseif ( email_exists( $email ) ) {
            $gp_errors[] = 'このメールアドレスはすでに登録されています。';
        }
        if ( strlen( $pass ) < 8 ) {
            $gp_errors[] = 'パスワードは8文字以上にしてね。';
        } elseif ( $pass !== $pass2 ) {
            $gp_errors[] = 'パスワード（確認）が一致しません。';
        }

        if ( ! $gp_errors ) {
            $user_id = wp_insert_user( array(
                'user_login' => $login,
                'user_email' => $email,
                'user_pass'  => $pass,
                'role'       => 'author', // 投稿者：自分のゲームを投稿できる
            ) );
            if ( is_wp_error( $user_id ) ) {
                $gp_errors[] = $user_id->get_error_message();
            } else {
                // 登録したらそのままログイン
                wp_set_current_user( $user_id );
                wp_set_auth_cookie( $user_id, true );
                $user = get_userdata( $user_id );
                if ( $user ) {
                    do_action( 'wp_login', $user->user_login, $user );
                }
                wp_safe_redirect( $gp_redirect ? $gp_redirect : get_author_posts_url( $user_id ) );
                exit;
            }
        }
    }
}

get_header();
?>

<main class="gp-auth-container">
  <section class="gp-auth-card">
    <h1 class="gp-auth-title">新規登録</h1>

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
      <?php wp_nonce_field( 'gp_register', 'gp_register_nonce' ); ?>
      <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $gp_redirect ); ?>">

      <label for="gp-user-login">ユーザー名<span class="gp-required">*</span></label>
      <input type="text" id="gp-user-login" name="user_login" class="gp-form-input" value="<?php echo esc_attr( $gp_values['user_login'] ); ?>" required autocomplete="username">

      <label for="gp-user-email">メールアドレス<span class="gp-required">*</span></label>
      <input type="email" id="gp-user-email" name="user_email" class="gp-form-input" value="<?php echo esc_attr( $gp_values['user_email'] ); ?>" required autocomplete="email">

      <label for="gp-pass1">パスワード（8文字以上）<span class="gp-required">*</span></label>
      <input type="password" id="gp-pass1" name="pass1" class="gp-form-input" required autocomplete="new-password">

      <label for="gp-pass2">パスワード（確認）<span class="gp-required">*</span></label>
      <input type="password" id="gp-pass2" name="pass2" class="gp-form-input" required autocomplete="new-password">

      <button type="submit" class="gp-submit-btn">登録する</button>
    </form>

    <p class="gp-auth-switch">すでにアカウントがある？ <a href="<?php echo esc_url( gameport_login_url( $gp_redirect ) ); ?>">ログイン</a></p>
  </section>
</main>

<?php get_footer(); ?>
