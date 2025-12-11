<?php
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="topbar">
  <div class="container p-2">
    <div class="flex items-center justify-between">
      <div class="mini"><?php echo esc_html__( 'Independent News', 'thema' ); ?></div>
      <a class="cta" href="#"><?php echo esc_html__( 'Subscribe', 'thema' ); ?></a>
    </div>
  </div>
</div>
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
<main class="container mt-6">
