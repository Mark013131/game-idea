<?php
/**
 * 通知パネル（ベルを押すと開く）
 * いまは仮のデータです。本物の通知を出すときは、この配列を
 * データベースやカスタム投稿から取ってくる形に変えます。
 */
$gp_notifications = array(
    array( 'title' => 'お知らせ', 'excerpt' => 'これは、通知用の確認メールで…', 'body' => "これは、通知用の確認メールです。\nイメージこんな感じで開く感じになります" ),
    array( 'title' => 'ランキング更新', 'excerpt' => '今週のランキングが更新されました', 'body' => '今週のランキングが更新されました。トップページから確認してみてね！' ),
    array( 'title' => 'コメント', 'excerpt' => 'あなたのゲームにコメントがつきました', 'body' => 'RUMBLETUMBLE に新しいコメントがつきました。' ),
    array( 'title' => 'フォロー', 'excerpt' => '新しいフォロワーが増えました', 'body' => '新しいフォロワーが 1 人増えました。' ),
);
$gp_bell = get_template_directory_uri() . '/assets/icon-bell.svg';
$gp_back = get_template_directory_uri() . '/assets/icon-back.svg';
?>
<div class="gp-notify-panel" id="gp-notify-panel" hidden>

  <!-- 一覧ビュー -->
  <div class="gp-notify-view gp-notify-list-view">
    <div class="gp-notify-top">
      <button type="button" class="gp-notify-back" data-gp-close aria-label="閉じる">
        <img src="<?php echo esc_url( $gp_back ); ?>" width="38" height="30" alt="">
      </button>
    </div>
    <ul class="gp-notify-list">
      <?php foreach ( $gp_notifications as $i => $n ) : ?>
        <li>
          <button type="button" class="gp-notify-item" data-gp-open="<?php echo esc_attr( $i ); ?>">
            <img class="gp-notify-bell" src="<?php echo esc_url( $gp_bell ); ?>" width="22" height="24" alt="">
            <span class="gp-notify-text">
              <span class="gp-notify-title"><?php echo esc_html( $n['title'] ); ?></span>
              <span class="gp-notify-excerpt"><?php echo esc_html( $n['excerpt'] ); ?></span>
            </span>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <!-- 詳細ビュー（1件ずつ。JSで表示を切り替えます） -->
  <?php foreach ( $gp_notifications as $i => $n ) : ?>
    <div class="gp-notify-view gp-notify-detail" data-gp-detail="<?php echo esc_attr( $i ); ?>" hidden>
      <div class="gp-notify-top">
        <button type="button" class="gp-notify-back" data-gp-back aria-label="一覧に戻る">
          <img src="<?php echo esc_url( $gp_back ); ?>" width="38" height="30" alt="">
        </button>
      </div>
      <div class="gp-notify-detail-head">
        <img class="gp-notify-bell" src="<?php echo esc_url( $gp_bell ); ?>" width="22" height="24" alt="">
        <h3><?php echo esc_html( $n['title'] ); ?></h3>
      </div>
      <p class="gp-notify-body"><?php echo nl2br( esc_html( $n['body'] ) ); ?></p>
    </div>
  <?php endforeach; ?>

</div>
