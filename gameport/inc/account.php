<?php
/**
 * ログイン・新規登録・フォロー・プロフィール編集の処理
 * functions.php から読み込まれます。
 */

/* =========================================================
 * 便利なURL（ログイン／登録／今見ているページ）
 * ========================================================= */

// いま開いているページのURL（ログイン後に戻るときに使う）
function gameport_current_url() {
    $uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
    return home_url( $uri );
}

// カスタムのログインページ。$redirect を渡すと、ログイン後にそこに戻ります
// ※ wp-login.php は残してあるよ。テーマのリンクだけこちらを使う
function gameport_login_url( $redirect = '' ) {
    $url = home_url( '/login/' );
    if ( $redirect ) {
        $url = add_query_arg( 'redirect_to', $redirect, $url );
    }
    return $url;
}

function gameport_register_url( $redirect = '' ) {
    $url = home_url( '/register/' );
    if ( $redirect ) {
        $url = add_query_arg( 'redirect_to', $redirect, $url );
    }
    return $url;
}

// 外部サイトへのリダイレクトを防ぐ
function gameport_safe_redirect_to( $url = '' ) {
    if ( ! $url && isset( $_REQUEST['redirect_to'] ) ) {
        $url = wp_unslash( $_REQUEST['redirect_to'] );
    }
    return wp_validate_redirect( esc_url_raw( $url ), home_url( '/' ) );
}

// ゲームを探すページの、タグ絞り込みURL
function gameport_games_tag_url( $slug ) {
    return add_query_arg( 'tag', $slug, home_url( '/games/' ) );
}


/* =========================================================
 * アバター（ユーザーアイコン）
 * ユーザーメタ gp_avatar に添付ファイルIDを保存しています。
 * ========================================================= */
function gameport_get_avatar_url( $user_id, $size = 96 ) {
    $id = (int) get_user_meta( $user_id, 'gp_avatar', true );
    if ( $id ) {
        $url = wp_get_attachment_image_url( $id, array( $size, $size ) );
        if ( $url ) {
            return $url;
        }
    }
    return '';
}

// アバターをHTMLで出力。画像が無ければ仮のアイコン
function gameport_the_avatar( $user_id, $size = 80, $context = 'default' ) {
    $url = gameport_get_avatar_url( $user_id, $size * 2 );
    if ( $url ) {
        printf(
            '<img class="gp-avatar-img" src="%s" width="%d" height="%d" alt="">',
            esc_url( $url ),
            (int) $size,
            (int) $size
        );
        return;
    }
    if ( 'header' === $context ) {
        echo '<svg width="32" height="32" viewBox="0 0 24 24" fill="#ffffff" aria-hidden="true"><circle cx="12" cy="12" r="11" fill="#ffffff"/><circle cx="12" cy="9" r="3.2" fill="#222222"/><path d="M6.5 18.5c1.2-2.6 3.3-4 5.5-4s4.3 1.4 5.5 4" stroke="#222222" stroke-width="2.2" stroke-linecap="round" fill="none"/></svg>';
        return;
    }
    echo '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="#000" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6z"/></svg>';
}


/* =========================================================
 * フォロー
 * ・gp_following（配列）＝その人がフォローしているユーザーIDのリスト
 * ・gp_followers（数字）＝フォロワー数のキャッシュ
 * ========================================================= */
function gameport_get_following( $user_id ) {
    $list = get_user_meta( $user_id, 'gp_following', true );
    if ( ! is_array( $list ) ) {
        return array();
    }
    return array_values( array_unique( array_map( 'intval', $list ) ) );
}

function gameport_get_following_count( $user_id ) {
    return count( gameport_get_following( $user_id ) );
}

function gameport_get_followers_count( $user_id ) {
    $c = get_user_meta( $user_id, 'gp_followers', true );
    return ( '' === $c || false === $c ) ? 0 : max( 0, (int) $c );
}

function gameport_is_following( $follower_id, $target_id ) {
    return in_array( (int) $target_id, gameport_get_following( $follower_id ), true );
}

// フォロー／フォロー解除。新しい状態を配列で返す
function gameport_toggle_follow( $follower_id, $target_id ) {
    $follower_id = (int) $follower_id;
    $target_id   = (int) $target_id;
    $following   = gameport_get_following( $follower_id );

    if ( in_array( $target_id, $following, true ) ) {
        $following = array_values( array_diff( $following, array( $target_id ) ) );
        $is_on     = false;
        $count     = max( 0, gameport_get_followers_count( $target_id ) - 1 );
    } else {
        $following[] = $target_id;
        $is_on       = true;
        $count       = gameport_get_followers_count( $target_id ) + 1;
    }
    update_user_meta( $follower_id, 'gp_following', $following );
    update_user_meta( $target_id, 'gp_followers', $count );

    return array(
        'following'        => $is_on,
        'followers'        => $count,
        'following_count'  => count( $following ),
    );
}

