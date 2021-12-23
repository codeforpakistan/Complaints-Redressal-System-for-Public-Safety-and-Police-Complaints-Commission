<?php
error_reporting(0);


defined('BASEPATH') OR exit('No direct script access allowed');


class Api extends CI_Controller {
    
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('AuthModel');
	} 
	
	public function format_response($response_type,$data_arr)
	{
	    $return_arr = [];
	    
	    switch($response_type)
	    {
	        case 'success':
	            $return_arr['response'] = 1;
	            $return_arr['data'] = $data_arr;
	        break;
	        
	        case 'error':
	            $return_arr['response'] = 0;
	            $return_arr['data'] = $data_arr;
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
            $this->format_response('error',array('response_msg'=>'user_name is required'));
        }
        
        if(!$this->input->post('user_password'))
        {
            $this->format_response('error',array('response_msg'=>'user_password is required'));
        }
        
        //======================================================================
        
        $data_arr = [];
        
        $data_arr['user_name']       = $this->input->post('user_name');
        $data_arr['user_password']   = $this->input->post('user_password');
        $data_arr['user_role_id_fk'] = 4; // AuthModel::role_id_complainant
       
        $complainant_login = $this->AuthModel->user_login($data_arr);
        
        if(is_object($complainant_login))
        {
            $data = array(
                            'user_id'     => $complainant_login->user_id,
                            'user_name'   => $complainant_login->user_name,
                            'user_status' => $complainant_login->user_status
                         );
                         
            $this->format_response('success',$data);
        }
        else
        {
            $this->format_response('error',array('response_msg'=>'username or password is incorrect'));
        }
    }
    
    //==========================================================================
    // Complainant
    //==========================================================================
    
    public function complainant_registration()
    {
        $this->load->model('ComplainantModel');
        
        $user_name     = $this->input->post('user_name');
        $user_contact  = $this->input->post('user_contact');
        $user_password = $this->input->post('user_password');
        
        if($user_name == '' || $user_contact == '' || $user_password == '')
        {
            $this->format_response('error',array('response_msg'=>'User name,contact & password are required fields'));
        }
        
        //======================================================================
        // check user duplication
        //======================================================================
        
        $data_arr_u = array('user_name'=>$user_name,'user_contact'=>$user_contact);
        
        $find_user_duplication = $this->AuthModel->users_get($data_arr_u);
        
        if(count($find_user_duplication))
        {
           $this->format_response('error',array('response_msg'=>'This user_name or user_contact already exists'));
        }
        
        //======================================================================
        // check complainant duplication
        //======================================================================
        
        $data_arr_c = array('complainant_contact'=>$user_contact); 
        
        $find_complainant_duplication = $this->ComplainantModel->complainants_get($data_arr_c);
        
        if(count($find_complainant_duplication))
        {
          $this->format_response('error',array('response_msg'=>'This contact has already registered as complainant'));
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
            $this->format_response('error',array('response_msg'=>'Failed to add user'));
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
            $this->format_response('error',$insert_complainant['data']);
        }
        else
        {
            $complainant_id = $insert_complainant['complainant_id'];
            $this->format_response('success',array('response_msg'=>'Complainant Registered Successfully','user_id'=>$user_id,'complainant_id'=>$complainant_id));
        }
    }
    
    
    public function complainant_profile_update()
    {
        $this->load->model('ComplainantModel');
        
        $user_id = $this->input->post('user_id'); // logged-in user's id
        
        if($user_id == 0 || $user_id == null || trim($user_id) == '')
        {
            $this->format_response('error',array('response_msg'=>'Logged-in user\'s id is required'));
        }
        
        //======================================================================
        //  Find user - check if this exists or not
        //======================================================================
        
        $find_user = $this->AuthModel->users_get(array('user_id'=>$user_id));
        
        if(count($find_user) == 0)
        {
            $this->format_response('error',array('response_msg'=>'This user_id does not exist'));
        }
        
        //======================================================================
        //  Find Complainant-id of Logged-in User
        //======================================================================
        
        $data_arr_c = array('user_id_fk'=>$user_id);
        
        $find_complainant = $this->ComplainantModel->complainants_get($data_arr_c);
        
        if(count($find_complainant) != 0)
        {
          $this->format_response('error',array('response_msg'=>'Given user_id #'.$user_id.' is not connected to complainant'));
        }
        
        if(count($find_complainant) > 1)
        {
          $this->format_response('error',array('response_msg'=>'More than one complainant connected with given user_id'));
        }
        
        //======================================================================
        // Complainant-id extracted from logged-in user
        //======================================================================
        
        $complainant_id = $find_complainant['complainant_id'];
        $data_arr_complainant = array('complainant_id'=>$complainant_id);
        
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
        
        print_r($data_arr_complainant);
        
        $complianant_update = $this->ComplainantModel->complainant_edit($data_arr_complainant);
        
        if($complianant_update['response'] == 0)
        {
            $this->format_response('success',array('response_msg'=>'Failed to update complainant\'s profile'));
        }
        else
        {
            $find_complainant_afterUpdate = $this->ComplainantModel->complainants_get(array('complainant_id'=>$complainant_id));
            $this->format_response('success',array('response_msg'=>'Complainant\'s profile updated successfully',$find_complainant_afterUpdate));
        }
        
    }
    
    //==========================================================================
    // Complaints
    //==========================================================================
    
    public function complaint_register()
    {
        
    }
    
    public function complaints_view()
    {
        
    }
    
    public function complaint_edit()
    {
        
    }
    
    public function complaint_feedback()
    {
        
    }

}

?>