<?php
$blog_name			=	get_bloginfo(	'name'	);
$blog_stylesheet	=	get_bloginfo(	'stylesheet_url'	);	
$blog_url			=	get_bloginfo(	'url'		);
	
echo	"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<title>";

wp_title('');
if	(	wp_title	(	'',
						false
	)				)
{	echo ' :';
}

echo	"$blog_name	</title>
		<link rel='stylesheet' href='$blog_stylesheet' />";

wp_deregister_script	(	'jquery'	);

echo	"<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js' type='text/javascript'></script>";
wp_head();
echo	"</head>
		<body>
		
		<!--BEGIN HEADER-->
		<div	id	=	'header'	>
			<h2	id	=	'name'	>
			<a	href	=	'$blog_url'	>
				$blog_name
			</a>	</h2>";

wp_nav_menu	( array	(	'container'			=>	'false',
								'container_id'		=> 'menu',
								'echo'				=>	'true',
								'menu'				=>	'Top_Menu',
								'sort_column'		=>	'menu_order',
				)			);
$category_ids	=	get_all_category_ids();
foreach(	$category_ids	as	$cat_id	)
{	$cat_name	=	get_cat_name	(	$cat_id	);
}

echo	"</div>
		<!--END HEADER-->
		<div	id	=	'body'";
if	(	true	==	is_front_page()	)
	echo	"style	=	'padding-top:	0px';";
echo	">";
?>