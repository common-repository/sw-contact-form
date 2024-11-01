<?php
  $site_key = get_option('sw-google-cap-stkey');
  $is_captcha_enable = get_option('sw-captcha-enable');
  global $wpdb;
  $dao = new CN_DAO($wpdb);
  $form_fields = $dao->get_form_fields();
  
?>
<script type="text/javascript">
     jQuery("#submit-cn-form").live("click",function(){ 
	 $data = jQuery("#sw-form").serialize();
	 $url = jQuery('#ajaxreq').val();
	 $preview = jQuery("#preview");
	 $emailvalidator = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	 $websitevalidator = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/; 
	 $phonenovalidator = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
	  $err=0;
	    <?php
		   $field_blanks="";
		    $else_need_flag=false;
		  if($form_fields){
		    foreach($form_fields as $field){
				$else_need_flag = false; 
			    $fieldid = $field->field_id;
				 ?>
				 var <?php echo $fieldid.'_val'?> = jQuery("#<?php echo $fieldid?>").val();
				 <?php
			    if($field->is_required){
					$else_need_flag = true;
				?>
				
				   if(<?php echo $fieldid.'_val'?>==""){
				       jQuery("#<?php echo $fieldid?>_error").html("<?php echo FIELD_BLANK_ERR_TEXT?>");
					   $err++;
				   }
				<?php }if($fieldid=="email"){
					  $else_need_flag = true;
					  if($field->is_required){
						  echo "else";
					  }
				   ?>
				  if(<?php echo $fieldid.'_val'?>!="" &&  !$emailvalidator.test(<?php echo $fieldid.'_val'?>)){
				      jQuery("#<?php echo $fieldid?>_error").html("<?php echo FIELD_VALIDATE_ERR_PRE_TEXT." ".$field->field_id?>");
					   $err++;
				   }
				   <?php
				   }
				    if($fieldid=="website"){
						$else_need_flag = true;
						if($field->is_required){
						  echo "else";
						 }						
					?>
					if(<?php echo $fieldid.'_val'?>!="" && !$websitevalidator.test(<?php echo $fieldid.'_val'?>)){
				      jQuery("#<?php echo $fieldid?>_error").html("<?php echo FIELD_VALIDATE_ERR_PRE_TEXT." ".$field->field_id?>");
					   $err++;
				   }
					<?php
					}
					if($fieldid=="phone_no"){
						 $else_need_flag = true;
						if($field->is_required){
						  echo "else";
					  }
					?>
					if(<?php echo $fieldid.'_val'?>!="" && !$phonenovalidator.test(<?php echo $fieldid.'_val'?>)){
				      jQuery("#<?php echo $fieldid?>_error").html("<?php echo FIELD_VALIDATE_ERR_PRE_TEXT." ".str_replace("_"," ",$field->field_id)?>");
					   $err++;
				   }
					<?php
					} if($else_need_flag){
				    ?>
				   else{
				      jQuery("#<?php echo $fieldid?>_error").html("");
				   }
				<?php
					}
				$field_blanks .= "jQuery('#".$fieldid."').val('');"; 
			}
		  }
		  if($is_captcha_enable==1){
		      ?>
			    if($err>0){
				  jQuery("#form_wait").hide();
			     jQuery(".form_error").css("display","block");
				  return false;
				}
				else{
				  $err=0;
			   $captcha = jQuery("#g-recaptcha-response").val();
			  $verifydata = "captcha="+$captcha+"&action=verifyCaptcha&class=FRONTEND_CLASS";
			   jQuery("#form_wait").show();
			 sendAjaxRequest($verifydata,"POST").success(function(response){
			 response = response.trim();
			   if(response=="no"){
			     jQuery("#captcha_error").html("captcha did not verify");
				  $err++;
			   }
			      if($err>0){
				  jQuery("#form_wait").hide();
			     jQuery(".form_error").css("display","block");
				  }
			   else{
			      jQuery(".form_error").css("display","none");
			      if($preview.length==0){
				   jQuery("#captcha_error").html("");
			    $data = $data+"&action=sendORSaveUserData&class=FRONTEND_CLASS";
			   sendAjaxRequest($data,"POST").success(function(response){
			    jQuery("#form_wait").hide();
				<?php echo $field_blanks;?>
				jQuery("#msg").html("<?php echo DATA_REC_SUCCESS_TEXT?>");
				jQuery("#msg").show();
				jQuery("#sw-form").hide(500);
				
			 }); 
			 }else{
			  jQuery("#captcha_error").html("");
			   jQuery("#msg").html("<?php echo DATA_REC_SUCCESS_TEXT?>");
			   jQuery("#msg").show();
			 }
			   }
			   });
			   }
			 
			  <?php
		  }
		  else{
		    ?>
			if($err>0){
			  jQuery("#form_wait").hide();
			     jQuery(".form_error").css("display","block");
			  return false;
			}
			else{
			  if($preview.length==0){
			    
			    $data = $data+"&action=sendORSaveUserData&class=FRONTEND_CLASS";
				jQuery("#form_wait").css('display','block');
			   sendAjaxRequest($data,"POST").success(function(response){
				   
			   jQuery("#form_wait").hide();
				<?php echo $field_blanks;?>
				jQuery("#msg").html("<?php echo DATA_REC_SUCCESS_TEXT?>");
				jQuery("#msg").show();
				jQuery("#sw-form").hide(500);
			 }); 
			 }else{
			    
			   jQuery("#msg").html("<?php echo DATA_REC_SUCCESS_TEXT?>");
			 }
			}
			<?php
		  }
		?>
	 });
	  function sendAjaxRequest(data,method){
	     var url = jQuery('#ajaxreq').val();
	      return jQuery.ajax({
		      url: url,
			type: method,
			data: data,		
			cache: false
		   });
	   }
	   function blankForm(){
	   <?php
	   
	    if($form_fields){
		    foreach($form_fields as $field){
				?>
				var <?php echo $fieldid?> = jQuery("#<?php echo $fieldid?>").val();
				 jQuery("<?php echo $fieldid?>").val();   
				<?php
				}
			}
	   ?>   
	   }
   	
   var onloadCallback = function() {
        grecaptcha.render('captcha-sec', {
          'sitekey' : '<?php echo $site_key;?>', // Site key
        });
      };
 </script>