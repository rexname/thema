<?php
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php $variant = get_theme_mod('thema_variant','newspaper'); if ($variant==='adventure'){ get_template_part('template-parts/header/header','adventure'); } else { ?>
<header class="site-header">
  <div class="container p-4">
    <div class="flex items-center justify-between">
      <a class="brand" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
      <div class="flex items-center">
        <nav class="main-nav">
          <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'fallback_cb'=>false]); ?>
        </nav>
        <form class="px-2" action="<?php echo esc_url(home_url('/')); ?>" method="get">
          <input type="text" name="s" placeholder="<?php echo esc_attr__( 'Search…', 'thema' ); ?>" class="p-2 border"/>
        </form>
      </div>
    </div>
  </div>
</header>
<?php } ?>
<main class="container mt-6">
