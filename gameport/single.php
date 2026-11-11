<?php
/**
 * single.php（ゲーム詳細ページ / Figma: Frame 7）
 * 投稿1件を表示するテンプレートです。
 * アイキャッチ画像 → サムネイル、本文 → 説明文、タグ → 紫のピルになります。
 */
get_header();
$gp_assets = get_template_directory_uri() . '/assets';
?>

<main class="gp-single-container">
  <?php while ( have_posts() ) : the_post(); ?>
    <article class="gp-single">

      <!-- サムネイル（アイキャッチが無いときは仮の画像） -->
      <div class="gp-single-thumb">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail( 'medium_large' ); ?>
        <?php else : ?>
          <img src="<?php echo esc_url( $gp_assets . '/game-thumb.png' ); ?>" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
      </div>

      <h1 class="gp-single-title"><?php the_title(); ?></h1>

      <!-- タグ -->
      <?php $gp_tags = get_the_tags(); ?>
      <?php if ( $gp_tags ) : ?>
        <ul class="gp-single-tags">
          <?php foreach ( $gp_tags as $tag ) : ?>
            <li><a class="gp-single-tag" href="<?php echo esc_url( gameport_games_tag_url( $tag->slug ) ); ?>"><?php echo esc_html( $tag->name ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <h2 class="gp-single-heading">説明文</h2>

      <!-- ゲーム画面（仮の画像。将来はゲームの埋め込みなどに置き換え） -->
      <div class="gp-single-screen">
        <img src="<?php echo esc_url( $gp_assets . '/game-screen.jpg' ); ?>" alt="ゲーム画面">
      </div>

      <div class="gp-single-content"><?php the_content(); ?></div>

      <!-- スクリーンショット（投稿ページで追加した画像） -->
      <?php $gp_gallery = (array) get_post_meta( get_the_ID(), 'gp_gallery', true ); ?>
      <?php if ( array_filter( $gp_gallery ) ) : ?>
        <div class="gp-single-gallery">
          <?php foreach ( array_filter( $gp_gallery ) as $gp_img_id ) : ?>
            <?php echo wp_get_attachment_image( (int) $gp_img_id, 'medium_large' ); ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- 作者 -->
      <?php $gp_author_id = (int) get_the_author_meta( 'ID' ); ?>
      <div class="gp-single-author">
        <a class="gp-single-avatar" href="<?php echo esc_url( get_author_posts_url( $gp_author_id ) ); ?>" aria-label="作者のプロフィール">
          <?php gameport_the_avatar( $gp_author_id, 96 ); ?>
        </a>
        <div class="gp-single-author-body">
          <div class="gp-single-author-head">
            <a class="gp-single-author-name" href="<?php echo esc_url( get_author_posts_url( $gp_author_id ) ); ?>"><?php the_author(); ?></a>
            <?php gameport_follow_button( $gp_author_id ); ?>
          </div>
          <div class="gp-single-author-bio">
            <?php
            $gp_bio = get_the_author_meta( 'description' );
            echo $gp_bio ? nl2br( esc_html( $gp_bio ) ) : esc_html( get_the_author() );
            ?>
          </div>
        </div>
      </div>

    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
