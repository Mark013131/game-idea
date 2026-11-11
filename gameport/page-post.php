<?php
/**
 * Template Name: 投稿する
 * ゲーム投稿ページ（Figma: Frame 5）
 *
 * 送信先は admin-post.php で、保存の処理は functions.php の
 * gameport_handle_game_submit() にあります。
 * 保存が終わるとこのページに戻ってきて、成功／エラーのメッセージを出します。
 */
get_header();

// 送信後に戻ってきたときのメッセージ（URLの ?gp_posted=ID / ?gp_error=コード）
$gp_posted = isset( $_GET['gp_posted'] ) ? absint( $_GET['gp_posted'] ) : 0;
$gp_errors = isset( $_GET['gp_error'] ) ? array_filter( explode( ',', sanitize_text_field( wp_unslash( $_GET['gp_error'] ) ) ) ) : array();
$gp_error_text = array(
    'nonce'  => 'もう一度ページを開き直してから送信してね。',
    'login'  => '投稿するにはログインが必要です。',
    'title'  => 'タイトルを入力してください。',
    'desc'   => '説明文を入力してください。',
    'image'  => 'サムネイル画像を選んでください。',
    'type'   => '画像は JPG / PNG / GIF / WebP だけ使えます。',
    'size'   => '画像が大きすぎます（1枚 5MB まで）。',
    'upload' => '画像のアップロードに失敗しました。',
    'save'   => '保存に失敗しました。もう一度試してね。',
);
?>

<main class="gp-post-container">

  <?php if ( $gp_posted && get_post( $gp_posted ) ) : ?>
    <div class="gp-post-notice gp-post-notice-ok">
      公開しました！ <a href="<?php echo esc_url( get_permalink( $gp_posted ) ); ?>">投稿したゲームを見る ＞</a>
    </div>
  <?php endif; ?>

  <?php if ( $gp_errors ) : ?>
    <div class="gp-post-notice gp-post-notice-error">
      <ul>
        <?php foreach ( $gp_errors as $code ) : ?>
          <li><?php echo esc_html( isset( $gp_error_text[ $code ] ) ? $gp_error_text[ $code ] : 'エラーが発生しました。' ); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ( ! is_user_logged_in() ) : ?>
    <!-- ログインしていない人にはフォームを出さない -->
    <div class="gp-post-login">
      <p>ゲームを投稿するにはログインしてね。</p>
      <a class="gp-post-submit" href="<?php echo esc_url( gameport_login_url( get_permalink() ) ); ?>">ログイン</a>
      <a class="gp-auth-sublink" href="<?php echo esc_url( gameport_register_url( get_permalink() ) ); ?>">新規登録はこちら</a>
    </div>
  <?php else : ?>

  <form class="gp-post-form" id="gp-post-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
    <!-- どの処理に送るか（functions.php の admin_post_gp_submit_game） -->
    <input type="hidden" name="action" value="gp_submit_game">
    <!-- なりすまし防止の合言葉（nonce） -->
    <?php wp_nonce_field( 'gp_submit_game', 'gp_nonce' ); ?>

    <!-- ゲームタイトル -->
    <div class="gp-post-title-box">
      <label for="gp-title" class="gp-post-label">ゲームタイトル<span class="gp-required">*</span></label>
      <input type="text" id="gp-title" name="game_title" class="gp-post-title-input" placeholder="例：RUMBLE TUMBLE" maxlength="80" required>
    </div>

    <!-- 説明文 -->
    <div class="gp-post-desc-box">
      <label for="gp-desc" class="gp-post-label">説明文<span class="gp-required">*</span></label>
      <textarea id="gp-desc" name="game_desc" class="gp-post-desc-input" rows="6" placeholder="どんなゲーム？遊び方やこだわりを書いてね" required></textarea>
    </div>

    <!-- タグ追加（Enter か「追加」でチップになる。js/post.js） -->
    <div class="gp-post-tag-box">
      <label for="gp-tag-input" class="gp-post-tag-label">タグ追加</label>
      <div class="gp-post-tag-row">
        <input type="text" id="gp-tag-input" class="gp-post-tag-input" placeholder="例：PvP（Enterで追加）" maxlength="20">
        <button type="button" class="gp-post-tag-add" id="gp-tag-add">追加</button>
      </div>
      <ul class="gp-post-chips" id="gp-tag-chips"></ul>
      <!-- 送信されるのはこの隠し欄（カンマ区切り） -->
      <input type="hidden" name="game_tags" id="gp-tags-hidden">
    </div>

    <!-- サムネイル画像（クリックで選択、選ぶとプレビュー） -->
    <div>
      <p class="gp-post-label">サムネイル画像<span class="gp-required">*</span></p>
      <label class="gp-post-image-box" id="gp-thumb-box">
        <img class="gp-post-image-icon" src="<?php echo esc_url( get_template_directory_uri() . '/assets/icon-image.svg' ); ?>" width="171" height="171" alt="">
        <span class="gp-post-image-hint">クリックして画像を選択</span>
        <img class="gp-post-preview" id="gp-thumb-preview" alt="" hidden>
        <input type="file" name="game_image" id="gp-thumb-input" accept="image/jpeg,image/png,image/gif,image/webp" class="gp-post-file" required>
      </label>
    </div>

    <!-- スクリーンショット（任意・複数OK） -->
    <div>
      <p class="gp-post-label">スクリーンショット（任意・最大6枚）</p>
      <label class="gp-post-shots-btn">
        ＋ 画像を追加
        <input type="file" name="game_screens[]" id="gp-shots-input" accept="image/jpeg,image/png,image/gif,image/webp" class="gp-post-file" multiple>
      </label>
      <div class="gp-post-shots" id="gp-shots-preview"></div>
    </div>

    <div class="gp-post-actions">
      <button type="submit" class="gp-post-submit">公開</button>
    </div>
  </form>

  <?php endif; ?>
</main>

<?php get_footer(); ?>
