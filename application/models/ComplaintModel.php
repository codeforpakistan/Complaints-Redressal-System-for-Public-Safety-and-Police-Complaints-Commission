<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');

class ComplaintModel extends CI_Model
{  
    public function complaint_add($data_arr)
    {
        $required_fields = array(
                                    'complaint_source'         => 0,
                                    'registered_by_user'       => 0,
                                    'complainant_id_fk'        => 0,
                                    'complaint_category_id_fk' => 0,
                                    'complaint_council_id_fk'  => 0,
                                    'complaint_detail'         => 0,
                                    'complaint_status_id_fk'   => 0
                                );
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
            return array('response'=>0,'response_msg'=>'Complainant-Id Required');
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
        
        $find_district = $this->db->query('select * from district_councils where district_id_fk = ?',array($data_arr['complaint_council_id_fk']))->result_array();
        
        if(count($find_district) == 0)
        {
            return array('response'=>0,'response_msg'=>'Selected council is not valid');
        }
        
        //======================================================================
        // category validation
        //======================================================================
        
        $find_category = $this->db->query('select * from complaint_categories where complaint_category_id = ?',array($data_arr['complaint_category_id_fk']))->result_array();
        
        if(count($find_category) == 0)
        {
            return array('response'=>0,'response_msg'=>'Selected category is not valid');
        }
        
        //======================================================================
        // Complaint-Detail validation
        //======================================================================

        if(trim($data_arr['complaint_detail']) == '' || trim($data_arr['complaint_detail']) == '0')
        {
            return array('response'=>0,'response_msg'=>'Please enter complaint-detail');
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
        
        // $find_complaint = $this->db->query('
        //                                    select * from complaints 
        //                                     where 
        //                                     complainant_id_fk = ? 
        //                                     and complaint_council_id_fk = ? 
        //                                     and complaint_category_id_fk = ? 
        //                                     and complaint_detail = ?',
        //                                     array($data_arr['complainant_id_fk'],$data_arr['complaint_council_id_fk'],$data_arr['complaint_category_id_fk'],$data_arr['complaint_detail']))->result_array();
       
        $find_complaint=    $this->db->select('*')
                                    ->from('complaints')
                                    ->where('complainant_id_fk',$data_arr['complainant_id_fk'])
                                    ->where('complaint_council_id_fk',$data_arr['complaint_council_id_fk'])
                                    ->where('complaint_category_id_fk',$data_arr['complaint_category_id_fk'])
                                    ->where('complaint_detail',$data_arr['complaint_detail'])
                                    ->get()
                                    ->result_array();
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
                
                return array('response'=>1,'response_msg'=>'Complaint Registered Successfully','complaint_id'=>$complaint_id);
            }
            else
            {
                return array('response'=>0,'response_msg'=>'Failed to insert complainant');
            }
        }
    }
    
	public function upload_files($path, $title, $files, $data_arr)
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
            
            $fileName = $title.'_'.str_shuffle(time()).'.'.$file_extension;
            $file_path =  $path.''.$fileName;
            
            $config['file_name'] = $fileName;
            $attachments[] = $fileName;

            $this->upload->initialize($config);

            if($this->upload->do_upload('attachments[]')) 
            {
                $timestamp_now = date("Y-m-d H:i:s",time());
                $file_path_formatted = substr($file_path, 2); // remove ./ from start of the path string
                
                $this->upload->data();
                $complaint_attachments_array = array('complaint_id_fk'=>$data_arr['complaint_id_fk'],'user_id_fk'=>$data_arr['user_id_fk'],'remarks_id_fk'=>$data_arr['remarks_id_fk'],'complaint_attachment_type'=>$file_extension,'complaint_attachment_file_path'=>$file_path_formatted,'complaint_attachment_status'=>1);                
                $this->db->insert('complaint_attachments',$complaint_attachments_array);            
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
        //======================================================================
        // joins
        //======================================================================
        
        $join_arr = [];
        array_push($join_arr,array('j_table'=>'complainants','t_table'=>'complaints','select_columns'=>array('complainant_name'=>0),'t_column'=>'complainant_id_fk','j_column'=>'complainant_id'));
        array_push($join_arr,array('j_table'=>'district_councils','t_table'=>'complaints','select_columns'=>array('district_council_name'=>0),'t_column'=>'complaint_council_id_fk','j_column'=>'district_council_id'));
        array_push($join_arr,array('j_table'=>'districts','t_table'=>'district_councils','select_columns'=>array('district_name'=>0),'t_column'=>'district_id_fk','j_column'=>'district_id'));
        
        //======================================================================
        // set condition arr
        //======================================================================
        
        $cond_arr = array('cond'=>[],'search'=>[]);
        
        $optional_cond_arr = array('complaint_id','complainant_id_fk','registered_by_user','complaint_council_id_fk','complaint_category_id_fk','complaint_status_id_fk');
        
        foreach($optional_cond_arr as $key=>$value)
        {
            if(isset($data_arr[$value]))
            {
                $cond_arr['cond'][$value] = array(trim($data_arr[$value]),'E');
            }
        }
        
        if(isset($data_arr['district_id_fk'])) // this column is not directly linked to complaints table, thats why added table name
        {
            $cond_arr['cond']['district_id_fk'] = array(trim($data_arr['district_id_fk']),'E','columnFromTable'=>'districts');
        }
        
        if(isset($data_arr['complainant_name'])) // this column is not directly linked to complaints table, thats why added table name
        {
            $cond_arr['search']['complainant_name'] = array(trim($data_arr['complainant_name']),'E','columnFromTable'=>'complainants');
        }
        
        if(isset($data_arr['date_from']) && isset($data_arr['date_to'])) // date-range filter
        {
            $cond_arr['search']['complaint_entry_timestamp'] = array(trim($data_arr['date_from']),'between',trim($data_arr['date_to']));
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
        // call function and return data
        //======================================================================
        
        $data = $this->DmlModel->get('complaints', $join_arr, $cond_arr, $order_by_arr, $limit);
        return $data;
    }
    
    public function complaint_remarks_add($data_arr)
    {
        $required_fields = array('complaint_id_fk'=>0,'user_id_fk'=>0,'complaint_status_id_fk'=>0,'respondent_id_fk'=>0,'complaint_remarks_detail'=>0,'complaint_remarks_timestamp'=>0,'complaint_remarks_status'=>0);
        $missing = array_diff_key($required_fields,$data_arr);

        if(count($missing) > 0)
        {
            return array('response'=>0,'response_msg'=>'Required Fields: '.implode(", ",array_keys($missing)));
        }
        
        //======================================================================
        // check duplication 
        //======================================================================
         
        $find_complaint_remarks = $this->db->query('select * from complaint_remarks where complaint_id_fk = ? and complaint_remarks_detail = ?',array($data_arr['complaint_id_fk'],$data_arr['complaint_remarks_detail']))->result_array();
        
        if(count($find_complaint_remarks) > 0)
        {
            return array('response'=>0,'response_msg'=>'These remarks have already submitted for this complaint');
        }
        else
        {
            //==================================================================
            // insert remarks
            //==================================================================
            
            $insert_remarks = $this->db->insert('complaint_remarks',$data_arr);
            
            if($insert_remarks != false)
            {
                $remarks_id = $this->db->insert_id();
                return array('response'=>1,'response_msg'=>'Remarks Submitted Successfully','complaint_remarks_id'=>$remarks_id);
            }
            else
            {
                return array('response'=>0,'response_msg'=>'Failed to insert remarks');
            }
        }
    }
    
    //==========================================================================
    // remarks
    //==========================================================================
    
    public function complaint_remarks_view()
    {
        
    }




    //::::::::::::::::::::::::::::::::::: sadam ::::::::::::::::::::::::::::::::::::::
    
    function get_complaints($limit=20,$offset=0)
    { 
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

      $this->db->select('complaints.*,complainants.complainant_name,cat.complaint_category_name,districts.district_name,complaint_statuses.complaint_status_title')->from('complaints');
      $this->db->join('complainants', 'complainants.complainant_id=complaints.complainant_id_fk','left');
      $this->db->join('district_councils', 'district_councils.district_council_id = complaints.complaint_council_id_fk','left');
      $this->db->join('districts', 'districts.district_id = district_councils.district_id_fk','left');
      $this->db->join('complaint_categories cat', 'cat.complaint_category_id=complaints.complaint_category_id_fk','left');
      $this->db->join('complaint_statuses','complaint_statuses.complaint_status_id=complaints.complaint_status_id_fk','left');

            if(trim($district_council_id)!="")
            {
                $this->db->where('complaint_council_id_fk',$district_council_id);
            }
            if(trim($complaint_status_id)!="")
            {
                $this->db->where('complaint_status_id_fk',$complaint_status_id);
            }
            if(trim($from_date)!="" && trim($to_date)!="")
            {
                $this->db->where('DATE(complaint_entry_timestamp) >=',$from_date);
                $this->db->where('DATE(complaint_entry_timestamp) <=',$to_date);
            }
            if(trim($complaint_source)!="")
            {
                if($complaint_source == 'All')
                {
                  $this->db->like('complaint_source','admin');
                  $this->db->like('complaint_source','complainant');
                }
                else
                {
                    $this->db->like('complaint_source',$complaint_source);
                }
            }


      $this->db->order_by('complaint_id','desc');
     $this->db->limit($limit,$offset);
      $query = $this->db->get(); 
     // echo $this->db->last_query(); 

        if($query->num_rows() > 0) 
        {
          return $query->result_array();
        }
        else
        {
          return FALSE;
        }
    }
    
    function count_complaints()
    {
        $district_council_id = "";
        $complaint_status_id = "";
        $from_date           = "";
        $to_date             = "";
        $complaint_source    = "";

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

        $this->db->select('*')->from('complaints');
        $this->db->join('complainants', 'complainants.complainant_id=complaints.complainant_id_fk','left');
        $this->db->join('district_councils', 'district_councils.district_council_id=complaints.complaint_council_id_fk','left');
        $this->db->join('complaint_categories cat', 'cat.complaint_category_id=complaints.complaint_category_id_fk','left');

        if(trim($district_council_id)!="")
            {
                $this->db->where('complaint_council_id_fk',$district_council_id);
            }
            if(trim($complaint_status_id)!="")
            {
                $this->db->where('complaint_status_id_fk',$complaint_status_id);
            }
            if(trim($from_date)!="" && trim($to_date)!="")
            {
                $this->db->where('DATE(complaint_entry_timestamp) >=',$from_date);
                $this->db->where('DATE(complaint_entry_timestamp) <=',$to_date);
            }
            if(trim($complaint_source)!="")
            {
                if($complaint_source == 'All')
                {
                  $this->db->like('complaint_source','admin');
                  $this->db->like('complaint_source','complainant');
                }
                else
                {
                    $this->db->like('complaint_source',$complaint_source);
                }
            }
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function get_complaint_by_id($complaint_id)
    { 
        $this->db->select('*')->from('complaints');
        $this->db->where('complaint_id',$complaint_id);
        $this->db->join('complainants', 'complainants.complainant_id=complaints.complainant_id_fk','left');
        $this->db->join('district_councils', 'district_councils.district_council_id=complaints.complaint_council_id_fk','left');
        $this->db->join('complaint_categories cat', 'cat.complaint_category_id=complaints.complaint_category_id_fk','left');
     return $this->db->get()->result();
    }
    
    function get_attachment_complaint_by_id($complaint_id)
    {
        $this->db->select('complaint_attachment_file_path')->from('complaint_attachments');
        $this->db->where('complaint_id_fk',$complaint_id);
     return $this->db->get()->result();
    }
    
    function get_complaint_remarks($complaint_id)
    {
        $this->db->select('complaint_remarks.*,complaint_statuses.complaint_status_title,complaints.complaint_source,respondents.respondent_title,districts.district_name');
        $this->db->from('complaint_remarks');
        $this->db->where('complaint_id_fk',$complaint_id);
        $this->db->join('complaints', 'complaints.complaint_id=complaint_remarks.complaint_id_fk','left');
        $this->db->join('districts', 'districts.district_id=complaints.complaint_council_id_fk','left');
        $this->db->join('complaint_statuses', 'complaint_statuses.complaint_status_id=complaint_remarks.complaint_status_id_fk','left');
        $this->db->join('respondents','respondents.respondent_id=complaint_remarks.respondent_id_fk','left');
     return $this->db->get()->result();
    }
				
}

?>