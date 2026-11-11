<?php
/**
 * ゲームカード1枚（トップの「新着ゲーム」と同じデザイン）
 * ループの中で get_template_part( 'parts/game-card' ); と書くと使えます。
 */
?>
<article class="gp-new-card">
  <a class="gp-new-thumb" href="<?php the_permalink(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
      <?php the_post_thumbnail( 'medium' ); ?>
    <?php else : ?>
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/game-thumb.png' ); ?>" alt="">
    <?php endif; ?>
  </a>

  <div class="gp-new-info">
    <h3 class="gp-new-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <div class="gp-new-tags">
      <?php foreach ( (array) get_the_tags() as $tag ) : if ( ! $tag ) { continue; } ?>
        <span class="gp-new-tag"><?php echo esc_html( $tag->name ); ?></span>
      <?php endforeach; ?>
    </div>
    <div class="gp-new-time"><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?>前に公開</div>
  </div>

  <a href="<?php the_permalink(); ?>" class="gp-play-btn" aria-label="再生">
    <svg width="10" height="10" viewBox="0 0 24 24" fill="#ffffff"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
  </a>
</article>
