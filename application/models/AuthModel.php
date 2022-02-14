<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');


use \Firebase\JWT\JWT;

class AuthModel extends CI_Model
{
    public static $role_id_complainant = 4;
    
    public function user_token_validation()
    {
        $token = null;
        
        if($this->input->post('token'))
        {
            $token = $this->input->post('token');
        }
        else
        {
            return false;
        }
        
        
        /// Decoding
        try 
        {
            $decoded = JWT::decode($token, PRIVATE_KEY, array('HS256'));
        }
        catch(Exception $e) 
        {
            // print_r($e);
            return false;
        }
        
        return $decoded;
    
    }
    
    public function user_login($data_arr)
    { 
        //======================================================================
        // query
        //======================================================================
        
        $this->db->select('users.*,user_roles.user_role_name')->from('users');
        $this->db->join('user_roles','user_roles.user_role_id=users.user_role_id_fk');
        $this->db->where('user_name',trim($data_arr['user_name']));
        $this->db->where('user_password',md5(trim($data_arr['user_password'])));
        // $this->db->where('user_role_id_fk',trim($data_arr['user_role_id_fk']));
        $this->db->where('user_status',1);
        
        //======================================================================
        // return
        //======================================================================
        
	    return $this->db->get()->row();
    }
    
    public function user_add($data_arr)
    {
        $required_fields = array('user_name'=>0,'user_password'=>0,'user_contact'=>0,'user_role_id_fk'=>0,'user_status'=>0,'user_district_id_fk'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'response_msg'=>'Required Fields: '.implode(", ",array_keys($missing)));
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
         
        $find_user = $this->db->query('select * from users where user_name = ? or user_contact = ? ',array($data_arr['user_name'],$data_arr['user_contact']))->result_array();
        
        if(count($find_user) > 0)
        {
            return array('response'=>0,'response_msg'=>'This user_name or user_contact already exists');
        }
        else
        {
            //==================================================================
            // insert user
            //==================================================================
            
            $data_arr['user_password'] = md5($data_arr['user_password']);
            $insert_user = $this->db->insert('users',$data_arr);
            
            if($insert_user != false)
            {
                $user_id = $this->db->insert_id();
                return array('response'=>1,'response_msg'=>'User Added Successfully','user_id'=>$user_id);
            }
            else
            {
                return array('response'=>0,'response_msg'=>'Failed to insert user');
            }
        }
    }
    
    public function users_get($data_arr)
    {
        $cond_query = '';
        $cond_arr = [];
        
        //======================================================================
        // set condition arr
        //======================================================================
        
        $optional_cond_arr = array('user_id','user_name','user_contact','user_email');
        
        foreach($optional_cond_arr as $key=>$value)
        {
            if(isset($data_arr[$value]))
            {
                $cond_row = ' and  '.$value.' = ?';
                
                $cond_query .= $cond_row;
                $cond_arr[$value] = trim($data_arr[$value]);
            }
        } 
        
        if(isset($data_arr['user_password']))
        {
            $cond_query .= ' and user_password = ?';
            $cond_arr['user_password'] = md5(trim($data_arr['user_password']));
        }
        
        //======================================================================
        // query
        //======================================================================
        
        $cond_query_formatted = ltrim(trim($cond_query),"and"); 

        $get_users = $this->db->query('select * from users where '.$cond_query_formatted,$cond_arr)->result_array();
        
        return $get_users;
        
    }
    
    
    public function user_edit($data_arr)
    {
        $required_fields = array('user_id'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'response_msg'=>'Required Fields: '.implode(", ",array_keys($missing)));
        }
        
        //======================================================================
        // optional update columns
        //======================================================================
        
        $data_arr_update = [];
        $set_query = '';
        
        $optional_update_cols = array('user_district_id_fk','user_name','user_contact','user_status','vcode');
        
        foreach($optional_update_cols as $key=>$value)
        {
           if(isset($data_arr[$value]))
           {
               $data_arr_update[$value] =  trim($data_arr[$value]);
               $set_query_row = ' ,  '.$value.' = ?';
               $set_query .= $set_query_row;
           }
        }
        
        //======================================================================
        // if password update
        //======================================================================
        
        if(isset($data_arr['user_password']))
        {
            $data_arr_update['user_password'] =  md5(trim($data_arr['user_password']));
            $set_query_row = ' , user_password = ?';
            $set_query .= $set_query_row;
            
            $user_get = $this->users_get(array('user_id'=>$data_arr["user_id"]));
            $user_info = $user_get[0];
            
            if(trim($user_info['user_password']) == md5(trim($data_arr['user_password'])))
            {
                return array('response'=>0,'response_msg'=>'New password can not be same as old one');
            }
        }
        
        //======================================================================
        // if not data sent for updation
        //======================================================================
        
        if(count($data_arr_update) == 0)
        {
            return array('response'=>0,'response_msg'=>'Select data to update');
        }
        
        //======================================================================
        // update query
        //======================================================================
        
        $set_query_formatted = ltrim(trim($set_query),","); 
        
        $data_arr_update['user_id'] = $data_arr['user_id'];
        
        // echo 'update users set '.$set_query_formatted.' where user_id = ?'; print_r($data_arr_update); exit();
        
        $update_user = $this->db->query('update users set '.$set_query_formatted.' where user_id = ? ',$data_arr_update);
        
        if($update_user == false)
        {
            return array('response'=>0,'response_msg'=>'Failed to update user#'.$data_arr["user_id"]);
        }
        else
        {
            return array('response'=>1,'data'=>array('response_msg'=>'User#'.$data_arr["user_id"].' Updated Successfully'));
        }
    
    }
}

?>