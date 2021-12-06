<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');

class AdminModel extends CI_Model
{
    function IT_staff_list()
    {
        return $this->db->where('user_role_id_fk',2)->get('users')->result();
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
    
    public function district_add()
    {
        
    }
    
    public function districts_view()
    {
        
    }
    
    public function respondents_view()
    {
        
    }
    
    public function complaint_categories_view()
    {
        
    }
    
    public function complaint_category_add()
    {
        
    }
				
}

?>