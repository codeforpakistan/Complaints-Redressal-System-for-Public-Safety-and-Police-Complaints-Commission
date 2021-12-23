<?php
error_reporting(0);


defined('BASEPATH') OR exit('No direct script access allowed');


use \Firebase\JWT\JWT;


class Api extends CI_Controller {
    
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('GeneralModel');
		$this->load->model('ComplainantModel');
		$this->load->model('ComplaintModel');
	} 
	
	//==========================================================================
    // Token Session Check
    //==========================================================================
	
	private function check_session()
	{
	    $session = $this->AuthModel->user_token_validation();
        
        if(!$session)
        {
            $this->format_response('error','Login is required',[]);
        }
        else
        {
            return $session;
        }
	}
	
	//==========================================================================
    // Format Response
    //==========================================================================
	
	public function format_response($response_type,$response_msg,$data_arr)
	{
	    $return_arr = [];
	    
	    switch($response_type)
	    {
	        case 'success':
	            $return_arr['response'] = 1;
	            $return_arr['response_msg'] = $response_msg;
	            
	            if(count($data_arr) != 0)
	            {
	                 $return_arr['data'] = $data_arr;
	            }
	            
	        break;
	        
	        case 'error':
	            $return_arr['response'] = 0;
	            $return_arr['response_msg'] = $response_msg;
	            
	            if(count($data_arr) != 0)
	            {
	                array_push($return_arr,$data_arr);
	                // $return_arr['data'] = $data_arr;
	            }
	             
	        break;
	    }
	    
	    header('Content-Type: application/json');
        echo json_encode($return_arr);
        exit();
	}
    
    //==========================================================================
    // Auth
    //==========================================================================
    
    public function login_complainant()
    {
        //======================================================================
        // validation
        //======================================================================
        
        if(!$this->input->post('user_name'))
        {
            $this->format_response('error','user_name is required',[]);
        }
        
        if(!$this->input->post('user_password'))
        {
            $this->format_response('error','user_password is required',[]);
        }
        
        //======================================================================
        
        $data_arr = [];
        
        $data_arr['user_name']       = $this->input->post('user_name');
        $data_arr['user_password']   = $this->input->post('user_password');
        $data_arr['user_role_id_fk'] = 4; // AuthModel::role_id_complainant
       
        $complainant_login = $this->AuthModel->user_login($data_arr);
        
        if(is_object($complainant_login))
        {
            //==================================================================
            // find complainant id for token
            //==================================================================
            
            $find_complainant = $this->ComplainantModel->complainants_get(array('user_id_fk'=>$complainant_login->user_id));
            
            if(count($find_complainant) == 0)
            {
                $this->format_response('error','User is not linked with any complainant',[]);
            }
            
            //==================================================================
            
            $key = PRIVATE_KEY;
            $iat = time(); // current timestamp value
            $exp = $iat + 3600;
     
            $payload = array(
                                "iat"            => $iat, //Time the JWT issued at
                                "exp"            => $exp, // Expiration time of token
                                "user_id"        => $complainant_login->user_id,
                                "user_name"      => $complainant_login->user_name,
                                "complainant_id" => $find_complainant[0]['complainant_id']
                            );
             
            $token = JWT::encode($payload, $key, 'HS256');
                         
            $this->format_response('success','Logged-in Successfully',["token" => $token]);
        }
        else
        {
            $this->format_response('error','username or password is incorrect',[]);
        }
    }
    
    //==========================================================================
    // Complainant
    //==========================================================================
    
    public function complainant_registration()
    {
        
        $user_name     = $this->input->post('user_name');
        $user_contact  = $this->input->post('user_contact');
        $user_password = $this->input->post('user_password');
        
        if($user_name == '' || $user_contact == '' || $user_password == '')
        {
            $this->format_response('error','User name,contact & password are required fields',[]);
        }
        
        //======================================================================
        // check user duplication
        //======================================================================
        
        $data_arr_u = array('user_name'=>$user_name,'user_contact'=>$user_contact);
        
        $find_user_duplication = $this->AuthModel->users_get($data_arr_u);
        
        if(count($find_user_duplication))
        {
           $this->format_response('error','This user_name or user_contact already exists',[]);
        }
        
        //======================================================================
        // check complainant duplication
        //======================================================================
        
        $data_arr_c = array('complainant_contact'=>$user_contact); 
        
        $find_complainant_duplication = $this->ComplainantModel->complainants_get($data_arr_c);
        
        if(count($find_complainant_duplication))
        {
          $this->format_response('error','This contact has already registered as complainant',[]);
        }
        
        //======================================================================
        // insert user & pass user_id as forign-key to complainant table
        //======================================================================
        
        $data_arr_user = array(
                               'user_name'           => $user_name,
                               'user_password'       => $user_password,
                               'user_contact'        => $user_contact,
                               'user_role_id_fk'     => 4,
                               'user_status'         => 1,
                               'user_district_id_fk' => 0
                               );
        
        $insert_user = $this->AuthModel->user_add($data_arr_user);
        
        if($insert_user['response'] == 0)
        {
            $this->format_response('error','Failed to add user',[]);
        }
        else
        {
            $user_id = $insert_user['user_id'];
        }
            
        //======================================================================
        // add complainant
        //======================================================================
            
        $complainant_data_arr = array(
                                      'user_id_fk'                 => $user_id,
                                      'complainant_district_id_fk' => 0,
                                      'complainant_name'           => $user_name,
                                      'complainant_guardian_name'  => 0,
                                      'complainant_contact'        => $user_contact,
                                      'complainant_cnic'           => 0,
                                      'complainant_gender'         => 0,
                                      'complainant_email'          => 0,
                                      'complainant_union_council'  => 0,
                                      'complainant_address'        => 0,
                                      'complainant_status'         => 1
                                      );
        
        $insert_complainant = $this->ComplainantModel->complainant_add($complainant_data_arr);
            
        if($insert_complainant['response'] == 0)
        {
            $this->format_response('error',$insert_complainant['response_msg'],[]);
        }
        else
        {
            $complainant_id = $insert_complainant['complainant_id'];
            $this->format_response('success','Complainant Registered Successfully',array('user_id'=>$user_id,'complainant_id'=>$complainant_id));
        }
    }
    
    //==========================================================================
    // Profile Update
    //==========================================================================
    
    public function complainant_profile_update()
    {
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        
        //======================================================================
        
        if($session_user_id == 0 || $session_user_id == null || trim($session_user_id) == '')
        {
            $this->format_response('error','Logged-in user\'s id is required',[]);
        }
        
        //======================================================================
        //  Find user - check if this exists or not
        //======================================================================
        
        $find_user = $this->AuthModel->users_get(array('user_id'=>$session_user_id));
        
        if(count($find_user) == 0)
        {
            $this->format_response('error','This user_id does not exist',[]);
        }
        
        //======================================================================
        // Complainant-id extracted from logged-in user
        //======================================================================
        
        $data_arr_complainant = array('complainant_id'=>$session_complainant_id);
        
        //======================================================================
        // 1. columns which are allowed to be updated from complainant's side (android)
        // 2. pass only those columns to function which are sent by android 
        //======================================================================
        
        $allowed_columns = array('complainant_district_id_fk','complainant_name','complainant_guardian_name','complainant_contact','complainant_cnic','complainant_gender','complainant_email','complainant_union_council','complainant_address');
        
        foreach($allowed_columns as $key=>$value)
        {
            if($this->input->post($value) != false)
            {
                $data_arr_complainant[$value] = trim($this->input->post($value));
            }
        }
        
        //======================================================================
        // call model function to update complainant
        //======================================================================
        
        $complianant_update = $this->ComplainantModel->complainant_edit($data_arr_complainant);
        
        if($complianant_update['response'] == 0)
        {
            if(isset($complianant_update['response_msg']))
            {
                $msg = trim($complianant_update['response_msg']);
            }
            else
            {
                $msg = 'Failed to update complainant\'s profile';   
            }
            
            $this->format_response('error',$msg,[]);
        }
        else
        {
            $complainant_updated_info = $this->ComplainantModel->complainants_get(array('complainant_id'=>$session_complainant_id));
            $this->format_response('success','Complainant\'s profile updated successfully',array('complainant'=>$complainant_updated_info));
        }
    }
    
    //==========================================================================
    // Get Districts
    //==========================================================================
    
    public function districts_get()
    {
        $this->check_session();
        
        $districts_data = $this->GeneralModel->districts_get(array('district_status'=>'1'));
        
        if(count($districts_data) == 0)
        {
            $this->format_response('error','No districts found',[]);
        }
        else
        {
            $this->format_response('success','Districts Fetched Successfully',array('districts'=>$districts_data));
        }
    }
    
    //==========================================================================
    // Get Complaint categories
    //==========================================================================
    
    public function complaint_categories_get()
    {
        $this->check_session();
        
        $complaint_categories_data = $this->GeneralModel->complaint_categories_get(array('complaint_category_status'=>'1'));
        
        if(count($complaint_categories_data) == 0)
        {
            $this->format_response('error','No complaint categories found',[]);
        }
        else
        {
            $this->format_response('success','Complaint Categories Fetched Successfully',array('complaint_categories'=>$complaint_categories_data));
        }
    }
    
    //==========================================================================
    // Complainant Profile
    //==========================================================================
    
    public function complainant_profile()
    {
        $this->check_session();
        
        //==================================================================
        // find complainant 
        //==================================================================
            
        $find_complainant = $this->ComplainantModel->complainants_get(array('user_id_fk'=>$complainant_login->user_id));
            
        if(count($find_complainant) == 0)
        {
            $this->format_response('error','User is not linked with any complainant',[]);
        }
    }
    
    //==========================================================================
    // Complaint Register
    //==========================================================================
    
    public function complaint_register()
    {
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        
        $data_arr = [];
        
        $data_arr['complaint_source']         = 'complainant';
        $data_arr['complainant_id_fk']        = $session_complainant_id;
        $data_arr['registered_by_user']       = $session_user_id;
        $data_arr['district_id_fk']           = $this->input->post('district_id_fk');
        $data_arr['complaint_category_id_fk'] = $this->input->post('complaint_category_id_fk');
        $data_arr['complaint_detail']         = $this->input->post('complaint_detail');
        $data_arr['complaint_status']         = 'pending';
        
        //======================================================================
        // validation
        //======================================================================
        
        if(trim($data_arr['complaint_category_id_fk']) == '' || trim($data_arr['complaint_category_id_fk']) == '0')
        {
            $this->format_response('error','Complaint-Category Required',[]);
        }
        
        if(trim($data_arr['complaint_detail']) == '')
        {
            $this->format_response('error','Complaint-Detail Required',[]);
        }
        
        //======================================================================
        // proceed to register complaint
        //======================================================================
        
        $complaint_add_response = $this->ComplaintModel->complaint_add($data_arr);
        
        if($complaint_add_response['response'] == '1')
        {
            $this->format_response('success','Complainant Registered Successfully',array('complaint_id'=>$complaint_add_response['complaint_id']));
        }
        else
        {
            $this->format_response('error',$complaint_add_response['response_msg'],[]);
        }
    }
    
    //==========================================================================
    // Get Complaints
    //==========================================================================
    
    public function complainant_complaints()
    {
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        
        $complaint_data = $this->ComplaintModel->complaints_get(array('complainant_id_fk'=>$session_complainant_id));
        
        if(count($complaint_data) == 0)
        {
            $this->format_response('error','No complaints data',[]);
        }
        else
        {
            $this->format_response('success','Complaints Fetched Successfully',array('complaints'=>$complaint_data));
        }
    }
    
    //==========================================================================
    // Phone.no Validation
    //==========================================================================
    
    public function phone_validation()
    {
        if(!$this->input->post('complainant_contact'))
        {
            $this->format_response('error','Complainant\'s contact is required',[]);
        }
        
        $complainant_contact = $this->input->post('complainant_contact');
        
        if($complainant_contact != null && trim($complainant_contact) != '' && $complainant_contact != '0')
        {
            
            if(strlen($complainant_contact) == 11)
            {
                $user_data = $this->AuthModel->users_get(array('user_contact'=>trim($complainant_contact)));
                
                if(count($user_data) == 0)
                {
                    $this->format_response('error','No user found with this contact detail',[]);
                }
                else
                {
                    $complainant_data = $this->ComplainantModel->complainants_get(array('complainant_contact'=>trim($complainant_contact),'user_id_fk'=>$user_data[0]['user_contact']));
        
                    if(count($complainant_data) == 0)
                    {
                       $this->format_response('error','Provided contact is not linked',[]);
                    }
                    else
                    {
                        $this->format_response('success','Phone.no Verified',['complainant_id'=>$complainant_data[0]['complainant_id'],'user_id'=>$user_data[0]['user_contact']]);
                    }
                }
            }
            else
            {
                $this->format_response('error','Complainant\'s contact required format eg; 03331234567',[]);
            }
        }
        else
        {
            $this->format_response('error','Complainant\'s contact can not be empty',[]);
        }
    }
    
    //==========================================================================
    // Reset Password
    //==========================================================================
    
    public function reset_password()
    {
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        
        //======================================================================
        // current password validation 
        //======================================================================
        
        if(!$this->input->post('user_password'))
        {
            $this->format_response('error','Current Password is required',[]);
        }
        
        $user_password = trim($this->input->post('user_password'));
        
        $user_data = $this->AuthModel->users_get(array('user_id'=>$session_user_id,'user_password'=>$user_password),1);
        
        if(count($user_data) == 0)
        {
            $this->format_response('error','Incorrect current password',[]);
        }
        
        if($user_data[0]['user_role_id_fk'] != '4')
        {
            $this->format_response('error','This user has not registered as complainant',[]);
        }
        
        //======================================================================
        // new password validation 
        //======================================================================
        
        if(!$this->input->post('new_password'))
        {
            $this->format_response('error','New password is required',[]);
        }
        
        $new_password = trim($this->input->post('new_password'));
        
        if(strlen($new_password) < 5)
        {
            $this->format_response('error','New password length should not be less than five letters' ,[]);
        }
        
        //======================================================================
        // new password and old password comparison 
        //======================================================================
        
        if(md5($new_password) == md5($user_password))
        {
             $this->format_response('error','New-password & old-Password can not be same' ,[]);
        }
        
        //======================================================================
        // proceed to update
        //======================================================================
        
        $update_complainant = $this->AuthModel->user_edit(array('user_id'=>$session_user_id,'user_password'=>trim($new_password)));
        
        if($update_complainant['response'] == 0)
        {
            $this->format_response('error','Failed to reset password',[]);
        }
        else
        {
            $this->format_response('success','Password updated successfully',[]);
        }
    }
    
    //==========================================================================
    // Complainant Feedback on Complaint
    //==========================================================================
    
    public function complaint_feedback()
    {
        $this->check_session();
    }

}

?>