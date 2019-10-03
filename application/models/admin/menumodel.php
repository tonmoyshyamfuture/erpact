<?php

class menumodel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function loadAllMenu($id = 0) { // Load All Menu or All Menu of a specific parent
        $sql = "select * from " . tablename('menu') . " where parentid=$id order by menu_order";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    public function modifyMenu($data, $id = NULL) { // Create or Update Menu


        if (empty($id)) {
            $this->db->insert(tablename('menu'), $data);
            $insertid = $this->db->insert_id();
            $this->db->where("id", $insertid);
            if ($data['menu_order'] == "0" || $data['menu_order'] == 0) {
                $totaldatas = $this->loadAllMenu();
                $countorder = count($totaldatas);
                $datas = array("menu_order" => $countorder);
                $this->db->update(tablename('menu'), $datas);
            }
            return $insertid;
        } else {
            $this->db->where("id", $id);
            $this->db->update(tablename('menu'), $data);
            return 1;
        }
    }

    public function delMenu($menuid) { // Delete Menu
        $sql = "delete from " . tablename('menu') . " where id='" . $menuid . "'";
        $query = $this->db->query($sql);

        if (!empty($query)) {
            return 1;
        } else {
            return "";
        }
    }

    public function getmenuitem($id) { // Get Deatils of a single menu item
        $sql = "select * from " . tablename('menu') . " where id=$id";
        $query = $this->db->query($sql);
        $result = $query->row();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    public function menuStatus($id) {
        $result = $this->getmenuitem($id);

        if (!empty($result)) {
            $status = $row->status;
            if ($status == "1" || $status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }


            $sql = "update " . tablename('menu') . " set status='" . $status . "' where id='" . $id . "'";
            $query = $this->db->query($sql);

            if (!empty($query)) {
                return 1;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public function modifyMenuorder($new, $id) { // Modify Menu Ordering
        $sql = "update " . tablename('menu') . " set menu_order='" . $new . "' where id='" . $id . "'";
        $query = $this->db->query($sql);

        if (!empty($query)) {
            return 1;
        } else {
            return "";
        }
    }

    public function getAllGroup() {
        $sql = "select *from " . tablename('menu_group') . " order by menuorder";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    public function loadAllMenuGroup($id = 0, $group) { // Load All Menu or All Menu of a specific parent
        $sql = "select * from " . tablename('menu') . " where parentid=$id and group_menu=$group order by menu_order";
        $query = $this->db->query($sql);
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return "";
        }
    }

    /** Sagnik Menu Model & Queries */
    /*  Get Main Menu */

    public function getMainMenu() {


        $sql = "SELECT * FROM " . tablename('menu_group') . " WHERE status = 1 ORDER BY menuorder";
        $query = $this->db->query($sql);

        $result = $query->result();
        if (!empty(($result))) {
            return $result;
        }
    }

    public function checkMenu($rows) {


        $sql = "SELECT * FROM " . tablename('menu') . " WHERE group_menu = " . $rows->id . " ORDER BY id";
        $query = $this->db->query($sql);

        $result = $query->result();
        if (!empty($result)) {

            return true;
        }
    }

    public function checkSubMenuExist($rows) {

        $sql = "SELECT * FROM  " . tablename('menu') . " WHERE group_menu = " . $rows->id . " ORDER BY id";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 1) {
            return true;
        }
    }

    public function getSubMenu($id) {


        $sql = "SELECT * FROM " . tablename('menu') . " WHERE group_menu = " . $id . " AND parentid = 0 AND status='1' ORDER BY menu_order";
        $query = $this->db->query($sql);

        $result = $query->result();
        if (!empty(($result))) {
            return $result;
        }
    }

    public function getSubMenuChildren($id) {

        $sql = "SELECT * FROM " . tablename('menu') . " WHERE parentid = " . $id . " AND status='1' ORDER BY menu_order";
        $query = $this->db->query($sql);

        // if($query->num_rows() > 0){

        $result = $query->result();
        return $result;

        // }	
    }

    public function getTranMenu() {
        $sql = "SELECT * FROM " . tablename('menu') . " WHERE group_menu = 5 AND status=1 ORDER BY id";
        $query = $this->db->query($sql);
        return $query->result();
    }


    public function checkMenuId($slug){
        $sql = "SELECT * FROM " . tablename('menu') . " WHERE slug = '".$slug."' ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function checkIfHasParent($id){
        $sql = "SELECT * FROM " . tablename('menu') . " WHERE id = '".$id."' ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function groupId($id){
        $sql = "SELECT * FROM " . tablename('menu') . " WHERE id = '".$id."' ";
        $query = $this->db->query($sql);
        return $query->row();
    }

    /** /Sagnik Menu Model & Queries */
}
