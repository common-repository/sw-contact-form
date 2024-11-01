<?php
Class ajaxRequest{
   private $r_data;
   private $callclass;
   function __construct($data){
     $this->r_data = $data;
	 $class = $this->r_data['class'];
	 $class = constant($class);
	 $this->callclass = new $class;
	 $this->init();	 
   }
   
   function init(){
     $callaction = $this->r_data['callaction'];
	 call_user_func(array($this,$callaction));
   }
   
  function edit_field(){
     $this->callclass->edit_form_field($this->r_data);
  }
  
  function update_form_field(){
     $this->callclass->update_form_field($this->r_data);
  }
  
  function canceledit(){
    $this->callclass->get_cancel_edit_data($this->r_data);
  }
  function showPreviewForm(){
   $this->callclass->showForm("preview");
  }
  function saveCaptchaCredential(){
    $this->callclass->saveCaptchaCredential($this->r_data);
  }
  function saveAdvancedSetting(){
    $this->callclass->save_advanced_form_data($this->r_data); 
 }
 function verifyCaptcha(){
      $this->callclass->verifyCaptchaCode($this->r_data);
   }
  function sendORSaveUserData(){
     $this->callclass->sendORSaveUserData($this->r_data); 
  } 
  function showNextPage(){
     $page = $this->r_data['page'];
	 $this->callclass->show_leads($page);
   }
   function deleteLead(){
     $this->callclass->deleteDataLeads($this->r_data);
   }
}

?>