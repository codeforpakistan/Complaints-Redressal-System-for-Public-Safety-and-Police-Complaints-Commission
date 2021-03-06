<?php
error_reporting(0);

defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends CI_Controller {
    
    public function __construct()
    {   
        // testing github 
        
        parent::__construct();
        $this->load->model('AdminModel','model');
        $this->load->model('ComplaintModel','complaint');
        $this->load->library('auto_no.php','zend');
        $this->load->library('form_validation');
        // Load the captcha helper
        $this->load->helper('captcha');
        date_default_timezone_set("Asia/Karachi");
        
        // if(empty($this->session->userdata('user_role_id_fk')))
        // {   
        //     $this->logout_user();
        //     // $this->messages('alert-danger','Your session is expired please loing');
        //     // return redirect(base_url() );
        // }
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
         // Captcha configuration
         $config = array(
                        'img_path'      => 'captcha_images/',
                        'img_url'       => base_url().'captcha_images/',
                        'img_width'     => '156',
                        'img_height'    => '41',
                        'word_length'   => 6,
                        // 'pool'          => abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,
                        'font_size'     => 20
                    ); 
            $captcha = create_captcha($config);

            // Unset previous captcha and set new captcha word
            $this->session->unset_userdata('captchaCode');
            $this->session->set_userdata('captchaCode', $captcha['word']);
            
            // Pass captcha image to view
            $data['captchaImg'] = $captcha['image'];
            $this->load->view('authLogin',$data); 
        }
        
    }
    public function refreshCaptcha(){

        // Captcha configuration
        $config = array(
            'img_path'      => 'captcha_images/',
            'img_url'       => base_url().'captcha_images/',
            'img_width'     => '156',
            'img_height'    => '41',
            'word_length'   => 6,
            // 'pool'          => abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,
            'font_size'     => 20
        );
        $captcha = create_captcha($config);
        
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode',$captcha['word']);
        
        // Display captcha image
        echo $captcha['image'];
    }
    
    public function login_user()
    {   
        $this->form_validation->set_rules('user_name', 'Username', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
        // $this->form_validation->set_rules('captcha', 'Captcha', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            $this->messages('alert-danger',$message);
            return redirect(base_url());
            
        }
        else
        {
            // $inputCaptcha = $this->input->post('captcha');
            // $sessCaptcha = $this->session->userdata('captchaCode');

            // if($inputCaptcha !== $sessCaptcha)
            // {
            //     //echo 'Captcha code matched.';
            //     $this->messages('alert-danger','Captcha code does not match, please try again.');
            //     return redirect(base_url());
            // }

            $username = $this->input->post('user_name');
            $password = $this->input->post('user_password');
            $array    = array('user_name'=>$username,'user_password'=>$password);
            $response = $this->AuthModel->user_login($array); 
            
            if(!empty($response))// is user name and passsword valid
            {
                $this->session->set_userdata('user_id',$response->user_id);
                $this->session->set_userdata('user_name',$response->user_name);
                $this->session->set_userdata('user_role_id_fk',$response->user_role_id_fk);
                $this->session->set_userdata('user_role_name',$response->user_role_name); 
                $this->session->set_userdata('user_district_id_fk',$response->user_district_id_fk);
                $this->session->set_userdata('user_first_name',$response->user_first_name);
                $this->session->set_userdata('user_last_name',$response->user_last_name);
                $this->session->set_userdata('prifile_image',$response->prifile_image);
                  if($response->user_role_id_fk == 4)
                  {
                    $this->session->set_flashdata('errorMsg', "Complainant Not allowed");
                    $this->messages('alert alert-danger',"Complainant Not allowed"); 

                    $this->logout_user(); exit();
                  }
                  else
                  {
                      redirect('/admin/dashboard'); exit();
                  }
                
                } // end is user name and passsword valid
                else // not match ue name and pass
                 {
                    $this->session->set_flashdata('errorMsg', "Username Or Password Invalid");
                    $this->messages('alert alert-danger',"Username Or Password Invalid"); 

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
        $this->session->unset_userdata('user_id'); 
        $this->session->unset_userdata('user_role_name'); 
        $this->session->unset_userdata('user_district_id_fk'); 
        $this->session->sess_destroy();
        $this->clear_cache();
        session_start();
        $this->messages('alert alert-danger',"session expired"); 
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
        $data['complaints']     = $this->model->countAllResult('complaints');
        $data['web']            = $this->model->countAll('complaints','complaint_source','web');
        $data['mobile_app']     = $this->model->countAll('complaints','complaint_source','mobile-app');
        $data['pending']        = $this->model->countAll('complaints','complaint_status_id_fk',1);
        $data['complete']       = $this->model->countAll('complaints','complaint_status_id_fk',5);
        $data['reject']         = $this->model->countAll('complaints','complaint_status_id_fk',6);
        $data['itstaffAdmin']   = $this->model->countAll('users','user_role_id_fk',2);
        $data['districtAdmin']  = $this->model->countAll('users','user_role_id_fk',3);
        $data['activeDistricts']= $this->model->countAll('districts','district_status',1);
        $data['inActiveDistricts']= $this->model->countAll('districts','district_status',0);
        $data['policeStations'] = $this->model->countAll('police_stations','police_station_status',1);
        $data['districts']      = $this->model->countAllResult('districts');
        $data['thisDay']        = $this->model->thisDay(); 
        $data['thisMonth']      = $this->model->thisMonth();
        $data['thisYear']       = $this->model->thisYear(); 
        $data['users']          = $this->model->countUsersByRoleId(3); 
        $data['complainants']   = $this->model->countUsersByRoleId(4);
        $this->load->view('template',$data);
    }

    public function dashboard1()
    {   
        //$this->check_role_privileges('dashboard',$this->session->userdata('user_role_id_fk'));
        
        $data['title']          = 'Dashboard';
        $data['page']           = 'dashboard1';
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
    // profile
    //==========================================================================

    public function profile()
    {   
        $data['title']       = 'User Profile';
        $data['page']        = 'profile';
        $user_role_id_fk     = $this->session->userdata('user_role_id_fk');
        if(empty($user_role_id_fk))
        {
            $this->logout_user();
        }       
        $this->load->view('template',$data);
    } 
    //==========================================================================
    // profile info ajax
    //==========================================================================

    public function profle_info()
    {   
        $user_role_id_fk = $this->session->userdata('user_role_id_fk');
        $profile         = $this->model->profile($user_role_id_fk);      
        echo json_encode($profile); exit;
    }

    //==========================================================================
    // view user
    //==========================================================================

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
    // user List
    //==========================================================================

    function users_list()
    { 
        $users = $this->model->IT_district_admins();
        echo json_encode($users); exit;
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
            echo strip_tags($message); exit;
            
        }
        else
        {   
            if($this->input->post('user_role_id_fk') == 3) // to for District admin
            { // check the dublicate user of district
                $dublicate_district_arr = array('user_role_id_fk'=>3,'user_district_id_fk'=>$this->input->post('district_id'));
                $dublicate_response = $this->model->dublicate_district_admin($dublicate_district_arr);
                if($dublicate_response > 0)
                {
                    echo "District Admin is Already Exist"; exit;
                }
            }
            $user_name            = $this->input->post('user_name');
            $user_password        = $this->input->post('user_password');
            $user_district_id_fk  = (empty($this->input->post('district_id'))? 0:$this->input->post('district_id'));
            $user_role_id_fk      = $this->input->post('user_role_id_fk');
            $table_name           = 'users';
            
            // insert user array
            $inert_it_array       = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_district_id_fk'=>$user_district_id_fk,'user_status'=>1,'user_role_id_fk'=>$user_role_id_fk);
            $table_name           = 'users';

            $response = $this->model->insert($inert_it_array,$table_name);

                if($response == true)
                {
                    echo "Record Add"; exit;
                }
                else
                {
                    echo "Some Thing Wrong"; exit;
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
            $user_status       = $this->input->post('user_status');
            $user_role_id_fk      = $this->input->post('user_role_id_fk');
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
            
            // $this->form_validation->set_rules('user_password', 'Password', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                echo strip_tags($message);exit;
                
            }
            else
            {
                $user_district_id_fk= (empty($this->input->post('district_id'))? 0:$this->input->post('district_id'));
                if(empty($user_password))
                {
                    $update_it_array   = array('user_name'=>$user_name,'user_status'=>$user_status,'user_district_id_fk'=>$user_district_id_fk,'user_role_id_fk'=>$user_role_id_fk);
                }
                else
                {
                  $update_it_array   = array('user_name'=>$user_name,'user_password'=>md5($user_password),'user_status'=>$user_status,'user_district_id_fk'=>$user_district_id_fk,'user_role_id_fk'=>$user_role_id_fk);  
                }
                
                $response = $this->model->update($update_it_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        echo "Recored Update"; exit; 
                    }
                    else
                    {
                        echo "Some Thing Wrong"; exit;
                    }
            }
        }
    }
    //==========================================================================
    // Delete User
    //==========================================================================
    function users_delete()
    {
        $user_id = $this->input->post('id');
        if($user_id > 0)
        {   
            $talbe_column_name = 'user_id';
            $table_name        = 'users';
            $table_id          = $user_id; 
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name); 
            if($response == true)
            {
                echo "Record Delete"; exit;
            }
            else
            {
                echo "Some Thing Wrong"; exit;
            }
        }
    }
    //==========================================================================
    // police station view
    //==========================================================================
    
    public function police_stations()
    {   
        $this->check_role_privileges('police_stations',$this->session->userdata('user_role_id_fk'));
        $data['police_sation'] = $this->model->police_stations();
        // git district
        $data['district']  = $this->model->status_active_record('districts','district_status',1);
        $data['title']    = 'Police Stations';
        $data['page']     = 'police_stations';
        $this->load->view('template',$data);
    }  
    //==========================================================================
    // police station List
    //==========================================================================
    
    public function police_station_list()
    {
        $police_sation = $this->model->police_stations();
        echo json_encode($police_sation); exit;
    }

    //==========================================================================
    //add police station
    //==========================================================================
    public function police_station_insert()
    {
        $this->form_validation->set_rules('police_station_name', 'Police Station Name', 'required|trim|is_unique[police_stations.police_station_name]|max_length[100]');
        $this->form_validation->set_rules('district_id_fk', 'Select District', 'required|trim');
        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            echo strip_tags($message); exit;
            
        }
        else
        {
            $police_station_name  = $this->input->post('police_station_name');
            $district_id_fk       = $this->input->post('district_id_fk');

            $insert_array   = array('police_station_name'=>$police_station_name,'district_id_fk'=>$district_id_fk,'police_station_status'=>1);
            $table_name        = 'police_stations';
            $response = $this->model->insert($insert_array,$table_name);
                if($response == true)
                {
                    echo "Record Add"; exit; 
                }
                else
                {
                    echo "Some Thing Wrong"; exit;
                }
        }
    }
    //==========================================================================
    // police station view modal 
    //==========================================================================
    public function police_station_edit_model($police_station_id)
    {
            $table_name        = "police_stations";
            $talbe_column_name = 'police_station_id';
            $table_id          = $police_station_id;

            $polioce_station = $this->model->exist_record_row($talbe_column_name,$table_id,$table_name);  // get row
            echo json_encode($polioce_station); exit(); 
    } 
    //==========================================================================
    // Police Station update
    //==========================================================================
    public function police_station_update()
    {
        if($this->input->post('police_station_id'))
        {
            $this->form_validation->set_rules('police_station_name', 'Police Station Name', 'required|trim');
            $this->form_validation->set_rules('police_station_status', 'Police Station Status', 'required|trim');
            $this->form_validation->set_rules('district_id_fk', 'District', 'required|trim');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                echo strip_tags($message); exit;
                
            }
            else
            {
                $police_station_id     = $this->input->post('police_station_id');
                $police_station_name   = $this->input->post('police_station_name');
                $district_id_fk        = $this->input->post('district_id_fk');
                $police_station_status = $this->input->post('police_station_status');

                $update_ps_array   = array('police_station_name'=>$police_station_name,'district_id_fk'=>$district_id_fk,'police_station_status'=>$police_station_status);
                $table_name        = 'police_stations';
                $talbe_column_name = 'police_station_id';
                $table_id          = $police_station_id;
                $response = $this->model->update($update_ps_array,$table_name,$talbe_column_name,$table_id);
                    if($response == true)
                    {
                        echo "Recored Update"; exit;
                    }
                    else
                    {
                        echo "Some Thing Wrong"; exit;
                    }
            }
        }
    }
    //==========================================================================
    // Police Station Delete
    //==========================================================================
    
    function police_station_delete()
    {
        $police_station_id = $this->input->post('id');
        if($police_station_id > 0)
        {   
            $table_name        = 'police_stations';
            $talbe_column_name = 'police_station_id';
            $table_id          = $police_station_id;
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name);
                if($response == true)
                { 
                    echo "Record Delete"; exit;
                }
                else
                {
                    echo "Some Thing Wrong"; exit;
                }
        }
    }
    //==========================================================================
    // district view
    //==========================================================================
    
    public function districts()
    {   
        $this->check_role_privileges('districts',$this->session->userdata('user_role_id_fk'));
        $data['title']    = 'Districts';
        $data['page']     = 'districts';
        $this->load->view('template',$data);
    } 
     //==========================================================================
    // district list
    //==========================================================================
    
    public function districts_list()
    {   
        $table_name = 'districts';
        $order_by = 'district_id';
        $order    = 'DESC';
        $districts = $this->model->get_all_records($table_name,$order_by,$order);
        echo json_encode($districts);
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
                echo strip_tags($message);
                
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
                        echo "Recored Update"; exit;
                    }
                    else
                    {
                        echo "Some Thing Wrong"; exit;
                    }
            }
        }
    }

    //==========================================================================
    // district insert
    //==========================================================================

    public function districts_insert()
    {
            $this->form_validation->set_rules('district_name', 'District Name', 'required|trim|is_unique[districts.district_name]');
            if ($this->form_validation->run() == FALSE)
            {
                $error   = array('error' => validation_errors());
                $message = implode(" ",$error);
                echo strip_tags($message); exit;
                
            }
            else
            {
                $district_name   = $this->input->post('district_name');

                if(strlen($district_name) > 35)
                {
                    echo "District Name should be less than 35 letters";exit;
                }

                $update_it_array   = array('district_name'=>$district_name,'district_status'=>1);
                $table_name        = 'districts';
                $response = $this->model->insert($update_it_array,$table_name);
                    if($response == true)
                    { 
                        echo "Record Add"; exit;
                    }
                    else
                    {
                        echo "Some Thing Wrong"; exit;
                    }
            }
        
    }
    //==========================================================================
    // district Delete
    //==========================================================================

    function district_delete()
    {  $district_id = $this->input->post('id');
        if($district_id > 0)
        {   
            $table_name        = 'districts';
            $talbe_column_name = 'district_id';
            $table_id          = $district_id;
            $response = $this->model->delete($talbe_column_name,$table_id,$table_name);
                if($response == true)
                {
                    echo "Record Delete"; exit; 
                }
                else
                {
                    echo "Some Thing Wrong"; exit;
                }
        }
    }

    //==========================================================================
    // complaint Categories view
    //==========================================================================

    public function complaint_categories()
    {
        $this->check_role_privileges('complaint_categories',$this->session->userdata('user_role_id_fk'));

        // $table_name = 'complaint_categories';
        // $order_by = 'complaint_category_id';
        // $order    = 'DESC';
        // $data['complaint_categories'] = $this->model->get_all_records($table_name,$order_by,$order);
        $data['title']    = 'Complaint Categories';
        $data['page']     = 'complaint_categories';
        $this->load->view('template',$data);
    }

    public function complaint_categories_list()
    {
        $table_name = 'complaint_categories';
        $order_by = 'complaint_category_id';
        $order    = 'DESC';
        $complaint_categories = $this->model->get_all_records($table_name,$order_by,$order);
        echo json_encode($complaint_categories); exit;
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
                echo strip_tags($message); exit;
                // $this->messages('alert-danger',$message);
                // return redirect('admin/complaint_categories');
                
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
                        // $this->messages('alert-success','Recored Update');
                        // return redirect('admin/complaint_categories'); 
                        echo "Recored Update"; exit;
                    }
                    else
                    {
                        // $this->messages('alert-danger','Some Thing Wrong');
                        // return redirect('admin/complaint_categories');
                        echo "Some Thing Wrong"; exit;
                    }
            }
        }
    }
    
    //==========================================================================
    // complaint category insert
    //==========================================================================

    function complaint_category_insert()
    { 
        $this->form_validation->set_rules('complaint_category_name', 'Username', 'required|trim|is_unique[complaint_categories.complaint_category_name]');
        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
             echo strip_tags($message); exit;
            
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
                        echo "Complaint Category Already Exists"; exit;
                    }
                    else
                    {
                        $inert_it_array   = array('complaint_category_name'=>$complaint_category_name,'complaint_category_status'=>1);
                        $response = $this->model->insert($inert_it_array,$table_name);
                            if($response == true)
                            {
                                echo "Record Add"; exit;
                            }
                            else
                            {
                                echo "Some Thing Wrong"; exit;
                            }
                    }
        }
    }
    //==========================================================================
    // complaint category delete
    //==========================================================================

    function complaint_categories_delete()
    {
        $complaint_category_id = $this->input->post('id');
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
                // $this->messages('alert-success','Successfully Deleted');
                // return redirect('admin/complaint_categories');
                echo "Record Delete"; exit;
            }
            else
            {
                // $this->messages('alert-danger','Some Thing Wrong');
                // return redirect('admin/complaint_categories');
                echo "Some Thing Wrong"; exit;
            }
        }
    }
    
    //==========================================================================
    // complaint
    //==========================================================================
    
    public function complaint_register_ajax() 
    {    
    	// CNIC Validation
         $form_cnic         = $this->input->post('complainant_cnic');
         $validate_cnic = str_replace("-", "", $form_cnic);
         $validate_cnic = str_replace("_", "", $validate_cnic);
         if(strlen($validate_cnic) < 13)
         {
             echo "Invalid CNIC"; exit;
         }
         
    	// contact number validation
    	 $form_contact          = $this->input->post('complainant_contact');
         $validate_contact = str_replace("-", "", $form_contact);
         $validate_contact = str_replace("_", "", $validate_contact);
         if(strlen($validate_contact) < 11)
         {
             echo "Invalid contact number"; exit;
         }
         

        $this->form_validation->set_rules('complainant_name', 'Complainant Name', 'required|trim');
        $this->form_validation->set_rules('complainant_contact', 'Complainant Contact', 'required|trim|max_length[12]|min_length[12]');
        $this->form_validation->set_rules('complainant_guardian_name', 'Complainant Guardian Name', 'required|trim');
        $this->form_validation->set_rules('complainant_council', 'Complainant Union Council', 'required|trim');
        $this->form_validation->set_rules('complainant_gender', 'Complainant Gender', 'required|trim');
        $this->form_validation->set_rules('complainant_cnic', 'Complainant CNIC', 'required|trim|max_length[15]|min_length[15]');
        $this->form_validation->set_rules('complaint_category_id', 'Complaint Category_id', 'required|trim');
        $this->form_validation->set_rules('complainant_address', 'Complainant Address', 'required|trim');
        $this->form_validation->set_rules('district_id_fk', 'District', 'required|trim');
        $this->form_validation->set_rules('complaint_detail', 'Complaint Detail', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            echo strip_tags($message,"<p></p>");
            exit;
        }
        else
        {  // get form data
            $attachments = array(); 
            $complainant_name           = $this->input->post('complainant_name');
            // $complainant_contact        = $this->input->post('complainant_contact');
            $complainant_contact        = $validate_contact;
            $complainant_guardian_name  = $this->input->post('complainant_guardian_name'); 
            $complainant_council        = $this->input->post('complainant_council'); 
            $complaint_council          = $this->input->post('complaint_council');
            $complainant_email          = $this->input->post('complainant_email');
            $complainant_gender         = $this->input->post('complainant_gender');
            // $complainant_cnic           = $this->input->post('complainant_cnic');
            $complainant_cnic           = $validate_cnic;
            $complaint_category_id      = $this->input->post('complaint_category_id');
            $complainant_address        = $this->input->post('complainant_address');
            $complaint_detail           = $this->input->post('complaint_detail');
            $attachments                = $this->input->post('attachments');
            $complainant_district_id_fk  = $this->input->post('home_district_id'); 
            $district_id_fk             = $this->input->post('district_id_fk');
            
            // complaintant checking
            $registered_by_user = $this->session->userdata('user_id'); 
            if(empty($registered_by_user))
            { 
                echo "Your session is expired please loing";
                exit;
            } 
            
            $complainant_response = $this->model->getComplainant($complainant_cnic);
            if(is_object($complainant_response))  
            {   
                $complainant_id     = $complainant_response->complainant_id; 
            }
            else
            {
                $inert_complainant_array   = array(
                                                    'user_id_fk'                 => 0,
                                                    'complainant_council'        => $complainant_council,
                                                    'complainant_name'           => $complainant_name,
                                                    'complainant_guardian_name'  => $complainant_guardian_name,
                                                    'complainant_contact'        => $complainant_contact,
                                                    'complainant_cnic'           => $complainant_cnic,
                                                    'complainant_gender'         => $complainant_gender,
                                                    'complainant_email'          => $complainant_email,
                                                    'complainant_address'        => $complainant_address,
                                                    'complainant_status'         => 1,
                                                    'complainant_district_id_fk' => $complainant_district_id_fk
                                                ); 
                $complainant_id     = $this->model->insert_with_last_insert_id('complainants',$inert_complainant_array);
            }
            
            $insert_complaint_array   = array(
                                        'registered_by_user'        => $registered_by_user,
                                        'complainant_id_fk'         => $complainant_id,
                                        'complaint_category_id_fk'  => $complaint_category_id,
                                        'complaint_council'         => $complaint_council,
                                        'complaint_detail'          => $complaint_detail,
                                        'complaint_status_id_fk'    => '1', //  1= pending, will make it global in next update
                                        'complaint_source'          => 'web',
                                        'district_id_fk'            => $district_id_fk
                                    );
                $this->load->model('ComplaintModel');
                $response = $this->ComplaintModel->complaint_add($insert_complaint_array); 
                echo $response['response_msg']; exit;
        }
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
        
        // get active police_stations 
        $table_name4                   = 'police_stations';
        $table_status_column_name4     = 'police_station_status';
        $table_status_column_value4    = 1;
        $data['police_stations'] = $this->model->status_active_record($table_name4,$table_status_column_name4,$table_status_column_value4);        
        
        $this->load->view('template',$data);
    }
    // ::::::::::::::::::::::::::::::::: complaints with datatable
    function complaints($test_msg= null)
    { 
        $this->check_role_privileges('complaints',$this->session->userdata('user_role_id_fk'));
        $data['complaints'] = $this->complaint->get_complaints_with_role();
        $data['title']    = 'Complaints';
        $data['page']     = 'complaints';
        if($test_msg == 1)
        {
            $this->messages('alert-success','Complaint Register Succcessfully');
        }
        $this->load->view('template',$data);
    }

    public function detail_report()
    { 
        $this->check_role_privileges('complaints',$this->session->userdata('user_role_id_fk'));

        $data = array();
        $displayLimit = "10";

        $district_id = "";
        $complaint_status_id = "";
        $from_date           = "";
        $to_date             = "";
        $complaint_source    = "";
        $search_text         = "";
        $sort_by_column      = "";
        $sort_by_value       = "";

        if($this->session->userdata('displayLimit'))
        {
            $displayLimit = $this->session->userdata('displayLimit');
        }
        if($this->session->userdata('district_id'))
        {
            $district_id = $this->session->userdata('district_id');
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
        if($this->session->userdata('search_text'))
        {
            $search_text = $this->session->userdata('search_text');
        }
        if($this->session->userdata('sort_by_column'))
        {
            $sort_by_column = $this->session->userdata('sort_by_column');
        }
        if($this->session->userdata('sort_by_value'))
        {
            $sort_by_value = $this->session->userdata('sort_by_value');
        }

        
        if($this->input->post())
        {

            $district_id = $this->input->post('district_id');
            $this->session->set_userdata('district_id',$district_id);

            $complaint_status_id = $this->input->post('complaint_status_id');
            $this->session->set_userdata('complaint_status_id',$complaint_status_id);

            $from_date           = $this->input->post('from_date');
            $this->session->set_userdata('from_date',$from_date);

            $to_date             = $this->input->post('to_date');
            $this->session->set_userdata('to_date',$to_date);

            $complaint_source    = $this->input->post('complaint_source');
            $this->session->set_userdata('complaint_source',$complaint_source);

            $search_text    = $this->input->post('search_text');
            $this->session->set_userdata('search_text',$search_text);

            $sort_by_column    = $this->input->post('sort_by_column');
            $this->session->set_userdata('sort_by_column',$sort_by_column);

            $sort_by_value    = $this->input->post('sort_by_value');
            $this->session->set_userdata('sort_by_value',$sort_by_value);
        }

        // $data['district']=$district;
        $data['displayLimit']        = $displayLimit;
        $data['district_id']         = $district_id;
        $data['complaint_status_id'] = $complaint_status_id;
        $data['from_date']           = $from_date;
        $data['to_date']             = $to_date;
        $data['complaint_source']    = $complaint_source;
        $data['search_text']         = $search_text;
        $data['sort_by_column']      = $sort_by_column;
        $data['sort_by_value']       = $sort_by_value;

       
        $uri_segment = 3;
        $offset      = $this->uri->segment($uri_segment);
        $config = array();
        $config['total_rows']      = $this->complaint->count_complaints();
        $config['base_url']        = site_url('/admin/detail_report');
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
        // Districts
        $table_name3                  = 'districts';
        $table_status_column_name3    = 'district_status';
        $table_status_column_value3   = 1;
        $data['districts'] = $this->model->status_active_record($table_name3,$table_status_column_name3,$table_status_column_value3);
    
            
        $this->load->view('template/common_header');
        $this->load->view('template/navigation');   
        $this->load->view('detail_report',$data);
        $this->load->view('template/common_footer');

    }
    function complaint_detail($complaint_id)
    {
        $this->check_role_privileges('complaint_detail',$this->session->userdata('user_role_id_fk'));

        $data['title'] = 'Complaint Detail';
        $data['page']  = 'complaint_detail'; 
        $check_valid_complaint = $this->complaint->get_complaint_by_id($complaint_id);
        if(empty($check_valid_complaint))
        {
          return redirect('admin/complaints'); exit;
        }
        else
        {
           $data['complaint_detail']     = $this->complaint->get_complaint_by_id($complaint_id); // complinat detailed  
        }
        
        $data['complaint_attachment'] = $this->complaint->get_attachment_complaint_by_id($complaint_id); // complinat attachments 
        // $data['complaint_statuses']   = $this->model->get_all_records('complaint_statuses');
         // complaints status
        $table_name                 = 'complaint_statuses';
        $table_status_column_name   = 'status';
        $table_status_column_value  = 1;
        
        $data['complaint_statuses'] =   $this->model->status_active_record($table_name,$table_status_column_name,$table_status_column_value);
        $data['respondats']           = $this->model->get_all_records('respondents');
        $data['complaint_id']         = $complaint_id;
        $data['complaints_remarks']   = $this->complaint->get_complaint_remarks($complaint_id);
        //get active districts
        $table_name                = 'districts';
        $table_status_column_name   = 'district_status';
        $table_status_column_value  = 1;
        $data['district']           = $this->model->status_active_record($table_name,$table_status_column_name,$table_status_column_value);

        
        $this->load->view('template',$data);
    }
    function complaints_remarks_list($complaint_id = null)
    {
        $complaints_remarks   = $this->complaint->get_complaint_remarks($complaint_id);
        echo json_encode($complaints_remarks);
    }
   
    function insert_comploaint_remarks()
    {
        $this->form_validation->set_rules('complaint_status_id_fk', 'Complainant Status', 'required|trim');
        // $this->form_validation->set_rules('complaint_remarks_timestamp', 'Complainant Date', 'required|trim');
        $this->form_validation->set_rules('complaint_remarks_detail', 'Complainant Remarks', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
            $error   = array('error' => validation_errors());
            $message = implode(" ",$error);
            echo strip_tags($message);
            
        }
        else
        {
            $complaint_status_id_fk       = $this->input->post('complaint_status_id_fk');
            $respondent_id_fk             = empty($this->input->post('respondent_id_fk') )?'0':$this->input->post('respondent_id_fk');
            // $complaint_remarks_timestamp  = $this->input->post('complaint_remarks_timestamp');
            $complaint_remarks_timestamp  = date('Y-m-d H:i:s');
            $complaint_remarks_detail     = $this->input->post('complaint_remarks_detail');
            $user_id_fk                   = $this->session->userdata('user_id');
            $table_name                   = 'complaint_remarks';
            $complaint_id                 = $this->input->post('complaint_id');
            if(empty($user_id_fk))
            {
                echo "Your session is expired please login again"; exit;
            } 
            $uploadPath     = 'assets/complaint_remarks_attachment';
            $this->load->library('image_lib');
				if (!file_exists($uploadPath)) 
				{
					mkdir($uploadPath);
					chmod($uploadPath, 0777);
				}
            if (!empty($_FILES['attachment']['name']))
            {
                $this->load->library('upload');
                $config['upload_path']   = $uploadPath;
                $config['allowed_types'] = '*';
                $config['max_width']     = '';
                $config['max_height']    = '';
                $config['remove_spaces'] = TRUE;
                $config['encrypt_name']  = FALSE;
                $config['detect_mime']   = TRUE;
                $config['overwrite']     = FALSE;
                $varAttachment = 'attachment_'.date("YmdHis");;
                $config['file_name'] = $varAttachment;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('attachment')):
                    echo "Error in uploading attachment";
                    exit;
                else:
                    $image_data =   $this->upload->data();
                   // image resize
                        $configer =  array(
                                            'image_library'   => 'gd2',
                                            'source_image'    =>  $image_data['full_path'],
                                            'maintain_ratio'  =>  TRUE,
                                            'width'           =>  auto,
                                            'height'          =>  auto,
                                          );
                                        
                        $this->image_lib->clear();
                        $this->image_lib->initialize($configer);
                        $this->image_lib->resize();
                    // edn of image resize
                        $data = array(
                            'upload_data' => $this->upload->data()
                        );
                        $attachment = $data['upload_data']['file_name'];
                endif;
            }
            else
            {
               $attachment = ''; 
            }
            $complaint_remarks_array   = array(
                                                'complaint_status_id_fk'       => $complaint_status_id_fk,
                                                'respondent_id_fk'             => $respondent_id_fk,
                                                'complaint_remarks_timestamp'  => $complaint_remarks_timestamp,
                                                'complaint_remarks_detail'     => $complaint_remarks_detail,
                                                'user_id_fk'                   => $user_id_fk,
                                                'complaint_id_fk'              => $complaint_id,
                                                'attachment'                   => $attachment,
                                                'complaint_remarks_status'     =>1
                                            ); 

            $response     = $this->model->insert($complaint_remarks_array,$table_name);
            $update_array = array('complaint_status_id_fk'=>$complaint_status_id_fk);
            $this->model->update($update_array,'complaints','complaint_id',$complaint_id); // update complaint status

                if($response == true)
                {
                    echo "Record Add"; exit;
                }
                else
                {
                    echo "Some Thing Wrong"; exit;
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

    //==========================================================================
    // police station json data for edit modal
    //==========================================================================

    function get_police_station_ajax($district_id_fk)
    { 
       $data = json_encode($this->model->get_by_id('police_stations','district_id_fk',$district_id_fk));
       echo $data;
    }

    //==========================================================================
    // complaints report district wise
    //==========================================================================

    function district_reports()
    { 
        $this->check_role_privileges('district_reports',$this->session->userdata('user_role_id_fk'));
        $data['district_reports'] = $this->complaint->district_reports();
        $data['title']    = 'District Reports';
        $data['page']     = 'district_reports';
        $this->load->view('template',$data);
    }

    //==========================================================================
    // complaints export into excel
    //==========================================================================

    public function exportIntoExcel() 
    {
        // load excel library
            $this->load->library('excel');
        
            $empInfo    = $this->complaint->export_complaints(); 
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Complaint Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Citizen Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Against District');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Source');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Status'); 
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Complaint Category'); 
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Complaint Council');
            // set Row
            $rowCount = 2;
            foreach ($empInfo as $att) 
            {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $att['complaint_entry_timestamp']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $att['complainant_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $att['district_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $att['complaint_source']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $att['complaint_status_title']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $att['complaint_category_name']); 
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $att['complaint_council']);
                $rowCount++;
            }
            
            $filename = "complaint". date("m-d-Y-H-i-s").".CSV";
            header('Content-Type: application/vnd.ms-excel'); 
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0'); 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');  
            $objWriter->save('php://output');       
        }

    function update_profile()
    { 
        $old_password = $this->input->post('old_password');
        $user_id      = $this->session->userdata('user_id');
        // check user session is available
            if($user_id > 0)
            {
            $user_password = $this->model->user_password($user_id);
                if(md5($old_password) !== $user_password) 
                {
                    echo "Invalid Old  Password"; exit;
                }
            }
            else
            {
                echo "Please login now"; exit;
            }
            // ::::::::::: profile image 
                $uploadPath     = 'assets/profile';
                $this->load->library('image_lib');
                    if (!file_exists($uploadPath)) 
                    {
                        mkdir($uploadPath);
                        chmod($uploadPath, 0777);
                    }
                if (!empty($_FILES['attachment']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path']   = $uploadPath;
                    $config['allowed_types'] = '*';
                    $config['max_width']     = '';
                    $config['max_height']    = '';
                    $config['remove_spaces'] = TRUE;
                    $config['encrypt_name']  = FALSE;
                    $config['detect_mime']   = TRUE;
                    $config['overwrite']     = FALSE;
                    $varAttachment = 'attachment_'.date("YmdHis");;
                    $config['file_name'] = $varAttachment;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('attachment')):
                        echo "Error in uploading attachment";
                        exit;
                    else:
                        $image_data =   $this->upload->data();
                    // image resize
                            $configer =  array(
                                                'image_library'   => 'gd2',
                                                'source_image'    =>  $image_data['full_path'],
                                                'maintain_ratio'  =>  TRUE,
                                                'width'           =>  auto,
                                                'height'          =>  auto,
                                            );
                                            
                            $this->image_lib->clear();
                            $this->image_lib->initialize($configer);
                            $this->image_lib->resize();
                        // edn of image resize
                            $data = array(
                                'upload_data' => $this->upload->data()
                            );
                            $prifile_image = $data['upload_data']['file_name'];
                    endif;
                }
                else
                {
                 $prifile_image = ''; 
                }
            // ::::::::::  profile image end
            if(!empty($prifile_image))
            {
                $update_profile = array(
                    'user_first_name'  => $this->input->post('user_first_name'),
                    'user_last_name'   => $this->input->post('user_last_name'),
                    'user_email'       => $this->input->post('user_email'),
                    'user_contact'     => $this->input->post('user_contact'),
                    'user_address'     => $this->input->post('user_address'),
                    'user_password'    => md5($this->input->post('confirm')),
                    'prifile_image'    => $prifile_image,
                   );
            }
            else
            {
                $update_profile = array(
                    'user_first_name'  => $this->input->post('user_first_name'),
                    'user_last_name'   => $this->input->post('user_last_name'),
                    'user_email'       => $this->input->post('user_email'),
                    'user_contact'     => $this->input->post('user_contact'),
                    'user_address'     => $this->input->post('user_address'),
                    'user_password'    => md5($this->input->post('confirm'))
                   );
            }
        
        $response = $this->model->update($update_profile,'users','user_id',$user_id);
            if($response == true)
            {
                if($this->input->post('remember') == 'on')
                {
                    echo "Please login now"; exit;
                }
                else
                { 
                    echo "Record Update"; exit;   
                }
                
            }
            else
            {
                echo "Some Thing Wrong"; exit;
            }                       
    }
    function print_complaint_detail($complaint_id=null)
    {

        $data['title'] = 'Complaint Detail';
        $data['page']  = 'print_complaint_detail'; 
        
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
        
        $this->load->view('print_complaint_detail',$data);
      
    }

    function forgot_passord()
    {
        $this->load->view('auth-forgot-password');
    }
    function get_complainant_by_cnic($cnic)
    {
     $response = $this->model->get_complainant_by_cnic($cnic);
     echo json_encode($response); exit;
    }
    function forgot_email_validation()
    {
        $user_email = $this->input->post('user_email');
        $returing_array = array();
        if(!empty($user_email))
        {
            $returing_array = $this->email_send_otp($user_email);
            // $response = $this->model->forgot_email_validation($user_email);
            // if(!empty($response))
            // {
            //     $user_first_name = $response->user_first_name;
            //     $user_last_name  = $response->user_last_name;
            //     $user_email      = $response->user_email;
            //     $user_id         = $response->user_id;
            //     $otp = substr(str_shuffle(time()), 0, 6);

            //     $update_array = array('vcode'=>trim($otp));
            //     $this->model->update($update_array,'users','user_id',$user_id);
            //     $htmlContent = "
            //                         <p>Hi, ".$user_first_name.' '.$user_last_name.", </p>
            //                         <p>We received a request to reset your password through your email address. Your PPSC verification code is: </p>
            //                         <h2>".$otp."</h2>
            //                         <p>
            //                             If you did not request this code, it is possible that someone else is trying to access your PPSC Account. 
            //                             <b>Do not forward or give this code to anyone.</b>
            //                         </p>
            //                         <a href='https://ppsc.kp.gov.pk'> https://ppsc.kp.gov.pk </a>
            //                     ";
            //                 $this->load->library('email');
            //                 $this->email->from('info@ppsc.kp.gov.pk', 'PPSC');
            //                 $this->email->to($user_email);
            //                 $this->email->subject('PPSC Verification Code');
            //                 $this->email->message($htmlContent); 
            //                 $this->email->set_mailtype("html");
                            
            //                 if($this->email->send())
            //                 {
            //                     $message =  "Kindly check your email for verification code.";
            //                     $returing_array['message'] = $message;
            //                     $returing_array['user_email'] = $user_email;
            //                     $returing_array['user_id'] = $user_id;
                                
            //                 }
            //                 else
            //                 {  $message =  "Kindly check your email for verification code.";
            //                     // echo "Failed to send verification code on email."; exit;
            //                     //$message =  "Kindly check your email for verification code.";
            //                     $returing_array['message'] = $message;
            //                     $returing_array['user_email'] = $user_email;
            //                     $returing_array['user_id'] = $user_id;
            //                 }
            // }
            // else
            // {
            //     $returing_array['message'] = "Invalid Email";
            // }
            
        }
        else
        {
            $returing_array['message'] = "Email filed is required";
        }
        echo json_encode($returing_array); exit;
    }
    function email_send_otp($user_email)
    {
        $response = $this->model->forgot_email_validation($user_email);
            if(!empty($response))
            {
                $user_first_name = $response->user_first_name;
                $user_last_name  = $response->user_last_name;
                $user_email      = $response->user_email;
                $user_id         = $response->user_id;
                $otp = substr(str_shuffle(time()), 0, 6);

                $update_array = array('vcode'=>trim($otp));
                $this->model->update($update_array,'users','user_id',$user_id);
                $htmlContent = "
                                    <p>Hi, ".$user_first_name.' '.$user_last_name.", </p>
                                    <p>We received a request to reset your password through your email address. Your PPSC verification code is: </p>
                                    <h2>".$otp."</h2>
                                    <p>
                                        If you did not request this code, it is possible that someone else is trying to access your PPSC Account. 
                                        <b>Do not forward or give this code to anyone.</b>
                                    </p>
                                    <a href='https://ppsc.kp.gov.pk'> https://ppsc.kp.gov.pk </a>
                                ";
                            $this->load->library('email');
                            $this->email->from('info@ppsc.kp.gov.pk', 'PPSC');
                            $this->email->to($user_email);
                            $this->email->subject('PPSC Verification Code');
                            $this->email->message($htmlContent); 
                            $this->email->set_mailtype("html");
                            
                            if($this->email->send())
                            {
                                $message =  "Kindly check your email for verification code.";
                                $returing_array['message'] = $message;
                                $returing_array['user_email'] = $user_email;
                                $returing_array['user_id'] = $user_id;
                                
                            }
                            else
                            {  $message =  "Kindly check your email for verification code.";
                                // echo "Failed to send verification code on email."; exit;
                                //$message =  "Kindly check your email for verification code.";
                                $returing_array['message'] = $message;
                                $returing_array['user_email'] = $user_email;
                                $returing_array['user_id'] = $user_id;
                            }
            }
            else
            {
                $returing_array['message'] = "Invalid Email";
            }
            return $returing_array; exit;
    }

    function conformation_code()
    {
       $user_email =  $this->input->post('user_email');
       $user_id    =  $this->input->post('user_id');
       $vcode      =  $this->input->post('vcode');
       $resend_code = $this->input->post('resend_code'); 
       $returing_array = array();
        if(isset($resend_code))
        {
           $returing_array = $this->email_send_otp($user_email); 
        //    $returing_array['user_id']=  $user_id;
        //    $returing_array['user_email']=  $user_email;
           echo json_encode($returing_array); exit;
        }
        else
        {
            if(!empty($user_id) && !empty($vcode) )
            {
                $array = array('user_email'=>$user_email,'vcode'=>$vcode,'user_id'=>$user_id);
                $user_response = $this->model->check_record_by_array($array,'users');
                if(!empty($user_response))
                {
                    $returing_array['message']=  "record exists";
                    $returing_array['user_id']=  $user_id;
                    $returing_array['user_email']=  $user_email;
                }
                else
                {
                    $returing_array['message'] =  "invalic conformation code";
                }
            }
            else
            {
                $returing_array['message'] = "vscode field is required";
            }
        }
       
       
       echo json_encode($returing_array); exit;
    }
    function update_password()
    {
       $user_email       =  $this->input->post('user_email');
       $user_id          =  $this->input->post('user_id');
       $user_cpassword    =  $this->input->post('r_cpassword');
       $user_password    =  $this->input->post('r_password');
       if($user_password  !== $user_cpassword)
       {
           echo "Password Not Match"; exit;
       }

       if(!empty($user_id) && !empty($user_password) )
       {
           $update_array = array('user_password'=>md5($user_password),'vcode'=>'');
           $user_response = $this->model->update($update_array,'users','user_id',$user_id);
           if(!empty($user_response))
           {
               echo "Password Update Successfully please Login now"; exit;
           }
           else
           {
               echo "invalic conformation code"; exit;
           }
       }
       else
       {
           echo "vscode field is required"; exit;
       }
       echo "password and conform passwor field is required"; exit;
    }

}

?>