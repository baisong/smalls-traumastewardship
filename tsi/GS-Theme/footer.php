<?php
$blog_url			=	get_bloginfo(	'url'		);

echo	"</div>
		<div id	= 'footer'>
			<div	class	=	'footer_left'	>
				<h4	id	=	'follow'	>	Follow Us	</h4>		
				Enter your e-mail address if you'd like to receive periodic updates on the Institute.	<br/>
				<form class='search' valign =	'top'>
					<input id =	'text' type	= 'text'>
					<input	id		=	'button'
								type	=	'image'
								src	='"	.$blog_url.	"/wp-content/themes/GS-Theme/media/go.jpg'
					>
				</form>
			</div>
			<div	class	=	'footer_center'	>
				<h4>Contact us:		</h4>
				<img	src	=	'"	.$blog_url.	"/wp-content/themes/GS-Theme/media/mail_image.jpg'	>
				Email the Trauma Stewardship Institute	<br/>
				PO Box 28302, Seattle, WA 98118
			</div>
			<div	class	=	'footer_right'	>
				<h4>Join Us</h4>
				<img	src	=	'"	.$blog_url.	"/wp-content/themes/GS-Theme/media/London.jpg'	>	<br/>
				London, England
			</div>
		</div>
		<h5	id	=	'footer_copyright'	>
			&copy; 2012 The Trauma Stewardship Institute
		</h5>
		</body>
		</html>";
?>