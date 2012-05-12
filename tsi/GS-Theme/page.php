<?php
get_header();

if (have_posts()) :
   while (have_posts()) :
      the_post();
      the_content();
   endwhile;
endif;

echo	"<div	id	=	'body_left'	>";

echo	"</div>	<div	id	=	'body_center'	>";


echo	"</div>	<div	id	=	'body_right'	>";

$page_id	=	73;
get_page_children(		);

echo	"</div>";
get_footer();
?>