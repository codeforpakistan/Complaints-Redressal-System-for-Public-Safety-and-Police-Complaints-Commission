<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {
    
    
    public function __construct()
	{
		parent::__construct();
        $this->load->model('AdminModel','model');
        $this->load->model('ComplaintModel','complaint');
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
                else // not match ue name and pass
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
    //==========================================================================
    // Flash messages for view action
    //==========================================================================
    public function messages($className,$messages)
    { 
       $this->session->set_flashdata('feedback',$messages);
       $this->session->set_flashdata('feedbase_class',$className);
   
    }
    //==========================================================================
    // user logout
    //==========================================================================
    public function logout_user()
    {
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_id'); 
        $this->session->unset_userdata('user_role_id_fk'); 
        $this->session->sess_destroy();
        $this->clear_cache();
        redirect(base_url());
    }
    //==========================================================================
    // clear browser cache
    //==========================================================================

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
        $data['users'] = $this->model->countUsersByRoleId(3);
        $data['complainant']    = $this->model->countUsersByRoleId(4);
        $this->load->view('template',$data);
    }
    //==========================================================================
    // view user
    //==========================================================================

    function users()
    { 
        $table_name                 = 'users';
        $table_name2                = 'districts';
        $table_status_column_name   = 'district_status';
        $user_district_id_fk        = 3;
        $table_status_column_value  = 1;
        $data['users']              = $this->model->IT_district_admins();
        $data['district']           = $this->model->status_active_record($table_name2,$table_status_column_name,$table_status_column_value);
        $data['title']              = 'Users';
        $data['page']               = 'users';
        $this->load->view('template',$data);
    }
    //==========================================================================
    // insert user
    //==========================================================================
    function users_insert()
    {
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim|is_unique[users.user_name]');
        $this->form_validation->set_rules('user_password', 'Password', 'required|trim');

        if($this->input->post('user_role_id_fk') == 3) // to for District admin
        {
            $this->form_validation->set_rules('district_id', 'Dostrict selection', 'required|trim');
        } 

        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect('admin/users');
            
        }
        else
        {
            $user_name            = $this->input->post('user_name');
            $user_password        = $this->input->post('user_password');
            $user_district_id_fk  = (empty($this->input->post('district_id'))? 0:$this->input->post('district_id'));
            $user_role_id_fk      = $this->input->post('user_role_id_fk');
            $table_name           = 'users';
            $inert_it_array       = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_district_id_fk'=>$user_district_id_fk,'user_status'=>1,'user_role_id_fk'=>$user_role_id_fk);
            $table_name           = 'users';

            $response = $this->model->insert($inert_it_array,$table_name);

                if($response == true)
                {
                    $this->messages('alert-success','Successfully Added');
                    return redirect('admin/users'); 
                }
                else
                {
                    $this->messages('alert-danger','Some Thing Wrong');
                    return redirect('admin/users');
                }
        }
    }
    //==========================================================================
    // update user modal view
    //==========================================================================
    function users_edit_model($user_id)
    { 
        $table_name        = "users";
        $talbe_column_name = 'user_id';
        $table_id          = $user_id;

        $userss = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
        echo json_encode($userss); exit;      
    }
    //==========================================================================
    // update user
    //==========================================================================
    function users_update()
    {
        if($this->input->post('user_id'))
        {   
            $user_id           = $this->input->post('user_id');
            $user_name         = $this->input->post('user_name');
            $user_password     = $this->input->post('user_password');
            // $user_district_id_fk=$this->input->post('district_id');
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
            if($this->input->post('user_role_id_fk') == 3) // to for District admin
            {
                $this->form_validation->set_rules('district_id', 'For Dostrict-admin District selection', 'required|trim');
            }
            
            $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                $this->messages('alert-danger',$message);
                return redirect('admin/users');
                
            }
            else
            {
                $user_district_id_fk= (empty($this->input->post('district_id'))? 0:$this->input->post('district_id'));
                $update_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_status'=>$user_status,'user_district_id_fk'=>$user_district_id_fk);
                $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        $this->messages('alert-success','Successfully Update');
                        return redirect('admin/users'); 
                    }
                    else
                    {
                        $this->messages('alert-danger','Some Thing Wrong');
                        return redirect('admin/users');
                    }
            }
        }
    }
    //==========================================================================
    // Delete User
    //==========================================================================
    function users_delete($user_id= null)
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
                return redirect('admin/users'); 
            }
            else
            {
                $this->messages('alert-danger','Some Thing Wrong');
                return redirect('admin/users');
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
    // district view
    //==========================================================================
    
    public function districts()
    {
        $table_name = 'districts';
        $data['districts'] = $this->model->get_all_records($table_name);
        $data['title']    = 'KP Districts';
        $data['page']     = 'districts';
        $this->load->view('template',$data);
    }
     //==========================================================================
    // district view modal 
    //==========================================================================
    public function districts_edit_model($district_id)
    {
            $table_name        = "districts";
            $talbe_column_name = 'district_id';
            $table_id          = $district_id;

            $districts = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            echo json_encode($districts); exit(); 
    }
     //==========================================================================
    // district update
    //==========================================================================
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

    //==========================================================================
    // district insert
    //==========================================================================

    public function districts_insert()
    {
            $this->form_validation->set_rules('district_name', 'District Name', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                $this->messages('alert-danger',$message);
                return redirect('admin/districts');
                
            }
            else
            {
                $district_name   = $this->input->post('district_name');

                $update_it_array   = array('district_name'=>$district_name,'district_status'=>1);
                $table_name        = 'districts';
                $response = $this->model->insert($update_it_array,$table_name);
                    if($response == true)
                    {
                        $this->messages('alert-success','Successfully Added');
                        return redirect('admin/districts'); 
                    }
                    else
                    {
                        $this->messages('alert-danger','Some Thing Wrong');
                        return redirect('admin/districts');
                    }
            }
        
    }

    //==========================================================================
    // complaint Categories view
    //==========================================================================

    public function complaint_categories()
    {
        $table_name = 'complaint_categories';
        $data['complaint_categories'] = $this->model->get_all_records($table_name);
        $data['title']    = 'Complaint Categories';
        $data['page']     = 'complaint_category';
        $this->load->view('template',$data);
    }

     //==========================================================================
    // complaint category view modal
    //==========================================================================

    public function complaint_categories_edit_model($complaint_category_id)
    {
        $table_name        = "complaint_categories";
        $talbe_column_name = 'complaint_category_id';
        $table_id          = $complaint_category_id;

        $complaint_cat = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
        echo json_encode($complaint_cat); exit;
    }

    //==========================================================================
    // complaint category update
    //==========================================================================

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
    
    //==========================================================================
    // complaint category insert
    //==========================================================================

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
    //==========================================================================
    // complaint category delete
    //==========================================================================

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
    // complaint
    //==========================================================================
    
    public function complaint_register()
    {
        $this->form_validation->set_rules('complainant_name', 'Complainant Name', 'required|trim');
        $this->form_validation->set_rules('complainant_contact', 'Complainant Contact', 'required|trim');
        $this->form_validation->set_rules('complainant_guardian_name', 'Complainant Guardian Name', 'required|trim');
        $this->form_validation->set_rules('complainant_city', 'Complainant City', 'required|trim');
        $this->form_validation->set_rules('complainant_union_council', 'Complainant Union Council', 'required|trim');
        $this->form_validation->set_rules('complainant_email', 'Complainant Email', 'required|trim');
        $this->form_validation->set_rules('complainant_gender', 'Complainant Gender', 'required|trim');
        $this->form_validation->set_rules('complainant_cnic', 'Complainant CNIC', 'required|trim');
        $this->form_validation->set_rules('complaint_category_id', 'Complaint Category_id', 'required|trim');
        $this->form_validation->set_rules('complainant_address', 'Complainant Address', 'required|trim');
        $this->form_validation->set_rules('district_id_fk', 'District', 'required|trim');
        $this->form_validation->set_rules('complaint_detail', 'Complaint Detail', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect('admin/complaints');
            
        }
        else
        {  // get form data
            $attachments = array();
            $complainant_name           = $this->input->post('complainant_name');
            $complainant_contact        = $this->input->post('complainant_contact');
            $complainant_guardian_name  = $this->input->post('complainant_guardian_name');
            $complainant_city           = $this->input->post('complainant_city');
            $complainant_union_council  = $this->input->post('complainant_union_council');
            $complainant_email          = $this->input->post('complainant_email');
            $complainant_gender         = $this->input->post('complainant_gender');
            $complainant_cnic           = $this->input->post('complainant_cnic');
            $complaint_category_id      = $this->input->post('complaint_category_id');
            $complainant_address        = $this->input->post('complainant_address');
            $district_id_fk             = $this->input->post('district_id_fk');
            $complaint_detail           = $this->input->post('complaint_detail');
            $attachments                = $this->input->post('attachments');
            $registered_by_user         = $this->session->userdata('user_id'); //echo $registered_by_user; exit;
            $complainant_id             =0;
            print_r($_FILES['attachments']['name']); exit;
            //print_r($this->input->post('attachments')); exit;
            // complaintant checking
            $complainant_response = $this->model->getComplainant($complainant_cnic);
            if(is_object($complainant_response))  
            {   
                $complainant_id = $complainant_response->complainant_id;
            }
            else
            {
                $inert_complainant_array   = array(
                    'user_id_fk'                 => 0,
                    'complainant_district_id_fk' =>$district_id_fk,
                    'complainant_name'           =>$complainant_name,
                    'complainant_guardian_name'  => $complainant_guardian_name,
                    'complainant_contact'        => $complainant_contact,
                    'complainant_cnic'           => $complainant_cnic,
                    'complainant_gender'         => $complainant_gender,
                    'complainant_email'          => $complainant_email,
                    'complainant_union_council'  => $complainant_union_council,
                    'complainant_address'        => $complainant_address,
                    'complainant_city'           => $complainant_city,
                    'complainant_status'         =>1
                );
                $complainant_id = $this->model->insert_with_last_insert_id('complainants',$inert_complainant_array);
            }
            // echo  $complainant_id;
            //  exit;
            $inert_it_array   = array(
                                        'registered_by_user'        => $registered_by_user,
                                        'complainant_id_fk'         => $complainant_id,
                                        'complaint_category_id_fk'  =>$complaint_category_id,
                                        'district_id_fk'            =>$district_id_fk,
                                        'complaint_detail'          =>$complaint_detail,
                                        'complaint_status'          =>1,
                                        'complaint_source'          =>1,
                                        'complaint_status'          =>1
                                    );

                $this->load->model('ComplaintModel');
                $response = $this->ComplaintModel->complaint_add($inert_it_array); 
                print_r($response);
                exit();

                // $dublicate = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);
                    // if(is_object($dublicate))
                    // {
                    //     $this->messages('alert-danger','Complaint Category Already Exists');
                    //     return redirect('admin/complaints'); 
                    // }
                    // else
                    // {
                    //     $inert_it_array   = array('complaint_category_name'=>$complaint_category_name,'complaint_category_status'=>1);
                    //     $response = $this->model->insert($inert_it_array,$table_name);
                    //         if($response == true)
                    //         {
                    //             $this->messages('alert-success','Successfully Added');
                    //             return redirect('admin/complaints'); 
                    //         }
                    //         else
                    //         {
                    //             $this->messages('alert-danger','Some Thing Wrong');
                    //             return redirect('admin/complaints');
                    //         }
                    // }
        }
    }
    
    public function complaints()
    {
        $data['title'] = 'Complaint Detail';
        $data['page']  = 'complaint'; 
        
        //get active districts
        $table_name                = 'districts';
        $table_status_column_name   = 'district_status';
        $table_status_column_value  = 1;
        $data['district']           = $this->model->status_active_record($table_name,$table_status_column_name,$table_status_column_value);
        
        // get active complaint type
        $table_name2                   = 'complaint_categories';
        $table_status_column_name2    = 'complaint_category_status';
        $table_status_column_value2    = 1;
        $data['complaint_categories'] = $this->model->status_active_record($table_name2,$table_status_column_name2,$table_status_column_value2);

        
        $this->load->view('template',$data);
    }
    
    public function complaint_edit()
    {
        
    }
    
    public function complaint_feedback()
    {
        
    }

}

?>