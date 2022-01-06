<?php
error_reporting(0);

defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {
    
    
    public function __construct()
	{   
		parent::__construct();
        $this->load->model('AdminModel','model');
        $this->load->model('ComplaintModel','complaint');
        $this->load->library('auto_no.php','zend');
        $this->load->library('form_validation');
        if(empty($this->session->userdata('user_role_id_fk')))
        {
            $this->messages('alert-danger','Your session is expired please loing');
           // return redirect(base_url() );
        }
	} 

    public function check_role_privileges($page_name,$role_id)
    {
        $pages_data = $this->model->check_page($page_name);  
        
        if(is_object($pages_data))
        {
          $response = $this->model->check_role_privileges($pages_data->page_id,$role_id);
           if($response == TRUE)
           {
              return true; exit;
           }
           else
           {
             return redirect(base_url());
           }
        } 

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
            $array    = array('user_name'=>$username,'user_password'=>$password,'user_status'=>1);
            $response = $this->AuthModel->user_login($array); 
            if(!empty($response))// is user name and passsword valid
			   {
                $this->session->set_userdata('user_id',$response->user_id);
                $this->session->set_userdata('user_name',$response->user_name);
                $this->session->set_userdata('user_role_id_fk',$response->user_role_id_fk);
                $this->session->set_userdata('user_role_name',$response->user_role_name);
                redirect('/admin/dashboard'); exit();
				/*	if($response->user_role_id_fk == '1')
					{	
                        					
					    redirect('/admin/dashboard'); exit();
					}
					
					elseif($response->user_role_id_fk == '2')
					{	
						echo "IT Staff"; exit();
					}
                    elseif($response->user_role_id_fk == '3')
					{	
						echo "District Admin"; exit();
					}
                    elseif($response->user_role_id_fk == '4')
					{	
						echo "Complainant"; exit();
					} */
				} // end is user name and passsword valid
                else // not match ue name and pass
	             {
	             	$this->session->set_flashdata('errorMsg', "Username Or Password Invalid");
                    $this->messages('alert alert-danger',"Username Or Password Invalid");
                  //  echo "username or passwrod invalid"; 
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
        //$this->check_role_privileges('dashboard',$this->session->userdata('user_role_id_fk'));
        
        $data['title']          = 'Dashboard';
        $data['page']           = 'dashboard';
        $data['complaints']     = $this->model->countAll('complaints','complaint_status_id_fk',1);
        $data['admin']          = $this->model->countAll('complaints','complaint_source','admin');
        $data['complainants']   = $this->model->countAll('complaints','complaint_source','complainant');
        $data['pending']        = $this->model->countAll('complaints','complaint_status_id_fk',1);
        $data['complete']       = $this->model->countAll('complaints','complaint_status_id_fk',5);
        $data['reject']         = $this->model->countAll('complaints','complaint_status_id_fk',6);
        $data['thisDay']        = $this->model->thisDay(); 
        $data['thisMonth']      = $this->model->thisMonth();
        $data['thisYear']       = $this->model->thisYear(); 
        $data['users']          = $this->model->countUsersByRoleId(3); 
        $data['complainant']    = $this->model->countUsersByRoleId(4);
        $this->load->view('template',$data);
    }
    //==========================================================================
    // view user
    //==========================================================================

    function check_privileges($page_name)
    {
        $user_role_id = 1; // get active user's ROLE
        
        // select privilege_value from page_priviliges where user_role_id = ? and page_name = ?
        // if (privilege_value == 0)
        // {
            // 
        // }
    }

    function users()
    { 
        $this->check_role_privileges('users',$this->session->userdata('user_role_id_fk'));
        
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
            // $update_it_array   = array('user_status'=>0);
            // $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name); 
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
        $this->check_role_privileges('districts',$this->session->userdata('user_role_id_fk'));
        $table_name = 'districts';
        $data['districts'] = $this->model->get_all_records($table_name);
        $data['title']    = 'Districts';
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
    function district_delete($district_id= null)
    {
        if($district_id > 0)
        {   
            $table_name        = 'districts';
            $talbe_column_name = 'district_id';
            $table_id          = $district_id;
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name);
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

    //==========================================================================
    // complaint Categories view
    //==========================================================================

    public function complaint_categories()
    {
        $this->check_role_privileges('complaint_categories',$this->session->userdata('user_role_id_fk'));

        $table_name = 'complaint_categories';
        $data['complaint_categories'] = $this->model->get_all_records($table_name);
        $data['title']    = 'Complaint Categories';
        $data['page']     = 'complaint_categories';
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
            // $update_it_array   = array('complaint_category_status'=>0);
            // $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name);
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
    
    public function complaint_register_ajax() 
    {
        // $this->form_validation->set_rules('complainant_name', 'Complainant Name', 'required|trim');
        // $this->form_validation->set_rules('complainant_contact', 'Complainant Contact', 'required|trim');
        // $this->form_validation->set_rules('complainant_guardian_name', 'Complainant Guardian Name', 'required|trim');
        // $this->form_validation->set_rules('complainant_council_id_fk', 'Complainant Union Council', 'required|trim');
        // $this->form_validation->set_rules('complainant_email', 'Complainant Email', 'required|trim');
        // $this->form_validation->set_rules('complainant_gender', 'Complainant Gender', 'required|trim');
        // $this->form_validation->set_rules('complainant_cnic', 'Complainant CNIC', 'required|trim');
        // $this->form_validation->set_rules('complaint_category_id', 'Complaint Category_id', 'required|trim');
        // $this->form_validation->set_rules('complainant_address', 'Complainant Address', 'required|trim');
        // $this->form_validation->set_rules('district_id_fk', 'District', 'required|trim');
        // $this->form_validation->set_rules('complaint_detail', 'Complaint Detail', 'required|trim');

        // if ($this->form_validation->run() == FALSE)
        // {
        //     $error   = array('error' => validation_errors());
        //     $message = implode(" ",$error);
        //     $this->messages('alert-danger',$message);
        //     return redirect('admin/complaint_register');
            
        // }
        // else
        // {  // get form data
            $attachments = array();
            $complainant_name           = $this->input->post('complainant_name');
            $complainant_contact        = $this->input->post('complainant_contact');
            $complainant_guardian_name  = $this->input->post('complainant_guardian_name'); 
            $complainant_council_id_fk  = $this->input->post('complainant_council_id_fk');
            $complaint_council_id_fk    = $this->input->post('complaint_council_id_fk');
            $complainant_email          = $this->input->post('complainant_email');
            $complainant_gender         = $this->input->post('complainant_gender');
            $complainant_cnic           = $this->input->post('complainant_cnic');
            $complaint_category_id      = $this->input->post('complaint_category_id');
            $complainant_address        = $this->input->post('complainant_address');
            $complaint_detail           = $this->input->post('complaint_detail');
            $attachments                = $this->input->post('attachments');
            $complainant_id             = $this->input->post('home_district_id');
            //print_r($this->input->post('attachments')); exit;
            // complaintant checking
            
            $complainant_response = $this->model->getComplainant($complainant_cnic);

            if(is_object($complainant_response))  
            {   
                $complainant_id     = $complainant_response->complainant_id;
                $registered_by_user = $complainant_response->user_id_fk; 
                echo 'from old complainant: '.$registered_by_user;
            }
            else
            {
                $inert_complainant_array   = array(
                                                    'user_id_fk'                 => 0,
                                                    'complainant_council_id_fk'  => $complainant_council_id_fk,
                                                    'complainant_name'           => $complainant_name,
                                                    'complainant_guardian_name'  => $complainant_guardian_name,
                                                    'complainant_contact'        => $complainant_contact,
                                                    'complainant_cnic'           => $complainant_cnic,
                                                    'complainant_gender'         => $complainant_gender,
                                                    'complainant_email'          => $complainant_email,
                                                    'complainant_address'        => $complainant_address,
                                                    'complainant_status'         => 1
                                                ); 

                $complainant_id     = $this->model->insert_with_last_insert_id('complainants',$inert_complainant_array);
                $registered_by_user = $this->session->userdata('user_id'); 

                echo 'from admin: '.$registered_by_user;
            }
            
            $inert_it_array   = array(
                                        'registered_by_user'        => $registered_by_user,
                                        'complainant_id_fk'         => $complainant_id,
                                        'complaint_category_id_fk'  => $complaint_category_id,
                                        'complaint_council_id_fk'   => $complaint_council_id_fk,
                                        'complaint_detail'          => $complaint_detail,
                                        'complaint_status_id_fk'    => '1', //  1= pending, will make it global in next update
                                        'complaint_source'          => 'admin'
                                    );

                $this->load->model('ComplaintModel');
                $response = $this->ComplaintModel->complaint_add($inert_it_array); 

                // print_r($response); exit;
                $response_message =  $response['response_msg'];
                $response_status  = $response['response'];

                    if($response_status == 0)
                    {
                        $this->messages('alert-danger',$response_message);
                        return redirect('admin/complaints'); 
                    }
                    else
                    {
                        $this->messages('alert-success',$response_message);
                        return redirect('admin/complaints');
                           
                    }
        // }
    }
    
    public function complaint_register()
    {
        $this->check_role_privileges('complaint_register',$this->session->userdata('user_role_id_fk'));

        $data['title'] = 'Regsiter Complaint';
        $data['page']  = 'complaint_register'; 
        
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
        
        // get active district_councils 
        $table_name3                   = 'district_councils';
        $table_status_column_name3    = 'district_council_status';
        $table_status_column_value3    = 1;
        $data['district_councils'] = $this->model->status_active_record($table_name3,$table_status_column_name3,$table_status_column_value3);

        // get active police_stations 
        $table_name4                   = 'police_stations';
        $table_status_column_name4     = 'police_station_status';
        $table_status_column_value4    = 1;
        $data['police_stations'] = $this->model->status_active_record($table_name4,$table_status_column_name4,$table_status_column_value4);        
        
        $this->load->view('template',$data);
    }

    public function complaints()
    { 
        $this->check_role_privileges('complaints',$this->session->userdata('user_role_id_fk'));

        $data = array();
        $displayLimit = "10";

        $district_council_id = "";
        $complaint_status_id = "";
        $from_date           = "";
        $to_date             = "";
        $complaint_source    = "";

        if($this->session->userdata('displayLimit'))
        {
            $displayLimit = $this->session->userdata('displayLimit');
        }
        if($this->session->userdata('district_council_id'))
        {
            $district_council_id = $this->session->userdata('district_council_id');
        }
        if($this->session->userdata('complaint_status_id'))
        {
            $complaint_status_id = $this->session->userdata('complaint_status_id');
        }
        if($this->session->userdata('from_date'))
        {
            $from_date = $this->session->userdata('from_date');
        }
        if($this->session->userdata('to_date'))
        {
            $to_date = $this->session->userdata('to_date');
        }
        if($this->session->userdata('complaint_source'))
        {
            $complaint_source = $this->session->userdata('complaint_source');
        }

        
        if($this->input->post())
        {

            $district_council_id = $this->input->post('district_council_id');
            $this->session->set_userdata('district_council_id',$district_council_id);

            $complaint_status_id = $this->input->post('complaint_status_id');
            $this->session->set_userdata('complaint_status_id',$complaint_status_id);

            $from_date           = $this->input->post('from_date');
            $this->session->set_userdata('from_date',$from_date);

            $to_date             = $this->input->post('to_date');
            $this->session->set_userdata('to_date',$to_date);

            $complaint_source    = $this->input->post('complaint_source');
            $this->session->set_userdata('complaint_source',$complaint_source);
        }

        // $data['district']=$district;
        $data['displayLimit']        = $displayLimit;
        $data['district_council_id'] = $district_council_id;
        $data['complaint_status_id'] = $complaint_status_id;
        $data['from_date']           = $from_date;
        $data['to_date']             = $to_date;
        $data['complaint_source']    = $complaint_source;

       
        $uri_segment = 3;
        $offset      = $this->uri->segment($uri_segment);
        $config = array();
        $config['total_rows']=$this->complaint->count_complaints();
        $config['base_url']        = site_url('/admin/complaints');
        $config['per_page']        = $displayLimit;
        $config['num_links']        = 4;
        
        $config['first_link']      = '<<';
        $config['next_link']       = 'Next';
        $config['prev_link']       = 'Previous';
        $config['last_link']       = '>>';
        $config['full_tag_open']   = '<div class="page-nation"><ul class="pagination pagination-large">';
        $config['full_tag_close']  = '</ul>';
        $config['first_tag_open']  = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li>';
        $config['last_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class=" btn active"><a  href="javascript:void(0)">';
        $config['cur_tag_close']   = '<span class="sr-only">(current)</span></a></li>';
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '</li>';
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '</li>';
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';
        $config['attributes']      = array('class' => 'btn btn-primary myajaxpagination');

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pagination']  = $this->pagination->create_links();
        $data['complaints']  = $this->complaint->get_complaints($displayLimit,$offset);
        // complaints status
        $table_name                 = 'complaint_statuses';
        $table_status_column_name   = 'status';
        $table_status_column_value  = 1;
        $data['complaint_statuses'] = $this->model->status_active_record($table_name,$table_status_column_name,$table_status_column_value);
        // union councils
        $table_name3                  = 'district_councils';
        $table_status_column_name3    = 'district_council_status';
        $table_status_column_value3   = 1;
        $data['district_councils'] = $this->model->status_active_record($table_name3,$table_status_column_name3,$table_status_column_value3);
    
            
        $this->load->view('template/common_header');
        $this->load->view('template/navigation');   
        $this->load->view('complaints',$data);
        $this->load->view('template/common_footer');
        
       // $this->load->view('template',$data);

    }
    function complaint_detail($complaint_id)
    {
        $this->check_role_privileges('complaint_detail',$this->session->userdata('user_role_id_fk'));

        $data['title'] = 'Complaint Detail';
        $data['page']  = 'complaint_detail'; 
        
        $data['complaint_detail']     = $this->complaint->get_complaint_by_id($complaint_id); // complinat detailed 
        $data['complaint_attachment'] = $this->complaint->get_attachment_complaint_by_id($complaint_id); // complinat attachments 
        $data['complaint_statuses']   = $this->model->get_all_records('complaint_statuses');
        $data['respondats']           = $this->model->get_all_records('respondents');
        $data['complaint_id']         = $complaint_id;
        $data['complaints_remarks']   = $this->complaint->get_complaint_remarks($complaint_id);
        //get active districts
        $table_name                = 'districts';
        $table_status_column_name   = 'district_status';
        $table_status_column_value  = 1;
        $data['district']           = $this->model->status_active_record($table_name,$table_status_column_name,$table_status_column_value);

        // get active district_councils 
        $table_name3                   = 'district_councils';
        $table_status_column_name3    = 'district_council_status';
        $table_status_column_value3    = 1;
        $data['district_councils'] = $this->model->status_active_record($table_name3,$table_status_column_name3,$table_status_column_value3);
        
        $this->load->view('template',$data);
    }
    function insert_comploaint_remarks()
    {
        $this->form_validation->set_rules('complaint_status_id_fk', 'Complainant Status', 'required|trim');
        // $this->form_validation->set_rules('respondent_id_fk', 'respondent', 'required|trim');
        $this->form_validation->set_rules('complaint_remarks_timestamp', 'Complainant Date', 'required|trim');
        $this->form_validation->set_rules('complaint_remarks_detail', 'Complainant Remarks', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect('admin/complaint_detail');
            
        }
        else
        {
            $complaint_status_id_fk       = $this->input->post('complaint_status_id_fk');
            $respondent_id_fk             = empty($this->input->post('respondent_id_fk') )?'0':$this->input->post('respondent_id_fk');
            $complaint_remarks_timestamp  = $this->input->post('complaint_remarks_timestamp');
            $complaint_remarks_detail     = $this->input->post('complaint_remarks_detail');
            $user_id_fk                   = $this->session->userdata('user_id'); 
            $table_name                   = 'complaint_remarks';
            $complaint_id                 = $this->input->post('complaint_id');
            $complaint_remarks_array   = array(
                                                'complaint_status_id_fk'       => $complaint_status_id_fk,
                                                'respondent_id_fk'             => $respondent_id_fk,
                                                'complaint_remarks_timestamp'  => $complaint_remarks_timestamp,
                                                'complaint_remarks_detail'     => $complaint_remarks_detail,
                                                'user_id_fk'                   => $user_id_fk,
                                                'complaint_id_fk'              => $complaint_id,
                                                'complaint_remarks_status'     =>1
                                            ); 

            $response     = $this->model->insert($complaint_remarks_array,$table_name);
            $update_array = array('complaint_status_id_fk'=>$complaint_status_id_fk);
            $this->model->update($update_array,'complaints','complaint_id',$complaint_id); // update complaint status

                if($response == true)
                {
                    $this->messages('alert-success','Successfully Added');
                    return redirect('admin/complaint_detail/'.$complaint_id); 
                }
                else
                {
                    $this->messages('alert-danger','Some Thing Wrong');
                    return redirect('admin/complaint_detail/'.$complaint_id);
                }

        }
    }
    
    // :::::::::::: Update Password
    function updatePassword()
    {
        if($this->session->userdata('user_id'))
        {
           $user_password = md5($this->input->post('user_password'));
           $user_id       = $this->session->userdata('user_id');

           $update_array = array('user_password'=>$user_password);
           $response     = $this->model->update($update_array,'users','user_id',$user_id); // update complaint status 
                if($response == true)
                { 
                    $this->messages('alert-success','Successfully Added');
                    return redirect(base_url()); 
                }
                else
                {
                    $this->messages('alert-danger','Some Thing Wrong');
                    return redirect(base_url());
                } 
        } 
        else
        { 
            $this->messages('alert-danger','Invalid Access');
            return redirect(base_url());
        }
       
    }

}

?>