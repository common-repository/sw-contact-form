<?php
function SWCF_edit_field(){
	$adminoj = returnClassObj();
	
	$adminoj->edit_form_field($_REQUEST);
	die('');
}
function SWCF_update_field(){
	$adminoj = returnClassObj();
	$adminoj->update_form_field($_REQUEST);
	die('');
}
function SWCF_canceleditfield(){
	$adminoj = returnClassObj();
	$adminoj->get_cancel_edit_data($_REQUEST);
	die('');
}
function SWCF_save_captcha_credential(){
	$adminoj = returnClassObj();
	$adminoj->saveCaptchaCredential($_REQUEST);
	die('');
	
}

function SWCF_show_form_preview(){
	$adminoj = returnClassObj();
	$adminoj->showForm("preview");
	die('');
}
function SWCF_save_advanced_setting(){
	$adminoj = returnClassObj();
	$adminoj->save_advanced_form_data($_REQUEST);
	die('');
}
function verifyCaptcha(){
	$adminoj = returnClassObj();
	$adminoj->verifyCaptchaCode($_REQUEST);
	die('');
}
function sendORSaveUserData(){
	$adminoj = returnClassObj();
	
	$adminoj->sendORSaveUserData($_REQUEST);
	die('');	
}
function showNextPage(){
 $page = $_REQUEST['page'];
 $adminoj = returnClassObj();
$adminoj->show_leads($page);
	die('');
}
function deleteLead(){
	$adminoj = returnClassObj();
$adminoj->deleteDataLeads($_REQUEST);
	die('');
}

add_action( 'wp_ajax_edit_field', 'SWCF_edit_field' );
add_action( 'wp_ajax_update_form_field', 'SWCF_update_field' );
add_action( 'wp_ajax_canceledit', 'SWCF_canceleditfield' );
add_action( 'wp_ajax_saveCaptchaCredential', 'SWCF_save_captcha_credential' );
add_action( 'wp_ajax_showPreviewForm', 'SWCF_show_form_preview' );
add_action( 'wp_ajax_saveAdvancedSetting', 'SWCF_save_advanced_setting' );
add_action( 'wp_ajax_nopriv_verifyCaptcha','verifyCaptcha');
add_action( 'wp_ajax_verifyCaptcha','verifyCaptcha');
add_action( 'wp_ajax_nopriv_sendORSaveUserData','sendORSaveUserData');
add_action( 'wp_ajax_sendORSaveUserData','sendORSaveUserData');
add_action( 'wp_ajax_showNextPage', 'showNextPage' );
add_action( 'wp_ajax_deleteLead', 'deleteLead' );

function returnClassObj(){
	$class = $_REQUEST['class'];
     $class = constant($class);
	 $classobj = new $class;
     return $classobj;	 
}
?>