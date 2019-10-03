<?php

class group extends CI_Model {

    public function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
    }

    public function getGroupCodeStatus() {
        $this->db->select('group_code_status');
        $query = $this->db->get('account_configuration');
        return $query->row_array();
    }
    
    public function getCodePosition() {
        $this->db->select('code_before_name');
        $query = $this->db->get('account_configuration');
        return $query->row();
    }
    
    public function getSingleGroup($where) {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('group');
        return $query->row();
    }
    public function getGroups($where) {
        $this->db->select('*');
        $this->db->where($where);
        $this->db->where('id !=', 17);
        $query = $this->db->get('group');
        return $query->result_array();
    }
	
    public function getAllGroups(){
            $query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id WHERE G.status = '1' AND G.deleted = '0' ORDER BY G.parent_id");
            return $query->result_array();
    }
    
    public function getAllGroupsByLimit($limit = 10,$offset = 0,$search=''){
            $query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id "
                    . "WHERE G.status = '1' "
                    . "AND G.deleted = '0' "
                    . "AND G.group_name LIKE '%".$search."%' "
                    . "OR PG.group_name LIKE '%".$search."%' "
                    . "OR PG.group_code LIKE '%".$search."%' "
                    . "OR G.group_code LIKE '%".$search."%' "
                    . "ORDER BY G.parent_id LIMIT ".$offset.", ".$limit);
            return $query->result_array();
    }
    
    public function getCountGroup($search=''){
        $query = $this->db->query("SELECT G.*, PG.group_name as parent_group_name,PG.group_code as parent_group_code FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id "
                . "WHERE G.status = '1' "
                . "AND G.deleted = '0'"
                . "AND G.group_name LIKE '%".$search."%' "
                . "OR PG.group_name LIKE '%".$search."%' "
                . "OR PG.group_code LIKE '%".$search."%' "
                . "OR G.group_code LIKE '%".$search."%' "
                . " ORDER BY G.parent_id");
        return $query->num_rows();
    }

    public function getParentGroup($where) {
        $this->db->select('*');
        $this->db->where('parent_id', 0);
        $this->db->where($where);
        $query = $this->db->get('group');
        return $query->result_array();
    }

    public function subGroups($where) {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('group');
        return $query->result_array();
    }

    public function saveGroup($data = NULL) {
        if ($this->input->post('id') == "") {
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'groups',
                'action' => '`'. $data['group_name'] .'` <b>created</b>',
                'previous_data' => '',
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);

            $this->db->insert('group', $data);
            return $this->db->insert_id();
        } else {
            $this->db->where('id', $data['id']);
            $group = $this->db->get('group')->row_array();
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'groups',
                'action' => '`'. $data['group_name'] .'` <b>edited</b>',
                'previous_data' => json_encode($group),
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);
            
            $this->db->where('id', $data['id']);
           return $this->db->update('group', $data);
        }
    }
    
    public function updateGroupCode($data = NULL,$last_id=NULL) {
        $this->db->where('id', $last_id);
        $this->db->update('group', $data);
    }

    public function getGroupName($where) {
        $this->db->select('*');
        $this->db->where($where);
        $query = $this->db->get('group');
        return $query->result_array();
    }

    public function delete($id) {
        $this->db->delete('group', array('id' => $id));
    }
    
    public function getGroupIdByName($group_name) {
        $this->db->select('id');
        $this->db->where('group_name',$group_name);
        $query = $this->db->get('group');
        return $query->row_array();
    }
    
    //check entry existance
    public function checkGroupExist($group_id) {
        $query = $this->db->select('id')
                ->from(tablename('ladger'))
                ->where('group_id =', $group_id)
                ->where('status', 1)
                ->where('deleted', 0)
                ->get();
        return $row = $query->result();
    }
    
    public function deleteGroup($group_id) {
        $this->db->where('id', $group_id);
        $group = $this->db->get('group')->row_array();
        $log = array(
            'user_id' => $this->session->userdata('admin_uid'),
            'branch_id' => $this->session->userdata('branch_id'),
            'module' => 'groups',
            'action' => '`' . $group['group_name'] . '` <b>deleted</b>',
            'previous_data' => json_encode($group),
            'performed_at' => date('Y-m-d H:i:s', time())
        );
        $this->currentusermodel->updateLog($log);

        $this->db->where('id', $group_id);
        return $this->db->update('group', array('deleted' => 1));
    }
    
     public function saveWatch_list($ledger_id,$status) {
         
         // group access for current user
         if($status ==1){             
            $data = array();
            $data['account_type'] = 2;
            $data['group_ledger_id'] = $ledger_id;
            $data['user_id'] = $this->session->userdata('admin_uid');
            $data['created_date'] = date('Y-m-d h:i:s', time());
            $data['modified_date'] = date('Y-m-d h:i:s', time());
            $this->db->insert('watchlist_access', $data);
        }else{
            $this->db->where(array(
                'user_id' => $this->session->userdata('admin_uid'),
                'group_ledger_id' => $ledger_id,
                'account_type' => 2
            ));
            $this->db->delete('watchlist_access');
        }
        
        $this->db->where('id', $ledger_id);
        return $this->db->update('ladger', array('watch_list_status' => $status));
    }
    
     public function saveGroupWatch_list($group_id,$status) {
         
         // group access for current user
         if($status ==1){ 
            $data = array();
            $data['account_type'] = 1;
            $data['group_ledger_id'] = $group_id;
            $data['user_id'] = $this->session->userdata('admin_uid');
            $data['created_date'] = date('Y-m-d h:i:s', time());
            $data['modified_date'] = date('Y-m-d h:i:s', time());
            $this->db->insert('watchlist_access', $data);
         }else{            
            $this->db->where(array(
                'user_id' => $this->session->userdata('admin_uid'),
                'group_ledger_id' => $group_id,
                'account_type' => 1
            ));
            $this->db->delete('watchlist_access');
        }
         
        $this->db->where('id', $group_id);
        return $this->db->update('group', array('watch_list_status' => $status));
    }

    /* ================== csv upload section ================= */

    // function save_data_from_file($dataArr,$table)
    // {
    //   $arrId = array();
    //      if($this->db->insert_batch($table, $dataArr))
    //   {
    //      $insertedID = $this->db->insert_id();
    //     $j = 0;
    //     for($i = 0;$i<count($dataArr);$i++)
    //     {
    //       //echo $j;
    //       $arrId[$i] = ($insertedID)+($j);
    //       $j++;
    //     }
        
    //     return $arrId;
    //   }else{
    //     return false;
    //   }
    // }

    function save_data_from_file($dataArr,$table)
    {
      $arrId = array();
      foreach ($dataArr as $key => $arr) {
          $this->db->insert($table, $arr);
          $arrId[] = $this->db->insert_id();
      }
      return $arrId;
    }

    public function singleInsertionFromFile($data, $table)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    function get_existing($args,$table)
    {
        $query = $this->db->get_where($table, $args);
        //echo  $this->db->last_query();
      $res = $query->result();
      if(count($res)>0)
      {
        return false;
      }else{
        return true;
      }

    }


    function all_uploaded_data($table){
    $query = $this->db->get($table);
    $res = $query->result();
    return $res;    
    }


    function get_tabledescription($args,$table)
    {
      $query = $this->db->get_where($table, $args);
      //echo  $this->db->last_query();
      $res = $query->result();
      return $res;

    }


    function all_uploaded_data_by_simple_join($maintable,$where,$jointable,$join,$joinby)
    {
      //echo $joinby;
      $this->db->select('*');
    $this->db->from($maintable);
    if(!empty($where)){
      $this->db->where($where);
    }

    $this->db->join($jointable,$joinby,$join);

    $query = $this->db->get();
    //echo  $this->db->last_query();
      $res = $query->result();
      return $res;
    }


    function all_uploaded_data_by_order($table,$order,$field,$limit)
    {
      $this->db->order_by($field,$order);
      $this->db->limit($limit,0);
      $query = $this->db->get($table);
      //echo  $this->db->last_query();
    $res = $query->result();
    return $res;  
    }


    function update_the_table_by_batch($args,$table,$field)
    {
      $this->db->where($args);
    $this->db->update($table, $field);
    //echo  $this->db->last_query();
    }

    /* ================== csv upload section ================= */

    public function get_max_id($tableName)
    {
        $this->db->select('MAX(id) as maxid');
        $this->db->from($tableName);
        $result = $this->db->get()->row();
        return $result->maxid;
    }

    public function getCsvQuery($fileds = '', $table = '', $joinTable = '', $joinCond = '', $joinType = '', $where = '', $orderBy = '')
    {
        // SELECT G.group_name as `Group Name`, PG.group_name as `Parent Group` FROM pb_group AS G LEFT JOIN pb_group AS PG ON G.parent_id = PG.id WHERE G.status = '1' AND G.deleted = '0' ORDER BY G.parent_id
        $this->db->select($fileds, FALSE);
        $this->db->from($table);
        if ($joinTable != '') {
            $this->db->join($joinTable, $joinCond, $joinType);
        }
        if ($where != '') {
            $this->db->where($where);
        }
        if ($orderBy != '') {
            $this->db->order_by($orderBy);
        }
        // $this->db->get();
        // print_r($this->db->last_query());exit();
        return $this->db->get();
    }

    public function outputTheCSV($query, $fileName='_.csv')
    {
        $results = $query->result_array();
        // echo "<pre>";print_r($results);exit();
         
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Expires: 0");
        header("Pragma: public");

        $fh = @fopen( 'php://output', 'w' );

        $headerDisplayed = false;

        foreach ( $results as $data ) {
            // Add a header row if it hasn't been added yet
            if ( !$headerDisplayed ) {
                // Use the keys from $data as the titles
                fputcsv($fh, array_keys($data));
                $headerDisplayed = true;
            }
         
            // Put the data into the stream
            fputcsv($fh, $data);
        }
        // Close the file
        fclose($fh);
        // Make sure nothing else is sent, our file is done
        exit;
    }

    public function checkCrDr($id)
    {
        $this->db->select('id, parent_id');
        $this->db->from('group');
        $this->db->where('id', $id);
        $group = $this->db->get()->row();
        if ($group->parent_id != 0) {
            return $this->checkCrDr($group->parent_id);
        } else {
            return $group;
        }
        
    }

}

?>
