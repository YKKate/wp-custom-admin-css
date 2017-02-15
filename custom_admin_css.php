<?php

/*
Plugin Name: Custom_admin_css
Plugin URI: http://
Description: 「設定」中新增自訂 後台CSS 的子頁，並在後台的 body 新增「role-角色」的 class，可在 css 前加上 body.role-角色 做篩選
Version: 1.0.0
Author: YK-LUO
Author URI: http://yk-luo.com
License: XXX
Copyright: XXX
*/

function load_ace($hook){
	if ( 'settings_page_admin_css' == $hook ) {
		wp_enqueue_style( 'ace', plugins_url() . '/custom-admin-css/css/custom_admin_css.ace.css', array(), '1.0.0', 'all' );
		wp_enqueue_script('ace', plugins_url() . '/custom-admin-css/js/ace/ace.js', array('jquery'), '1.2.1', true );
		wp_enqueue_script('custom-admin-css-script', plugins_url() . '/custom-admin-css/js/custom_admin_css.js', array('jquery'), '1.0.0', true );
	}
}
add_action( 'admin_enqueue_scripts', 'load_ace' );



add_action('admin_menu', 'custom_admin_css_page');
  
function custom_admin_css_page() {
    add_submenu_page(
        'options-general.php',
        '自訂後台CSS',
        '自訂後台CSS',
        'manage_options',
        'admin_css',
        'custom_admin_css' );

    add_action( 'admin_init', 'custom_admin_css_settings' );
}

function custom_admin_css_settings(){
	register_setting( 'custom-admin-css-options', 'admin_css', 'sanitize_custom_css' );
	add_settings_section( 'custom-admin-css-section', '', 'custom_admin_css_section_callback', 'admin_css' );
	add_settings_field( 'custom-admin-css', '後台CSS', 'custom_css_callback', 'admin_css', 'custom-admin-css-section' );
}

function sanitize_custom_css( $input ){
	$output = esc_textarea( $input );
	return $output;
}

function custom_admin_css_section_callback(){
	//echo 'Customize Admin CSS';
	echo '後台的 body 中加上了「role-角色」的 class，可在 css 前加上「.role-角色」的 class 做篩選'; 
}

function custom_css_callback(){
	$custom_admin_CSS = get_option( 'admin_css' );
	$custom_admin_CSS = ( empty($custom_admin_CSS) ? '/* Custom Admin CSS */' : $custom_admin_CSS);
	echo '<div id="customCss">'.$custom_admin_CSS.'</div><textarea id="admin_css" style="display:none;visibility:hidden;" name="admin_css">'.$custom_admin_CSS.'</textarea>';
}

function custom_admin_css() { ?>
	<h1>自定後台CSS</h1>
	<?php //settings_errors(); ?>

	<form method="post" action="options.php" id="save-custom-admin-css-form">
		<?php settings_fields( 'custom-admin-css-options' ); ?>
		<?php do_settings_sections( 'admin_css' ); ?>
		<?php submit_button(); ?>
	</form>
<?php } 



// Add role class to admin body
function add_role_to_body($classes) {
	
	global $current_user;
	$user_role = array_shift($current_user->roles);
	
	$classes .= 'role-'. $user_role;
	return $classes;
}
//add_filter('body_class','add_role_to_body');
add_filter('admin_body_class', 'add_role_to_body');

//admin css
function admin_style() {
	$custom_admin_CSS = get_option( 'admin_css' );
	echo '<style>'.$custom_admin_CSS.'</style>';
}
add_action('admin_enqueue_scripts', 'admin_style');





?>