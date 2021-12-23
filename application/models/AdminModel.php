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
    function get_all_records($table_name)
    {
        return $this->db->get($table_name)->result();
    }
    function countUsersByRoleId($user_role_id_fk)
    {
      return  $this->db->where('user_role_id_fk',$user_role_id_fk)->where('user_status',1)->count_all_results('users');
    }
    
    public function district_add()
    {
        
    }
    
    public function complaint_category_add()
    {
        
    }
				
}

?>