<?php
get_header();
?>
<section class="section">
  <header class="section-header">
    <div class="section-title"><?php the_archive_title(); ?></div>
    <?php the_archive_description('<div class="muted text-sm">','</div>'); ?>
  </header>

  <?php
  $paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
  $per   = 16;
  $args  = [
    'posts_per_page' => $per,
    'paged' => $paged,
    'ignore_sticky_posts' => true,
  ];
  if (is_category()) {
    $args['cat'] = get_queried_object_id();
  }
  $loop = new WP_Query($args);
  ?>

  <div class="grid-posts" style="grid-template-columns:repeat(4,1fr)">
    <?php if ($loop->have_posts()): while ($loop->have_posts()): $loop->the_post(); ?>
      <article class="card">
        <?php if (has_post_thumbnail()) { echo '<a href="' . esc_url(get_permalink()) . '">'; the_post_thumbnail('medium'); echo '</a>'; } ?>
        <h3 class="text-lg leading-tight"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
      </article>
    <?php endwhile; endif; wp_reset_postdata(); ?>
  </div>

  <div class="pagination">
    <?php
    echo paginate_links([
      'total' => $loop->max_num_pages,
      'current' => $paged,
      'prev_text' => '&laquo;',
      'next_text' => '&raquo;',
      'type' => 'list',
    ]);
    ?>
  </div>
</section>
<?php get_footer(); ?>
