<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');


use \Firebase\JWT\JWT;

class GeneralModel extends CI_Model
{
    
    public function districts_get($data_arr)
    {
        $cond_query = '';
        $cond_arr = [];
        
        //======================================================================
        // set condition arr
        //======================================================================
        
        $optional_cond_arr = array('district_id','district_name','district_status');
        
        foreach($optional_cond_arr as $key=>$value)
        {
            if(isset($data_arr[$value]))
            {
                $cond_row = ' and  '.$value.' = ?';
                
                $cond_query .= $cond_row;
                $cond_arr[$value] = trim($data_arr[$value]);
            }
        } 
        //======================================================================
        // query
        //======================================================================
        
        $cond_query_formatted = ltrim(trim($cond_query),"and"); 
        
        $get_districts = $this->db->query('select * from districts where '.$cond_query_formatted,$cond_arr)->result_array();
        return $get_districts;
        
    }
    
    public function complaint_categories_get($data_arr)
    {
        $cond_query = '';
        $cond_arr = [];
        
        //======================================================================
        // set condition arr
        //======================================================================
        
        $optional_cond_arr = array('complaint_category_id','complaint_category_name','complaint_category_status');
        
        foreach($optional_cond_arr as $key=>$value)
        {
            if(isset($data_arr[$value]))
            {
                $cond_row = ' and  '.$value.' = ?';
                
                $cond_query .= $cond_row;
                $cond_arr[$value] = trim($data_arr[$value]);
            }
        } 
        //======================================================================
        // query
        //======================================================================
        
        $cond_query_formatted = ltrim(trim($cond_query),"and"); 
        
        $get_complaint_categories = $this->db->query('select * from complaint_categories where '.$cond_query_formatted,$cond_arr)->result_array();
        return $get_complaint_categories;
    }
}

?>