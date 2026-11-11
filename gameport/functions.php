<?php
// テーマの基本設定（元の設定そのまま）
function gameport_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'gameport_setup' );

// ログイン・フォロー・プロフィール編集（inc/account.php）
require get_template_directory() . '/inc/account.php';

function gameport_enqueue_scripts() {
    $dir = get_template_directory_uri();

    // 全ページ共通：テーマ直下の style.css
    wp_enqueue_style( 'gameport-style', get_stylesheet_uri(), array(), '1.0.0' );

    // 通知パネル：全ページで使うのでいつも読み込む
    wp_enqueue_style( 'gameport-notifications', $dir . '/css/notifications.css', array( 'gameport-style' ), '1.0.0' );
    wp_enqueue_script( 'gameport-notifications', $dir . '/js/notifications.js', array(), '1.0.0', true );

    // ユーザーメニュー・フォローボタン・プロフィール編集の共通CSS/JS
    wp_enqueue_style( 'gameport-account', $dir . '/css/account.css', array( 'gameport-style' ), '1.0.0' );
    wp_enqueue_script( 'gameport-account', $dir . '/js/account.js', array(), '1.0.0', true );

    // フォローボタンがあるページだけ Ajax 用のJSを読む
    if ( is_author() || is_singular( 'post' ) ) {
        wp_enqueue_script( 'gameport-follow', $dir . '/js/follow.js', array(), '1.0.0', true );
        wp_localize_script( 'gameport-follow', 'GP_FOLLOW', array(
            'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'gp_follow' ),
            'loggedIn' => is_user_logged_in(),
        ) );
    }

    // ここから下は「そのページのときだけ」読み込むファイル
    if ( is_front_page() ) {
        wp_enqueue_style( 'gameport-slider', $dir . '/css/slider.css', array( 'gameport-style' ), '1.0.0' );
        wp_enqueue_script( 'gameport-slider', $dir . '/js/slider.js', array(), '1.0.0', true );
    }
    if ( is_page_template( 'page-contact.php' ) || is_page( 'contact' ) ) {
        wp_enqueue_style( 'gameport-contact', $dir . '/css/contact.css', array( 'gameport-style' ), '1.0.0' );
    }
    if ( is_page_template( 'page-post.php' ) || is_page( 'post' ) ) {
        wp_enqueue_style( 'gameport-post', $dir . '/css/post.css', array( 'gameport-style' ), '1.1.0' );
        wp_enqueue_script( 'gameport-post', $dir . '/js/post.js', array(), '1.0.0', true );
    }
    if ( is_page_template( array( 'page-games.php', 'page-ranking.php' ) ) || is_page( array( 'games', 'ranking' ) ) ) {
        wp_enqueue_style( 'gameport-games', $dir . '/css/games.css', array( 'gameport-style' ), '1.0.0' );
    }
    if ( is_singular( 'post' ) ) {
        wp_enqueue_style( 'gameport-single', $dir . '/css/single.css', array( 'gameport-style' ), '1.1.0' );
    }
    if ( is_author() ) {
        wp_enqueue_style( 'gameport-author', $dir . '/css/author.css', array( 'gameport-style' ), '1.0.0' );
    }
    if ( is_search() ) {
        wp_enqueue_style( 'gameport-search', $dir . '/css/search.css', array( 'gameport-style' ), '1.0.0' );
    }
    if ( is_page_template( array( 'page-login.php', 'page-register.php' ) ) || is_page( array( 'login', 'register' ) ) ) {
        wp_enqueue_style( 'gameport-auth', $dir . '/css/auth.css', array( 'gameport-style' ), '1.0.0' );
    }
}
add_action( 'wp_enqueue_scripts', 'gameport_enqueue_scripts' );


/* =========================================================
 * 閲覧数カウンター（ランキング・閲覧数順で使う）
 * ゲーム詳細ページが開かれるたびに、投稿のメタ gp_views を +1 します。
 * ※ログイン中の管理者が見た分は数えません。
 * ========================================================= */
function gameport_count_views() {
    if ( ! is_singular( 'post' ) || is_preview() || current_user_can( 'edit_others_posts' ) ) {
        return;
    }
    $id    = get_queried_object_id();
    $views = (int) get_post_meta( $id, 'gp_views', true );
    update_post_meta( $id, 'gp_views', $views + 1 );
}
add_action( 'template_redirect', 'gameport_count_views' );

// 新しい投稿には gp_views = 0 を入れておく（閲覧数順で0件の投稿も出るように）
function gameport_init_views( $post_id, $post, $update ) {
    if ( ! $update && 'post' === $post->post_type && '' === get_post_meta( $post_id, 'gp_views', true ) ) {
        update_post_meta( $post_id, 'gp_views', 0 );
    }
}
add_action( 'wp_insert_post', 'gameport_init_views', 10, 3 );


/* =========================================================
 * ゲーム投稿フォームの保存（page-post.php から送られてくる）
 * 送信先：/wp-admin/admin-post.php （action=gp_submit_game）
 * ========================================================= */

