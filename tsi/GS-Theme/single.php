<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?> 
			<?php the_title(); ?>
			<?php the_time('m/d/y'); ?>
			<?php the_content(); ?>
			<?php endwhile; endif; ?> 
		<?php comments_template();?>

<?php get_footer(); ?>