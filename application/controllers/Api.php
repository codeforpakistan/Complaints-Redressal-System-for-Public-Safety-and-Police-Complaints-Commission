<?php

error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
date_default_timezone_set("Asia/Karachi");

class Api extends CI_Controller {
    
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('ComplainantModel');
		$this->load->model('ComplaintModel');
		$this->load->model('DmlModel');
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
	
	
	public function format_response_2($response_type,$response_msg,$data_arr)
	{
	    $return_arr = [];
	    
	    switch($response_type)
	    {
	        case 'success':
	            $return_arr['response'] = 1;
	            $return_arr['response_msg'] = $response_msg;
	            
	            if(count($data_arr) != 0)
	            {
	                foreach($data_arr as $key => $key_data)
	                {
	                    $return_arr[$key] = $key_data;
	                }
	            }
	            
	        break;
	        
	        case 'error':
	            $return_arr['response'] = 0;
	            $return_arr['response_msg'] = $response_msg;
	            
	            if(count($data_arr) != 0)
	            {
	                array_push($return_arr,$data_arr);
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
     
            $payload = array(
                                "user_id"        => $complainant_login->user_id,
                                "user_name"      => $complainant_login->user_name,
                                "complainant_id" => $find_complainant[0]['complainant_id']
                            );
             
            $token = JWT::encode($payload, $key, 'HS256');
                         
            $this->format_response('success','Logged-in Successfully',["token" => $token]);
            
           // $this->format_response('success','Logged-in Successfully',[]);

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
        //======================================================================
        // Required Data Validation
        //======================================================================
        
        $check_required_values = array('user_name','user_contact','user_password','complainant_district_id_fk','complainant_email','complainant_name');
    
        foreach($check_required_values as $key=>$value)
        {
            if(!$this->input->post($value))
            {
                $this->format_response('error',$value.' is required',[]);
            }
        }
        
        //======================================================================
        
        $user_name     = $this->input->post('user_name');
        $user_contact  = $this->input->post('user_contact');
        $user_password = $this->input->post('user_password');
        
        if($user_name == '' || $user_contact == '' || $user_password == '')
        {
            $this->format_response('error','user_name, user_contact & user_password are required fields',[]);
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
        
        // $data_arr_c = array('complainant_contact'=>$user_contact); 
        
        // $find_complainant_duplication = $this->ComplainantModel->complainants_get($data_arr_c);
        
        // if(count($find_complainant_duplication))
        // {
        //   $this->format_response('error','This contact has already registered as complainant',[]);
        // }
        
        //======================================================================
        // insert user & pass user_id as forign-key to complainant table
        //======================================================================
        
        $data_arr_user = array(
                               'user_name'           => $user_name,
                               'user_password'       => $user_password,
                               'user_contact'        => $user_contact,
                               'user_role_id_fk'     => 4,
                               'user_status'         => 1,
                               'user_district_id_fk' => $this->input->post('complainant_district_id_fk'),
                               'user_email'          => $this->input->post('complainant_email'),
                               'user_first_name'     => $this->input->post('complainant_name'),
                               'user_address'        => $this->input->post('complainant_address')
                               );
        
        $insert_user = $this->AuthModel->user_add($data_arr_user);
        
        if($insert_user['response'] == 0)
        {
            $this->format_response('error',$insert_user['response_msg'],[]);
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
                                      'complainant_name'           => $user_name,
                                      'complainant_contact'        => $user_contact,
                                      'complainant_status'         => 1
                                      );
                                      
        //======================================================================
        // optional parameters
        //======================================================================
        
        $allowed_columns = array('complainant_name','complainant_cnic','complainant_gender','complainant_district_id_fk','complainant_guardian_name','complainant_email','complainant_council','complainant_address');
        
        foreach($allowed_columns as $key=>$value)
        {
            if($this->input->post($value) != false)
            {
                $complainant_data_arr[$value] = trim($this->input->post($value));
            }
        }
            
        //======================================================================
        // insert complainant
        //======================================================================
        
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
    // Complainant-Withdrawal
    //==========================================================================
    
    public function complaint_withdrawal()
    {
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        
        $data_arr = [];
        
        //======================================================================
        // Required Data Validation
        //======================================================================
        
        $check_required_values = array('complaint_id_fk','complaint_remarks_detail');
    
        foreach($check_required_values as $key=>$value)
        {
            if(!$this->input->post($value))
            {
                $this->format_response('error',$value.' is required',[]);
            }
            else
            {
                $data_arr[$value] = trim($this->input->post($value));
            }
        }
        
        //======================================================================
        // Complaint Authentication
        //======================================================================
        
        $find_complaint = $this->ComplaintModel->complaints_get(array('complaint_id'=>$data_arr['complaint_id_fk']));
            
        if(count($find_complaint) == 0)
        {
            $this->format_response_2('error','Complaint-id# '.$data_arr['complaint_id_fk'].' not found ',[]);
        }
        else
        {
            $complaint_info = $find_complaint[0];
            
            if($complaint_info['complainant_id_fk'] != $session_complainant_id)
            {
                $this->format_response_2('error','This complaint is not registered under your account',[]);
            }
            
            if($complaint_info['complaint_status_id_fk'] == '9')
            {
                $this->format_response_2('error','This complaint has already been closed by applicant',[]);
            }
        }
        
        //======================================================================
        // Set default values
        //======================================================================
        
        $data_arr['user_id_fk'] = $session_user_id;
        $data_arr['complaint_status_id_fk'] = 9; // 9 => closed by applicant
        $data_arr['respondent_id_fk'] = 0;
        $data_arr['attachment'] = null;
        $data_arr['complaint_remarks_timestamp'] = date('Y-m-d H:i:s',time()); // format => 2022-02-17 12:01:51
        $data_arr['complaint_remarks_status'] = 1;
        
        //======================================================================
        // Close/Withdraw complaint through remarks function
        //======================================================================
        
        $complaint_withdrawal = $this->ComplaintModel->complaint_remarks_add($data_arr);
         
        if($complaint_withdrawal['response'] == 0)
        {
            if(isset($complaint_withdrawal['response_msg']))
            {
                $msg = trim($complaint_withdrawal['response_msg']);
            }
            else
            {
                $msg = 'Failed to withdraw complaint';   
            }
            
            $this->format_response('error',$msg,[]);
        }
        else
        {
            $this->format_response_2('success','Complaint withdrawn successfully',[]);
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
        
        $allowed_columns = array('complainant_name','complainant_guardian_name','complainant_contact','complainant_cnic','complainant_gender','complainant_email','complainant_district_id_fk','complainant_address');
        
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
        // $this->check_session();
        
        //======================================================================
        // joins
        //======================================================================
        
        $join_arr = [];
        
        //======================================================================
        // cond
        //======================================================================
        
        $cond_arr = array('cond'=>[],'search'=>[]);
        
        if($this->input->post('district_id'))
        {
            $district_id = $this->input->post('district_id');
            $cond_arr['cond']['district_id'] = array($district_id,'E');
        }
        
        if($this->input->post('district_status'))
        {
            $district_status = $this->input->post('district_status');
            $cond_arr['cond']['district_status'] = array($district_status,'E');
        }
        
        if($this->input->post('district_name'))
        {
            $district_name = $this->input->post('district_name');
            $cond_arr['search']['district_name'] = array($district_name,'L','districts');
        }
        
        //======================================================================
        // order by
        //======================================================================
        
        $order_by_arr = [];
        
        if($this->input->post('order_by'))
        {
            $order_by = $this->input->post('order_by');
            $order_by_exploded = explode(':',$order_by);
            $order_by_column = $order_by_exploded[0];
            $order_by_value = $order_by_exploded[1];
            $order_by_arr = array('column'=>$order_by_column,'sequence'=>$order_by_value);
        }
        
        //======================================================================
        // limit
        //======================================================================
        
        $limit = false;
        
        if($this->input->post('limit'))
        {
            $limit = $this->input->post('limit');
        }
        
        //======================================================================
        // call function
        //======================================================================
        
        $data = $this->DmlModel->get('districts', $join_arr, $cond_arr, $order_by_arr, $limit);
        
        if(count($data) == 0)
        {
            $this->format_response('error','No districts found',[]);
        }
        else
        {
            $this->format_response_2('success','Districts Fetched Successfully',array('districts'=>$data));
        }
    }
    
    //==========================================================================
    // Get District-Councils
    //==========================================================================
    
    // public function district_councils_get()
    // {
    //     $this->check_session();  
        
    //     //======================================================================
    //     // joins
    //     //======================================================================
        
    //     $join_arr = [];
    //     array_push($join_arr,array('j_table'=>'districts','t_table'=>'district_councils','select_columns'=>array('district_name'=>0),'t_column'=>'district_id_fk','j_column'=>'district_id'));
        
    //     //======================================================================
    //     // cond
    //     //======================================================================
        
    //     $cond_arr = array('cond'=>[],'search'=>[]);
        
    //     if($this->input->post('district_id_fk'))
    //     {
    //         $district_id_fk = $this->input->post('district_id_fk');
    //         $cond_arr['cond']['district_id_fk'] = array($district_id_fk,'E');
    //     }
        
    //     if($this->input->post('district_council_id'))
    //     {
    //         $district_council_id = $this->input->post('district_council_id');
    //         $cond_arr['cond']['district_council_id'] = array($district_council_id,'E');
    //     }
        
    //     if($this->input->post('district_council_status'))
    //     {
    //         $district_council_status = $this->input->post('district_council_status');
    //         $cond_arr['cond']['district_council_status'] = array($district_council_status,'E');
    //     }
        
    //     if($this->input->post('district_council_name'))
    //     {
    //         $district_council_name = $this->input->post('district_council_name');
    //         $cond_arr['search']['district_council_name'] = array($district_council_name,'L','district_councils');
    //     }
        
    //     //======================================================================
    //     // order by
    //     //======================================================================
        
    //     $order_by_arr = [];
        
    //     if($this->input->post('order_by'))
    //     {
    //         $order_by = $this->input->post('order_by');
    //         $order_by_exploded = explode(':',$order_by);
    //         $order_by_column = $order_by_exploded[0];
    //         $order_by_value = $order_by_exploded[1];
    //         $order_by_arr = array('column'=>$order_by_column,'sequence'=>$order_by_value);
    //     }
        
    //     //======================================================================
    //     // limit
    //     //======================================================================
        
    //     $limit = false;
        
    //     if($this->input->post('limit'))
    //     {
    //         $limit = $this->input->post('limit');
    //     }
        
    //     //======================================================================
    //     // call function
    //     //======================================================================
        
    //     $data = $this->DmlModel->get('district_councils', $join_arr, $cond_arr, $order_by_arr, $limit);
        
    //     if(count($data) == 0)
    //     {
    //         $this->format_response('error','No district-councils found',[]);
    //     }
    //     else
    //     {
    //         $this->format_response_2('success','District-Councils Fetched Successfully',array('district_councils'=>$data));
    //     }
    // }
    
    //==========================================================================
    // Get Complaint categories
    //==========================================================================
    
    public function complaint_categories_get()
    {
        $this->check_session();  
        
        //======================================================================
        // joins
        //======================================================================
        
        $join_arr = [];
        
        //======================================================================
        // cond
        //======================================================================
        
        $cond_arr = array('cond'=>[],'search'=>[]);
        
        if($this->input->post('complaint_category_id'))
        {
            $complaint_category_id = $this->input->post('complaint_category_id');
            $cond_arr['cond']['complaint_category_id'] = array($complaint_category_id,'E');
        }
        
        if($this->input->post('complaint_category_status'))
        {
            $complaint_category_status = $this->input->post('complaint_category_status');
            $cond_arr['cond']['complaint_category_status'] = array($complaint_category_status,'E');
        }
        
        if($this->input->post('complaint_category_name'))
        {
            $complaint_category_name = $this->input->post('complaint_category_name');
            $cond_arr['search']['complaint_category_name'] = array($complaint_category_name,'L','complaint_categories');
        }
        
        //======================================================================
        // order by
        //======================================================================
        
        $order_by_arr = [];
        
        if($this->input->post('order_by'))
        {
            $order_by = $this->input->post('order_by');
            $order_by_exploded = explode(':',$order_by);
            $order_by_column = $order_by_exploded[0];
            $order_by_value = $order_by_exploded[1];
            $order_by_arr = array('column'=>$order_by_column,'sequence'=>$order_by_value);
        }
        
        //======================================================================
        // limit
        //======================================================================
        
        $limit = false;
        
        if($this->input->post('limit'))
        {
            $limit = $this->input->post('limit');
        }
        
        //======================================================================
        // call function
        //====================================================================== 
        // print_r($cond_arr); exit;
        
        $data = $this->DmlModel->get('complaint_categories', $join_arr, $cond_arr, $order_by_arr, $limit);
        
        if(count($data) == 0)
        {
            $this->format_response('error','No complaint-Categories found',[]);
        }
        else
        {
            $this->format_response_2('success','Complaint-Categories fetched successfully',array('complaint_categories'=>$data));
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
        
        $data_arr['complaint_source']         = 'mobile-app';
        $data_arr['complainant_id_fk']        = $session_complainant_id;
        $data_arr['registered_by_user']       = $session_user_id;
        $data_arr['district_id_fk']           = $this->input->post('district_id_fk');
        $data_arr['complaint_council']        = $this->input->post('complaint_council');
        $data_arr['complaint_category_id_fk'] = $this->input->post('complaint_category_id_fk');
        $data_arr['complaint_detail']         = $this->input->post('complaint_detail'); 
        $data_arr['complaint_status_id_fk']   = '1';
        
        //======================================================================
        // validation
        //======================================================================
        
        if(trim($data_arr['complaint_category_id_fk']) == '' || trim($data_arr['complaint_category_id_fk']) == '0')
        {
            $this->format_response('error','complaint_category_id_fk is required',[]);
        }
        
        if(trim($data_arr['complaint_detail']) == '')
        {
            $this->format_response('error','complaint_detail is required',[]);
        }
        
        //======================================================================
        // proceed to register complaint
        //======================================================================
        
        $complaint_add_response = $this->ComplaintModel->complaint_add($data_arr);
        
        if($complaint_add_response['response'] == '1')
        {
            $this->format_response_2('success','Complaint Registered Successfully',array('complaint_id'=>$complaint_add_response['complaint_id']));
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
        
        //======================================================================
        // conditions
        //======================================================================
        
        $cond_arr = array('complainant_id_fk'=>$session_complainant_id);
        
        if($this->input->post('complaint_status_id_fk'))
        {
            $complaint_status_id_fk = $this->input->post('complaint_status_id_fk');
            $cond_arr['complaint_status_id_fk'] = $complaint_status_id_fk;
        }
        
        if($this->input->post('complaint_category_id_fk'))
        {
            $complaint_category_id_fk = $this->input->post('complaint_category_id_fk');
            $cond_arr['complaint_category_id_fk'] = $complaint_category_id_fk;
        }
        
        if($this->input->post('district_id_fk'))
        {
            $district_id_fk = $this->input->post('district_id_fk');
            $cond_arr['district_id_fk'] = $district_id_fk;
        }
        
        //======================================================================
        
        $complaint_data = $this->ComplaintModel->complaints_get($cond_arr);
        
        if(count($complaint_data) == 0)
        {
            $this->format_response('error','No complaints data',[]);
        }
        else
        {
            $this->format_response_2('success','Complaints Fetched Successfully',array('complaints'=>$complaint_data));
        }
    }
    
    //==========================================================================
    // Phone.no Validation
    //==========================================================================
    
    public function phone_validation()
    {
        if(!$this->input->post('user_contact'))
        {
            $this->format_response('error','Complainant\'s contact is required',[]);
        }
        
        $user_contact = $this->input->post('user_contact');
        
        if($user_contact != null && trim($user_contact) != '' && $user_contact != '0')
        {
            
            if(strlen($user_contact) == 11)
            {
                $user_data = $this->AuthModel->users_get(array('user_contact'=>trim($user_contact)));
                
                if(count($user_data) == 0)
                {
                    $this->format_response('error','No user found with this contact.no',[]);
                }
                else
                {
                    if($user_data[0]['user_role_id_fk'] != '4')
                    {
                        $this->format_response('error','User not registered as applicant',[]);
                    }
                    else
                    {
                        $this->format_response_2('success','Phone.no Verified',['user_id'=>$user_data[0]['user_contact']]);
                    }
                }
            }
            else
            {
                $this->format_response('error','User\'s contact required format eg; 03331234567',[]);
            }
        }
        else
        {
            $this->format_response('error','User\'s contact can not be empty',[]);
        }
    }
    
    //==========================================================================
    // Signup Validation
    //==========================================================================
    
    public function signup_validation()
    {
        $check_required_values = array('user_name','user_contact');
    
        foreach($check_required_values as $key=>$value)
        {
            if(!$this->input->post($value))
            {
                $this->format_response('error',$value.' is required',[]);
            }
            else
            {
                if(trim($this->input->post($value)) == '' || trim($this->input->post($value)) == null)
                {
                    $this->format_response('error',$value.' can not be empty/null',[]);
                }
                else
                {
                    $data_arr[$value] = trim($this->input->post($value));
                }
            }
        }
        
        //======================================================================
        // check user_contact format
        //======================================================================
        
        if(strlen($data_arr['user_contact']) != 11)
        {
            $this->format_response('error','user_contact required format eg; 03331234567',[]);
        }
        
        //======================================================================
        // user_contact validation
        //======================================================================
               
        $user_data_1 = $this->AuthModel->users_get(array('user_contact'=>$data_arr['user_contact']));
                
        if(count($user_data_1) != 0)
        {
            $this->format_response('error','This contact already exist',[]);
        }
        
        //======================================================================
        // user_name validation
        //======================================================================
        
        $user_data_2 = $this->AuthModel->users_get(array('user_name'=>$data_arr['user_name']));
                
        if(count($user_data_2) != 0)
        {
            $this->format_response('error','This user_name already exist',[]);
        }
               
        //======================================================================
        
        $this->format_response_2('success','Proceed to signup, user_name & contact is unique',[]);
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
            $this->format_response('error','Current Password is required i-e; user_password',[]);
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
            $this->format_response('error','New password is required i-e; new_password',[]);
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
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        
        //======================================================================
        // Required Data Validation
        //======================================================================
        
        $check_required_values = array('complaint_id_fk','complaint_remarks_detail');
    
        foreach($check_required_values as $key=>$value)
        {
            if(!$this->input->post($value))
            {
                $this->format_response('error',$value.' is required',[]);
            }
            else
            {
                $data_arr[$value] = $this->input->post($value);
            }
        }
        
        $data_arr['user_id_fk'] = $session_user_id;
        $data_arr['respondent_id_fk'] = 0;
        $data_arr['complaint_status_id_fk'] = 7; // from datatable -> complaint_statuses (7 => complainant feedback)
        $data_arr['complaint_remarks_timestamp'] = date('Y-m-d',time()).' 00:00:00'; // 2021-12-28 00:00:00;
        $data_arr['complaint_remarks_status'] = 1;
        
        //======================================================================
        // Validate Complaint_id
        //======================================================================
        
        if($data_arr['complaint_id_fk'] == 0 || $data_arr['complaint_id_fk'] == null || trim($data_arr['complaint_id_fk']) == '')
        {
            $this->format_response('error','Kindly provide complaint-id',[]);
        }
        else
        {
            $complaint_data = $this->ComplaintModel->complaints_get(array('complaint_id'=>$data_arr['complaint_id_fk'],'complainant_id_fk'=>$session_complainant_id));
        
            if(count($complaint_data) == 0)
            {
                $this->format_response('error','This complaint has not been registered under your account.',[]);
            }
        }
        
    
        //======================================================================
        // Call Function
        //======================================================================
        
        $remarks_add_response = $this->ComplaintModel->complaint_remarks_add($data_arr);
        
        if($remarks_add_response['response'] == 1)
        {
            $this->format_response_2('success','Your feedback submitted successfully',['complaint_remarks_id'=>$remarks_add_response['complaint_remarks_id']]);
        }
        else
        {
            $this->format_response('error',$remarks_add_response['response_msg'],[]);
        }
        
    }
    
    //==========================================================================
    // Get Profile
    //==========================================================================
    
    public function profile_get()
    {
        $session_info = $this->check_session();
        $session_user_id = $session_info->user_id;
        $session_complainant_id = $session_info->complainant_id;
        $data_arr = [];
        
        //======================================================================
        // get complainant info
        //======================================================================
        
        $find_complainant = $this->ComplainantModel->complainants_get(array('complainant_id'=>$session_complainant_id));
            
        if(count($find_complainant) == 0)
        {
            $this->format_response_2('error','User is not linked with any complainant',[]);
        }
        else
        {
            $complainant_info = $find_complainant[0];
            
            $data_arr['complainant_name']           = $complainant_info['complainant_name'];
            $data_arr['complainant_cnic']           = $complainant_info['complainant_cnic'];
            $data_arr['complainant_gender']         = $complainant_info['complainant_gender'];
            $data_arr['complainant_guardian_name']  = $complainant_info['complainant_guardian_name'];
            $data_arr['complainant_email']          = $complainant_info['complainant_email'];
            $data_arr['complainant_district_id_fk'] = $complainant_info['complainant_district_id_fk' ];
            $data_arr['complainant_district_name']  = $complainant_info['district_name' ];
            $data_arr['complainant_council']        = $complainant_info['complainant_council' ]; 
            $data_arr['complainant_address']        = $complainant_info['complainant_address'];  
            $data_arr['complainant_contact']        = $complainant_info['complainant_contact'];
            
            if($complainant_info['user_id_fk'] == '0' || trim($complainant_info['user_id_fk']) == '')
            {
                $this->format_response_2('error','User Account is not linked with this complainant',['user_id'=>$complainant_info['user_id_fk']]);
            }
            else
            {
                $find_user = $this->AuthModel->users_get(array('user_id'=>$complainant_info['user_id_fk']));
        
                if(count($find_user) == 0)
                {
                    $this->format_response_2('error','Account info does not exist',[]);
                }
                else
                {
                    $user_info = $find_user[0];
                    $data_arr['user_name'] = $user_info['user_name'];
                }
            }
            
            $this->format_response_2('success','Profile Fetched Successfully',['user_profile'=>$data_arr]);
        }
    
    }
    
    //==========================================================================
    // Forget Passsword Reset - Step 1
    //==========================================================================
    
    public function verify_account()
    {
        //======================================================================
        // Required Data Validation
        //======================================================================
        
    
        if(!$this->input->post('verification_source'))
        {
            $this->format_response_2('error','verification_source is required',[]);
        }
        else
        {
            $verification_source = $this->input->post('verification_source');
        }
        
        //======================================================================
        
        $cond_arr = [];
        
        switch($verification_source)
        {
            case 'sms':
                
                if(!$this->input->post('user_contact'))
                {
                    $this->format_response_2('error','user_contact is required ',[]);
                }
                
                $user_contact = $this->input->post('user_contact');
                $cond_arr['user_contact'] = trim($user_contact);
                
            break;
            
            case 'email':
                
                if(!$this->input->post('user_email'))
                {
                    $this->format_response_2('error','user_email is required ',[]);
                }
                
                $user_email = $this->input->post('user_email');
                $cond_arr['user_email'] = trim($user_email);
                
            break;
            
            default:
                $this->format_response_2('error','verification_source can be sms/email ',[]);
            break;
        }
        
        //======================================================================
        
        $find_user = $this->AuthModel->users_get($cond_arr);
        
        if(count($find_user) == 0)
        {
            $this->format_response_2('error','So such account exist',[]);
        }
        else
        {
            $user_info = $find_user[0];
            
            if($user_info['user_role_id_fk'] != '4')
            {
                $this->format_response_2('error','Android app is only for applicants',[]);
            }

            //==================================================================
            
            $otp = substr(str_shuffle(time()), 0, 6);
            
            switch($verification_source)
            {
                case 'sms':
                    // send otp on user's mobile
                    $this->format_response_2('success','There is no sms api integrated, kindly try verifying through email',[]);
                break;
                
                case 'email':
                    
                    $update_user = $this->AuthModel->user_edit(array('user_id'=>$user_info['user_id'],'vcode'=>trim($otp)));
        
                    if($update_user['response'] == 0)
                    {
                        $this->format_response('error','Failed to set vcode',[]);
                    }
                    else
                    {
                        $htmlContent = "
                                        <p>Hi, ".$user_info['user_name'].", </p>
                                        <p>We received a request to reset your password through your email address. Your PPSC verification code is: </p>
                                        <h2>".$otp."</h2>
                                        <p>
                                            If you did not request this code, it is possible that someone else is trying to access your PPSC Account. 
                                            <b>Do not forward or give this code to anyone.</b>
                                        </p>
                                        <a href='https://ppsc.kp.gov.pk'> https://ppsc.kp.gov.pk </a>
                                       ";
                        
                        $this->load->library('email');
                        $this->email->from('noreply@ppsc.kp.gov.pk', 'PPSC');
                        $this->email->to($user_info['user_email']);
                        $this->email->subject('PPSC Verification Code');
                        $this->email->message($htmlContent); 
                        $this->email->set_mailtype("html");
                        
                        if($this->email->send())
                        {
            				$this->format_response_2('success','Kindly check your email for verification code',[]);
            			}
                        else
                        {
            				$this->format_response_2('error','Failed to send verification code on email. Try again',[]);
                        }
                    }
                    
                
                break;
            }
        }
    }
    
    //==========================================================================
    // Forget Passsword Reset - Step 2
    //==========================================================================
    
    public function forget_password_reset()
    {
        //======================================================================
        // Required Data Validation
        //======================================================================
    
        $check_required_values = array('verification_source','vcode','new_password');
    
        foreach($check_required_values as $key=>$value)
        {
            if(!$this->input->post($value))
            {
                $this->format_response_2('error',$value.' is required',[]);
            }
        }
        
        //======================================================================
        // verification-source validation
        //======================================================================
        
        $cond_arr = [];
    
        $verification_source = trim($this->input->post('verification_source'));
            
        switch($verification_source)
        {
            case 'sms':
                    
                if(!$this->input->post('user_contact'))
                {
                    $this->format_response_2('error','user_contact is required ',[]);
                }
                    
                $user_contact = $this->input->post('user_contact');
                $cond_arr['user_contact'] = trim($user_contact);
                    
            break;
                
            case 'email':
                    
                if(!$this->input->post('user_email'))
                {
                    $this->format_response_2('error','user_email is required ',[]);
                }
                    
                $user_email = $this->input->post('user_email');
                $cond_arr['user_email'] = trim($user_email);
                    
            break;
            
            default:
                $this->format_response_2('error','verification_source can be sms/email ',[]);
            break;
        }
        
        //======================================================================
        //  proceed to validate vcode
        //======================================================================
        
        $find_user = $this->AuthModel->users_get($cond_arr);
        
        if(count($find_user) == 0)
        {
            $this->format_response_2('error','So such account exist',[]);
        }
        else
        {
            $user_info = $find_user[0];
            
            if(trim($user_info['vcode']) != trim($this->input->post('vcode')))
            {
                $this->format_response_2('error','Incorrect verification code ',[]);
            }
            else
            {
                $new_password = $this->input->post('new_password');
                
                $update_user = $this->AuthModel->user_edit(array('user_id'=>$user_info['user_id'],'user_password'=>trim($new_password)));
        
                if($update_user['response'] == 0)
                {
                    $this->format_response('error',$update_user['response_msg'],[]);
                }
                else
                {
                    $this->format_response('success','Password updated successfully',[]);
                }
            }
        }
        
        
    }

}

?>