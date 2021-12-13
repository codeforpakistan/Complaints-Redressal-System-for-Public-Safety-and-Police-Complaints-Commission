<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {
    
    
    public function __construct()
	{
		parent::__construct();
        $this->load->model('AdminModel','model');
        $this->load->library('auto_no.php','zend');
        $this->load->library('form_validation');
	} 
	
    //==========================================================================
    // Auth
    //==========================================================================
    public function index()
    { 
        if($this->session->userdata('user_role_id_fk'))
        {
            $this->dashboard();
        }
        else
        {
           $this->load->view('authLogin'); 
        }
        
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
                $this->session->set_userdata('user_id',$response->user_id);
                $this->session->set_userdata('user_name',$response->user_name);
                $this->session->set_userdata('user_role_id_fk',$response->user_role_id_fk);
                $this->session->set_userdata('user_role_name',$response->user_role_name);

					if($response->user_role_id_fk == '1')
					{						
					    redirect('/admin/dashboard'); exit();
					}
					
					elseif($response->user_role_id_fk == '2')
					{	
						echo "IT Staff"; exit();
					}
                    elseif($response->user_role_id_fk == '3')
					{	
						echo "IT Team"; exit();
					}
                    elseif($response->user_role_id_fk == '4')
					{	
						echo "Public"; exit();
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
             //print_r($response);
        }  
    }
    
    public function messages($className,$messages)
    { 
       $this->session->set_flashdata('feedback',$messages);
       $this->session->set_flashdata('feedbase_class',$className);
   
    }
    public function logout_user()
    {
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_id'); 
        $this->session->unset_userdata('user_role_id_fk'); 
        $this->session->sess_destroy();
        $this->clear_cache();
        redirect(base_url());
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
        $data['title']          = 'Dashboard';
        $data['page']           = 'dashboard';
        $data['it_staff']       = $this->model->countUsersByRoleId(2);
        $data['district_admin'] = $this->model->countUsersByRoleId(3);
        $data['complainant']    = $this->model->countUsersByRoleId(4);
        $this->load->view('template',$data);
    }
    //==========================================================================
    // IT Staff 
    //==========================================================================
    function IT_staff()
    { 
        $table_name      = 'users';
        $user_role_id_fk = 2;
        $data['it_staff'] = $this->model->user_by_role($table_name,$user_role_id_fk);
        $data['title']    = 'IT Staff';
        $data['page']     = 'IT_staff';
        $this->load->view('template',$data);
    }
    function IT_staff_insert()
    {
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim|is_unique[users.user_name]');
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
    function IT_staff_edit_model($user_id)
    {
            $table_name        = "users";
            $talbe_column_name = 'user_id';
            $table_id          = $user_id;

            $IT_staff = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            echo json_encode($IT_staff);exit();
    }
    function IT_staff_update()
    {
        if($this->input->post('user_id'))
        {   
            $user_id           = $this->input->post('user_id');
            $user_name     = $this->input->post('user_name');
            $user_password = $this->input->post('user_password');
            $user_status   = $this->input->post('user_status');
            $table_name        = "users";
            $talbe_column_name = 'user_id';
            $table_id          = $user_id;

            $IT_staff = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            $exists_user_name =  $IT_staff->user_name;
            if($exists_user_name != $user_name)
            {
              $this->form_validation->set_rules('user_name', 'Username', 'required|trim|is_unique[users.user_name]');  
            }
            else
            {
                $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
            }
            
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
                $update_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_status'=>$user_status);
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
            $update_it_array   = array('user_status'=>0);
            $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id); 
            // $response = $this->model->delete($talbe_column_name,$table_id,$table_name);
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
    // District Admin
    //==========================================================================

    function district_admin()
    { 
        $table_name                 = 'users';
        $user_role_id_fk            = 3;
        $table_name2                = 'districts';
        $table_status_column_name   = 'district_status';
        $user_district_id_fk        =3;
        $table_status_column_value  = 1;
        $data['district_admin'] = $this->model->user_by_role($table_name,$user_role_id_fk,$user_district_id_fk);
        $data['district']       = $this->model->status_active_record($table_name2,$table_status_column_name,$table_status_column_value);
        $data['title']          = 'District Admin';
        $data['page']           = 'district_admin';
        $this->load->view('template',$data);
    }
    function district_admin_insert()
    {
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim|is_unique[users.user_name]');
        $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
        $this->form_validation->set_rules('district_id','District', 'required|trim');
        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect('admin/district_admin');
            
        }
        else
        {
            $user_name         = $this->input->post('user_name');
            $user_password     = $this->input->post('user_password');
            $user_district_id_fk=$this->input->post('district_id');
            $table_name        = 'users';
            $inert_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_district_id_fk'=>$user_district_id_fk,'user_status'=>1,'user_role_id_fk'=>3);
            $table_name        = 'users';
            $response = $this->model->insert($inert_it_array,$table_name);
                if($response == true)
                {
                    $this->messages('alert-success','Successfully Added');
                    return redirect('admin/district_admin'); 
                }
                else
                {
                    $this->messages('alert-danger','Some Thing Wrong');
                    return redirect('admin/district_admin');
                }
        }
    }
    function district_admin_edit_model($user_id)
    { 
        $table_name        = "users";
        $talbe_column_name = 'user_id';
        $table_id          = $user_id;

        $district_admins = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
        echo json_encode($district_admins); exit;      
    }
    function district_admin_update()
    {
        if($this->input->post('user_id'))
        {   
            $user_id           = $this->input->post('user_id');
            $user_name         = $this->input->post('user_name');
            $user_password     = $this->input->post('user_password');
            $user_district_id_fk=$this->input->post('district_id');
            $user_status       = $this->input->post('user_status');
            $table_name        = "users";
            $talbe_column_name = 'user_id';
            $table_id          = $user_id;

            $IT_staff = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            $exists_user_name =  $IT_staff->user_name;
            if($exists_user_name != $user_name)
            {
              $this->form_validation->set_rules('user_name', 'Username', 'required|trim|is_unique[users.user_name]');  
            }
            else
            {
                $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
            }
            
            $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                $this->messages('alert-danger',$message);
                return redirect('admin/district_admin');
                
            }
            else
            {

                $update_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_status'=>$user_status,'user_district_id_fk'=>$user_district_id_fk);
                $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        $this->messages('alert-success','Successfully Update');
                        return redirect('admin/district_admin'); 
                    }
                    else
                    {
                        $this->messages('alert-danger','Some Thing Wrong');
                        return redirect('admin/district_admin');
                    }
            }
        }
    }
    function district_admin_delete($user_id= null)
    {
        if($user_id > 0)
        {   
            $talbe_column_name = 'user_id';
            $table_name        = 'users';
            $table_id          = $user_id; 
            $update_it_array   = array('user_status'=>0);
            $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id); 
            if($response == true)
            {
                $this->messages('alert-success','Successfully Deleted');
                return redirect('admin/district_admin'); 
            }
            else
            {
                $this->messages('alert-danger','Some Thing Wrong');
                return redirect('admin/district_admin');
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
    
    public function districts()
    {
        $table_name = 'districts';
        $data['districts'] = $this->model->get_all_records($table_name);
        $data['title']    = 'KPK Districts';
        $data['page']     = 'districts';
        $this->load->view('template',$data);
    }
    public function districts_edit_model($district_id)
    {
            $table_name        = "districts";
            $talbe_column_name = 'district_id';
            $table_id          = $district_id;

            $districts = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            echo json_encode($districts); exit(); 
    }
    public function district_update()
    {
        if($this->input->post('district_id'))
        {
            $this->form_validation->set_rules('district_name', 'District Name', 'required|trim');
            $this->form_validation->set_rules('district_status', 'District Status', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                $this->messages('alert-danger',$message);
                return redirect('admin/districts');
                
            }
            else
            {
                $district_id    = $this->input->post('district_id');
                $district_name   = $this->input->post('district_name');
                $district_status = $this->input->post('district_status');

                $update_it_array   = array('district_name'=>$district_name,'district_status'=>$district_status);
                $table_name        = 'districts';
                $talbe_column_name = 'district_id';
                $table_id          = $district_id;
                $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        $this->messages('alert-success','Successfully Update');
                        return redirect('admin/districts'); 
                    }
                    else
                    {
                        $this->messages('alert-danger','Some Thing Wrong');
                        return redirect('admin/districts');
                    }
            }
        }
    }
    
    public function districts_add()
    {
        
    }
   //==========================================================================
    // complaint Categories
    //==========================================================================
    public function complaint_categories()
    {
        $table_name = 'complaint_categories';
        $data['complaint_categories'] = $this->model->get_all_records($table_name);
        $data['title']    = 'Complaint Categories';
        $data['page']     = 'complaint_category';
        $this->load->view('template',$data);
    }

    public function complaint_categories_edit_model($complaint_category_id)
    {
        $table_name        = "complaint_categories";
        $talbe_column_name = 'complaint_category_id';
        $table_id          = $complaint_category_id;

        $complaint_cat = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
        echo json_encode($complaint_cat); exit;
    }
    
    public function complaint_category_update()
    {
        if($this->input->post('complaint_category_id'))
        {
            $this->form_validation->set_rules('complaint_category_name', 'Complaint Category Name', 'required|trim');
            $this->form_validation->set_rules('complaint_category_status', 'Complaint Category Status', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                $this->messages('alert-danger',$message);
                return redirect('admin/complaint_categories');
                
            }
            else
            {
                $complaint_category_id     = $this->input->post('complaint_category_id');
                $complaint_category_name   = $this->input->post('complaint_category_name');
                $complaint_category_status = $this->input->post('complaint_category_status');

                $update_it_array   = array('complaint_category_name'=>$complaint_category_name,'complaint_category_status'=>$complaint_category_status);
                $table_name        = 'complaint_categories';
                $talbe_column_name = 'complaint_category_id';
                $table_id          = $complaint_category_id;
                $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        $this->messages('alert-success','Successfully Update');
                        return redirect('admin/complaint_categories'); 
                    }
                    else
                    {
                        $this->messages('alert-danger','Some Thing Wrong');
                        return redirect('admin/complaint_categories');
                    }
            }
        }
    }

    function complaint_category_insert()
    {
        $this->form_validation->set_rules('complaint_category_name', 'Username', 'required|trim');
        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect('admin/complaint_categories');
            
        }
        else
        {
            $complaint_category_name  = $this->input->post('complaint_category_name');
            $talbe_column_name        = 'complaint_category_name';
            $table_id                 = $complaint_category_name;
            $table_name               = 'complaint_categories';
                $dublicate = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);
                    if(is_object($dublicate))
                    {
                        $this->messages('alert-danger','Complaint Category Already Exists');
                        return redirect('admin/complaint_categories'); 
                    }
                    else
                    {
                        $inert_it_array   = array('complaint_category_name'=>$complaint_category_name,'complaint_category_status'=>1);
                        $response = $this->model->insert($inert_it_array,$table_name);
                            if($response == true)
                            {
                                $this->messages('alert-success','Successfully Added');
                                return redirect('admin/complaint_categories'); 
                            }
                            else
                            {
                                $this->messages('alert-danger','Some Thing Wrong');
                                return redirect('admin/complaint_categories');
                            }
                    }
        }
    }
    function complaint_categories_delete($complaint_category_id= null)
    {
        if($complaint_category_id > 0)
        {   
            $talbe_column_name = 'complaint_category_id';
            $table_name        = 'complaint_categories';
            $table_id          = $complaint_category_id;
            $update_it_array   = array('complaint_category_status'=>0);
            $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
            if($response == true)
            {
                $this->messages('alert-success','Successfully Deleted');
                return redirect('admin/complaint_categories'); 
            }
            else
            {
                $this->messages('alert-danger','Some Thing Wrong');
                return redirect('admin/complaint_categories');
            }
        }
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
    
    public function complaints()
    {
        echo "complaint list here";
    }
    
    public function complaint_edit()
    {
        
    }
    
    public function complaint_feedback()
    {
        
    }

}

?>