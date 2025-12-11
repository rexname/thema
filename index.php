<?php
get_header();
?>
<section class="section">
  <div class="section-header"><div class="section-title">Latest</div></div>
  <div class="grid-posts">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <article class="card">
        <?php if (has_post_thumbnail()) { echo '<a href="' . esc_url(get_permalink()) . '">'; the_post_thumbnail('medium'); echo '</a>'; } ?>
        <h3 class="text-lg leading-tight"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
      </article>
    <?php endwhile; endif; ?>
  </div>
</section>
<?php get_footer(); ?>
