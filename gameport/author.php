<?php
/**
 * author.php（プロフィールページ / Figma: Frame 6）
 * URL例：/author/ユーザー名/
 * 自分のページを開いているときは「編集」で表示名・自己紹介・アバターを変えられます。
 */
get_header();
$gp_author  = get_queried_object();
$gp_uid     = $gp_author ? (int) $gp_author->ID : 0;
$gp_is_own  = is_user_logged_in() && $gp_uid && get_current_user_id() === $gp_uid;
$gp_assets  = get_template_directory_uri() . '/assets';
$gp_name    = $gp_author ? $gp_author->display_name : '';
$gp_bio     = $gp_uid ? get_the_author_meta( 'description', $gp_uid ) : '';

$gp_profile_ok    = isset( $_GET['gp_profile'] ) && 'ok' === $_GET['gp_profile'];
$gp_profile_error = isset( $_GET['gp_profile_error'] ) ? sanitize_key( wp_unslash( $_GET['gp_profile_error'] ) ) : '';
$gp_profile_errors = array(
    'nonce'  => 'もう一度ページを開き直してから保存してね。',
    'name'   => '表示名を入力してください。',
    'type'   => '画像は JPG / PNG / GIF / WebP だけ使えます。',
    'size'   => '画像が大きすぎます（5MBまで）。',
    'upload' => '画像のアップロードに失敗しました。',
    'save'   => '保存に失敗しました。もう一度試してね。',
);
?>

<main class="gp-author-container">

  <?php if ( $gp_is_own && $gp_profile_ok ) : ?>
    <div class="gp-post-notice gp-post-notice-ok">プロフィールを保存しました！</div>
  <?php endif; ?>
  <?php if ( $gp_is_own && $gp_profile_error ) : ?>
    <div class="gp-post-notice gp-post-notice-error"><?php echo esc_html( isset( $gp_profile_errors[ $gp_profile_error ] ) ? $gp_profile_errors[ $gp_profile_error ] : 'エラーが発生しました。' ); ?></div>
  <?php endif; ?>

  <section class="gp-author-profile">
    <div class="gp-author-side">
      <div class="gp-author-avatar">
        <?php gameport_the_avatar( $gp_uid, 135 ); ?>
      </div>
      <p class="gp-author-follow">
        フォロー：<?php echo (int) gameport_get_following_count( $gp_uid ); ?>人<br>
        フォロワー：<span class="gp-followers-count"><?php echo (int) gameport_get_followers_count( $gp_uid ); ?></span>人
      </p>
      <?php gameport_follow_button( $gp_uid ); ?>
      <?php if ( $gp_is_own ) : ?>
        <button type="button" class="gp-edit-btn" id="gp-edit-toggle" aria-expanded="false" aria-controls="gp-profile-form">編集</button>
      <?php endif; ?>
    </div>
    <div class="gp-author-main">
      <h1 class="gp-author-name"><?php echo esc_html( $gp_name ); ?></h1>
      <div class="gp-author-bio">
        <?php echo $gp_bio ? nl2br( esc_html( $gp_bio ) ) : '自己紹介はまだありません。'; ?>
      </div>
    </div>
  </section>

  <?php if ( $gp_is_own ) : ?>
    <!-- 自分のページだけの編集フォーム（「編集」を押すと開きます） -->
    <form class="gp-profile-form" id="gp-profile-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" hidden>
      <input type="hidden" name="action" value="gp_update_profile">
      <?php wp_nonce_field( 'gp_update_profile', 'gp_profile_nonce' ); ?>

      <label class="gp-profile-label" for="gp-display-name">表示名</label>
      <input type="text" id="gp-display-name" name="display_name" class="gp-form-input" value="<?php echo esc_attr( $gp_name ); ?>" maxlength="60" required>

      <label class="gp-profile-label" for="gp-description">自己紹介</label>
      <textarea id="gp-description" name="description" class="gp-form-input gp-form-textarea" rows="5" maxlength="1000"><?php echo esc_textarea( $gp_bio ); ?></textarea>

      <label class="gp-profile-label" for="gp-avatar-input">アバター画像</label>
      <input type="file" id="gp-avatar-input" name="gp_avatar" accept="image/jpeg,image/png,image/gif,image/webp">

      <div class="gp-profile-actions">
        <button type="submit" class="gp-post-submit">保存する</button>
      </div>
    </form>
  <?php endif; ?>

  <section>
    <div class="gp-author-works-head">
      <h2 class="gp-author-works-title">投稿作品一覧</h2>
      <?php if ( $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
        <a href="<?php echo esc_url( get_pagenum_link( 2 ) ); ?>" class="gp-more-link">もっと見る＞</a>
      <?php endif; ?>
    </div>

    <div class="gp-author-works">
      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <a class="gp-author-work" href="<?php the_permalink(); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'medium' ); ?>
            <?php else : ?>
              <img src="<?php echo esc_url( $gp_assets . '/game-thumb.png' ); ?>" alt="<?php the_title_attribute(); ?>">
            <?php endif; ?>
          </a>
        <?php endwhile; ?>
      <?php else : ?>
        <p>まだ作品がありません。</p>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php get_footer(); ?>
