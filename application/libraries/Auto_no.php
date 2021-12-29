<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auto_no{
	
	function get_auto_num($type,$auto_no)
	{
			$CI =& get_instance();
			
			$CI->load->model('MyModel');

			$mx_no = $CI->MyModel->get_mx_num($type); //in argument table name is required also the table id
			$patteron      = $CI->MyModel->get_patteron($type); // in argument patteron type is
			 
			$no            = $mx_no['auto_no']+1; 
			$total = $patteron['limit'];
			/*start for article auto number prefix */ 
			if($patteron['type_name'] == "article_no"){
			$prefix = substr($patteron['prefix'], 0, 4);
			if(strlen($no) == 3){
			 $ditits = '00'.$no;
			}else{
				$ditits = '0'.$no;
			}
			$auto_no = $prefix.$ditits.$patteron['sufex']; 
			return($auto_no);
			}
			/*end for article auto number prefix */
			
			$prefix = substr($patteron['prefix'], 0, $total - isset($current_limit));
			$auto_no = $prefix.$no.$patteron['sufex']; 
			return($auto_no);
			//view($patient_no,1);
	}
	
}

