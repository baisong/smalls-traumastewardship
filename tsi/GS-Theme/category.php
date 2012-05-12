<?php get_header(); ?>

<?php $category = get_the_category($post->ID);
$category_id = get_cat_ID( $category[0]->cat_name );
$category_link = get_category_link( $category_id );
?>

<h1><?php echo $category[0]->cat_name;?></h1>

<?php
 while(have_posts()): the_post();?>
<h4> <?php the_time('M d, Y');?></h4>

<a href="<?php echo esc_url( $category_link ); ?>"><div class="category" id="<?php echo $category[0]->category_nicename;?>"><?php echo $category[0]->cat_name;?></div></a>

<a href="<?php the_permalink();?>"><h2><?php the_title();?></h2></a>

<?php if(has_post_thumbnail()):?>
 <a href="<?php the_permalink();?>"><?php the_post_thumbnail('thumbnail');?></a>
<?php endif;?>

<?php the_content_limit('620');?>
<?php endwhile;?>

<?php get_footer(); ?>