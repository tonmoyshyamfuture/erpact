<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MX_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->library('form_validation');
        $this->load->model('admin/subadminmodel', 'adminsmodel');
        $this->load->helper('admin_permission');
        admin_authenticate();
    }

    /* sub-admin management start */

    public function index() {

        $id = $this->session->userdata('admin_uid');

        $data['subadmins'] = $this->adminsmodel->selectallsubadmin();
        $data['invited'] = $this->adminsmodel->getInvitedUsers();
        $data['log_details'] = $this->adminsmodel->getLogForUser();

        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | All Users');
        $this->layouts->render('admin/subadminlist', $data, 'admin');
    }

    /*
     * for add user
     */

    public function add_user() {
        if ($_POST) {
            $this->form_validation->set_rules('fname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('user-err', validation_errors());
                redirect(base_url() . 'admin/add-user');
            } else {
                $user = array(
                    'fname' => $this->input->post('fname'),
                    'lname' => $this->input->post('lname'),
                    'emailid' => $this->input->post('email'),
                    'status' => '1',
                    'user_type' => '1',
                    'created_date' => date('Y-m-d H:i:s')
                );

                $this->adminsmodel->add_user($user);
                redirect(base_url() . 'admin/add-user');
            }
        }

        $data = array();
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' | Add User');
        $this->layouts->render('admin/add_user', $data, 'admin');
    }

    public function formsubadmin($id = NULL) {
        if (!empty($id))
            $param = 'E';
        else
            $param = 'A';

        $permission = admin_users_permission($param, 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();
            $admin_uid = $this->session->userdata('admin_uid');
            $msg = "Sub Admin Added Successfully";
            if (!empty($id)) {
                $id = base64_decode(urldecode($id));
                $msg = "Sub Admin Updated Successfully";
            }

            if ($this->input->post()) {

                $arr = array();
                $arr['fname'] = $fname = $this->input->post('fname');
                $arr['lname'] = $this->input->post('lname');
                $arr['username'] = $this->input->post('username');
                $arr['email'] = $this->input->post('email');
                if (!empty($this->input->post('pass'))) {
                    $arr['password'] = sha1($this->input->post('pass'));
                }
                $arr['group_code'] = $this->input->post('group');
                if (!empty($_FILES['profile_pic']['name'])) {

                    $config['upload_path'] = './assets/uploads/';
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['file_name'] = time();

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('profile_pic')) {
                        echo $this->upload->display_errors();
                        exit;
                    } else {
                        $upload_data = $this->upload->data();
                        $arr['image'] = $upload_data['file_name'];
                    }
                } else {
                    if (!empty($id)) {
                        $arr['image'] = $this->input->post('old-profile-pic');
                    } else {
                        $arr['image'] = "";
                    }
                }
                $arr['parentid'] = $admin_uid;
                $arr['status'] = 1;
                $arr['created_date'] = date("Y-m-d H:i:s");
                $arr['modified_date'] = date("Y-m-d H:i:s");

                $returnval = $this->adminsmodel->changeadmin($arr, $id);
                if (!empty($returnval)) {

                    if (!empty($this->input->post('pass'))) {
                        $message = "<p style=\"border: 1px solid #f0f0f0;padding: 12px;background-color: #f0f0f0;\"><strong style=\"display:block;\">Email : </strong> " . $this->input->post('email') . "</p>";
                        $message .="<p style=\"border: 1px solid #f0f0f0;padding: 12px;background-color: #f0f0f0;\"><strong style=\"display:block;\" >Password : </strong> " . $this->input->post('pass') . "</p>";
                        $arr = array();
                        $arr['message'] = $message;
                        $arr['email'] = $this->input->post('email');
                        $arr['subject'] = " Sub Admin Login Details";
                        admin_email($arr);
                    }

                    $this->session->set_flashdata('successmessage', $msg);
                } else {
                    $this->session->set_flashdata('errormessage', "Oops! Something went wrong.Please try agagin later.");
                }
                $redirect = site_url('admin/users');
                redirect($redirect);
            }
            $data = array();

            if (!empty($id)) {
                $data['result'] = $this->adminsmodel->getsubadmin($id);
            }

            $data['list'] = $this->adminsmodel->rolelist();
            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' |Subadmin Management');
            if (!empty($id)) {
                $this->layouts->render('admin/editsubadmin', $data, 'admin');
            } else {
                $this->layouts->render('admin/addsubadmin', $data, 'admin');
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function subadminstatus($id) {
        $permission = admin_users_permission('S', 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();
            $id = base64_decode(urldecode($id));
            $status = $this->adminsmodel->subadminstatus($id);
            if (!empty($status)) {
                $this->session->set_flashdata('successmessage', 'Subadmin status changed successfully');
                $redirect = site_url('admin/users');
                redirect($redirect);
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function subadminonlinestatus($id) {
        $permission = admin_users_permission('E', 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();
            $id = base64_decode(urldecode($id));
            $status = $this->adminsmodel->subadminonlinestatus($id);
            if (!empty($status)) {
                $this->session->set_flashdata('successmessage', 'Subadmin online status changed successfully');
                $redirect = site_url('admin/users');
                redirect($redirect);
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function deleteSubadmin($id) {
        $permission = admin_users_permission('D', 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();
            $id = base64_decode(urldecode($id));
            $delete = $this->adminsmodel->subadmindeletion($id);
            if (!empty($delete)) {
                $this->session->set_flashdata('successmessage', 'Subadmin deleted successfully');
                $redirect = site_url('admin/users');
                redirect($redirect);
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function checkUnique() {
        $field = $this->input->post('field');
        $value = $this->input->post('value');

        $where[$field] = $value;

        echo $this->adminsmodel->isunique($where) ? "true" : "false";
    }

    /* sub-admin management end */



    /* -- Start Of Role-- */

    public function role() {
        $permission = admin_users_permission('V', 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();

            $data['result'] = $this->adminsmodel->rolelist();

            $statuspermission = admin_users_permission('S', 'users', $rtype = FALSE);
            if ($statuspermission)
                $data['statuspermission'] = "";
            else
                $data['statuspermission'] = "not-permited";

            $editpermission = admin_users_permission('E', 'users', $rtype = FALSE);
            if ($editpermission)
                $data['editpermission'] = "";
            else
                $data['editpermission'] = "not-permited";

            $deletepermission = admin_users_permission('D', 'users', $rtype = FALSE);
            if ($deletepermission)
                $data['deletepermission'] = "";
            else
                $data['deletepermission'] = "not-permited";


            $getsitename = getsitename();
            $this->layouts->set_title($getsitename . ' | All Roles');
            $this->layouts->render('admin/rolelist', $data, 'admin');
        }
        else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function addRole() {
        $this->load->model('role/admin/rolemodel');
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
        // echo "<pre>";print_r($menu);exit();
        $user_access = $this->rolemodel->getUserAccess(1, 1);
        $account_user_id = $this->session->userdata('admin_uid');
        $data['saas_user_id'] = $this->rolemodel->getSaasUserId($account_user_id);

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
        $data['branch'] = $all_branch;
        $data['user'] = $all_user;
        $getsitename = getsitename();
        $this->layouts->set_title($getsitename . ' |Role Management');
        $this->layouts->render('admin/role_management', $data, 'admin');
    }

    public function editRole($id) {
        $permission = admin_users_permission('E', 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();
            $id = base64_decode(urldecode($id));
            if ($this->input->post()) {
                $group_name = $this->input->post('group');
                $role = $this->input->post('role');
                $description = $this->input->post('desc');
                $flag = $this->adminsmodel->roleupdate($id, $group_name, $role, $description);
                if (!empty($flag)) {
                    $this->session->set_flashdata('successmessage', 'Role Updated successfully');
                } else {
                    $this->session->set_flashdata('errormessage', 'Oops! something went wrong. Please try again');
                }
                $redirect = $this->config->item('base_url') . 'admin/role';
                redirect($redirect);
            } else {
                $data['result'] = $this->adminsmodel->getRole($id);
                $data['role'] = $this->adminsmodel->selectmodules();
                $getsitename = getsitename();
                $this->layouts->set_title($getsitename . ' |Role Management');
                $this->layouts->render('admin/role_management', $data, 'admin');
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    public function deleteRole($id) {
        $permission = admin_users_permission('D', 'users', $rtype = TRUE);
        if ($permission) {
            admin_authenticate();
            $id = base64_decode(urldecode($id));

            $delete = $this->adminsmodel->roledeletion($id);
            if (!empty($delete)) {
                $this->session->set_flashdata('successmessage', 'Role Group deleted successfully');
                $redirect = site_url('admin/role');
                redirect($redirect);
            }
        } else {
            $this->session->set_flashdata('errormessage', "You are not permitted to perform this action");
            $redirect = site_url('admin/dashboard');
            redirect($redirect);
        }
    }

    /* -- End Of Role-- */

    public function getLogByUserId() {
        $user_id = $this->input->post('user_id');
        $data['log_details'] = $this->adminsmodel->getLogByUserId($user_id);
        return $this->load->view('admin/log_view', $data);
    }

    /*
     * Delete Logs
     */

    public function deleteLogs() {
        $ids = $this->input->post('ids');
        $res = $this->adminsmodel->deleteLogs($ids);
        if ($res) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
