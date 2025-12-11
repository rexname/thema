<?php
get_header();
?>
<article class="section">
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
<?php get_footer(); ?>
