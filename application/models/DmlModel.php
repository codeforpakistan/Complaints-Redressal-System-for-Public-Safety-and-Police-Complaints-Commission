<?php 

if (!defined('BASEPATH'))	exit('No direct script access allowed');


use \Firebase\JWT\JWT;

class DmlModel extends CI_Model
{
   
  public function get($table, $joins = [], $conditions = [], $orderBy = false, $limit = false)
  {
      $array_data     = [];
      $sub_clause     = ' 1=1 ';
      // by sadam noreena please check
      switch ($table) 
      {
        case 'complaints':
          $select_columns = $table.'.complaint_source,complaint_council,complaint_detail,complaint_entry_timestamp';
          break;

          case 'complaint_categories':
           $select_columns = $table.'.complaint_category_id,complaint_category_name'; 
          break;
        
        default:
          $select_columns = $table.'.* ';
          break;
      }
      // end by sadam
      
      $join_clauses   = null;
      $orderByClause  = null;
      $selection      = " $table.* ";

      // echo 'table: '.$table;
      // echo 'joins: '; print_r($joins);
      // echo 'conditions: '; print_r($conditions);
      // echo 'orderBy: '; print_r($orderBy);
      // echo 'limit: '.$limit;
      // exit();

      //========================================================================
      // joins
      //========================================================================

      if(is_array($joins))
      {
          foreach($joins as $key=>$j_row)
          {
              // select_columns;
            
              if(array_key_exists("select_columns",$j_row))
              {
                  foreach($j_row['select_columns'] as $key=>$value)
                  {
                      $select_columns .= ' , '.$j_row['j_table'].'.'.$key;
                  }
              }
              else
              {
                  $select_columns .= ' , '.$j_row['j_table'].'.*'; // will select all columns from joining table
              }
              
              //================================================================
              
              if(array_key_exists("t_table",$j_row))
              {
                  $join_clauses .= ' LEFT JOIN '.$j_row['j_table'].' on '.$j_row['j_table'].'.'.$j_row['j_column'].' = '.$j_row['t_table'].'.'.$j_row['t_column'];
              }
              else
              {
                  $join_clauses .= ' LEFT JOIN '.$j_row['j_table'].' on '.$j_row['j_table'].'.'.$j_row['j_column'].' = '.$table.'.'.$j_row['t_column'];
              }
              
              if(array_key_exists("j_search",$j_row))
              {
                  foreach($j_row['j_search'] as $search_column_key => $search_column_value)
                  {
                      $filter = ' AND '.$j_row['j_table'].'.'.$search_column_key.' = ?';
                      $sub_clause .= $filter;
                      array_push($array_data,$search_column_value);
                  }
              }
              
          }
      }

      //========================================================================
      // where conditions
      //========================================================================

      //========== with AND statement =============//

      if(array_key_exists("cond",$conditions))
      {
          if(is_array($conditions['cond']))
          {
              foreach($conditions['cond'] as $c_key => $c_value)
              {
                  //================== filter from table =======================
                  
                  $from_table = $table;
                
                  if(isset($c_value[3]))
                  {
                     $from_table = $c_value[3];
                  }
                  else if(isset($c_value['columnFromTable']))
                  {
                     $from_table = $c_value['columnFromTable'];
                  }
                  
                  //============================================================
                
                  $filter = null; 
                  
                  switch(strtolower($c_value[1]))
                  {
                      case 'e':
                          $filter = ' AND '.$from_table.'.'.$c_key.' = ?';
                          array_push($array_data,$c_value[0]);
                      break;
                      
                      case 'l':
                          
                          $filter = ' AND ('.$from_table.'.'.$c_key.' RLIKE ?';
                          $filter .= ' OR '.$from_table.'.'.$c_key.' RLIKE ?';
                          $filter .= ' OR '.$from_table.'.'.$c_key.' RLIKE ?';
                          $filter .= ' OR '.$from_table.'.'.$c_key.' LIKE ?';
                          $filter .= ' OR MATCH('.$from_table.'.'.$c_key.') AGAINST (?)';
                          $filter .= ' ) ';
                          
                          array_push($array_data,'^'.$c_value[0]);
                          array_push($array_data,'[[:<:]]'.$c_value[0].'[[:>:]]');
                          array_push($array_data,'^'.$c_value[0]);
                          array_push($array_data,'%'.$c_value[0].'%');
                          array_push($array_data,$c_value[0]);
                          
                          // orderByClause to display products in this sequqnce   
                          
                          $orderByClause = '(CASE 
                                                 WHEN '.$from_table.'.'.$c_key.' RLIKE ? THEN 1 
                                                 WHEN '.$from_table.'.'.$c_key.' RLIKE ? THEN 2 
                                                 WHEN '.$from_table.'.'.$c_key.' RLIKE ? THEN 3 
                                                 WHEN '.$from_table.'.'.$c_key.' LIKE ? THEN 4 
                                                 WHEN MATCH('.$from_table.'.'.$c_key.') AGAINST (?) THEN 5
                                             END)';
                          
                          array_push($array_data,'^'.$c_value[0]);
                          array_push($array_data,'[[:<:]]'.$c_value[0].'[[:>:]]');
                          array_push($array_data,'^'.$c_value[0]);
                          array_push($array_data,'%'.$c_value[0].'%');
                          array_push($array_data,$c_value[0]);
                          
                      break;
                      
                      case 'greater':
                          $filter = ' AND '.$from_table.'.'.$c_key.' > ?';
                          array_push($array_data,$c_value[0]);
                      break;
                      
                      case 'less':
                          $filter = ' AND '.$from_table.'.'.$c_key.' < ?';
                          array_push($array_data,$c_value[0]);
                      break;
                      
                      case 'greater_equal':
                          $filter = ' AND '.$from_table.'.'.$c_key.' >= ?';
                          array_push($array_data,$c_value[0]);
                      break;
                      
                      case 'less_equal':
                          $filter = ' AND '.$from_table.'.'.$c_key.' <= ?';
                          array_push($array_data,$c_value[0]);
                      break;
                      
                      case 'not_equal':
                          $filter = ' AND '.$from_table.'.'.$c_key.' != ?';
                          array_push($array_data,$c_value[0]);
                      break;
                      
                      case 'between':
                          $filter = ' AND ('.$from_table.'.'.$c_key.' BETWEEN ? AND ?)';
                          array_push($array_data,$c_value[0]);
                          array_push($array_data,$c_value[2]);
                      break;
                      
                  }
                  
                  $sub_clause .= $filter;
                  
              }
          }
      }
      
