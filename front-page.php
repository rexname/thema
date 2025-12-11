<?php
get_header();

$variant = get_theme_mod('thema_variant', 'newspaper');
if ($variant === 'adventure') {
  get_template_part('template-parts/variants/adventure-front');
  get_footer();
  return;
}

$main_q = new WP_Query([
  'posts_per_page' => 9,
  'ignore_sticky_posts' => true,
]);

?>
<section class="grid cols-2">
  <div>
    <?php if ($main_q->have_posts()): $main_q->the_post(); ?>
      <article class="hero-card">
        <?php if (has_post_thumbnail()) { echo '<a href="' . esc_url(get_permalink()) . '">'; the_post_thumbnail('large'); echo '</a>'; } ?>
        <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="excerpt text-sm muted"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?></div>
        <div class="meta text-xs muted"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_author() ); ?></div>
      </article>
    <?php endif; ?>

    <div class="grid grid-sm grid-posts mt-6">
      <?php while ($main_q->have_posts()): $main_q->the_post(); ?>
        <article class="card">
          <?php if (has_post_thumbnail()) { echo '<a href="' . esc_url(get_permalink()) . '">'; the_post_thumbnail('medium'); echo '</a>'; } ?>
          <h3 class="text-lg leading-tight"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="meta text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <aside class="sidebar">
    <?php $just_cat = get_theme_mod('thema_just_in_category', 0); ?>
    <div class="section-header"><div class="section-title"><?php echo esc_html( $just_cat ? get_cat_name( (int) $just_cat ) : esc_html__( 'Just In', 'thema' ) ); ?></div></div>
    <ul class="list-plain">
      <?php
      $just_q = new WP_Query([
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => true,
        'cat' => $just_cat ? (int) $just_cat : '',
      ]);
      while ($just_q->have_posts()): $just_q->the_post(); ?>
        <li><a href="<?php the_permalink(); ?>" class="leading-tight"><?php the_title(); ?></a>
          <div class="text-xs muted"><?php echo esc_html( human_time_diff( get_the_time('U'), current_time('timestamp') ) ); ?> <?php echo esc_html__( 'ago', 'thema' ); ?></div>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>

    <?php $pick_cat = get_theme_mod('thema_editors_pick_category', 0); $pick_args = ['posts_per_page'=>2,'offset'=>1]; if ($pick_cat) { $pick_args['cat'] = (int) $pick_cat; } $pick_q = new WP_Query($pick_args); if ($pick_q->have_posts()): ?>
      <div class="section mt-6">
        <div class="section-header"><div class="section-title"><?php echo esc_html( $pick_cat ? get_cat_name( (int) $pick_cat ) : esc_html__( "Editor's Pick", 'thema' ) ); ?></div></div>
        <div class="grid grid-sm" style="grid-template-columns:1fr;gap:16px">
          <?php while ($pick_q->have_posts()): $pick_q->the_post(); ?>
            <article class="card">
              <?php if (has_post_thumbnail()) { echo '<a href="' . esc_url(get_permalink()) . '">'; the_post_thumbnail('medium'); echo '</a>'; } ?>
              <h3 class="text-lg"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <div class="text-xs muted"><?php echo esc_html( get_the_date() ); ?></div>
            </article>
          <?php endwhile; ?>
        </div>
      </div>
    <?php wp_reset_postdata(); endif; ?>
  </aside>
</section>

<?php
function section_grid_dynamic($index, $fallback_title, $fallback_slug) {
  $setting = get_theme_mod('thema_home_section_' . $index, 0);
  $cat_id = $setting ? (int) $setting : \Thema\get_cat_id_by_slug($fallback_slug);
  $title = $cat_id ? get_cat_name($cat_id) : $fallback_title;
  $per = (int) get_theme_mod('thema_home_posts_per_section', 4);
  $args = [
    'posts_per_page' => $per > 0 ? $per : 4,
    'ignore_sticky_posts' => true,
  ];
  if ($cat_id) { $args['cat'] = $cat_id; }
  $q = new WP_Query($args);
  echo '<section class="section">';
  echo '<div class="section-header"><div class="section-title">' . esc_html($title) . '</div>';
  if ($cat_id) echo '<a class="more-link" href="' . esc_url(get_category_link($cat_id)) . '">' . esc_html__('More', 'thema') . ' ' . esc_html($title) . '</a>';
  echo '</div>';
  echo '<div class="grid-posts">';
  while ($q->have_posts()): $q->the_post();
    echo '<article class="card">';
    if (has_post_thumbnail()) echo '<a href="' . esc_url(get_permalink()) . '">' . get_the_post_thumbnail(null, 'medium') . '</a>';
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

get_footer();
