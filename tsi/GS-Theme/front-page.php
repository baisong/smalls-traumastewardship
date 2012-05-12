<?php
get_header();

$blog_url			=	get_bloginfo(	'url'		);

echo
	"<div	id		=	'body'
		style	=	'padding-top:	0px;'
	>
		<img	src	=	'"	.$blog_url.	"/wp-content/themes/GS-Theme/media/home_content.jpg'
				style	=	'width:	100%;'
		>
		<ul	style	=	'position:	absolute;
								right:	5%;
								top:		50%;
							text-align:	right;
							width:		100%;'
		>
		<li>	<h2	style	=	'color: 			#ffffff;
									font-weight: 	300;'
		>	raising awareness and
		</h2>	</li>
		<li>	<h2	style	=	'color: 			#ffffff;
									font-weight: 	300;'
		>	responding to the cumulative toll
		</h2>	</li>
		<li>	<h2	style	=	'color: 			#ffffff;
									font-weight: 	300;'
		>	on those who are exposed to the
		</h2>	</li>
		<li>	<h2	style	=	'color: 			#ffffff;
									font-weight: 	300;'
		>	suffering, hardship, crisis or
		</h2>	</li>
		<li>	<h2	style	=	'color: 			#ffffff;
									font-weight: 	300;'
		>	trauma of humans, living beings
		</h2>	</li>
		<li>	<h2	style	=	'color: 			#ffffff;
									font-weight: 	300;'
		>	or the planet itself
		</h2>	</li>
	</ul>
	</div>";
get_footer();
?>

