<?php
require_once('admin.option.php');
require_once('leads.php');
require_once('contact.widget.php');
 Class CN_Adminbase{
    private $dao;
     function __construct(){
	    global $wpdb;
	   $this->dao = new CN_DAO($wpdb); 
	 }
    function cn_admin_base_activate(){
	   update_option( 'cn_plugin_control', 1);
	}
    function cn_admin_base_deactivate(){
	update_option( 'cn_plugin_control', 0);
	}	
	function initiate_admin_menu(){
	  add_action('admin_menu', array($this,'add_cn_admin_menu_option'));
	  add_action('network_admin_menu', array($this,'add_cn_admin_menu_option')); 
	}
	function add_cn_admin_menu_option(){
	 add_menu_page( __( 'SW Contact Form', 'swcf' ), __( 'SW Contact Form', 'swcf' ), 'delete_others_posts', 'swcf-plugin-menu', array($this,'swcf_admin_setting'),SWCF_URL.'/images/cn_form_icon.png',70 );
	add_submenu_page('swcf-plugin-menu', 'Contact Form Data Leads', 'Contact Form Data Leads', 'delete_others_posts', 'show-cn-form-leads', array($this,'show_cn_form_leads'));
	}
	function swcf_admin_setting(){
	   $admin_option = new CN_Adminoption();
	   $admin_option->init();
	}
	function show_cn_form_leads(){
	  $cn_leads = new CN_Formleads();
	  $cn_leads->init();
	}
	function loadScript(){
	wp_enqueue_style( 'form-backend-style', SWCF_URL. '/css/backend.css');
	wp_enqueue_script('pop-jquery-js', SWCF_URL.'/js/jquery.simplemodal.js',array('jquery'),'1.0',false);	 
	wp_enqueue_script('sw-admin-field-js', SWCF_URL.'/js/admin.backend.js',array('jquery'),'1.0',false);
	}
	function init_set_data(){	  
	  $tablename = FORM_FIELD_TBL;
	  $is_tableexist = $this->dao->get_variable("show tables from ".DB_NAME." where Tables_in_".DB_NAME." like '%$tablename%'");
	    if($is_tableexist!=$tablename){
		 $sql = "create table $tablename(ID int auto_increment primary key, field_name varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, field_type varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, field_placeholder varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, is_required tinyint NOT NULL, is_enabled tinyint NOT NULL,field_id varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL)";
		 $this->dao->create_table($sql);
		 $sql = "insert into $tablename values(1,'name','text','enter your name',0,1,'name'),(2,'email','email','enter your email',0,1,'email'),(3,'website','text','enter your website',0,1,'website'),(4,'phone no','text','enter your phone number',0,1,'phone_no'),(5,'message','textarea','enter your message',0,1,'message')";
		 $this->dao->insertdata($sql);
		}
		$tablename = CN_FORM_DATA_TBL;
		$is_tableexist = $this->dao->get_variable("show tables from ".DB_NAME." where Tables_in_".DB_NAME." like '%$tablename%'");
		if($is_tableexist!=$tablename){
		  $sql = "create table $tablename(ID int auto_increment primary key, name varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, email varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, website varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, phone_no varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, message TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL)";
		  $this->dao->create_table($sql);
		}
	}
 }
?>