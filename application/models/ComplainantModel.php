<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');

class ComplainantModel extends CI_Model
{
    
    public function complainant_add($data_arr)
    {
        $required_fields = array('user_id_fk'=>0,'complainant_district_id_fk'=>0,'complainant_name'=>0,'complainant_guardian_name'=>0,'complainant_contact'=>0,'complainant_cnic'=>0,'complainant_gender'=>0,'complainant_email'=>0,'complainant_union_council'=>0,'complainant_address'=>0,'complainant_status'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Required Fields: '.implode(", ",array_keys($missing))));
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
         
        $find_complainant = $this->db->query('select * from complainants where complainant_contact = ?',array($data_arr['complainant_contact']))->result_array();
        
        if(count($find_complainant) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'This contact has already registered as complainant'));
        }
        else
        {
            //==================================================================
            // insert complainant
            //==================================================================
            
            $insert_complainant = $this->db->insert('complainants',$data_arr);
            
            if($insert_complainant != false)
            {
                $complainant_id = $this->db->insert_id();
                return array('response'=>1,'response_msg'=>'Complainant Added Successfully','complainant_id'=>$complainant_id);
            }
            else
            {
                return array('response'=>0,'response_msg'=>'Failed to insert complainant');
            }
        }
    }
    
    public function complainants_get($data_arr)
    {
        
        $cond_query = '';
        $cond_arr = [];
        
        //======================================================================
        // set condition arr
        //======================================================================
        
        $optional_cond_arr = array('complainant_id','complainant_name','complainant_contact','complainant_cnic','complainant_email','user_id_fk');
        
        foreach($optional_cond_arr as $key=>$value)
        {
            if(isset($data_arr[$value]))
            {
                $cond_row = ' or  '.$value.' = ?';
                
                $cond_query .= $cond_row;
                $cond_arr[$value] = trim($data_arr[$value]);
            }
        }
        
        //======================================================================
        // query
        //======================================================================
        
        $cond_query_formatted = ltrim(trim($cond_query),"or"); 
        $get_complainants = $this->db->query('select * from complainants where '.$cond_query_formatted,$cond_arr)->result_array();
        return $get_complainants;
    }
    
    public function complainant_edit($data_arr)
    {
        $required_fields = array('complainant_id'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Required Fields: '.implode(", ",array_keys($missing))));
        }
        
        //======================================================================
        // optional update columns
        //======================================================================
        
        $data_arr_update = [];
        $set_query = '';
        
        $optional_update_cols = array('complainant_district_id_fk','complainant_name','complainant_guardian_name','complainant_contact','complainant_cnic','complainant_gender','complainant_email','complainant_union_council','complainant_address');
        
        foreach($optional_update_cols as $key=>$value)
        {
           if(isset($data_arr[$value]))
           {
               $data_arr_update[$value] =  trim($data_arr[$value]);
               $set_query_row = ' ,  '.$value.' = ?';
               $set_query .= $set_query_row;
           }
        }
        
        $data_arr_update['complainant_id'] = $data_arr['complainant_id'];
        
        //======================================================================
        // update query
        //======================================================================
        
        $set_query_formatted = ltrim(trim($set_query),","); 
        
        // echo 'update complainant set '.$set_query_formatted;
        
        $update_complainant = $this->db->query('update complainant set '.$set_query_formatted.' where complainant_id = ? ',$data_arr_update);
        
        if($update_complainant == false)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Failed to update complainant#'.$data_arr["complainant_id"]));
        }
        else
        {
            return array('response'=>1,'data'=>array('response_msg'=>'Complainant#'.$data_arr["complainant_id"].' Updated Successfullt'));
        }
    
    }
}

?>