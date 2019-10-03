<?php
class usermodel extends CI_Model
{
    public function __construct()
    {

            parent::__construct();
            $this->load->database();
    }
    
    // Update front user info update
    public function updateuserinfo()
    {
        $id=$this->input->post('userid');
        $firstname=$this->input->post('firstname');
        $lastname=$this->input->post('lastname');
        $email=$this->input->post('email');
        $phone=$this->input->post('phone');
        $address=$this->input->post('address');
        $defaultcountry=$this->input->post('defaultcountry');
        $defaultstate=$this->input->post('defaultstate');
        $defaultcity=$this->input->post('defaultcity');
        $userhidimage=$this->input->post('userhidimage');
        
        $file=$_FILES['userimage'];
        if(!empty($file['name']))
        {
            $imagename=$file['name'];
            $imagearr=explode('.',$imagename);
            $ext=end($imagearr);
            $newimage=time().rand().".".$ext;

            if($ext=="jpg" or $ext=="jpeg" or $ext=="png" or $ext=="bmp")
            {
                $this->load->library('image_lib');

                $config['image_library'] = 'gd2';
                $config['source_image'] = $file['tmp_name'];
                $config['new_image'] = "application/modules/customer/uploads/thumb/".$newimage;
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 200;
                $config['height'] = 100;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                $this->image_lib->resize();

                $config1['image_library'] = 'gd2';
                $config1['source_image'] = $file['tmp_name'];
                $config1['new_image'] = "application/modules/customer/uploads/".$newimage;
                $config1['create_thumb'] = FALSE;
                $config1['maintain_ratio'] = FALSE;
                $config1['width'] = 1582;
                $config1['height'] = 751;

                $this->image_lib->clear();
                $this->image_lib->initialize($config1);
                $this->image_lib->resize();
            }
            else
            {
                $newimage="";
                //$this->session->set_flashdata('errormessage', 'Only .jpg,.jpeg,.bmp and .png image extensions are supported');
            }
        }
        else 
        {
            if(!empty($userhidimage))
                $newimage=$userhidimage;
            else
                $newimage="";
        }
        $image=$newimage;
        $modified_date=date('Y-m-d H:i:s');
        
        $sql="update ".tablename('users')." set fname='".$firstname."',lname='".$lastname."',address='".$address."',phone='".$phone."',country_id='".$defaultcountry."',state_id='".$defaultstate."',city_id='".$defaultcity."',email='".$email."',image='".$image."',modified_date='".$modified_date."' where id=".$id;
        $query=$this->db->query($sql);
        if(!empty($query))
        {
                return 1;
        }
        else
        {
                return;
        }
    }
    
    public function updateuseraddress()
    {
        $id=$this->input->post('addressuserid');
        $address=$this->input->post('address');
        $addresscountry=$this->input->post('addresscountry');
        $addressstate=$this->input->post('addressstate');
        $addresscity=$this->input->post('addresscity');
        $postal_code=$this->input->post('zipcode');
        $modified_date=date('Y-m-d H:i:s');
        
        $sql="update ".tablename('users')." set address='".$address."',country_id='".$addresscountry."',state_id='".$addressstate."',city_id='".$addresscity."',postal_code='".$postal_code."',modified_date='".$modified_date."' where id=".$id;
        $query=$this->db->query($sql);
        if(!empty($query))
        {
                return 1;
        }
        else
        {
                return;
        }
    }
    
    /*
     * update user log after any action performed
     */
    public function updateLog($data) {
        $this->db->insert('activity_logs', $data);
    }
    
    /*
     * get log details for a specific module
     */
    public function getLog($module) {
        $this->db->select('activity_logs.*, admins.fname as username');
        $this->db->from('activity_logs');
        $this->db->join('admins', 'activity_logs.user_id = admins.id');
        $this->db->where('module', $module);
        return $this->db->get()->result();
    }
}