      //========================================================================
      // with OR statement
      //========================================================================
      
      if(array_key_exists("search",$conditions))
      {
          if(is_array($conditions['search']))
          {
              $filter_search = null;
              
              foreach($conditions['search'] as $s_key => $s_value) // $s_value = array('value','Like/Equal','tableName of selected column')
              {
                  
                  if(strcasecmp($s_value[1],'L') == 0) // if like (L)
                  {
                      $filter = ' OR '.$s_value[2].'.'.$s_key.' LIKE ?';
                      array_push($array_data,'%'.$s_value[0].'%');
                  }
                  else // if equal (E)
                  {
                      $filter = ' AND '.$s_value[2].'.'.$s_key.' = ?';
                      array_push($array_data,$s_value[0]);
                  }
                  
                  $filter_search .= $filter;
                  
              }
              
              if($filter_search !== null)
              {
                  $sub_clause .= ' AND ('.ltrim(trim($filter_search),"OR").')';
              }
          }
      }

        //======================================================================
        // order by
        //======================================================================

        if(trim($orderByClause) != '')
        {
            $sub_clause .= ' ORDER BY '.$orderByClause;
        }
        else if (is_array($orderBy) && count($orderBy) > 0)
        {
            if(isset($orderBy['table']))
            {
                $orderBy_col = trim($orderBy['table']).".".$orderBy['column'];
            }
            else
            {
                $orderBy_col = $table.".".$orderBy['column'];
            }
            
            $sub_clause .= " ORDER BY $orderBy_col ".$orderBy['sequence']." ";
        }

        //======================================================================
        // limit
        //======================================================================

        if ($limit)
        {
            $sub_clause .= " LIMIT " . $limit;
        }

        //======================================================================
        // execute query
        //======================================================================
        // by sadam 
        if($sub_clause !=1)
        {
          $sql = "SELECT $select_columns FROM $table $join_clauses WHERE $sub_clause ";
          $data = $this->db->query($sql,$array_data)->result_array();
        }

        else
        {
          $sql = "SELECT $select_columns FROM $table $join_clauses";
          $data = $this->db->query($sql,$array_data)->result_array();
        }
        
        // echo $sql;
        // print_r($array_data);
        
        // $sql = "SELECT $select_columns FROM $table $join_clauses WHERE $sub_clause ";
        return $data;
          

} // get_data function end

}

?>