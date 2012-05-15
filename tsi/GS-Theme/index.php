<?php get_header(); ?>

<div id="body_left">';
  <?php if (have_posts() === TRUE):?>
    <?php
      while (have_posts()) {
        the_post();
        the_content();
      }
    ?>
  <?php endif; ?>
</div>
<div id="body_center">
  <?php if (have_posts()): ?>
    <?php
      while (have_posts() && is_tag('body_center')) {
        the_post();
        the_content();
      }
    ?>
  <?php endif; ?>
</div>
<div id="body_right">
  <?php if (have_posts()): ?>
    <?php
      while (have_posts() && is_tag('body_right')) {
        the_post();
        the_content();
      }
    ?>
  <?php endif; ?>
</div>

<?php get_footer(); ?>