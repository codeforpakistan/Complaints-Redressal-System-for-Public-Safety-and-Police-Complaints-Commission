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
            return array('response'=>0,'response_msg'=>'Required Fields: '.implode(", ",array_keys($missing)));
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
                   return array('response'=>0,'response_msg'=>'Admin not authorized');
                }
                
            break;
            
            case 'complainant':
                
                $find_complainant_id = $this->db->query('select complainants.complainant_id, complainants.user_id_fk, users.user_id from complainants left join users on users.user_id = complainants.user_id_fk where user_id_fk = ?',array($data_arr['registered_by_user']))->result_array();
        
                if(count($find_complainant_id) == 1)
                {
                    if($find_complainant_id[0]['user_id'] == null)
                    {
                        return array('response'=>0,'response_msg'=>'Complainant\'s user_id not valid');
                    }
                }
                else if(count($find_complainant_id) == 0 || count($find_complainant_id) > 1)
                {
                    return array('response'=>0,'response_msg'=>'Complainant\'s user_id not found'.$find_complainant_id['user_id']);
                }
                
            break;
            
            default:
            break;
        }
        
        //======================================================================
        // category validation
        //======================================================================
        
        if(trim($data_arr['complainant_id_fk']) == '' || trim($data_arr['complainant_id_fk']) == '0')
        {
            return array('response'=>0,'response_msg'=>'Complaint-Category Required');
        }
        
        //======================================================================
        // complainant validation
        //======================================================================
        
        $find_complainant = $this->db->query('select * from complainants where complainant_id = ?',array($data_arr['complainant_id_fk']))->result_array();
        
        if(count($find_complainant) == 0)
        {
            return array('response'=>0,'response_msg'=>'Selected complainant is not valid');
        }
        
        //======================================================================
        // district validation
        //======================================================================
        
        $find_district = $this->db->query('select * from districts where district_id = ?',array($data_arr['district_id_fk']))->result_array();
        
        if(count($find_district) == 0)
        {
            return array('response'=>0,'response_msg'=>'Selected district is not valid');
        }
        
        //======================================================================
        // category validation
        //======================================================================
        
        $find_category = $this->db->query('select * from complaint_categories where category_id = ?',array($data_arr['complaint_category_id_fk']))->result_array();
        
        if(count($find_category) == 0)
        {
            return array('response'=>0,'response_msg'=>'Selected category is not valid');
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
        
        $find_complaint = $this->db->query('select * from complaints where complainant_id_fk = ? and district_id_fk = ? and complaint_category_id_fk = ? and complaint_detail = ?',
                                            array($data_arr['complainant_id_fk'],$data_arr['district_id_fk'],$data_arr['complaint_category_id_fk']))->result_array();
        
        if(count($find_complaint) > 0)
        {
            return array('response'=>0,'response_msg'=>'This complaint is already registered');
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
                    
                    $attachment_upload_response = $this->upload_files($path,'complaint'.$complaint_id, $_FILES['attachments'],array('user_id_fk'=>$data_arr['registered_by_user'],'complaint_id_fk'=>$complaint_id,'remarks_id_fk'=>0));
                    
                    if ($attachment_upload_response['response'] == 0)
                    {
        			    return array('response'=>0,'response_msg'=>$attachment_upload_response['response_msg']);
                    }
                    else
                    {
                        $attachment_ids_string = implode(",",$attachment_upload_response['attachment_ids']); // if attachment id's required in return response
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
    
	function upload_files($path, $title, $files, $data_arr)
    {
        $required_fields = array('complaint_id_fk'=>0,'user_id_fk'=>0,'remarks_id_fk'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'response_msg'=>'Required Fields: '.implode(", ",array_keys($missing)));
        }
        
        //======================================================================
       
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

            if($this->upload->do_upload('attachments[]')) 
            {
                $timestamp_now = date("Y-m-d H:i:s",time());
                $file_path_formatted = substr($file_path, 2); // remove ./ from start of the path string
                
                $this->upload->data();
                
                $this->db->insert("INSERT INTO complaint_attachments (complaint_id_fk, user_id_fk, remarks_id_fk, complaint_attachment_type, complaint_attachment_file_path, complaint_attachment_status) VALUES (?, ?, ?, ?, ?, ?)", 
                                  array($data_arr['complaint_id_fk'],$data_arr['user_id_fk'],$data_arr['remarks_id_fk'],$file_extension,$file_path_formatted,'1')
                                );
                                
                $attachment_ids[] = $this->db->insert_id();
            }
            else 
            {
                return array('response'=>0,'response_msg'=>'Failed to upload attachment, no.'.$key);
            }
        }

        return array('response'=>1,'attachment_ids'=>$attachment_ids);
    }
    
    public function complaints_get($data_arr)
    {
        $cond_query = '';
        $cond_arr = [];
        
        //======================================================================
        // set condition arr
        //======================================================================
        
        $optional_cond_arr = array('complainant_id_fk','registered_by_user','district_id_fk','complaint_category_id_fk','complaint_status'=>0);
        
        foreach($optional_cond_arr as $key=>$value)
        {
            if(isset($data_arr[$value]))
            {
                $cond_row = ' and  complaints.'.$value.' = ?';
                
                $cond_query .= $cond_row;
                // $cond_arr[$value] = trim($data_arr[$value]);
                array_push($cond_arr,trim($data_arr[$value]));
            }
        }
        
        if(isset($data_arr['complainant_name']))
        {
            $cond_query .= ' and complainants.complainant_name = ?';
            array_push($cond_arr,trim($data_arr['complianant_name']));
        }
        
        //======================================================================
        // query
        //======================================================================
        
        $cond_query_formatted = ltrim(trim($cond_query),"and");
        
        $get_complainants = $this->db->query('select 
                                              complaints.*,
                                              complainants.complainant_name
                                              from complaints
                                              left join complainants on complainants.complainant_id = complaints.complainant_id_fk
                                              where
                                              '.$cond_query_formatted,$cond_arr)->result_array();
        return $get_complainants;
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