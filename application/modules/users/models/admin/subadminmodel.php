<?php
class subadminmodel extends CI_Model
{
    public function __construct()
	{
        parent::__construct();
        $this->load->database();
        $this->load->model('front/usermodel', 'currentusermodel');
	}
    
	
	public function changeadmin($arr,$id=NULL)
    {
            if(empty($id))
            {
            $this->db->insert(tablename('admins'),$arr);
            return $this->db->insert_id();

            }
            else
            {

              $this->db->where("id",$id);
              $this->db->update(tablename('admins'),$arr);
              return "1";

            }


    }

    public function getsubadmin($id)
    {
        $sql="select * from ".tablename('admins')."  where id='".$id."' ";
        $query=$this->db->query($sql);
        $r=$query->row();
        if(!empty($r))
        {
            return $r;
        }
        else
        {
            return;
        }
    }


     

    public function subadmindeletion($id)
    {
        $sql="UPDATE ".tablename('admins')." SET status='0', archive_status='1' where id='".$id."' ";
        $query=$this->db->query($sql);
        if(!empty($query))
        {
            return true;
        }
        else
        {
            return;
        }
    }

    public function subadminstatus($id)
    {
        $sql="select status from ".tablename('admins')."  where id='".$id."' ";
        $query=$this->db->query($sql);
        $adminstatus=$query->row();
        if($adminstatus->status==0)
        {
            $status=1;
        }
        else
        {
            $status=0;
        }
        $sq="update ".tablename('admins')."  set status='".$status."' where id='".$id."'";
        $qq=$this->db->query($sq);
        if(!empty($qq))
        {
            return $this->db->affected_rows();
        }
        else
        {
            return;
        }
    }
    
    public function subadminonlinestatus($id)
    {
        $sql="select online_status from ".tablename('admins')."  where id='".$id."' ";
        $query=$this->db->query($sql);
        $adminstatus=$query->row();
        if($adminstatus->online_status==0)
        {
            $status=1;
        }
        else
        {
            $status=0;
        }
        $sq="update ".tablename('admins')."  set online_status='".$status."' where id='".$id."'";
        $qq=$this->db->query($sq);
        if(!empty($qq))
        {
            return $this->db->affected_rows();
        }
        else
        {
            return;
        }
    }

    public function selectsubadmin($id)
    {
        $sql="select admin.*,role.group_name,role.group_role from ".tablename('admins')." admin,".tablename('roles')." role where admin.parentid='".$id."' and admin.group_code=role.id";
        $query=$this->db->query($sql);
        $r=$query->result();
        if(!empty($r))
        {
            return $r;
        }
        else
        {
            return;
        }
    }

    public function selectallsubadmin()
    {
        $branch_id=$this->session->userdata('branch_id');
        $this->db->select('A.*');
        $this->db->from('user_branch UB');
        $this->db->join('admins A', 'A.id = UB.user_id');
        $this->db->where('UB.company_id',$branch_id);
        $this->db->where('UB.status','1');
        $query = $this->db->get();
        return $query->result();  

    }

   
    public function selectmodules()
    {
        $sql="select * from ".tablename('modules')." where status='1' ";
        $query=$this->db->query($sql);
        $r=$query->result();
        if(!empty($r))
        {
            return $r;
        }
        else
        {
            return;
        }
    }
	

  public function rolelist()
    {
        
        $sql="select * from ".tablename('roles')."";
        $query=$this->db->query($sql);
        $r=$query->result();
        if(!empty($r))
        {
            return $r;
        }
        else
        {
            return;
        }
    }
  public function getRole($id)
    {
        
        $sql="select * from ".tablename('roles')." where id='".$id."'";
        $query=$this->db->query($sql);
        $r=$query->row();
        if(!empty($r))
        {
            return $r;
        }
        else
        {
            return;
        }
    }

       public function roleinsertion($group_name,$role,$description)
    {
        
        $sq="insert into ".tablename('roles')." set group_name='".$group_name."',group_role='".json_encode($role)."',group_desc='".$description."' ";
        $qq=$this->db->query($sq);
        
        if(!empty($qq))
        {
            return $this->db->insert_id();
        }
        else
        {
            return;
        }
    }
    

