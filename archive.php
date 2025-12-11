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
  $cat_id = is_category() ? (int) get_queried_object_id() : 0;
  $args  = [
    'posts_per_page' => $per,
    'paged' => $paged,
    'ignore_sticky_posts' => true,
  ];
  if ($cat_id) {
    $args['cat'] = $cat_id;
  }
  $loop = new WP_Query($args);
  ?>

  <div class="grid cols-2">
    <div>
      <div class="grid-posts grid-2" style="grid-template-columns:repeat(2,1fr)">
        <?php if ($loop->have_posts()): while ($loop->have_posts()): $loop->have_posts() && $loop->the_post(); ?>
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
    </div>
    <aside class="sidebar">
      <div class="section-header"><div class="section-title"><?php echo esc_html__( 'Latest', 'thema' ); ?></div></div>
      <ul class="list-plain">
        <?php
        $latest_q = new WP_Query([
          'posts_per_page' => 8,
          'ignore_sticky_posts' => true,
          'cat' => $cat_id ?: '',
        ]);
        while ($latest_q->have_posts()): $latest_q->the_post(); ?>
          <li><a href="<?php the_permalink(); ?>" class="leading-tight"><?php the_title(); ?></a>
            <div class="text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
          </li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    </aside>
  </div>
</section>
<?php get_footer(); ?>
