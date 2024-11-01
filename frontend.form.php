<?php
Class FormFrontend {
  private $dao;
 function __construct(){
    global $wpdb;
	$this->dao = new CN_DAO($wpdb);
 }
 function verifyCaptchaCode($data){
   $captcha = stripslashes($data['captcha']);
   $secret_key = get_option('sw-google-cap-sckey');
   $ip = $_SERVER['REMOTE_ADDR'];
   $url = 'https://www.google.com/recaptcha/api/siteverify';
$full_url = $url.'?secret='.$secret_key.'&response='.$captcha.'&remoteip='.$ip;
$data = json_decode(file_get_contents($full_url));
 if(isset($data->success) && $data->success==1){
   echo "yes";
 }
 else{
   echo "no";
 }
//error-codes array(missing-input-response)
 } 
 public function showForm($show_action){
    $str="";
	$data_receive_method = get_option('sw-data-rec');
	if($data_receive_method){
	  if($show_action=="preview" || $show_action=="publish")
	echo "<link rel='stylesheet' href='".SWCF_URL. "/css/frontend.css' type='text/css' media='all'>";
	else
	echo "<link rel='stylesheet' href='".SWCF_URL. "/css/widget.frontend.css' type='text/css' media='all'>";
	 
	$custom_css = stripslashes(get_option('sw-custom-css'));
	$is_captcha_enable = get_option('sw-captcha-enable');
	echo "<style>$custom_css</style>";
   $form_fields = $this->dao->get_form_fields();
   if($form_fields){
     $str .= "<div id='sw-contact-form'>";
	   $contact_form_heading = get_option("contact_form_heading");
	     if($contact_form_heading){
		echo "<p id='form_heading'>$contact_form_heading</p>";	 
		 }
	 echo "<p id='msg'></p><form action='#' method='post' name='sw-form' id='sw-form'>"; 
      foreach($form_fields as $field){
	       if($field->is_enabled==1){
	      $str.="<div class='field-sec'><p><label class='sw-form-label'>$field->field_name</label></p>";
		  $field_id = $field->field_id;
		  $str.="<p>";
	     if($field->field_type=="text" || $field->field_type=="email"){
		    
		  $str .= "<input type='$field->field_type' name='$field_id' id='$field_id' placeholder='$field->field_placeholder'>";   
		 }
		 if($field->field_type=="textarea"){
		   $str .="<textarea name='$field_id' id='$field_id' placeholder='$field->field_placeholder'></textarea>";
		 }
		 
		     $str.="<span id='".$field_id."_error' class='sw_error form_error'></span>";
		   
		  $str.="</p></div><div class='clear'></div>";
		 }
	  }
	  if($show_action=="preview"){
	    $str .= "<input type='hidden' id='preview-form' name='preview-form'>";
	}
	 else{
	   $str .= "<input type='hidden' name='submit-form'>";
	}
	    if($is_captcha_enable==1){
		  $str.="<div><p><label></label></p><div><div id='captcha-sec'></div><span id='captcha_error' class='sw_error form_error'></span><script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&response=yes' async defer></script></div></div>";
		}
	  $str .= "<input type='button' class='button' name='submit-from' id='submit-cn-form' value='Send Query'>&nbsp;<span><img src='".SWCF_URL."/images/form_wait.gif' id='form_wait' /></span>";
	 $str .="<input type='hidden' id='ajaxreq' value='".admin_url('admin-ajax.php')."'>";
	 $str .= "</form></div>";
   }
   
   echo $str;
   }
   else{
     echo SW_SETTING_NOT_UPDATE_ERR." <a href='".admin_url()."admin.php?page=swcf-plugin-menu' target='_blank'>Update Settings of Contact Form</a>";
   }
   
 }
  public function sendORSaveUserData($data){
    $data_receive_method = get_option('sw-data-rec');
	$fields = $this->dao->get_form_fields();
	  if($data_receive_method==1){
	    if($this->sendMail($data,$fields)){
		  echo "done";
		}
		else{
		   echo "err";
		}
	  }
	  if($data_receive_method==2){
	    if($this->saveData($data,$fields)){
		echo "done";
		}
		else{
		   echo "err";
		}
	  }
	  if($data_receive_method==3){
	    if($this->sendMail($data,$fields) & $this->saveData($data,$fields)){
		  echo "done";
		}
		else{
		   echo "err";
		}
	  }
  }
  private function sendMail($data,$fields){
    $mailstr="<div style='margin:5px;'><table>";
	$to = get_option('sw-admin-email');
	foreach($fields as $field){
	  if($field->field_id=="name"){
	    $sender_name = stripslashes($data[$field->field_id]);
	  }
	  if($field->field_id=="email"){
	    $sender_email = stripslashes($data[$field->field_id]);
	  }
	  $mailstr.="<tr><td>". strtoupper(substr($field->field_name,0,1)).substr($field->field_name,1) ."</td><td>". stripslashes($data[$field->field_id]) . "</td></tr>";
	}
	$mailstr.="</table></div>";
	$subject = "Contact From $sender_name";
	$headers  = "From: \"$sender_name\" <$sender_email>\n";
	$headers .= "Return-Path: <" . $sender_email . ">\n";
	$headers .= "Reply-To: \"" . $sender_name . "\" <" . $sender_email . ">\n";
	$headers .= "X-Mailer: PHP" . phpversion() . "\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
	$headers .= "Content-type: text/html\r\n"; 
	if(@wp_mail($to, $subject, $mailstr, $headers)){
	  return true;
	}else{
	  return false;
	}
  }
  private function saveData($data,$fields){
    $dbstr = "insert into ".CN_FORM_DATA_TBL."(name,email,website,phone_no,message) values(";
	foreach($fields as $field){ 
	  $dbstr.="'".esc_sql(stripslashes($data[$field->field_id]))."', ";
	}
	$indx = strrpos($dbstr,",");
	$dbstr = substr($dbstr,0,$indx);
	$dbstr.=")";
	if($this->dao->insertdata($dbstr)){
	  return true;
	}
	else{
	  return false;
	}
  }
}
?>