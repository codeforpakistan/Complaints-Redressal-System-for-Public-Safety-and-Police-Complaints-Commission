<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');

class ComplaintModel extends CI_Model
{
    
    public function complaint_add($data_arr)
    {
        $required_fields = array('complaint_source'=>0,'registered_by_user'=>0,'complainant_id_fk'=>0,'complaint_category_id_fk'=>0,'district_id_fk'=>0,'complaint_detail'=>0,'complaint_status'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Required Fields: '.implode(", ",array_keys($missing))));
        }
        
        //======================================================================
        // registered_by_user validation
        // -> complainant's user_id if from android/complainant
        // -> admin's user_id if from web-portal/admin
        //======================================================================
        
        switch($data_arr['complaint_source'])
        {
            case 'admin':
                
                $this->load->model('AuthModel');
    
                $find_admin = $this->AuthModel->users_get(array('user_id'=>$data_arr['registered_by_user']));
        
                if(count($find_admin) == 0)
                {
                   return array('response'=>0,'data'=>array('response_msg'=>'Admin not authorized'));
                }
                
            break;
            
            case 'complainant':
                
                $find_complainant_id = $this->db->query('select complainants.complainant_id, complainants.user_id_fk, users.user_id from complainants left join users on users.user_id = complainants.user_id_fk where user_id_fk = ?',array($data_arr['registered_by_user']))->result_array();
        
                if(count($find_complainant) == 1)
                {
                    if($find_complainant['user_id'] == null)
                    {
                        return array('response'=>0,'data'=>array('response_msg'=>'Complainant\'s user_id not valid'));
                    }
                }
                else if(count($find_complainant) == 0 || count($find_complainant) > 1)
                {
                    return array('response'=>0,'data'=>array('response_msg'=>'Complainant\'s user_id not found'));
                }
                
            break;
            
            default:
            break;
        }
        
       
        
        //======================================================================
        // complainant validation
        //======================================================================
        
        $find_complainant = $this->db->query('select * from complainants where complainant_id = ?',array($data_arr['complainant_id_fk']))->result_array();
        
        if(count($find_complainant) == 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Selected complainant is not valid'));
        }
        
        //======================================================================
        // district validation
        //======================================================================
        
        $find_district = $this->db->query('select * from districts where district_id = ?',array($data_arr['district_id_fk']))->result_array();
        
        if(count($find_district) == 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Selected district is not valid'));
        }
        
        //======================================================================
        // category validation
        //======================================================================
        
        $find_category = $this->db->query('select * from complaint_categories where category_id = ?',array($data_arr['complaint_category_id_fk']))->result_array();
        
        if(count($find_category) == 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'Selected category is not valid'));
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
        
        $find_complaint = $this->db->query('select * from complaints where complainant_id_fk = ? and district_id_fk = ? and complaint_category_id_fk = ? and complaint_detail = ?',
                                            array($data_arr['complainant_id_fk'],$data_arr['district_id_fk'],$data_arr['complaint_category_id_fk']))->result_array();
        
        if(count($find_complaint) > 0)
        {
            return array('response'=>0,'data'=>array('response_msg'=>'This complaint is already registered'));
        }
        else
        {
            //==================================================================
            // insert complaint
            //==================================================================
            
            $insert_complainant = $this->db->insert('complaints',$data_arr);
            
            if($insert_complainant != false)
            {
                $complaint_id = $this->db->insert_id();
                
                //==============================================================
                // insert attachments
                //==============================================================
                
                if (!empty($_FILES['attachments']['name'][0])) 
                {
                    //==========================================================
                    // check if directory exists
                    //==========================================================
                    
                    if (!is_dir('./assets/complaints/'.$complaint_id))
                    {
                        mkdir('./assets/complaints/'.$complaint_id, 0777, true);
                    }
                    
                    //==========================================================
                    // pass attachments to upload function
                    //==========================================================
    
                    $path ='./assets/complaints/'.$complaint_id.'/';
                    
                    $attachment_ids_arr = $this->upload_files($path,'deposit', $_FILES['attachments'],$data_arr['registered_by_user']);
                    
                    if ($attachment_ids_arr === FALSE)
                    {
        			    return array('response'=>0,'response_msg'=>'Failed to upload attachments');
                    }
                    else
                    {
                        $attachment_ids_string = implode(",",$attachment_ids_arr); // if attachment id's required in return response
                    }
                }
                
                //==============================================================
                // return
                //==============================================================
                
                return array('response'=>1,'response_msg'=>'Complaint Registered Successfully','complaint_id'=>$complainant_id);
            }
            else
            {
                return array('response'=>0,'response_msg'=>'Failed to insert complainant');
            }
        }
    }
    
    
	function upload_files($path, $title, $files)
    {
       
        $config = array(
                        'upload_path'   => $path,
                        'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx|csv|txt|rtf|zip|mp3|wma|flv|mpg|avi',
                        'overwrite'     => 1,                       
                        );

        $this->load->library('upload', $config);

        $attachments = array();
        $attachment_ids = array();

        foreach ($files['name'] as $key => $image) 
        {
            $file_path = '';
            
            $_FILES['attachments[]']['name']     = $files['name'][$key];
            $_FILES['attachments[]']['type']     = $files['type'][$key];
            $_FILES['attachments[]']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['attachments[]']['error']    = $files['error'][$key];
            $_FILES['attachments[]']['size']     = $files['size'][$key];
            
            $file_extension = pathinfo($image, PATHINFO_EXTENSION);
            
            $image_name = str_replace(str_split('\\/:*?"<>|_-'), '', $image);
            
            $fileName = $title.$user_id.'_'.str_shuffle(time()).'.'.$file_extension;
            $file_path =  $path.''.$fileName;
            
            $config['file_name'] = $fileName;
            $attachments[] = $fileName;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('attachments[]')) 
            {
                $timestamp_now = date("Y-m-d H:i:s",time());
                $file_path_formatted = substr($file_path, 2); // remove ./ from start of the path string
                
                $this->upload->data();
                
                $this->db->query("INSERT INTO tbl_investment_attachment 
                                  (investment_request_id_fk, user_id_fk, path, status) 
                                  VALUES 
                                  ('0','{$user_id}','{$file_path_formatted}','1')"
                                );
                                
                $attachment_ids[] = $this->db->insert_id();
            }
            else 
            {
                return false;
            }
        }

        return $attachment_ids;
    }
    
    public function complaints_view()
    {
        
    }
    
    public function complaint_edit()
    {
        
    }
    
    //==========================================================================
    // remarks
    //==========================================================================
    
    public function complaint_remarks_view()
    {
        
    }
    
    public function complaint_remark_add()
    {
        
    }
				
}

?>