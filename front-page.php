<?php
get_header();

$main_q = new WP_Query([
  'posts_per_page' => 7,
  'ignore_sticky_posts' => true,
]);

?>
<section class="grid cols-2">
  <div>
    <?php if ($main_q->have_posts()): $main_q->the_post(); ?>
      <article class="hero-card">
        <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
        <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="excerpt text-sm muted"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></div>
        <div class="meta text-xs muted"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_author() ); ?></div>
      </article>
    <?php endif; ?>

    <div class="grid grid-sm grid-posts mt-6">
      <?php while ($main_q->have_posts()): $main_q->the_post(); ?>
        <article class="card">
          <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); ?>
          <h3 class="text-lg leading-tight"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="meta text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="newsletter">
      <div class="text-lg bold"><?php echo esc_html__( 'TMT Weekly Newsletter', 'thema' ); ?></div>
      <div class="muted text-sm"><?php echo esc_html__( 'Get our big stories in your inbox.', 'thema' ); ?></div>
      <form class="controls" method="post" action="#">
        <input type="email" name="email" placeholder="<?php echo esc_attr__( 'Your email', 'thema' ); ?>" required>
        <button type="submit"><?php echo esc_html__( 'Subscribe', 'thema' ); ?></button>
      </form>
    </div>
  </div>
  <aside class="sidebar">
    <div class="section-header"><div class="section-title"><?php echo esc_html__( 'Just In', 'thema' ); ?></div></div>
    <ul class="list-plain">
      <?php
      $just_q = new WP_Query([
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => true,
      ]);
      while ($just_q->have_posts()): $just_q->the_post(); ?>
        <li><a href="<?php the_permalink(); ?>" class="leading-tight"><?php the_title(); ?></a>
          <div class="text-xs muted"><?php echo esc_html( human_time_diff( get_the_time('U'), current_time('timestamp') ) ); ?> <?php echo esc_html__( 'ago', 'thema' ); ?></div>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>

    <?php $pick_q = new WP_Query(['posts_per_page'=>1,'offset'=>1]); if ($pick_q->have_posts()): $pick_q->the_post(); ?>
      <div class="section mt-6">
        <div class="section-header"><div class="section-title"><?php echo esc_html__( "Editor's Pick", 'thema' ); ?></div></div>
        <article class="card">
          <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
          <h3 class="text-lg"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
        </article>
      </div>
    <?php wp_reset_postdata(); endif; ?>
  </aside>
</section>

<?php
function section_grid_dynamic($index, $fallback_title, $fallback_slug) {
  $setting = get_theme_mod('thema_home_section_' . $index, 0);
  $cat_id = $setting ? (int) $setting : \Thema\get_cat_id_by_slug($fallback_slug);
  $title = $cat_id ? get_cat_name($cat_id) : $fallback_title;
  $q = new WP_Query([
    'posts_per_page' => 4,
    'cat' => $cat_id,
    'ignore_sticky_posts' => true,
  ]);
  echo '<section class="section">';
  echo '<div class="section-header"><div class="section-title">' . esc_html($title) . '</div>';
  if ($cat_id) echo '<a class="more-link" href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html__('More', 'thema') . ' ' . esc_html($title) . '</a>';
  echo '</div>';
  echo '<div class="grid-posts">';
  while ($q->have_posts()): $q->the_post();
    echo '<article class="card">';
    if (has_post_thumbnail()) the_post_thumbnail('medium');
    echo '<h3 class="text-lg leading-tight"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
    echo '<div class="text-xs muted">' . esc_html(get_the_date()) . '</div>';
    echo '</article>';
  endwhile;
  echo '</div>';
  echo '</section>';
  wp_reset_postdata();
}

section_grid_dynamic(1, esc_html__('Climate', 'thema'), 'climate');
section_grid_dynamic(2, esc_html__('Business', 'thema'), 'business');
section_grid_dynamic(3, esc_html__('Arts and Life', 'thema'), 'arts-life');
section_grid_dynamic(4, esc_html__('The New Diaspora', 'thema'), 'new-diaspora');

echo '<div class="section"><div class="section-header"><div class="section-title">' . esc_html__( 'More Features', 'thema' ) . '</div></div></div>';

get_footer();
