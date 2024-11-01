<?php
  Class CN_Adminoption{
    private $dao;
    function __construct(){
	global $wpdb;
	$this->dao = new CN_DAO($wpdb);
	}
	function init(){
	 echo "<p class='help'>".SW_PLUGIN_HELP_TEXT."</p>";
	 echo "<div id='pop-div'></div>";
	  $this->show_basic_form_fields();
	  $this->show_advanced_form_fields();
	  include_once(SWCF_PATH.'/js/frontend.js.php');
	  echo "<input type='hidden' id='ajaxreq' value='".admin_url('admin-ajax.php')."'>";
	}
	function show_basic_form_fields(){
	  echo "<div class='sw-form-section' id='basic_section'>";
      echo "<div class='postbox'><h3 class='hndle'><span>Form Basic Settings</span></h3>";
	   $this->show_form_fields();
	  $this->captcha_box(); 
	  echo "<div class='inside setting_section'><input type='button' class='button' value='Preview' id='preview'/></div>
	  </div>
	  </div>";
	}
	function show_form_fields(){
	   $form_fields = $this->dao->get_form_fields();
       if($form_fields){
	      echo "<div class='inside setting_section'><p class='sec_head'>Master Form Fields</p><p class='help'>".FIELD_TEXT_HELP."</p><table class='widefat'><tr><th class='check-colomn'>Show/Hide</th><th>Field name</th><th>Field Type</th><th>Field Placeholder</th><th>Required</th><th>action</th></tr>";
		    foreach($form_fields as $field){
			echo "<tr id='$field->ID'>".$this->show_form_field($field)."</tr>";
			}
		  echo "</table></div>";
	   }	   
	}
	function captcha_box(){
	   echo "<div class='inside setting_section'><p class='sec_head'>Captcha Section</p><p class='help'>".CAPTCHA_TEXT_HELP."</p>";
	    $site_key = get_option('sw-google-cap-stkey');
		$secret_key = get_option('sw-google-cap-sckey');
		$is_enabled = get_option('sw-captcha-enable');
		$checked="";
		  if($is_enabled==1){
		    $checked="checked='checked'";
		  }
		echo "<form id='captcha_form'><p><input type='checkbox' id='cap_enable' name='cap_enable' value='1' $checked> click on checkbox to enable Google captcha in Contact Form</p>";
		echo "<div id='key-sec'><p class='captcha_key'><label for='site_key'>Site Key</label><input type='text' class='captcha_enable' name='site_key' id='site_key' value='$site_key'><br/><span id='site_key_err' class='sw_error key_error'></span></p>
		<p class='captcha_key'><label for='secret_key'>Secret Key</label><input type='text' class='captcha_enable' name='secret_key' id='secret_key' value='$secret_key'><br/><span id='secret_key_err' class='sw_error key_error'></span></p>
		<input type='hidden' name='action' value='saveCaptchaCredential' />
		<input type='hidden' name='class' value='ADMIN_OPTION_CLASS' />
		<p class='captcha_key'><label></label><input type='button' id='add_captcha_detail' class='button' value='Save Captcha Credentails' disabled='disabled'></p>
		</div></form></div>";
	}
	function show_advanced_form_fields(){
	   $admin_email = get_option('sw-admin-email');
	    if(!$admin_email){
		 $admin_email = get_option('admin_email');
		}
		$custom_css = get_option('sw-custom-css');
	   $data_rec_met = get_option('sw-data-rec');
	   $contact_form_heading = get_option('contact_form_heading');
	   $radio1="";
	   $radio2="";
	   $radio3="";
	      if($data_rec_met==1){
		     $radio1 = "checked='checked'";
		  }
		  if($data_rec_met==2){
		  $radio2 = "checked='checked'";
		  }
		  if($data_rec_met==3){
		  $radio3 = "checked='checked'";
		  }
	   echo "<div class='sw-form-section' id='advanced_section'>";
      echo "<div class='postbox'><h3 class='hndle'><span>Form Advance Settings</span></h3>
            <form id='form-advanced-setting' action='' method='post'>
			<div class='inside setting_section'>
			    <p><label for='contact-form-heading'>Contact Form Heading:</label><input type='text' name='contact-form-heading' id='contact-form-heading' value='$contact_form_heading'></p>
			    <p><label for='receiveid'>Email ID(mail receiving email id):</label><input type='text' name='receiverid' id='receiverid' value='$admin_email'><span id='receiverid-err'></span></p>
				<p><label for='custom_css'>Custom Css:</label><textarea name='custom_css' id='custom_css' rows='10' cols='63'>".stripslashes($custom_css)."</textarea></label>
				</div>
				<div class='inside setting_section'>
				<p class='sec_head'>User Information Receiving Method</p>
		        <p><input type='radio' name='data-rec-met' value='1' $radio1><span>By Email</span></p>
		        <p><input type='radio' name='data-rec-met' value='2' $radio2><span>Save user information in database(you can see info of user by our lead option)</span></p>
				<p><input type='radio' name='data-rec-met' value='3' $radio3><span>Email and Save user information in database</span><br/><span id='data-rec-met-err'></span></p>
				<p><input type='button' class='button' id='advanc-set-bt' value='Update Advanced Setting'></p>
				<input type='hidden' name='action' value='saveAdvancedSetting'/>
				<input type='hidden' name='class' value='ADMIN_OPTION_CLASS'/>
				
			</div>
			</form>
	  </div>
	  </div>";
	}
	function show_form_field($field){
		 $reqcheck="";
		 $enablechecked="";
	    if($field->is_enabled==1){
		  $enablechecked= "checked='checked'";
		}
		if($field->is_required==1){
		  $reqcheck = "checked='checked'";
		}
	   $str = "<td><input type='checkbox' name='field_$field->ID' value='1' $enablechecked disabled='disabled'></td>
	   <td>$field->field_name</td>
	   <td>$field->field_type</td>
	   <td>$field->field_placeholder</td>
	   <td><input type='checkbox' name='required_$field->ID' value='1' $reqcheck disabled='disabled'></td>
	   <td><input type='button' class='button editf' value='Edit' field='$field->ID'></td>";
	  return $str;
	}
	function saveCaptchaCredential($data){
	  $cap_enable = stripslashes($data['cap_enable']);
	  $site_key = stripslashes($data['site_key']); 
	  $secret_key = stripslashes($data['secret_key']);
	   if($cap_enable){
	   update_option('sw-google-cap-stkey',$site_key);
	  update_option('sw-google-cap-sckey',$secret_key);
	   }
	   else{
	   $cap_enable=0;
	   }
	  update_option('sw-captcha-enable',$cap_enable);
	  
	  echo "added";
	}
	function edit_form_field($data){
		
	  $fieldid = stripslashes($data['fieldid']);
	  $field =  $this->dao->get_form_fields($fieldid);
	  ?>
	  
	  <td><input type='checkbox' id='enable' name='enable' value='1' <?php if($field->is_enabled==1) echo "checked='checked'"?>></td>
	    <td><input type='text' name='field_name' id='field_name' value='<?php echo $field->field_name?>'><br/><span id='name_error' class='sw_error'></span></td>
		<td><select name='field_type' id='field_type'>
		    <option value='select'>Select Field Type</option>
		    <option value='text' <?php if($field->field_type=="text") echo "selected='selected'"?>>Text</option>
		    <option value='email' <?php if($field->field_type=="email") echo "selected='selected'"?> >Email</option>
		    <option value='textarea' <?php if($field->field_type=="textarea") echo "selected='selected'"?>>Textarea</option>
			</select>
			<br/><span id='type_error' class='sw_error'></span>
		</td>
		<td><input type='text' name='placeholder' id='placeholder' value='<?php echo $field->field_placeholder?>'>
		<br/><span id='plholder_error' class='sw_error'></span>
		</td>
		<td><input type='checkbox' name='is_required' id='is_required' value='1' <?php if($field->is_required==1) echo "checked='checked'"?>></td>
		<td><input type='hidden' name='action' id='update_form_field' value='update_form_field'><input type='hidden' name='fieldid' id='fieldid' value='<?php echo $field->ID?>'>
		<input type='hidden' name='class' id='class' value='ADMIN_OPTION_CLASS'>
		<input type='button' class='button' name='update' id='update_field' value='Update Field'>&nbsp;<input type='button' class='button' name='cancel' id='cancel' value='Cancel action'></td>
	
	  <?php
	}
	function get_cancel_edit_data($data){
	  $fieldid = stripslashes($data['cancelactionid']);
	  $field = $this->dao->get_form_fields($fieldid);
	  echo $this->show_form_field($field);
	}
	function save_advanced_form_data($data){
	  $adminid = stripslashes($data['receiverid']);
	  $mail_rec_method = stripslashes($data['data-rec-met']);
	  $custom_css = stripslashes($data['custom_css']);
	  $contact_form_heading = stripslashes($data['contact-form-heading']);
	  update_option('sw-custom-css',$custom_css);
 	  update_option('sw-admin-email',$adminid);
	  update_option('sw-data-rec',$mail_rec_method);
	  update_option('contact_form_heading',$contact_form_heading);
	  echo "updated";
	}
	function update_form_field($data){
	 $field_name = stripslashes($data['field_name']);
	 $field_type = stripslashes($data['field_type']);
	 $field_placeholder = stripslashes($data['placeholder']);
	 $is_enabled = stripslashes($data['enable']);
	 $is_required = stripslashes($data['is_required']);
	 $field_id = stripslashes($data['fieldid']);
	 $sql = "update ".FORM_FIELD_TBL." set field_name='".esc_sql($field_name)."', field_type='".esc_sql($field_type)."', field_placeholder='".esc_sql($field_placeholder)."', is_required=$is_required, is_enabled=$is_enabled where ID=$field_id";
	 $this->dao->updatedata($sql);
	 $field = $this->dao->get_form_fields($field_id);
	 echo $this->show_form_field($field);
	}
  }
?>