       public function roleupdate($id,$group_name,$role,$description)

    {
        
        $sq="update ".tablename('roles')." set group_name='".$group_name."',group_role='".json_encode($role)."',group_desc='".$description."' where id=".$id;
        $qq=$this->db->query($sq);
        
        if(!empty($qq))
        {
            return "1";
        }
        else
        {
            return;
        }
    }

    public function roledeletion($id)
    {
        $sql="delete from ".tablename('roles')." where id=".$id."";
        $query=$this->db->query($sql);
        if(!empty($query))
        {
            return true;
        }
        else
        {
            return;
        }
    }
	
function isunique($where) {
        $c = $this->db->where($where)->count_all_results(tablename('admins'));
        return $c === 0 ? TRUE : FALSE;
    }
    
    /*
     * add user into saas `user` and `user-relation` table
     */
    public function add_user($data) {
        $this->db2 = $this->load->database('db2', TRUE);
        
        $this->db2->where('id', $this->session->userdata('company_id'));
        $c = $this->db2->get('company_details');
        $company = $c->row();

        $this->db2->where('emailid', $data['emailid']);
        $query = $this->db2->get('user');
        
        //if already email exist, then relation will be added into user-relation table
        if($query->num_rows() > 0){
            
            $this->db2->trans_begin();
            $this->db2->where('emailid', $data['emailid']);
            $q = $this->db2->get('user');
            $r = $q->row();
            $existUserId = $r->id;
            
            $user = array(
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $existUserId,
                'status' => '1',
                'created_date' => date('Y-m-d H:i:s')
            );

            $this->db2->insert('user_relation', $user);

            

            if ($this->db2->trans_status() === FALSE) {
                $this->db2->trans_rollback();
                $this->session->set_flashdata('user-err', 'database error');
            } else {

                $this->db2->trans_commit();

                $this->db->where('email', $data['emailid']);
                $query = $this->db->get('admins');
                if ($query->num_rows() > 0) {
                    // if user already existed to company get the company user id
                    $user = $query->row();
                    $company_user_id = $user->id;
                } else {
                    // if user not existed to company insert into the admin list
                    $user = array(
                        'sass_user_id' => $existUserId,
                        'fname' => $data['fname'],
                        'lname' => $data['lname'],
                        'email' => $data['emailid'],
                        'status' => '1',
                        'created_date' => date('Y-m-d H:i:s')
                    );
                    
                    $this->db->insert('admins', $user);
                    $company_user_id = $this->db->insert_id();
                }                

                // give the user current branch access
                $user_branch = array(
                    'user_id'       =>  $company_user_id,
                    'company_id'    =>  $this->session->userdata('branch_id'),
                    'status'        =>  1,
                    'created_at'   =>  date('Y-m-d'),
                    'updated_date'   =>  date('Y-m-d')
                );
                $this->db->insert('user_branch', $user_branch);
                
                $log = array(
                    'user_id' => $this->session->userdata('admin_uid'),
                    'branch_id' => $this->session->userdata('branch_id'),
                    'module' => 'users',
                    'action' => 'User `' . $data['fname'] . '` <b>added</b> to company `'.$company->company_name.'`',
                    'previous_data' => '',
                    'performed_at' => date('Y-m-d H:i:s', time())
                );
                $this->currentusermodel->updateLog($log);


               // $config = Array(
               //     'protocol' => 'smtp',
               //     'smtp_host' => 'ssl://smtp.googlemail.com',
               //     'smtp_port' => 465,
               //     'smtp_user' => 'cartface.acterp@gmail.com',
               //     'smtp_pass' => 'acterp@1234',
               //     'mailtype'  => 'html', 
               //     'charset'   => 'iso-8859-1'
               // );
               // $this->load->library('email', $config);
               // $this->email->set_newline("\r\n");

               // // for sending a mail to the user's email address
               // $msg = "<p>Dear <strong>" . $data['fname'] . "</strong>,<p>"
               //         . "<p>You have been added to the company " . $company->company_name . "</p>"
               //         . "<p>Please click on the link below to login<br/>"
               //         . SAAS_URL . "</p>";

               // $this->email->from('info@act-erp.com', 'ACT-ERP');
               // $this->email->to($data['emailid']);
               // $this->email->subject('Added to new company at ACT-ERP');
               // $this->email->message($msg);

               // $this->email->send();
               
               // // for sending a mail to the respective company email address
               // $msg = "<p>A user has been added to your company</p>"
               //         . "<p>User details - <br/>"
               //         . "Name : " . $data['fname'] . " " . $data['lname'] . "<br/>"
               //         . "Email: " . $data['emailid'] . "</p>";

               // $this->email->from('info@act-erp.com', 'ACT-ERP');
               // $this->email->to($company->email);
               // $this->email->subject('Added to new company at ACT-ERP');
               // $this->email->message($msg);

               // $this->email->send();
                
                /* test with php mail */

                $to = $company->email;

                $subject = 'Added to new company at ACT-ERP';

                $headers = "From: Accounts <info@act-erp.com>"."\r\n";
                $headers .= "Reply-To: info@act-erp.com"."\r\n";
                // $headers .= "MIME-Version: 1.0\r\n";
                // $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers.= 'MIME-Version: 1.0' . "\r\n" .
                            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                            "X-Priority: 1\n" .
                            'X-Mailer: PHP/' . phpversion();

                $message = "<p>A user has been added to your company</p>"
                        . "<p>User details - <br/>"
                        . "Name : " . $data['fname'] . " " . $data['lname'] . "<br/>"
                        . "Email: " . $data['emailid'] . "</p>";

                mail($to, $subject, $message, $headers);
                
                $to = $data['emailid'];

                $subject = 'Added to new company at ACT-ERP';

                $headers = "From: Accounts <info@act-erp.com>"."\r\n";
                $headers .= "Reply-To: info@act-erp.com"."\r\n";
                // $headers .= "MIME-Version: 1.0\r\n";
                // $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers.= 'MIME-Version: 1.0' . "\r\n" .
                        'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                        "X-Priority: 1\n" .
                        'X-Mailer: PHP/' . phpversion();

                $message = "<p>Dear <strong>" . $data['fname'] . "</strong>,<p>"
                        . "<p>You have been added to the company " . $company->company_name . "</p>"
                        . "<p>Please click on the link below to login<br/>"
                        . SAAS_URL . "</p>";

                mail($to, $subject, $message, $headers);

                /* test with php mail */
            }
            
            
            $this->session->set_flashdata('user-success', 'User added to the company, check email...');
            return false;
        }

        $this->db2->trans_begin();
        $this->db2->insert('user', $data);
        $insert_id = $this->db2->insert_id();
        
        $user = array(
            'company_id' => $this->session->userdata('company_id'),
            'user_id' => $insert_id,
            'status' => '1',
            'created_date' => date('Y-m-d H:i:s')
        );
        
        $this->db2->insert('user_relation', $user);
        
        $user = array(
            'sass_user_id' => $insert_id,
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'email' => $data['emailid'],
            'status' => '1',
            'created_date' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert('admins', $user);
        $company_user_id = $this->db->insert_id();

        $user_branch = array(
            'user_id'       =>  $company_user_id,
            'company_id'    =>  $this->session->userdata('branch_id'),
            'status'        =>  1,
            'created_at'   =>  date('Y-m-d'),
            'updated_date'   =>  date('Y-m-d')
        );
        $this->db->insert('user_branch', $user_branch);

        
        
        
        if ($this->db2->trans_status() === FALSE) {
            $this->db2->trans_rollback();
            $this->session->set_flashdata('user-err', 'database error');
        } else {

            $this->db2->trans_commit();
            
            $log = array(
                'user_id' => $this->session->userdata('admin_uid'),
                'branch_id' => $this->session->userdata('branch_id'),
                'module' => 'users',
                'action' => 'User `' . $data['fname'] . '` is <b>invited</b>',
                'previous_data' => '',
                'performed_at' => date('Y-m-d H:i:s', time())
            );
            $this->currentusermodel->updateLog($log);

            
            $this->session->set_flashdata('user-success', 'User successfullly created');
            
           // $config = Array(
           //     'protocol' => 'smtp',
           //     'smtp_host' => 'ssl://smtp.googlemail.com',
           //     'smtp_port' => 465,
           //     'smtp_user' => 'cartface.acterp@gmail.com',
           //     'smtp_pass' => 'acterp@1234',
           //     'mailtype'  => 'html', 
           //     'charset'   => 'iso-8859-1'
           // );
           // $this->load->library('email', $config);
           // $this->email->set_newline("\r\n");

           // // $msg = "<p>Dear <strong>" . $data['fname'] . "</strong>,<p>"
           // //         . "<p>This is a invitation mail from act-erp to join us as a user of our erp system</p>"
           // //         . "<p>Please click on the link below to accept this invitation<br/>"
           // //         . SAAS_URL. 'accept-invitation?id=' .  base64_encode($insert_id) . "&company=" .  base64_encode($this->session->userdata('company_id')) . "&branch=" .  base64_encode($this->session->userdata('branch_id')) ."</p>";
           // $msg = "<p>Dear <strong>" . $data['fname'] . "</strong>,<p>"
           //         . "<p>This is a invitation mail from act-erp to join us as a user of our erp system</p>"
           //         . "<p>Please click on the link below to accept this invitation<br/>"
           //         . SAAS_URL. 'accept-invitation?id=' .  base64_encode($insert_id) ."</p>";

           // $this->email->from('info@act-erp.com', 'ACT-ERP');
           // $this->email->to($data['emailid']);
           // $this->email->subject('Invitation from ACT-ERP');
           // $this->email->message($msg);

           // $this->email->send();
            
            
            /* test with php mail */
            
            $to = $data['emailid'];

            $subject = 'Invitation from ACT-ERP';

            $headers = "From: info@act-erp.com"."\r\n";
            $headers .= "Reply-To: info@act-erp.com"."\r\n";
            // $headers .= "MIME-Version: 1.0\r\n";
            // $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers.= 'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                    "X-Priority: 1\n" .
                    'X-Mailer: PHP/' . phpversion();

            $message = "<p>Dear <strong>" . $data['fname'] . "</strong>,<p>"
                   . "<p>This is a invitation mail from act-erp to join us as a user of our erp system</p>"
                   . "<p>Please click on the link below to accept this invitation<br/>"
                   . SAAS_URL. 'accept-invitation?id=' .  base64_encode($insert_id) ."</p>";

            mail($to, $subject, $message, $headers);

            /* test with php mail */

        }
    }
    
    /*
     * get all invited users
     */
    public function getInvitedUsers() {
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db2->select('emailid, fname, lname, password, company_id, user.id as id');
        $this->db2->where('user_relation.status', '2');
        $this->db2->where('user_relation.company_id', $this->session->userdata('company_id'));
        $this->db2->from('user');
        $this->db2->join('user_relation', 'user.id = user_relation.user_id');
        $query = $this->db2->get();
        return $query->result();
    }
    
    /*
     * get log details for a current user
     */
    public function getLogForUser() {
        $this->db->select('activity_logs.*, admins.fname as username');
        $this->db->from('activity_logs');
        $this->db->join('admins', 'activity_logs.user_id = admins.id');
        $this->db->where('user_id', $this->session->userdata('admin_uid'));
        $this->db->order_by('id', 'desc');
        return $this->db->get()->result();
    }
    
    /*
     * get log details for a specific user
     */
    public function getLogByUserId($user_id) {
        $this->db->select('activity_logs.*, admins.fname as username');
        $this->db->from('activity_logs');
        $this->db->join('admins', 'activity_logs.user_id = admins.id');
        $this->db->where('activity_logs.user_id', $user_id);
        $this->db->order_by('activity_logs.id', 'desc');
        return $this->db->get()->result();
    }

    /*
     * delete logs
     */
    public function deleteLogs($ids)
    {
        $this->db->where_in('id', $ids);
        return $this->db->delete('activity_logs');
    }
    

}