// フォローボタンを出す（自分のページでは出さない）
function gameport_follow_button( $user_id ) {
    $user_id = (int) $user_id;
    if ( $user_id < 1 ) {
        return;
    }
    if ( is_user_logged_in() && get_current_user_id() === $user_id ) {
        return; // 自分自身はフォローできない
    }

    $is_on     = is_user_logged_in() && gameport_is_following( get_current_user_id(), $user_id );
    $login_url = gameport_login_url( gameport_current_url() );
    printf(
        '<button type="button" class="gp-follow-btn%s" data-user-id="%d" data-following="%s" data-login-url="%s">%s</button>',
        $is_on ? ' is-following' : '',
        $user_id,
        $is_on ? '1' : '0',
        esc_url( $login_url ),
        $is_on ? 'フォロー中' : 'フォロー'
    );
}

// Ajax：フォローのオン／オフ（ログインしている人だけ）
function gameport_ajax_toggle_follow() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'gp_follow' ) ) {
        wp_send_json_error( array( 'message' => 'もう一度やり直してね。' ), 403 );
    }
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'login' => true, 'url' => gameport_login_url( home_url( '/' ) ) ), 401 );
    }

    $target = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
    if ( $target < 1 || $target === get_current_user_id() || ! get_userdata( $target ) ) {
        wp_send_json_error( array( 'message' => 'このユーザーはフォローできません。' ), 400 );
    }

    wp_send_json_success( gameport_toggle_follow( get_current_user_id(), $target ) );
}
add_action( 'wp_ajax_gp_toggle_follow', 'gameport_ajax_toggle_follow' );

function gameport_ajax_toggle_follow_nopriv() {
    $redirect = isset( $_POST['redirect'] ) ? gameport_safe_redirect_to( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );
    wp_send_json_error( array( 'login' => true, 'url' => gameport_login_url( $redirect ) ), 401 );
}
add_action( 'wp_ajax_nopriv_gp_toggle_follow', 'gameport_ajax_toggle_follow_nopriv' );


/* =========================================================
 * プロフィール編集（自分のページの「編集」から送られてくる）
 * 送信先：admin-post.php （action=gp_update_profile）
 * ========================================================= */
function gameport_handle_profile_update() {
    $back = get_author_posts_url( get_current_user_id() );
    $back = remove_query_arg( array( 'gp_profile', 'gp_profile_error' ), $back );

    if ( ! isset( $_POST['gp_profile_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gp_profile_nonce'] ) ), 'gp_update_profile' ) ) {
        wp_safe_redirect( add_query_arg( 'gp_profile_error', 'nonce', $back ) );
        exit;
    }
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( gameport_login_url( $back ) );
        exit;
    }

    $user_id = get_current_user_id();
    $name    = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
    $bio     = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';

    if ( '' === $name ) {
        wp_safe_redirect( add_query_arg( 'gp_profile_error', 'name', $back ) );
        exit;
    }

    $updated = wp_update_user( array(
        'ID'           => $user_id,
        'display_name' => $name,
        'description'  => $bio,
    ) );
    if ( is_wp_error( $updated ) ) {
        wp_safe_redirect( add_query_arg( 'gp_profile_error', 'save', $back ) );
        exit;
    }

    // アバター画像（任意）
    if ( ! empty( $_FILES['gp_avatar']['name'] ) && UPLOAD_ERR_OK === $_FILES['gp_avatar']['error'] ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $avatar = gameport_upload_image( 'gp_avatar', 0 );
        if ( ! is_int( $avatar ) ) {
            wp_safe_redirect( add_query_arg( 'gp_profile_error', $avatar, $back ) );
            exit;
        }
        update_user_meta( $user_id, 'gp_avatar', $avatar );
    }

    wp_safe_redirect( add_query_arg( 'gp_profile', 'ok', $back ) );
    exit;
}
add_action( 'admin_post_gp_update_profile', 'gameport_handle_profile_update' );

function gameport_handle_profile_update_nopriv() {
    wp_safe_redirect( gameport_login_url( home_url( '/' ) ) );
    exit;
}
add_action( 'admin_post_nopriv_gp_update_profile', 'gameport_handle_profile_update_nopriv' );
