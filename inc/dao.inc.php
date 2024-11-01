<?php
Class CN_DAO{
 private $dao;
 function __construct($wpdb) {
  $this->dao = $wpdb;
 }
 function get_form_fields($id=''){
     if($id==''){
    $form_fields = $this->dao->get_results("select * from ".FORM_FIELD_TBL);
     if($form_fields){
	   return $form_fields;
	 }	
	 else{
	  return null;
	 }
	 }else{
	   $field = $this->dao->get_row("select * from ".FORM_FIELD_TBL." where ID=$id");
	   if($field){
	     return $field;
	   }
	   else{
	     return null;
	   }
	 }
 }
 function get_variable($variablequery){
   return $this->dao->get_var($variablequery);
 }
 function get_contentresult($query){
   return $this->dao->get_results($query);
 }
 function create_table($tablequery){
   $this->dao->query($tablequery);
 }
 function insertdata($insertquery){
   $this->dao->query($insertquery); 
   return $this->dao->insert_id;
 }
 function updatedata($updatequery){
   $this->dao->query($updatequery);
 }
 function deletedata($deletequery){
   $this->dao->query($deletequery); 
 }
 
 
}
?>