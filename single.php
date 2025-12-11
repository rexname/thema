<?php
get_header();
?>
<section class="section">
  <div class="container">
    <div class="grid cols-2">
      <article>
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
          <header class="mb-4">
            <h1 class="text-2xl leading-tight"><?php the_title(); ?></h1>
            <div class="muted text-sm"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_author() ); ?></div>
          </header>
          <?php if (has_post_thumbnail()) { echo '<div class="mb-4">'; the_post_thumbnail('large'); echo '</div>'; } ?>
          <div class="content">
            <?php the_content(); ?>
          </div>
        <?php endwhile; endif; ?>
      </article>
      <aside class="sidebar">
        <div class="section-header"><div class="section-title"><?php echo esc_html__( 'Latest', 'thema' ); ?></div></div>
        <ul class="list-plain">
          <?php $latest = new WP_Query(['posts_per_page'=>6,'ignore_sticky_posts'=>true]); while ($latest->have_posts()): $latest->the_post(); ?>
            <li><a href="<?php the_permalink(); ?>" class="leading-tight"><?php the_title(); ?></a></li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </aside>
    </div>
  </div>
</section>
<?php get_footer(); ?>
