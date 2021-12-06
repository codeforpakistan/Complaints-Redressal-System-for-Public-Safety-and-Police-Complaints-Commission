<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {
    
    
    public function __construct()
	{
		parent::__construct();
		
		// if($this->session->userdata('IsSuperAdminLogin'))
		// {
			$this->load->model('AdminModel','model');
            $this->load->library('auto_no.php','zend');
            $this->load->library('form_validation');
		//}
		// else
		// {	
		// 	redirect(base_url());
		// }
	} 
	
    //==========================================================================
    // Auth
    //==========================================================================
    public function index()
    { 
        $this->load->view('authLogin');
    }
    
    public function login_user()
    {
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect(base_url());
            
        }
        else
        {
            $username = $this->input->post('user_name');
            $password = $this->input->post('user_password');
            $array    = array('user_name'=>$username,'user_password'=>$password,'user_status'=>1,'user_role_id_fk'=>1);
            $response = $this->AuthModel->user_login($array); 
            if(!empty($response))// is user name and passsword valid
			   {
					if($response->user_role_id_fk == '1')
					{	
                            $this->session->set_userdata('IsSuperAdminLogin',$response->user_role_id_fk);					
                            $this->session->set_userdata('AdminId',$response->user_id);
                            $this->session->set_userdata('username',$response->user_name);
                            $this->session->set_userdata('intRoleId',$response->user_role_id_fk);
					    redirect('/admin/dashboard');
						exit();
					}
					
					elseif($response->user_role_id_fk == '2')
					{	
						echo "IT Staff";
						exit();
					}
                    elseif($response->user_role_id_fk == '3')
					{	
						echo "IT Team";
						exit();
					}
                    elseif($response->user_role_id_fk == '4')
					{	
						echo "Public";
						exit();
					}
				} // end is user name and passsword valid
                else// not match ue name and pass
	             {
	             	$this->session->set_flashdata('errorMsg', "Username Or Password Invalid");
                     $this->messages('alert alert-danger',"Username Or Password Invalid");
                     echo "username or passwro invalid"; 
	         	    redirect(base_url());
	         	    exit();
	             }  //end // not match ue name and pass 
             print_r($response);
        }  
    }
    
    public function messages($className,$messages)
    { 
       $this->session->set_flashdata('feedback',$messages);
       $this->session->set_flashdata('feedbase_class',$className);
   
    }
    public function logout_user()
    {
        
    }
    
    public function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
    
    //==========================================================================
    // dashboard
    //==========================================================================
    
    public function dashboard()
    {
        $data['title'] = 'Dashboard';
        $data['page']  = 'dashboard';
        $this->load->view('template',$data);
    }
    //==========================================================================
    // IT Staff 
    //==========================================================================
    function IT_staff()
    { 
        $data['it_staff'] = $this->model->IT_staff_list();
        $data['title']    = 'Dashboard';
        $data['page']     = 'IT_staff';
        $this->load->view('template',$data);
    }
    function IT_staff_insert()
    {
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect('admin/IT_staff');
            
        }
        else
        {
            $user_name         = $this->input->post('user_name');
            $user_password     = $this->input->post('user_password');
            $talbe_column_name = 'user_name';
            $table_id          = $user_name;
            $table_name        = 'users';
                $dublicate = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);
                    if(is_object($dublicate))
                    {
                        $this->messages('alert-danger','Username Already Exists');
                        return redirect('admin/IT_staff'); 
                    }
                    else
                    {
                        $inert_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_status'=>1,'user_role_id_fk'=>2);
                        $table_name        = 'users';
                        $response = $this->model->insert($inert_it_array,$table_name);
                            if($response == true)
                            {
                                $this->messages('alert-success','Successfully Added');
                                return redirect('admin/IT_staff'); 
                            }
                            else
                            {
                                $this->messages('alert-danger','Some Thing Wrong');
                                return redirect('admin/IT_staff');
                            }
                    }
        }
    }
    function IT_staff_edit_model()
    {
        if($this->input->post('user_id'))
        {
            $user_id           = $this->input->post('user_id');
            $table_name        = "users";
            $talbe_column_name = 'user_id';
            $table_id          = $user_id;

            $IT_staff = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            $active_class = '';
            $inactive_class = '';
            if($IT_staff->user_status == 1){ $active_class = 'selected'; } else{ $inactive_class = 'selected';}

            $html ='
                        <form class="" method="post" action="'.base_url("admin/IT_staff_update").'">
                            <div class="form-group">
                            <label>Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </div>
                                </div>
                                <input type="text" class="form-control" placeholder="Username" name="user_name" required value="'.$IT_staff->user_name.'">
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </div>
                                </div>
                                <input type="password" class="form-control" placeholder="Password" name="user_password" requored value="'.$IT_staff->user_password.'">
                            </div>
                            </div>
                            <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </div>
                                </div>
                                    <select class="form-control" name="user_status">
                                        <option value="1" '.$active_class.'>Active</option>
                                        <option value="0" '.$inactive_class.'>Inactive<option>
                                    </select>
                            </div>
                            </div>
                            <input type="hidden" name="user_id" value="'.$IT_staff->user_id.'" >
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>
                        </form>
                ';
        }
        else
        {
            $html ="No Record Found Against Selected User";
        }   
        echo $html;       
    }
    function IT_staff_update()
    {
        if($this->input->post('user_id'))
        {
            $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
            $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                $this->messages('alert-danger',$message);
                return redirect('admin/IT_staff');
                
            }
            else
            {
                $user_id       = $this->input->post('user_id');
                $user_name     = $this->input->post('user_name');
                $user_password = $this->input->post('user_password');
                $user_status   = $this->input->post('user_status');
                $update_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_status'=>$user_status);
                $table_name        = 'users';
                $talbe_column_name = 'user_id';
                $table_id          = $user_id;
                $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        $this->messages('alert-success','Successfully Update');
                        return redirect('admin/IT_staff'); 
                    }
                    else
                    {
                        $this->messages('alert-danger','Some Thing Wrong');
                        return redirect('admin/IT_staff');
                    }
            }
        }
    }
    function IT_staff_delete($user_id= null)
    {
        if($user_id > 0)
        {   
            $talbe_column_name = 'user_id';
            $table_name        = 'users';
            $table_id          = $user_id;
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name);
            if($response == true)
            {
                $this->messages('alert-success','Successfully Deleted');
                return redirect('admin/IT_staff'); 
            }
            else
            {
                $this->messages('alert-danger','Some Thing Wrong');
                return redirect('admin/IT_staff');
            }
        }
    }
    
    //==========================================================================
    // respondents
    //==========================================================================
    
     public function respondents_view()
    {
        
    }
    
    //==========================================================================
    // district
    //==========================================================================
    
    public function districts_view()
    {
        
    }
    
    public function districts_add()
    {
        
    }
    
    //==========================================================================
    // user (including district-admin, it-staff)
    //==========================================================================
    
    public function users_view()
    {
    //     $data['title'] = 'IT Staff';
    // $data['page']  = 'admin/itstaff'; 
    // $data['itstaff'] = $this->model->iTList();
    // $this->load->view('template',$data);
    }
    
    public function user_add()
    {
        
        
        
        
    //====================================================
    // sadam code
    //====================================================
    
    //     $this->load->library('form_validation');
    // 	$this->form_validation->set_rules('username', 'Username', 'required|trim');
    //     $this->form_validation->set_rules('password', 'Password', 'required|trim');
    //     if ($this->form_validation->run() == FALSE)
    //     {
    //         $error = array('error' => validation_errors()); 
    //         $message= implode(" ",$error);
    //         $this->messages('alert-danger',$message);
    //         return redirect('admin/itstaff');
            
    //     }
    //     else
    //     {
    //     	$username = $this->input->post('username');
    //     	$password = $this->input->post('password');
    //     	$logincheck_data   = $this->model->logincheckByUsername($username);
        	
		  //      if(is_object($logincheck_data)) 
		  //      {
		  //      	$this->messages('alert-danger',"User Already Exists with this username");
		  //          return redirect('admin/itstaff');
		  //      }
		  //       else
		  //       {
		  //       	$array        = array('user_name'=>$username,'user_password'=>$password,'user_role_id_fk'=>2);
		  //          $insetCheck   = $this->model->insertRecord($array);
		  //          $this->messages('alert-success',"Add User Successfully");
		  //          return redirect('admin/itstaff');
		  //       }
        	
    //     }
    }
    
    public function user_edit()
    {
        
    }
    
    //==========================================================================
    // complaint
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