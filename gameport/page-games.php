<?php
/**
 * Template Name: ゲームを探す
 * ゲーム一覧ページ（タグで絞り込み＋並び替え＋ページ送り）
 * URL例：/games/?tag=rpg&orderby=popular
 */
get_header();

// URLのパラメータを読む（?tag=〇〇&orderby=〇〇）
$gp_tag     = isset( $_GET['tag'] ) ? sanitize_title( wp_unslash( $_GET['tag'] ) ) : '';
$gp_orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'new';
$gp_paged   = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

// 並び替えの種類
$gp_sorts = array(
    'new'     => '新着順',
    'popular' => '閲覧数順',
    'comment' => 'コメント数順',
    'title'   => '名前順',
);
if ( ! isset( $gp_sorts[ $gp_orderby ] ) ) {
    $gp_orderby = 'new';
}

// WP_Query の条件
$gp_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 12,
    'paged'          => $gp_paged,
);
if ( $gp_tag ) {
    $gp_args['tag'] = $gp_tag;
}
if ( 'popular' === $gp_orderby ) {
    $gp_args['meta_key'] = 'gp_views'; // functions.php で数えている閲覧数
    $gp_args['orderby']  = 'meta_value_num';
} elseif ( 'comment' === $gp_orderby ) {
    $gp_args['orderby'] = 'comment_count';
} elseif ( 'title' === $gp_orderby ) {
    $gp_args['orderby'] = 'title';
    $gp_args['order']   = 'ASC';
}
$gp_games = new WP_Query( $gp_args );

// 絞り込み用タグ（投稿数が多い順に12個）
$gp_all_tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 12 ) );

// 今の条件を残したままURLを作る小さな関数
$gp_url = function ( $tag, $orderby ) {
    return add_query_arg( array_filter( array( 'tag' => $tag, 'orderby' => 'new' === $orderby ? '' : $orderby ) ), get_permalink() );
};
?>

<main class="gp-container gp-games-container">

  <h1 class="gp-page-title">ゲームを探す</h1>

  <!-- タグの絞り込み（トップの「人気のタグ」と同じ紫バー） -->
  <section class="gp-tags-section">
    <div class="gp-tags-bar gp-games-tags">
      <div class="gp-tags-list">
        <a href="<?php echo esc_url( $gp_url( '', $gp_orderby ) ); ?>" class="gp-tag-pill<?php echo $gp_tag ? '' : ' is-active'; ?>">すべて</a>
        <?php foreach ( $gp_all_tags as $tag ) : ?>
          <a href="<?php echo esc_url( $gp_url( $tag->slug, $gp_orderby ) ); ?>" class="gp-tag-pill<?php echo $gp_tag === $tag->slug ? ' is-active' : ''; ?>"><?php echo esc_html( $tag->name ); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 並び替え -->
  <div class="gp-section-header">
    <p class="gp-games-count"><?php echo (int) $gp_games->found_posts; ?>件のゲーム</p>
    <nav class="gp-games-sort" aria-label="並び替え">
      <?php foreach ( $gp_sorts as $key => $label ) : ?>
        <a href="<?php echo esc_url( $gp_url( $gp_tag, $key ) ); ?>" class="<?php echo $gp_orderby === $key ? 'is-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
      <?php endforeach; ?>
    </nav>
  </div>

  <!-- ゲーム一覧 -->
  <?php if ( $gp_games->have_posts() ) : ?>
    <div class="gp-new-grid">
      <?php while ( $gp_games->have_posts() ) : $gp_games->the_post(); ?>
        <?php get_template_part( 'parts/game-card' ); ?>
      <?php endwhile; ?>
    </div>

    <!-- ページ送り -->
    <div class="gp-pagination">
      <?php
      echo paginate_links( array(
          'total'     => $gp_games->max_num_pages,
          'current'   => $gp_paged,
          'prev_text' => '＜',
          'next_text' => '＞',
      ) );
      ?>
    </div>
  <?php else : ?>
    <p class="gp-empty">ゲームが見つかりませんでした。</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); // WP_Query を使ったあとは必ずリセット ?>

</main>

<?php get_footer(); ?>
