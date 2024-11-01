
jQuery(function(){
   var waiting = jQuery("<p id='waiting'></p>");
   var message = jQuery("<p id='admin-msg'></p>");
jQuery("body").append(waiting);
jQuery("body").append(message);
    $edit_flag = false;
	 if(jQuery("#cap_enable").attr("checked")=="checked"){
	   jQuery("#key-sec").show(500);
	   jQuery("#add_captcha_detail").removeAttr("disabled");
	 }
    jQuery('.editf').live('click',function(){
    $fieldid = jQuery(this).attr('field');
    	
	  if($edit_flag==true){
	     alert("edit action is blocked please update previous one or cancel previous one");
		 return;
	  }
	  $edit_flag = true;
    $data = "fieldid="+$fieldid+"&action=edit_field&class=ADMIN_OPTION_CLASS";	
	
	 jQuery("#waiting").css('display','block');
     sendAjaxRequest($data,'POST').success(function(response){
	  jQuery("#waiting").css('display','none');
	    jQuery('#'+$fieldid).html(response);
	 });
	 });
	 jQuery('#cancel').live('click',function(){
	    $fieldid = jQuery("#fieldid").val();
		$data = "cancelactionid="+$fieldid+"&action=canceledit&class=ADMIN_OPTION_CLASS";
		 jQuery("#waiting").css('display','block');
		sendAjaxRequest($data,'POST').success(function(response){
		 jQuery("#waiting").css('display','none');
		$edit_flag = false;
	    jQuery('#'+$fieldid).html(response);
	 });
	  });
	 jQuery("#update_field").live('click',function(){
	    $err="";
		$err_ct=0;
		$field_name = jQuery("#field_name");
		$field_type = jQuery("#field_type");
		$placeholder = jQuery("#placeholder");
		$fieldid = jQuery("#fieldid").val();
		$class = jQuery("#class").val();
		$enable=0;
		$required = 0;
		$callaction = jQuery("#update_form_field").val();
		if(jQuery("#enable").attr("checked")=="checked"){
		   $enable = jQuery("#enable").val();
		}
		if(jQuery("#is_required").attr("checked")=="checked"){
		  $required = jQuery("#is_required").val();
		}
		 if($field_name.val()==""){
		   jQuery("#name_error").html("please provide Field Name");
		   $err_ct++;
		 }
		 else{
		 jQuery("#name_error").html("");
		 }
		 if($field_type.val()=="select"){
		   jQuery("#type_error").html("please select Field Type");
		   $err_ct++;
		 }
		 else{
		 jQuery("#type_error").html("");
		 }
		 if($placeholder.val()==""){
		   jQuery("#plholder_error").html("please provide Field placeholder");
		   $err_ct++;
		 }
		 else{
		   jQuery("#plholder_error").html("");
		 }
		 if($err_ct>0){
		  return false;
		 }
		 else{
		   $formdata = "field_name="+$field_name.val()+"&field_type="+$field_type.val()+"&placeholder="+$placeholder.val()+"&enable="+$enable+"&is_required="+$required+"&class="+$class+"&fieldid="+$fieldid+"&action="+$callaction;
		    jQuery("#waiting").css('display','block');
		   sendAjaxRequest($formdata,"POST").success(function(response){
		    jQuery("#waiting").css('display','none');
			show_message("Field Updated");
		     jQuery("#"+$fieldid).html(response);  
			 $edit_flag = false;
		   });
		 }
	 });
	 jQuery("#cap_enable").click(function(){
	    if(jQuery(this).attr("checked")=="checked"){
		   jQuery("#key-sec").show(500); 
           jQuery("#add_captcha_detail").removeAttr("disabled");		   
		}
		else{
		  jQuery("#key-sec").hide(500);
		  jQuery("#add_captcha_detail").attr("disabled","disabled");
          $data = jQuery("#captcha_form").serialize();
		  sendAjaxRequest($data,"POST");
		}
	 });
	 jQuery("#add_captcha_detail").live("click",function(){
	     $err=0;
		 $site_key = jQuery("#site_key").val();
		 $secret_key = jQuery("#secret_key").val();
		 if($site_key==""){
		   jQuery("#site_key_err").html("please provide Site key");
		   $err++;
		 }
		 else{
		   jQuery("#site_key_err").html('');
		 }
		 if($secret_key==""){
		   jQuery("#secret_key_err").html("please provide secret key");
		   $err++;
		 }
		 else{
		   jQuery("#secret_key_err").html("");
		 }
		 if($err>0){
		  return false;
		 }else{
		   $formdata = jQuery("#captcha_form").serialize();
		    jQuery("#waiting").css('display','block');
		   sendAjaxRequest($formdata,"POST").success(function(response){
			   
		      jQuery("#waiting").css('display','none');
			  show_message("Captcha Credentials Updated");
			
		   });
		 }
	 });
	 jQuery("#preview").live("click",function(){
	    $data = "action=showPreviewForm&class=FRONTEND_CLASS";
		 jQuery("#waiting").css('display','block');
		sendAjaxRequest($data,"POST").success(function(response){
		 jQuery("#waiting").css('display','none');
		  jQuery('#pop-div').html(response); 
  		  jQuery('#pop-div').modal('','popupb');
		});
	 });
	 jQuery("#advanc-set-bt").live("click",function(){
	    $err=0;
	    $admin_email =  jQuery("#receiverid").val();
		   if($admin_email==""){
		      jQuery("#receiverid-err").html("please provide mail receiver ID");
			  $err++;
		   }
		   else{
		   jQuery("#receiverid-err").html("");
		   }
		$data_meth = jQuery("input[name='data-rec-met']");
		
		var mthd_flag = false; 
		 for($i=0;$i<$data_meth.length;$i++){
			       if(jQuery($data_meth[$i]).attr("checked")=="checked"){
				     mthd_flag = true;
					 break;
				   }
		}
		if(!mthd_flag){
		  jQuery("#data-rec-met-err").html("please select Data receiving method");
		  $err++;
		}else{
		jQuery("#data-rec-met-err").html("");
		}
		 if($err>0){
		    return false;
		 } 
		 else{
		     $advsetdata = jQuery("#form-advanced-setting").serialize();
			  jQuery("#waiting").css('display','block');
			 sendAjaxRequest($advsetdata,"POST").success(function(response){
			    jQuery("#waiting").css('display','none');
				show_message("Advanced Setting Updated");
		
			 });
		 }
		 
	 });
	 jQuery('.page-numbers').live('click',function(){
       jQuerypage = jQuery(this).attr('href');
	   jQuerypageind = jQuerypage.indexOf('page=');
	   jQuerypage = jQuerypage.substring((jQuerypageind+5));
       $data = "page="+jQuerypage+"&action=showNextPage&class=LEAD_CLASS";
	   jQuery("#waiting").css('display','block');
	   sendAjaxRequest($data,"POST").success(function(response){
	    jQuery("#waiting").css('display','none');
		   jQuery('#lead-sec').html(response);		
	   });
	return false;
	});
	jQuery('#delete-in-bulk').live('click',function(){
	  $selectedleads= jQuery(".lead-val");
	  $selectedids = "";
	  $select_flag = 0;
	  for($i=0;$i<$selectedleads.length;$i++){
	     if(jQuery($selectedleads[$i]).attr("checked")=="checked"){
		   $selectedids += "leadid[]="+jQuery($selectedleads[$i]).val()+"&"; 
		   $select_flag++;
		 } 
	  }	
	  if($select_flag>0){
	     $page = jQuery(this).attr("page");
		$data = $selectedids+"page="+$page+"&action=deleteLead&class=LEAD_CLASS";
		jQuery("#waiting").css('display','block');
        sendAjaxRequest($data,"POST").success(function(response){
		jQuery("#waiting").css('display','none');
		   jQuery('#lead-sec').html(response);		
		   show_message("Lead(s) deleted");
		});
	  }
	  else{
	    show_message("Please select atleast one Lead");
	  }
	});
	jQuery('.lead-delete').live('click',function(){
	   $page = jQuery(this).attr('page');
	   $leadid= jQuery(this).attr('lead');
	   $data = "leadid="+$leadid+"&page="+$page+"&action=deleteLead&class=LEAD_CLASS";
	   jQuery("#waiting").css('display','block');
	   sendAjaxRequest($data,"POST").success(function(response){
		jQuery("#waiting").css('display','none');
		   jQuery('#lead-sec').html(response);		
		   show_message("Lead deleted");
			
		});
	});
	function show_message(message){
	jQuery("#admin-msg").html(message);
			jQuery("#admin-msg").show(500);
			setTimeout(function(){ jQuery("#admin-msg").hide(500); }, 2000);
	}
   function sendAjaxRequest(data,method){
	     var url = jQuery('#ajaxreq').val();
	      return jQuery.ajax({
		      url: url,	
			type: method,
			data: data,		
			cache: false
		   });
	   }
});