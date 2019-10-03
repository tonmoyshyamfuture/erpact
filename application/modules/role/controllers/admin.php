<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class admin extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/rolemodel');
    }

    public function user_access() {
        $data = [];
        $menu = [];

        $all_user = $this->rolemodel->getAllUser();
        $all_branch = $this->rolemodel->getAllBranch();
        $parent_menu = $this->rolemodel->getAllParentMenu();
        foreach ($parent_menu as $p) {
            if ($p->id == 2) {
                $menu[] = (object) array(
                            'id' => $p->id,
                            'name' => $p->name,
                );
            } else {
                $all_menu = $this->rolemodel->getAllMenu($p->id);
                foreach ($all_menu as $r) {
                    $all_child_menu = $this->rolemodel->getAllChildMenu($r->id);
                    if ($r->parentid == 0 && count($all_child_menu) == 0 && $p->id != 1) {
                        $menu[] = (object) array(
                                    'id' => $r->id,
                                    'name' => $p->name . ' / ' . $r->label,
                        );
                    } else {
                        foreach ($all_child_menu as $rr) {
                            $menu[] = (object) array(
                                        'id' => $rr->id,
                                        'name' => $p->name . ' / ' . $r->label . ' / ' . $rr->label,
                            );
                        }
                    }
                }
            }
        }
        $user_access = $this->rolemodel->getUserAccess(1, 1);
        $account_user_id = $this->session->userdata('admin_uid');
        $data['saas_user_id'] = $this->rolemodel->getSaasUserId($account_user_id);

        $module_access = isset($user_access->module) ? unserialize($user_access->module) : array();
        $new_module = [];
        foreach ($menu as $n) {
            $new_module[] = (object) array(
                        'id' => $n->id,
                        'name' => $n->name,
                        'v' => isset($module_access[$n->id]->view) ? $module_access[$n->id]->view : 0,
                        'a' => isset($module_access[$n->id]->add) ? $module_access[$n->id]->add : 0,
                        'e' => isset($module_access[$n->id]->edit) ? $module_access[$n->id]->edit : 0,
                        'd' => isset($module_access[$n->id]->delete) ? $module_access[$n->id]->delete : 0,
            );
        }
        $data['user'] = $all_user;
        $data['branch'] = $all_branch;
        $data['menu'] = $new_module;

        $this->layouts->render('admin/user_access', $data, 'branch');
    }

    public function Ajax_get_module() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('user_id', 'User', 'required|numeric');
            $this->form_validation->set_rules('branch_id', 'Branch', 'required|numeric');
            $data = [];
            if ($this->form_validation->run() === TRUE) {
                $user_id = $this->input->post('user_id');
                $branch_id = $this->input->post('branch_id');
                $menu = [];
                $parent_menu = $this->rolemodel->getAllParentMenu();
                foreach ($parent_menu as $p) {
                    if ($p->id == 2) {
                        $menu[] = (object) array(
                                    'id' => $p->id,
                                    'name' => $p->name,
                                    'parent' => $p->name,
                                    'sub_cat' => '',
                        );
                    } else {
                        $all_menu = $this->rolemodel->getAllMenu($p->id);
                        foreach ($all_menu as $r) {
                            $all_child_menu = $this->rolemodel->getAllChildMenu($r->id);
                            if ($r->parentid == 0 && count($all_child_menu) == 0 && $p->id != 1) {
                                $menu[] = (object) array(
                                            'id' => $r->id,
                                            'parent' => $p->name,
                                            'sub_cat' => '',
                                            'name' => $p->name . ' / ' . $r->label,
                                );
                            } else {
                                foreach ($all_child_menu as $rr) {
                                    $menu[] = (object) array(
                                                'id' => $rr->id,
                                                'parent' => $p->name,
                                                'sub_cat' => $r->label,
                                                'name' => $p->name . ' / ' . $r->label . ' / ' . $rr->label,
                                    );
                                }
                            }
                        }
                    }
                }
                $admin_user_id = $this->rolemodel->getAdminUser($user_id);
                $user_access = $this->rolemodel->getUserAccess($admin_user_id, $branch_id);

                $module_access = isset($user_access->module) ? unserialize($user_access->module) : array();

                $new_module = [];
                foreach ($menu as $n) {
                    $new_module[] = (object) array(
                                'id' => $n->id,
                                'name' => $n->name,
                                'parent' => $n->parent,
                                'sub_cat' => $n->sub_cat,
                                'v' => isset($module_access[$n->id]->view) ? $module_access[$n->id]->view : 0,
                                'a' => isset($module_access[$n->id]->add) ? $module_access[$n->id]->add : 0,
                                'e' => isset($module_access[$n->id]->edit) ? $module_access[$n->id]->edit : 0,
                                'd' => isset($module_access[$n->id]->delete) ? $module_access[$n->id]->delete : 0,
                    );
                }

                $data['menu'] = $new_module;
                $data_msg['html'] = $this->load->view('admin/ajax_module', $data, TRUE);
                $data_msg['res'] = 'success';
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
            exit;
        }
    }

    public function save_module_access() {
        $data_msg = [];
        $this->load->helper('email');
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('user_id', 'User', 'required|numeric');
            $this->form_validation->set_rules('branch_id', 'Branch', 'required|numeric');
            if ($this->form_validation->run() === TRUE) {

                $account_user_id = $this->session->userdata('admin_uid');
                $company_id = $this->session->userdata('company_id');
                $user_id = $this->input->post('user_id');
                $branch_id = $this->input->post('branch_id');
                $module_id = $this->input->post('module_id');
                $view = $this->input->post('view');
                $add = $this->input->post('add');
                $edit = $this->input->post('edit');
                $delete = $this->input->post('delete');
                $item = array();
                foreach ($module_id as $i => $row) {
                    $item[$row] = (object) array(
                                'list' => (isset($view[$row]) || isset($add[$row]) || isset($edit[$row]) || isset($delete[$row])) ? 1 : 0,
                                'view' => isset($view[$row]) ? 1 : 0,
                                'add' => isset($add[$row]) ? 1 : 0,
                                'edit' => isset($edit[$row]) ? 1 : 0,
                                'delete' => isset($delete[$row]) ? 1 : 0,
                    );
                }
                $module = serialize($item);

                $data = array(
                    'company_id' => $this->session->userdata('company_id'),
                    'branch_id' => $branch_id,
                    'user_id' => $user_id,
                    'module' => $module,
                    'status' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                );

                $this->db->trans_start();
                $branch_details = $this->rolemodel->getBranchDetails();
             
                $user = $this->rolemodel->getUser($user_id);
                $saas_user = $this->rolemodel->getSaasUserRelation($company_id, $user_id);
                if ($saas_user) {
                    $this->rolemodel->updatePassword($user_id, $user->password); // somnath - for update the password
                    $admin_user_id = $this->rolemodel->getAdminUser($user_id);
                    $data['user_id'] = $admin_user_id;
                    $user_branch = $this->rolemodel->getUserBranch($branch_id, $admin_user_id);
                    $user_branch_access = $this->rolemodel->getUserBranchAccess($branch_id, $admin_user_id);
                    if ($user_branch && $user_branch_access) {
                        $this->rolemodel->updateUserAccess($branch_id, $admin_user_id, $data);
                        //user mail
                        $message = "Your access has been updated.";
                        $user_mail_data = array($user->fname . ' ' . $user->lname, $message,$branch_details->company_name);
                        sendMail($template = 'user_access', $slug = 'user_access', $to = $user->emailid, $user_mail_data);
                    } else if ($user_branch && !$user_branch_access) {
                        $this->rolemodel->insertUserAccess($data);
                        //user mail
                        $message = "You get access for " . $branch_details->company_name . " successfully.";
                        $user_mail_data = array($user->fname . ' ' . $user->lname, $message,$branch_details->company_name);
                        sendMail($template = 'user_access', $slug = 'user_access', $to = $user->emailid, $user_mail_data);
                    } else {
                        $user_branch_data = array(
                            'user_id' => $admin_user_id,
                            'company_id' => $branch_id,
                            'status' => 1,
                            'created_at' => date("Y-m-d H:i:s")
                        );
                        $this->rolemodel->insertUserBranch($user_branch_data);
                        $this->rolemodel->insertUserAccess($data);
                        //user mail
                        $message = "You get access for " . $branch_details->company_name . " successfully.";
                        $user_mail_data = array($user->fname . ' ' . $user->lname, $message,$branch_details->company_name);
                        sendMail($template = 'user_access', $slug = 'user_access', $to = $user->emailid, $user_mail_data);
                    }
                } else {
                    $this->rolemodel->insertUserRelation($company_id, $user_id);
                    $user = $this->rolemodel->getUser($user_id);
                    $admin_user = array(
                        'sass_user_id' => $user->id,
                        'username' => $user->username,
                        'hash' => $user->hash,
                        'fname' => $user->fname,
                        'lname' => $user->lname,
                        'email' => $user->emailid,
                        'password' => $user->password,
                        'image' => $user->image,
                        'ip_address' => $user->ip_address,
                        'last_login' => $user->last_login,
                        'user_agent' => $user->user_agent,
                        'parentid' => $user->parentid,
                        'status' => $user->status,
                        'online_status' => $user->online_status,
                        'created_date' => date("Y-m-d H:i:s"),
                        'archive_status' => $user->archive_status,
                    );
                    $admin_user_id = $this->rolemodel->insertAdminUser($admin_user);
                    $user_branch_data = array(
                        'user_id' => $admin_user_id,
                        'company_id' => $branch_id,
                        'status' => 1,
                        'created_at' => date("Y-m-d H:i:s")
                    );
                    $this->rolemodel->insertUserBranch($user_branch_data);
                    $data['user_id'] = $admin_user_id;
                    $this->rolemodel->insertUserAccess($data);
                    //branch mail                 

                    $message = "You give access to " . $user->fname . ' ' . $user->lname . " successfully.";
                    $company_mail_data = array($branch_details->company_name, $message,$branch_details->company_name);
                    sendMail($template = 'user_access', $slug = 'user_access', $to = $branch_details->email, $company_mail_data);
                    //user mail
                    $message = "You get access for " . $branch_details->company_name . " successfully.";
                    $user_mail_data = array($user->fname . ' ' . $user->lname, $message,$branch_details->company_name);
                    sendMail($template = 'user_access', $slug = 'user_access', $to = $user->emailid, $user_mail_data);
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'save_err';
                    $data_msg['message'] = 'Error in process. Please try again.';
                } else {
                    $this->db->trans_commit();
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'Access successfully saved';
                }
            } else {
                $data_msg['res'] = 'error';
                $data_msg['message'] = $this->form_validation->error_array();
            }
            echo json_encode($data_msg);
            exit;
        }
    }

    public function ajax_delete_user() {
        $data_msg = [];
        if ($this->input->is_ajax_request()) {
            $saas_user_id = $this->input->post('delete_user_id');
            $row = $this->rolemodel->getSaasCompanyUser($saas_user_id);
            $admin_id = $this->rolemodel->getAdminUserId($saas_user_id);
            if (!count($row) && !$admin_id) {
                $data_msg['res'] = 'error';
                $data_msg['message'] = 'This user can not exist in your database.';
            } else {
                $this->db->trans_begin();
                
                $user = $this->rolemodel->getUserById($admin_id);
                $this->load->model('front/usermodel', 'currentusermodel');
                $log = array(
                    'user_id' => $this->session->userdata('admin_uid'),
                    'branch_id' => $this->session->userdata('branch_id'),
                    'module' => 'users',
                    'action' => '`' . $user['fname'] . '` <b>deleted</b>',
                    'previous_data' => json_encode($user),
                    'performed_at' => date('Y-m-d H:i:s', time())
                );
                $this->currentusermodel->updateLog($log);

                $this->rolemodel->deleteCompanyUser($saas_user_id);
                $this->rolemodel->deleteBranchUser($admin_id);
                $this->rolemodel->deleteAdminUser($saas_user_id);
                $this->rolemodel->deleteUserAccess($admin_id);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data_msg['res'] = 'error';
                    $data_msg['message'] = 'Error occured please try again.';
                } else {
                    $this->db->trans_commit();
                    $data_msg['user_id'] = $saas_user_id;
                    $data_msg['res'] = 'success';
                    $data_msg['message'] = 'User deleted successfully.';
                }
            }
            echo json_encode($data_msg);
            exit;
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
