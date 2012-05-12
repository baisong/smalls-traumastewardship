<?php
//Required Functions

// Calls in Custom Admin changes, theme options, meta-box functions, and hard-coded CPT registrations if not using CPTUI plugin
include_once(TEMPLATEPATH . '/include/custom-admin.php');
include_once(TEMPLATEPATH . '/include/sidebar-functions.php');

// --- Add WP Nav Menu Support --- //
add_theme_support( 'menus' );
register_nav_menu('util', 'Top Navigation Menu');
register_nav_menu('main', 'Main Navigation Menu');
register_nav_menu('footer', 'Footer Navigation Menu');
// --- End Add WP Nav Menu Support --- //

// --- Add Feed Links to wp_head --- //
// Older themes would have these added to the header manually, removing those lines and doing it this way lets the WP manage links and future-proofs themes
add_theme_support( 'automatic-feed-links' );
// --- End Add Feed Links to wp_head --- //

// --- Add Template Featured Image Support and custom image sizes --- //
// Call with <php the_post_thumbnail(); > for default thumbnail size or <php the_post_thumbnail('custom-size'); >
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 150, 150, true ); // Normal post thumbnails
add_image_size( 'blogThumb', 230, 190, true ); 
add_image_size( 'homeslide', 960, 500, true ); 

// --- End Featured Image Support --- //

// --- Excerpt Box for Pages --- //
//add_post_type_support( 'page', 'excerpt' );
// --- End Excerpt Box for Pages --- //

// --- Remove WP Version Number --- //
remove_action('wp_head', 'wp_generator');
// --- End Remove WP Version Number Action --- //

// --- Remove Curly Quotes --- //
remove_filter('the_content', 'wptexturize');
remove_filter('comment_text', 'wptexturize'); 
// --- End Remove Curly Quotes Filter --- //

// --- Automatically changes blog title heading depending on page ---//
function blog_heading_swap() {
	$blogname = bloginfo('name');
	if (is_home()) {
		$heading = '<h1>';
		$heading .= $blogname;
		$heading .= '</h1>';
	} else {
		$heading = '<h2>';
		$heading .= $blogname;
		$heading .= '</h2>';
	}  
	print $heading;
} 
// --- End blog_heading_swap Function ---//

// --- Remove the WP 3.x Admin Bar --- //
// At the top of the page for logged in users, the new versions of WP have an admin bar... its appearance is a personal choice, really... I don't like. :)
add_action( 'admin_print_scripts-profile.php', 'hide_admin_bar_prefs' );
function hide_admin_bar_prefs() { ?>
<style type="text/css">
	.show-admin-bar { display: none; }
</style>
<?php
}

add_filter( 'show_admin_bar', '__return_false' );
remove_action( 'personal_options', '_admin_bar_preferences' );
// --- End Remove the WP 3.x Admin Bar --- //

// --- Featured Image Caption Support --- //
// By default, if you add a caption to an image in the Media Library, but then use it as a Featured Image you can't produce the caption on the template side.
// This allows for the caption.
function the_post_thumbnail_caption() {
  global $post;

  $thumbnail_id    = get_post_thumbnail_id($post->ID);
  $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

  if ($thumbnail_image && isset($thumbnail_image[0])) {
    echo '<span>'.$thumbnail_image[0]->post_excerpt.'</span>';
  }
}
// --- End Featured Image Caption Support --- //

// --- Drop Down Filter for Custom Taxonomies in the Dashboard Post / CPT Lists --- //
// Creating custom taxonomies doesn't automatically give you the option to filter by those when vieweing the Post lists.
// This creates filters for each of the custom taxonomies.
function andrade_restrict_manage_posts() {
    global $typenow;
    $args=array( 'public' => true, '_builtin' => false ); 
    $post_types = get_post_types($args);
    if ( in_array($typenow, $post_types) ) {
    $filters = get_object_taxonomies($typenow);
        foreach ($filters as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            wp_dropdown_categories(array(
                'show_option_all' => __('Show All '.$tax_obj->label ),
                'taxonomy' => $tax_slug,
                'name' => $tax_obj->name,
                'selected' => $_GET[$tax_obj->query_var],
                'hierarchical' => $tax_obj->hierarchical,
                'show_count' => false,
                'hide_empty' => true
            ));
        }
    }
}
function andrade_convert_restrict($query) {
    global $pagenow;
    global $typenow;
    if ($pagenow=='edit.php') {
        $filters = get_object_taxonomies($typenow);
        foreach ($filters as $tax_slug) {
            $var = &$query->query_vars[$tax_slug];
            if ( isset($var) ) {
                $term = get_term_by('id',$var,$tax_slug);
                $var = $term->slug;
            }
        }
    }
}
add_action( 'restrict_manage_posts', 'andrade_restrict_manage_posts' );
add_filter('parse_query','andrade_convert_restrict');
// --- End Drop Down Filter for Custom Taxonomies in the Dashboard Post / CPT Lists --- //

