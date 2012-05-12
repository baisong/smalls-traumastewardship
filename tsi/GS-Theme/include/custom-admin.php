<?php
/** 
* Remove the default admin dashboard boxes
*/  

function remove_dashboard_widgets(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

/** 
* Remove the default Custom Fields meta box 
*/  
function removeDefaultCustomFields() {  
        remove_meta_box( 'postcustom', 'post', 'normal' );  
        remove_meta_box( 'postcustom', 'page', 'normal' );
        remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
        remove_meta_box( 'commentsdiv', 'page', 'normal' );
        remove_meta_box( 'authordiv', 'page', 'normal' );
    }  
add_action( 'admin_menu' , 'removeDefaultCustomFields' ); 

/** 
* Remove the dashboard theme editor function
*/ 
function remove_editor_menu() {
  remove_action('admin_menu', '_add_themes_utility_last', 101);
}

add_action('_admin_menu', 'remove_editor_menu', 1);

/** 
* Custom Admin Footer
*/ 
function modify_footer_admin () {
  echo 'Created for <a href="http://growthspark.com" target="_blank">Growth Spark</a> ';
  echo 'by <a href="http://growthspark.com" target="_blank">Growth Spark</a>';
}

add_filter('admin_footer_text', 'modify_footer_admin');

/** 
* Custom Admin Logo
*/ 
function custom_logo() {
  echo '<style type="text/css">
    #header-logo { background-image: url('.get_bloginfo('template_directory').'/include/admin-header-logo.png) !important; }
    </style>';
}

add_action('admin_head', 'custom_logo');

function custom_login_logo() {
  echo '<style type="text/css">
    h1 a { background-image:url('.get_bloginfo('template_directory').'/include/logo-login.png) !important; }
    </style>';
}

add_action('login_head', 'custom_login_logo');
?>