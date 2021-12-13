<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');

class AuthModel extends CI_Model
{
    public static $role_id_complainant = 4;
    
    public function user_login_check()
    {
    
    }
    
    public function user_login($data_arr)
    { 
        
            $this->db->select('users.*,user_roles.user_role_name')->from('users');
            $this->db->join('user_roles','user_roles.user_role_id=users.user_role_id_fk');
            $this->db->where('user_name',trim($data_arr['user_name']));
            $this->db->where('user_password',md5(trim($data_arr['user_password'])));
            $this->db->where('user_role_id_fk',trim($data_arr['user_role_id_fk']));
            $this->db->where('user_status',1);
	return  $this->db->get()->row();
    }
    
    public function user_add($data_arr)
    {
        $required_fields = array('user_name'=>0,'user_password'=>0,'user_contact'=>0,'user_role_id_fk'=>0,'user_status'=>0,'user_district_id_fk'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Required Fields: '.implode(", ",array_keys($missing))));
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
         
        $find_user = $this->db->query('select * from users where user_name = ? or user_contact = ? ',array($data_arr['user_name'],$data_arr['user_contact']))->result_array();
        
        if(count($find_user) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'This user_name or user_password already exists'));
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
        
        $optional_cond_arr = array('user_id','user_name','user_contact');
        
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
        
        $get_users = $this->db->query('select * from users where '.$cond_query_formatted,$cond_arr)->result_array();
        
        return $get_users;
        
    }
    
    public function user_edit()
    {
        
    }			
}

?><?php 

//======================================================================
// sadam code
//======================================================================

// if (!defined('BASEPATH'))	exit('No direct script access allowed');

// class AuthModel extends CI_Model
// {
    
// 	public function checkUser($array)
// 	{      
// 		$sql= $this->db->select('*')->from('users')->where($array)->get()->row();
//      	return $sql;
// 	}
	
//     function insertRecord($array)
// 	{
//       return $this->db->insert('users',$array);
// 	}
	
// 	function logincheck($username,$password)
// 	{
// 		$this->db->select('*')->from('users');
// 		$this->db->where('user_name',$username);
// 		$this->db->where('user_password',md5($password));
// 		$this->db->where('user_status',1);
// 		return $this->db->get()->row(); 
// 	}
// 	function logincheckByContact($contact)
// 	{
// 		$this->db->select('*')->from('users');
// 		$this->db->where('user_contact',$contact);
// 		$this->db->where('user_status',1);
// 		return $this->db->get()->row();
// 	}

// 	function logincheckByUsername($username)
// 	{
// 		$this->db->select('*')->from('users');
// 		$this->db->where('user_name',$username);
// 		$this->db->where('user_status',1);
// 		return $this->db->get()->row();
// 	}
				
// }

?>