<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');

class AdminModel extends CI_Model
{
    function user_by_role($table_name,$user_role_id_fk,$user_district_id_fk=null)
    {
        if($user_district_id_fk != null )
        {
         return $this->db->where('user_role_id_fk',$user_role_id_fk)
                        ->join('districts d','d.district_id=users.user_district_id_fk','left')
                         ->get($table_name)->result();   
        }
        else
        {
            return $this->db->where('user_role_id_fk',$user_role_id_fk)->get($table_name)->result();
        }
        
    }
    function status_active_record($table_name,$table_status_column_name,$table_status_column_value)
    {
        return $this->db->where($table_status_column_name,$table_status_column_value)->get($table_name)->result();
    }
    function exist_record_row($talbe_column_name,$table_id,$table_name)
    {
        return $this->db->where($talbe_column_name,$table_id)->get($table_name)->row();
    }
    function update($update_array,$table_name,$talbe_column_name,$table_id)
    {
      return $this->db->where($talbe_column_name,$table_id)->update($table_name,$update_array);
    }
    function delete($talbe_column_name,$table_id,$table_name)
    {
     return $this->db->where($talbe_column_name,$table_id)->delete($table_name);
    }
    function insert($inert_array,$table_name)
    {
        return $this->db->insert($table_name,$inert_array);
    }
    function get_all_records($table_name,$order_by = NULL,$order = NULL) // order_by and order is optional
    {

        if(!is_null($order_by) && !is_null($order) )
        { 
            $this->db->order_by($order_by,$order); 
        }
        return $this->db->get($table_name)->result();
        
    }
    function countUsersByRoleId($user_role_id_fk)
    {
      return  $this->db->where('user_role_id_fk',$user_role_id_fk)->where('user_status',1)->count_all_results('users');
    }
    function countAll($table_name,$talbe_column_name,$value)
    {
      return  $this->db->where($talbe_column_name,$value)->count_all_results($table_name);
    }
    function thisDay()
    {
        $thisDay = date('Y-m-d');
        $this->db->where('DATE(complaint_entry_timestamp) >= now()');
        $this->db->where('DATE(complaint_entry_timestamp) <= now()');
        $this->db->count_all_results('complaints'); 
        // echo $this->db->last_query(); exit;
    }
    function thisMonth()
    {
        $this->db->where('MONTH(complaint_entry_timestamp)', date('m'));
        $this->db->where('YEAR(complaint_entry_timestamp)', date('Y'));
       return $this->db->count_all_results('complaints'); 
       
    }
    function thisYear()
    {
        $this->db->where('DATE(complaint_entry_timestamp)',date('Y'));
       return $this->db->count_all_results('complaints'); 
    }
    function IT_district_admins()
    {
        return $this->db->from('users')
                        ->where('user_role_id_fk !=',1)
                        ->where('user_role_id_fk !=',4)
                        ->join('districts d','d.district_id=users.user_district_id_fk','left')
                        ->join('user_roles r','r.user_role_id=users.user_role_id_fk','left')
                        ->order_by('user_id','desc')
                         ->get()->result();
    }
    public function getComplainant($complainant_cnic)
    {
      return $this->db->select('complainant_id')->where('complainant_cnic',$complainant_cnic)->where('complainant_status',1)->get('complainants')->row();
    }
    function insert_with_last_insert_id($table_name,$array)
    {
        $this->db->insert($table_name, $array);
        return $this->db->insert_id();
    }
    // sadam 
    function check_page($page_name)
    {
        return $this->db->where('page_name',$page_name)->get('pages')->row();
    }
    function check_role_privileges($page_id,$role_id)
    {
        $query = $this->db->where('page_id_fk',$page_id)->where('user_role_id_fk',$role_id)->where('access','1')->get('page_privileges');
        
        if ($query->num_rows() > 0) 
        {
            return true;
        }
        else
        { 
            return false;
        }
    }
    function get_by_id($table,$coloumn_name,$id)
    {
        return $this->db->where($coloumn_name,$id)->get($table)->result();
    }
				
}

?>