// 画像1枚をチェックしてアップロード。成功ならID、失敗ならエラーコードを返す
function gameport_upload_image( $file_key, $post_id ) {
    $allowed = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' );
    $file    = $_FILES[ $file_key ];

    if ( $file['size'] > 5 * MB_IN_BYTES ) {
        return 'size';
    }
    $check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
    if ( empty( $check['type'] ) || ! in_array( $check['type'], $allowed, true ) ) {
        return 'type';
    }
    $id = media_handle_upload( $file_key, $post_id );
    return is_wp_error( $id ) ? 'upload' : $id;
}

function gameport_handle_game_submit() {
    $back = wp_get_referer() ? wp_get_referer() : home_url( '/post/' );
    $back = remove_query_arg( array( 'gp_posted', 'gp_error' ), $back );

    // 1. セキュリティチェック
    if ( ! isset( $_POST['gp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gp_nonce'] ) ), 'gp_submit_game' ) ) {
        wp_safe_redirect( add_query_arg( 'gp_error', 'nonce', $back ) );
        exit;
    }
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( add_query_arg( 'gp_error', 'login', $back ) );
        exit;
    }

    // 2. 入力チェック
    $title = isset( $_POST['game_title'] ) ? sanitize_text_field( wp_unslash( $_POST['game_title'] ) ) : '';
    $desc  = isset( $_POST['game_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['game_desc'] ) ) : '';
    $tags  = isset( $_POST['game_tags'] ) ? sanitize_text_field( wp_unslash( $_POST['game_tags'] ) ) : '';
    $tags  = array_slice( array_filter( array_map( 'trim', explode( ',', $tags ) ) ), 0, 10 );

    $errors = array();
    if ( '' === $title ) {
        $errors[] = 'title';
    }
    if ( '' === $desc ) {
        $errors[] = 'desc';
    }
    if ( empty( $_FILES['game_image']['name'] ) || UPLOAD_ERR_OK !== $_FILES['game_image']['error'] ) {
        $errors[] = 'image';
    }
    if ( $errors ) {
        wp_safe_redirect( add_query_arg( 'gp_error', implode( ',', $errors ), $back ) );
        exit;
    }

    // 3. 投稿を作る
    $post_id = wp_insert_post( array(
        'post_title'   => $title,
        'post_content' => $desc,
        'post_status'  => 'publish', // 下書きにしたいときは 'pending'（承認待ち）に
        'post_type'    => 'post',
        'post_author'  => get_current_user_id(),
    ), true );
    if ( is_wp_error( $post_id ) ) {
        wp_safe_redirect( add_query_arg( 'gp_error', 'save', $back ) );
        exit;
    }

    // 4. タグ
    if ( $tags ) {
        wp_set_post_tags( $post_id, $tags );
    }

    // 5. 画像（media_handle_upload を使うための読み込み）
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // サムネイル → アイキャッチ画像
    $thumb = gameport_upload_image( 'game_image', $post_id );
    if ( ! is_int( $thumb ) ) {
        wp_delete_post( $post_id, true ); // 画像が失敗したら投稿ごと取り消す
        wp_safe_redirect( add_query_arg( 'gp_error', $thumb, $back ) );
        exit;
    }
    set_post_thumbnail( $post_id, $thumb );

    // スクリーンショット（複数）→ 投稿メタ gp_gallery にIDを保存
    $gallery = array();
    if ( ! empty( $_FILES['game_screens']['name'][0] ) ) {
        $screens = $_FILES['game_screens'];
        $count   = min( 6, count( $screens['name'] ) );
        for ( $i = 0; $i < $count; $i++ ) {
            if ( UPLOAD_ERR_OK !== $screens['error'][ $i ] ) {
                continue;
            }
            // media_handle_upload は1枚ずつしか扱えないので、1枚分の形に詰め替える
            $_FILES['gp_screen_one'] = array(
                'name'     => $screens['name'][ $i ],
                'type'     => $screens['type'][ $i ],
                'tmp_name' => $screens['tmp_name'][ $i ],
                'error'    => $screens['error'][ $i ],
                'size'     => $screens['size'][ $i ],
            );
            $id = gameport_upload_image( 'gp_screen_one', $post_id );
            if ( is_int( $id ) ) {
                $gallery[] = $id;
            }
        }
    }
    if ( $gallery ) {
        update_post_meta( $post_id, 'gp_gallery', $gallery );
    }

    // 6. 完了 → 投稿ページに戻って「公開しました！」を表示
    wp_safe_redirect( add_query_arg( 'gp_posted', $post_id, $back ) );
    exit;
}
add_action( 'admin_post_gp_submit_game', 'gameport_handle_game_submit' );

// ログインしていない人が送信したとき
function gameport_handle_game_submit_nopriv() {
    wp_safe_redirect( gameport_login_url( home_url( '/post/' ) ) );
    exit;
}
add_action( 'admin_post_nopriv_gp_submit_game', 'gameport_handle_game_submit_nopriv' );
