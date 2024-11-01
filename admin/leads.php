<?php
Class CN_Formleads{
   private $dao;   
  function __construct(){
      global $wpdb;
     $this->dao = new CN_DAO($wpdb);
  }
  public function init(){
     echo "<div id='lead-sec'>";
     $this->show_leads(0);
  }
  public function show_leads($page){
    $fields = $this->dao->get_form_fields();
	echo "<table class='widefat'>";
	if($fields){
	    echo "<tr><th></th>";
	    foreach($fields as $field){
		  $field_name = strtoupper(substr($field->field_name,0,1)).substr($field->field_name,1);
		  echo "<th>$field_name</th>";
		}
	  echo "<th>Action</th></tr>";
	}
	$total_leads = $this->dao->get_variable("select count(*) from ".CN_FORM_DATA_TBL);
	if($page==0){
	  $start = 0;
	}
	else{
	  $start = ($page-1)*LEAD_ON_PAGE;
	}
	$leads = $this->dao->get_contentresult("select * from ".CN_FORM_DATA_TBL." LIMIT $start,".LEAD_ON_PAGE);
	  if(!$leads && $page>0){
	     $page = $page-1;
	     $start = ($page-1)*LEAD_ON_PAGE;
		 $leads = $this->dao->get_contentresult("select * from ".CN_FORM_DATA_TBL." LIMIT $start,".LEAD_ON_PAGE);
	  }
	if($leads){
	  foreach($leads as $lead){
	      $website = stripslashes($lead->website);
	    echo "<tr>
		    <td><input type='checkbox' value='$lead->ID' class='lead-val'></td>
		   <td>".stripslashes($lead->name)."</td>
		   <td><a href='mailto:".stripslashes($lead->email)."'>".stripslashes($lead->email)."</a></td>
		   <td><a href='".$this->addhttp($website)."' target='_blank'>".$website."</a></td>
		   <td><a href='tel:".stripslashes($lead->phone_no)."'>".stripslashes($lead->phone_no)."</td>
		   <td>".stripslashes($lead->message)."</td>
		   <td><a href='javascript:void' class='lead-delete' lead=$lead->ID page=$page>Delete</a></td>
		</tr>";
	  }
	  echo "</table><input type='hidden' id='ajaxreq' value='".admin_url('admin-ajax.php')."'>";
	$this->pagination(LEAD_ON_PAGE,ADJACENTS,$total_leads,$page);
	echo "<input type='button' class='button' page='$page' value='Delete in Bulk' id='delete-in-bulk'>";
	}
	
	else{
	     echo "<tr><td colspan='7'>No lead available</td></tr></table>";
	}
	
  }
  public function deleteDataLeads($data){
     $leadid = $data['leadid'];
    if(is_array($leadid)){
	  $leadstr = "(";
	  foreach($leadid as $id){
	    $leadstr.= stripslashes($id).",";
	  }
	  $indx = strrpos($leadstr,",");
	  $leadstr = substr($leadstr,0,$indx);
	  $leadstr.=")";
      $sql = "delete from ".CN_FORM_DATA_TBL." where ID in $leadstr";	  
	}
    else{
	   $leadid = stripslashes($leadid);
	  $sql = "delete from ".CN_FORM_DATA_TBL." where ID=$leadid";
	}	
	$this->dao->deletedata($sql);
	$page = stripslashes($data['page']);
	$this->show_leads($page);
  }
  
  private function pagination($limit,$adjacents,$rows,$page){	
	$pagination='';
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	
	$lastpage = ceil($rows/$limit);	
	
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a class='page-numbers' href=\"?page=$prev\">previous</a>";
		else{
			//$pagination.= "<span class=\"disabled\">previous</span>";	
			}
		
		//pages	
		if ($lastpage < 5 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 3 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
				}
				
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
				}
				
			}
			//close to end; only hide early pages
			else
			{
			
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a class='page-numbers' href=\"$?page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a class='page-numbers' href=\"?page=$next\">next</a>";
		else{
			//$pagination.= "<span class=\"disabled\">next</span>";
			}
		$pagination.= "</div>\n";		
	}

	echo $pagination;  
}
private function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
  }
  
$cnformlead = new CN_Formleads();

?>