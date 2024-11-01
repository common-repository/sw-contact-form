 <?php global $wpdb;
	if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();
	
	if (is_multisite()) 
{
    
    $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

    if(!empty($blogs))
    {
        foreach($blogs as $blog) 
        {
	    switch_to_blog($blog['blog_id']);
      delete_option('cn_plugin_control');
	delete_option('sw-admin-email');
	delete_option('sw-data-rec');
	delete_option('sw-google-cap-stkey');
	delete_option('sw-google-cap-sckey');
	delete_option('sw-captcha-enable');
	$wpdb->query("drop table {$wpdb->prefix}sw_formfieldtbl");
    $wpdb->query("drop table {$wpdb->prefix}sw_formdata");	
        }
    }
}else{
	delete_option('cn_plugin_control');
	delete_option('sw-admin-email');
	delete_option('sw-data-rec');
	delete_option('sw-google-cap-stkey');
	delete_option('sw-google-cap-sckey');
	delete_option('sw-captcha-enable');
	$wpdb->query("drop table {$wpdb->prefix}sw_formfieldtbl");
    $wpdb->query("drop table {$wpdb->prefix}sw_formdata");	
	}
	
	?>