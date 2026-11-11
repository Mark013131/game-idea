<?php
/**
 * Template Name: GAMEPORT フロントページ
 */

// 注目のゲーム（4件すべてに画像・タイトルを設定）
$featured_games = array(
    array(
        'title' => 'RUMBLETUMBLE',
        'image' => 'https://picsum.photos/seed/rumbletumble/400/250',
        'views' => '1.6k',
        'likes' => '365',
    ),
    array(
        'title' => 'CYBER DRIFTER',
        'image' => 'https://picsum.photos/seed/cyberdrifter/400/250',
        'views' => '1.5k',
        'likes' => '200',
    ),
    array(
        'title' => 'ASTRAL KNIGHT',
        'image' => 'https://picsum.photos/seed/astralknight/400/250',
        'views' => '2.5k',
        'likes' => '450',
    ),
    array(
        'title' => 'PIXEL DUNGEON',
        'image' => 'https://picsum.photos/seed/pixeldungeon/400/250',
        'views' => '1.1k',
        'likes' => '98',
    ),
);

// 人気のタグ
$popular_tags = array(
    'ローグライク',
    'RPG',
    'アドベンチャー',
    'シミュレーション',
    'パズル',
    'プラットフォーム',
);

// 新着ゲーム（6件すべてに画像・タイトルを設定）
$new_games = array(
    array(
        'title' => 'RUMBLETUMBLE',
        'image' => 'https://picsum.photos/seed/rumbletumble/300/180',
        'tags'  => array( 'RPG', 'シミュレーション' ),
        'time'  => '7時間前に公開',
    ),
    array(
        'title' => 'UNSEEN',
        'image' => 'https://picsum.photos/seed/unseen/300/180',
        'tags'  => array( 'パズル', 'シミュレーション' ),
        'time'  => '16時間前に公開',
    ),
    array(
        'title' => 'STARLIGHT PUZZLE',
        'image' => 'https://picsum.photos/seed/starlight/300/180',
        'tags'  => array( 'パズル', 'シミュレーション' ),
        'time'  => '5時間前に公開',
    ),
    array(
        'title' => 'CHRONO FACTORY',
        'image' => 'https://picsum.photos/seed/chronofactory/300/180',
        'tags'  => array( 'パズル', 'シミュレーション' ),
        'time'  => '10時間前に公開',
    ),
    array(
        'title' => 'NEON LABYRINTH',
        'image' => 'https://picsum.photos/seed/neonlabyrinth/300/180',
        'tags'  => array( 'パズル', 'シミュレーション' ),
        'time'  => '5時間前に公開',
    ),
    array(
        'title' => 'SKY ISLAND BUILDER',
        'image' => 'https://picsum.photos/seed/skyisland/300/180',
        'tags'  => array( 'パズル', 'シミュレーション' ),
        'time'  => '6時間前に公開',
    ),
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

  <!-- Header -->
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

  <!-- Hero Banner（スライダー：js/slider.js で自動で切り替わります） -->
  <?php
  // スライドに使う画像（ここを書き換えるとスライドが変わります）
  $hero_slides = array(
      array( 'image' => 'https://picsum.photos/seed/rumblehero/1200/450', 'title' => 'RUMBLE<br>TUMBLE', 'alt' => 'RUMBLE TUMBLE' ),
      array( 'image' => 'https://picsum.photos/seed/cyberdrifter/1200/450', 'title' => 'CYBER<br>DRIFTER', 'alt' => 'CYBER DRIFTER' ),
      array( 'image' => 'https://picsum.photos/seed/astralknight/1200/450', 'title' => 'ASTRAL<br>KNIGHT', 'alt' => 'ASTRAL KNIGHT' ),
      array( 'image' => 'https://picsum.photos/seed/pixeldungeon/1200/450', 'title' => 'PIXEL<br>DUNGEON', 'alt' => 'PIXEL DUNGEON' ),
      array( 'image' => 'https://picsum.photos/seed/neonlabyrinth/1200/450', 'title' => 'NEON<br>LABYRINTH', 'alt' => 'NEON LABYRINTH' ),
      array( 'image' => 'https://picsum.photos/seed/skyisland/1200/450', 'title' => 'SKY<br>ISLAND', 'alt' => 'SKY ISLAND BUILDER' ),
  );
  ?>
  <section class="gp-hero" data-gp-slider>
    <div class="gp-hero-banner">
      <?php foreach ( $hero_slides as $i => $slide ) : ?>
        <div class="gp-hero-slide<?php echo 0 === $i ? ' active' : ''; ?>">
          <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>">
          <div class="gp-hero-overlay">
            <div class="gp-hero-title"><?php echo wp_kses( $slide['title'], array( 'br' => array() ) ); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
      <!-- 前へ・次へボタン -->
      <button type="button" class="gp-hero-arrow gp-hero-prev" aria-label="前のスライド">‹</button>
      <button type="button" class="gp-hero-arrow gp-hero-next" aria-label="次のスライド">›</button>
    </div>
    <!-- 下の○（今のスライドが大きくなります。押すとそのスライドへ） -->
    <div class="gp-hero-dots">
      <?php foreach ( $hero_slides as $i => $slide ) : ?>
        <button type="button" class="gp-dot<?php echo 0 === $i ? ' active' : ''; ?>" aria-label="<?php echo esc_attr( ( $i + 1 ) . '枚目' ); ?>"></button>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Main Content -->
  <main class="gp-container">

    <!-- お知らせ -->
    <section class="gp-news-section">
      <div class="gp-news-top">
        <!-- お知らせ一覧ページはまだ無いので、用意したら URL を入れてね -->
        <a href="#" class="gp-more-link">お知らせ一覧＞</a>
      </div>
      <div class="gp-news-bar">
        <span class="gp-news-badge">お知らせ</span>
        <p class="gp-news-text">GAMEPORTに新しくランキング機能を実装しました！</p>
      </div>
    </section>

    <!-- 注目のゲーム -->
    <section>
      <div class="gp-section-header">
        <h2 class="gp-section-title">注目のゲーム</h2>
        <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>" class="gp-more-link">もっと見る＞</a>
      </div>

      <div class="gp-featured-grid">
        <?php foreach ( $featured_games as $game ) : ?>
          <article class="gp-featured-card">
            <div>
              <div class="gp-featured-thumb">
                <img src="<?php echo esc_url( $game['image'] ); ?>" alt="<?php echo esc_attr( $game['title'] ); ?>">
              </div>
              <h3 class="gp-featured-name"><?php echo esc_html( $game['title'] ); ?></h3>
            </div>
            <div class="gp-featured-stats">
              <span class="gp-stat-item">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9d84ff" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <?php echo esc_html( $game['views'] ); ?>
              </span>
              <span class="gp-stat-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9d84ff" stroke-width="2">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <?php echo esc_html( $game['likes'] ); ?>
              </span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- 人気のタグ -->
    <section class="gp-tags-section">
      <div class="gp-section-header">
        <h2 class="gp-section-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9d84ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
          </svg>
          人気のタグ
        </h2>
      </div>

      <div class="gp-tags-bar">
        <div class="gp-tags-list">
          <?php foreach ( $popular_tags as $tag ) : ?>
            <a href="<?php echo esc_url( gameport_games_tag_url( sanitize_title( $tag ) ) ); ?>" class="gp-tag-pill"><?php echo esc_html( $tag ); ?></a>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>" class="gp-tags-next" aria-label="すべてのゲームを見る">＞</a>
      </div>
    </section>

    <!-- 新着ゲーム -->
    <section>
      <div class="gp-section-header">
        <h2 class="gp-section-title">新着ゲーム</h2>
        <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>" class="gp-more-link">もっと見る＞</a>
      </div>

      <div class="gp-new-grid">
        <?php foreach ( $new_games as $game ) : ?>
          <article class="gp-new-card">
            <div class="gp-new-thumb">
              <img src="<?php echo esc_url( $game['image'] ); ?>" alt="<?php echo esc_attr( $game['title'] ); ?>">
            </div>

            <div class="gp-new-info">
              <h3 class="gp-new-title"><?php echo esc_html( $game['title'] ); ?></h3>
              <div class="gp-new-tags">
                <?php foreach ( $game['tags'] as $tag ) : ?>
                  <span class="gp-new-tag"><?php echo esc_html( $tag ); ?></span>
                <?php endforeach; ?>
              </div>
              <div class="gp-new-time"><?php echo esc_html( $game['time'] ); ?></div>
            </div>

            <!-- トップの新着はサンプルカード。本物の投稿に差し替えたら the_permalink() に変えてね -->
            <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>" class="gp-play-btn" aria-label="再生">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="#ffffff">
                <polygon points="5 3 19 12 5 21 5 3"></polygon>
              </svg>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

  </main>

  <!-- Footer -->
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