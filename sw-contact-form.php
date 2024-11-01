<?php
/*
Plugin Name: SW Contact Form Plugin 
Description: Easily integrate contact form anywhere in their website using shortcode and widget.
Version: 1.0
Author: infotuts
*/

require_once 'constant-option.php';
require_once SWCF_PATH . '/inc/dao.inc.php';
require_once SWCF_PATH . '/frontend.form.php';
require_once SWCF_PATH . '/admin/cn.adminbase.php';
require_once SWCF_PATH . '/inc/ajax_hook_functions.php';
//require_once SWCF_PATH . '/inc/cn.ajaxrequest.php';
require_once SWCF_PATH . '/admin/contact.widget.php';
$adminbase = new CN_Adminbase();
register_activation_hook(__FILE__, array($adminbase, 'cn_admin_base_activate'));
register_deactivation_hook(__FILE__, array($adminbase, 'cn_admin_base_deactivate'));

$adminbase->initiate_admin_menu();
$adminbase->init_set_data();


function custom_enqueue(){
	if(!is_admin()){
	  wp_enqueue_script( 'jquery');
	}
	  }
function _my_plugin_php_warning() {
      $data_rec_method = get_option("sw-data-rec"); 
	  if(!$data_rec_method) { 
    echo '<div id="message" class="sw_error">';
    echo "<p>please update the settings of SW Contact Form. To update the settings please click here <a href='".admin_url()."admin.php?page=swcf-plugin-menu"."'>Contact Form Setting</a></p>";
    echo '</div>';
	}
}

function activate_plugin_conditional() {
        $plugin = plugin_basename(__FILE__);
        if ( is_plugin_active($plugin) ) {
            add_action('admin_notices', '_my_plugin_php_warning');
            }
}
function display_contact_form(){
  $frontend = new FormFrontend();
  $frontend->showForm("publish");

  include_once(SWCF_PATH.'/js/frontend.js.php');
}

if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(dirname(__FILE__).'/uninstall.php','');

add_action( 'admin_init', 'activate_plugin_conditional' );
add_shortcode('sw-contact-form','display_contact_form');
add_action( 'admin_enqueue_scripts', array($adminbase,'loadScript'));
add_action('init','custom_enqueue');
?>