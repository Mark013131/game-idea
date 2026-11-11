<?php
/**
 * Template Name: ランキング
 * 閲覧数（gp_views）の多い順にゲームを並べます。
 * 閲覧数は functions.php の gameport_count_views() でゲーム詳細を開くたびに +1 されます。
 * ?type=comment にするとコメント数順になります。
 */
get_header();

$gp_type = ( isset( $_GET['type'] ) && 'comment' === $_GET['type'] ) ? 'comment' : 'views';

$gp_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 20,
    'no_found_rows'  => true,
);
if ( 'comment' === $gp_type ) {
    $gp_args['orderby'] = 'comment_count';
} else {
    $gp_args['meta_key'] = 'gp_views';
    $gp_args['orderby']  = 'meta_value_num';
}
$gp_rank = new WP_Query( $gp_args );
$gp_n    = 0;
?>

<main class="gp-container gp-ranking-container">

  <div class="gp-section-header">
    <h1 class="gp-page-title">ランキング</h1>
    <nav class="gp-games-sort" aria-label="ランキングの種類">
      <a href="<?php echo esc_url( get_permalink() ); ?>" class="<?php echo 'views' === $gp_type ? 'is-active' : ''; ?>">閲覧数</a>
      <a href="<?php echo esc_url( add_query_arg( 'type', 'comment', get_permalink() ) ); ?>" class="<?php echo 'comment' === $gp_type ? 'is-active' : ''; ?>">コメント数</a>
    </nav>
  </div>

  <?php if ( $gp_rank->have_posts() ) : ?>
    <ol class="gp-rank-list">
      <?php while ( $gp_rank->have_posts() ) : $gp_rank->the_post(); $gp_n++; ?>
        <!-- 1〜3位は gp-rank-top と gp-rank-1〜3 で色を変えています -->
        <li class="gp-rank-item<?php echo $gp_n <= 3 ? ' gp-rank-top gp-rank-' . $gp_n : ''; ?>">
          <span class="gp-rank-num"><?php echo (int) $gp_n; ?></span>
          <a class="gp-rank-thumb" href="<?php the_permalink(); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'medium' ); ?>
            <?php else : ?>
              <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/game-thumb.png' ); ?>" alt="">
            <?php endif; ?>
          </a>
          <div class="gp-rank-info">
            <h2 class="gp-rank-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="gp-rank-meta">
              <?php if ( 'comment' === $gp_type ) : ?>
                💬 <?php echo (int) get_comments_number(); ?>
              <?php else : ?>
                👁 <?php echo (int) get_post_meta( get_the_ID(), 'gp_views', true ); ?>
              <?php endif; ?>
              ・<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
            </p>
          </div>
          <a href="<?php the_permalink(); ?>" class="gp-play-btn" aria-label="再生">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="#ffffff"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
          </a>
        </li>
      <?php endwhile; ?>
    </ol>
  <?php else : ?>
    <p class="gp-empty">まだランキングがありません。ゲームが見られると表示されます。</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>

</main>

<?php get_footer(); ?>
