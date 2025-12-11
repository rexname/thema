<?php
get_header();
?>
<section class="section">
  <header class="section-header">
    <div class="section-title"><?php the_archive_title(); ?></div>
    <?php the_archive_description('<div class="muted text-sm">','</div>'); ?>
  </header>
  <div class="grid-posts">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <article class="card">
        <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); ?>
        <h3 class="text-lg leading-tight"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
      </article>
    <?php endwhile; endif; ?>
  </div>
</section>
<?php get_footer(); ?>