// --- First / Last Classes for WP Nav Menu --- //
// WP doesn't add any custom classes to identify the first item in a menu or the last item in a menu.
// This adds those classes. This allows you to better use the before / after wp_nav_menu parameters for creation of nav item separators.
function nav_menu_first_last( $items ) { $position = strrpos($items, 'class="menu-item', -1);
	$items=substr_replace($items, 'menu-item-last ', $position+7, 0);
	$position = strpos($items, 'class="menu-item');
	$items=substr_replace($items, 'menu-item-first ', $position+7, 0);
	return $items;
}
add_filter( 'wp_nav_menu_items', 'nav_menu_first_last' );
// --- End First / Last Classes for WP Nav Menu --- //

// --- Call The Loop with parameters as a shortcode in the WP editor --- //
// Instead of creating custom page templates for each CPT or making a custom CPT abstract page template, you can define standard page templates and then use
// this shortcode to call a loop on the fly. THIS WORKS IN SIDEBAR WIDGETS, TOO.
// Example: [loop the_query="showposts=100&post_type=page&post_parent=453"]
function custom_query_shortcode($atts) {   
   // Defaults
   extract(shortcode_atts(array(
      "the_query" => ''
   ), $atts));

   // de-funkify query
   $the_query = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $the_query);
   $the_query = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $the_query);

   // query is made               
   query_posts($the_query);
   
   // Reset and setup variables
   $output = '';
   $temp_title = '';
   $temp_link = '';
   
   // the loop
   if (have_posts()) : while (have_posts()) : the_post();
   
      $temp_title = get_the_title($post->ID);
      $temp_link = get_permalink($post->ID);
      
      // output all findings - CUSTOMIZE TO YOUR LIKING
      $output .= "<li><a href='$temp_link'>$temp_title</a></li>";
          
   endwhile; else:
   
      $output .= "nothing found.";
      
   endif;
   
   wp_reset_query();
   return $output;
   
}
add_shortcode("loop", "custom_query_shortcode");
// --- End Call The Loop with parameters as a shortcode in the WP editor --- //

?>
<?php

// --- Template tag for in-template URL shortening --- ///
// Create bit.ly url - call with <php bitly(); > like so: <a href="<php bitly(); >">LinkText</a>
// This will call the post's permalink and wash it through Bit.ly and generate the correct link (use in place of <php echo get_permalink(); >).
// By doing so, you can also track uses of the shortened link via your Bit.ly account.  
// Replace login and apikey variables for a client or leave the default info.
function bitly()
{
	//login information
	$url = get_permalink();  //generates wordpress' permalink
	$login = 'andradenation';	//your bit.ly login
	$apikey = 'R_166ed2d1559085209b29962195908ef9'; //bit.ly apikey
	$format = 'json';	//choose between json or xml
	$version = '2.0.1';

	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;

	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);

	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		echo $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		echo 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}
// --- End bitly Function --- ///

// --- Function to output the first image attachment's URL with size attribute --- //
function the_image_url($size = 'full' , $class = ''){
global $post;

//setup the attachment array
$att_array = array(
'post_parent' => $post->ID,
'post_type' => 'attachment',
'post_mime_type' => 'image',
'order_by' => 'menu_order'
);

//get the post attachments
$attachments = get_children($att_array);

//make sure there are attachments
if (is_array($attachments)){
//loop through them
foreach($attachments as $att){
//find the one we want based on its characteristics
if ( $att->menu_order == 0){
$image_src_array = wp_get_attachment_image_src($att->ID, $size);

//get url – 1 and 2 are the x and y dimensions
$url = $image_src_array[0];
$caption = $att->post_excerpt;
$image_html = '%s';

//combine the data
$html = sprintf($image_html,$url,$caption,$class);

//echo the result
echo $html;
}
}
}

}
// --- End the_image_url Function --- //

// --- Custom Excerpt function - Differs from the_excerpt ---//
// A different way to pull in an excerpt with in-template excerpt length. Use is <php the_content_limit('length');> where 'length' is the number of characters.
// Then that length can have a value passed to it either hard-coded or as a theme option - <php the_content_limit($themeoption);>
// It can also be customized below so you can remove the paragraph tags or enclose in any other tag.
function the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
      echo "<p>";
      echo $content;
      echo "</p>";
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo "<p>";
        echo $content;
        echo "...";
        echo "</p>";
   }
   else {
      echo "<p>";
      echo $content;
      echo "</p>";
   }
}
// --- End the_content_limit Function --- //

