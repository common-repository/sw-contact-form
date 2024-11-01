<?php
global $wpdb;
define('SWCF_PATH', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)));
define('SWCF_URL', plugins_url('', __FILE__));
define('FORM_FIELD_TBL',$wpdb->prefix.'sw_formfieldtbl');
define('CN_FORM_DATA_TBL',$wpdb->prefix.'sw_formdata');
define('ADMIN_OPTION_CLASS','CN_Adminoption');
define('LEAD_CLASS','CN_Formleads');
define('FRONTEND_CLASS','FormFrontend');
define('LEAD_ON_PAGE',10);
define('ADJACENTS',3);
define('SW_PLUGIN_HELP_TEXT','Update the settings of Contact Form and add into page by shortcode <strong>[sw-contact-form]</strong>. You can also integrate contact form in sidebar with help of widget.');
define('FIELD_TEXT_HELP','These are master contact form fields. You can edit any field(like name of field, placeholder. You can show or hide field in contact form or make field as required field by click on <b>required checkbox</b>) as per your need....');
define('CAPTCHA_TEXT_HELP','if you want to add captcha to form for stopping spamming.. Click on below checkbox and get the site key and secret key and save into your database. you can get site key and secret key from below link. <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>. you can checkout our tutorial to get site key and secret key. <a href="http://www.infotuts.com/google-new-nocaptcha-recaptcha-in-php/" target="_blank">http://www.infotuts.com/google-new-nocaptcha-recaptcha-in-php/</a>');
define('SW_SETTING_NOT_UPDATE_ERR','please Update the basic and advanced settings of Contact Form by this link');
define('DATA_REC_SUCCESS_TEXT','We just got your information. We will get back to your very soon');
define('FIELD_BLANK_ERR_TEXT','this field is required');
define('FIELD_VALIDATE_ERR_PRE_TEXT','please provide valid');
?>