<?php

class rolemodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAllParentMenu() {
        $this->db->select('*');
        $this->db->from('menu_group');
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllMenu($id) {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where('group_menu', $id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllChildMenu($id) {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where('parentid', $id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllUser() {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('user.id as id,fname,lname,emailid');
        $this->db2->from('user');
        $this->db2->join('user_relation', 'user.id = user_relation.user_id');
        $this->db2->where('user.status', '1');
        $this->db2->where('user_relation.company_id', $this->session->userdata('company_id'));
        $query = $this->db2->get();
        return $query->result();
    }

    public function getAllBranch() {
        $this->db->select('id,company_name');
        $this->db->from('account_settings');
        $query = $this->db->get();
        return $query->result();
    }

    public function getSaasUserRelation($company_id, $user_id) {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('*');
        $this->db2->from('user_relation');
        $this->db2->where('company_id', $company_id);
        $this->db2->where('user_id', $user_id);
        $this->db2->where('status', '1');
        $query = $this->db2->get();
        return $query->row();
    }

    public function updateUserAccess($branch_id, $user_id, $data) {
        $this->db->where('branch_id', $branch_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('user_branch_access', $data);
    }

    public function insertUserRelation($company_id, $user_id) {
        $this->db2 = $this->load->database('db2', TRUE);
        $data = array(
            'company_id' => $company_id,
            'user_id' => $user_id,
            'level' => '2',
            'requesting_status' => '1',
            'status' => '1',
            'created_date' => date("Y-m-d H:i:s"),
        );
        $this->db2->insert('user_relation', $data);
    }

    public function getUser($user_id) {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('*');
        $this->db2->from('user');
        $this->db2->where('id', $user_id);
        $this->db2->where('status', 1);
        $query = $this->db2->get();
        return $query->row();
    }

    public function insertAdminUser($data) {
        $this->db->insert('admins', $data);
        return $this->db->insert_id();
    }

    public function insertUserBranch($data) {
        $this->db->insert('user_branch', $data);
        return $this->db->insert_id();
    }

    public function insertUserAccess($data) {
        $this->db->insert('user_branch_access', $data);
        return $this->db->insert_id();
    }

    public function getAdminUser($user_id) {
        $this->db->select('id');
        $this->db->from('admins');
        $this->db->where('sass_user_id', $user_id);
        $this->db->where('status', '1');
        $query = $this->db->get();
        $data = $query->row();
        if ($data) {
            return $data->id;
        } else {
            return null;
        }
    }

    public function getUserAccess($user_id, $branch_id) {
        $this->db->select('module');
        $this->db->from('user_branch_access');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->row();
    }

    public function getSaasUserId($id) {
        $this->db->select('sass_user_id');
        $this->db->from('admins');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $data = $query->row();
        if ($data) {
            return $data->sass_user_id;
        } else {
            return null;
        }
    }

    public function getUserBranch($branch_id, $user_id) {
        $this->db->select('id');
        $this->db->from('user_branch');
        $this->db->where('company_id', $branch_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->row();
    }

    public function getUserBranchAccess($branch_id, $user_id) {
        $this->db->select('id');
        $this->db->from('user_branch_access');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', '1');
        $query = $this->db->get();
        return $query->row();
    }

    public function getSaasCompanyUser($saas_user_id) {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('id');
        $this->db2->from('user_relation');
        $this->db2->where('company_id', $this->session->userdata('company_id'));
        $this->db2->where('user_id', $saas_user_id);
        $this->db2->where('status', '1');
        $query = $this->db2->get();
        return $query->row();
    }

    public function getAdminUserId($saas_user_id) {
        $this->db->select('id');
        $this->db->from('admins');
        $this->db->where('sass_user_id', $saas_user_id);
        $this->db->where('status', '1');
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return $result->id;
        } else {
            return '';
        }
    }

    public function deleteCompanyUser($saas_user_id) {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->where('company_id', $this->session->userdata('company_id'));
        $this->db2->where('user_id', $saas_user_id);
        $this->db2->update('user_relation', array('status' => '0'));
    }

    public function deleteBranchUser($admin_id) {
        $this->db->where('company_id', $this->session->userdata('branch_id'));
        $this->db->where('user_id', $admin_id);
        $this->db->update('user_branch', array('status' => '3'));
    }

    public function deleteAdminUser($saas_user_id) {
        $this->db->where('sass_user_id', $saas_user_id);
        $this->db->update('admins', array('status' => '0'));
    }

    public function deleteUserAccess($admin_id) {
        $this->db->where('company_id', $this->session->userdata('company_id'));
        $this->db->where('branch_id', $this->session->userdata('branch_id'));
        $this->db->where('user_id', $admin_id);
        $this->db->update('user_branch_access', array('status' => '3'));
    }

    public function getBranchDetails() {
        $this->db->select('company_name,email');
        $this->db->from('account_settings');
        $this->db->where('id', $this->session->userdata('branch_id'));
        $query = $this->db->get();
        return $query->row();
    }
    
    /*
     * update password at the time of access grant
     */
    public function updatePassword($user_id, $password) {
        $data = array(
            'password' => $password
        );
        
        $this->db->where('sass_user_id', $user_id);
        $this->db->update('admins', $data);
    }
    
    public function getUserById($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->get('admins')->row_array();
    }

}
