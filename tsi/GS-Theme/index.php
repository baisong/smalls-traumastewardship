<?php
get_header();
$DIV_ID	=	"'body_left'";
echo
	"<div	id	=	$DIV_ID	>";
if (have_posts()) :
   while (have_posts()) :
      the_post();
      the_content();
   endwhile;
endif;

$DIV_ID	=	"'body_center'";
echo
	"</div>
	<div	id	=	$DIV_ID	>";
if	(	true	==	have_posts()
	)
{	while	(	true	==	have_posts()
   &&			true	==	is_tag(	'body_center'	)
   		)
   {	the_post();
		the_content();
}	}

$DIV_ID	=	"'body_right'";
echo
	"</div>
	<div	id	=	$DIV_ID	>";
if	(	true	==	have_posts()	)
{	while	(	true	==	have_posts()
   &&			true	==	is_tag(	'body_right'	)
   		)
   {	the_post();
		the_content();
}	}
	
echo
	"</div>";
   
get_footer();
?>