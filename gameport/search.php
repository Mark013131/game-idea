<?php
/**
 * search.php（検索結果ページ / Figma: Frame 4）
 * ヘッダーの検索ボックスでEnterを押すとこのページになります。
 */
get_header();
?>

<main class="gp-search-container">

  <div class="gp-search-top">
    <!-- 戻る（ひとつ前のページへ） -->
    <a href="<?php echo esc_url( home_url( '/games/' ) ); ?>" class="gp-search-back" aria-label="ゲームを探すに戻る">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icon-back.svg' ); ?>" width="38" height="30" alt="">
    </a>
  </div>

  <!-- 大きな検索ボックス -->
  <form class="gp-search-big" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    <input type="search" name="s" placeholder="ゲーム名・タグで検索" value="<?php the_search_query(); ?>">
  </form>
  <?php /* WordPress標準の検索フォームを使いたいときは get_search_form(); に置き換えてOK */ ?>

  <?php if ( get_search_query() !== '' ) : ?>
    <p class="gp-search-count">「<?php the_search_query(); ?>」の検索結果：<?php echo (int) $wp_query->found_posts; ?>件</p>
  <?php endif; ?>

  <!-- 検索結果の一覧 -->
  <?php if ( have_posts() ) : ?>
    <div class="gp-search-results">
      <?php while ( have_posts() ) : the_post(); ?>
        <a class="gp-search-result" href="<?php the_permalink(); ?>">
          <div class="gp-search-thumb">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'medium' ); ?>
            <?php else : ?>
              <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/game-thumb.png' ); ?>" alt="">
            <?php endif; ?>
          </div>
          <div class="gp-search-info">
            <h2><?php the_title(); ?></h2>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p class="gp-search-empty">見つかりませんでした。別のキーワードで試してね。</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
