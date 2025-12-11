<?php
?>
</main>
<?php $variant = get_theme_mod('thema_variant','newspaper'); if ($variant==='adventure'){ get_template_part('template-parts/footer/footer','adventure'); } else { ?>
<footer class="site-footer">
  <div class="container">
    <div class="flex items-center justify-between">
      <div>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></div>
      <div class="text-sm">Follow us</div>
    </div>
  </div>
</footer>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>