// --- Improved Control over WP the_excerpt ---//
// New filter control for the built-in WP the_excerpt function. With the filter below we can customize the_excerpt on a client-by-client basis or leave as is.
function improved_trim_excerpt($text) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		$text = strip_tags($text, '<p>');
		$excerpt_length = 55;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '...');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');
// --- End improved_trim_excerpt Filter ---//

function the_excerpt_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $excerpt = get_the_excerpt($more_link_text, $stripteaser, $more_file);
    $excerpt = apply_filters('the_excerpt', $excerpt);
    $excerpt = str_replace(']]>', ']]&gt;', $excerpt);
    $excerpt = strip_tags($excerpt);

   if (strlen($_GET['p']) > 0) {
      echo "<p>";
      echo $excerpt;
      echo "</p>";
   }
   else if ((strlen($excerpt)>$max_char) && ($espacio = strpos($excerpt, " ", $max_char ))) {
        $excerpt = substr($excerpt, 0, $espacio);
        $excerpt = $excerpt;
        echo "<p>";
        echo $excerpt;
        echo "</p>";
   }
   else {
      echo "<p>";
      echo $excerpt;
	  echo '<a href="'.get_permalink($post->ID).'">';
	  echo '</a>';
      echo "</p>";
   }
}
//Added
function sub_nav_children() {
   global $post;
		if($post->post_parent)
			$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
		else
			$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
		if ($children) { ?>
	<ul>
	<?php echo $children; ?>
	</ul>
  <?php } 
}
?>
<?php
/*GForm PlaceHolder*/
/* Add a custom field to the field editor (See editor screenshot) */
add_action("gform_field_standard_settings", "my_standard_settings", 10, 2);
function my_standard_settings($position, $form_id){
        // Create settings on position 25 (right after Field Label)
        if($position == 25){
                ?>
                <li class="admin_label_setting field_setting" style="display: list-item; ">
                        <label for="field_placeholder">Placeholder Text
                                <!-- Tooltip to help users understand what this field does -->
                                <a href="javascript:void(0);" class="tooltip tooltip_form_field_placeholder" tooltip="<h6>Placeholder</h6>Enter the placeholder/default text for this field.">(?)</a>
                        </label>
                        <input type="text" id="field_placeholder" class="fieldwidth-3" size="35" onkeyup="SetFieldProperty('placeholder', this.value);">
                </li>
                <?php
        }
}

/* Now we execute some javascript technicalitites for the field to load correctly */
add_action("gform_editor_js", "my_gform_editor_js");
function my_gform_editor_js(){
        ?>
        <script>
                //binding to the load field settings event to initialize the checkbox
                jQuery(document).bind("gform_load_field_settings", function(event, field, form){
                        jQuery("#field_placeholder").val(field["placeholder"]);
                });
        </script>
        <?php
}

/* We use jQuery to read the placeholder value and inject it to its field */
add_action('gform_enqueue_scripts',"my_gform_enqueue_scripts", 10, 2);
function my_gform_enqueue_scripts($form, $is_ajax=false){
        ?>
        <script>
        jQuery(function(){
                <?php
                /* Go through each one of the form fields */
                foreach($form['fields'] as $i=>$field){
                        /* Check if the field has an assigned placeholder */
                        if(isset($field['placeholder']) && !empty($field['placeholder'])){
                                /* If a placeholder text exists, inject it as a new property to the field using jQuery */
                                ?>
                                jQuery('#input_<?php echo $form['id']?>_<?php echo $field['id']?>').attr('placeholder','<?php echo $field['placeholder']?>');
                                <?php
                        }
                }
                ?>
        });
        </script>
        <?php
}
function shareThis_Link() {
   global $post;{
		echo'';
		echo'<span class="st_sharethis" st_title="';
		the_title();
		echo'" st_url="';
		the_permalink(); 
		echo'" displayText="ShareThis"></span>';
	}
}
function subHeader() {
   global $post;
		if(get_field('sub_title_header') != ""){
			echo '<h1>';
			the_field('sub_title_header');
			echo '</h1>';
		} else {
			echo '<h1>';
			the_title();
			echo '</h1>';
		}
}
function my_tags() {
   global $post;{
		$tags = wp_get_post_tags($post->ID);
		if ($tags) {
		echo'<p>Tagged in: ';
		foreach($tags as $tag) {
        echo $title . '<a href="' . get_term_link( $tag, 'post_tag' ) . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>, &nbsp;';
	}
		echo'</p>';
  }
}
}
?>