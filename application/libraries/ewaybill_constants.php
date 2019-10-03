<?php
// all api request define here
$host = "https://clientbasic.mastersindia.co";
$requestUrl=array(
    'access_token'=>$host.'/oauth/access_token'
);

//Sample user data information to get access_token
$branch_id = $this->session->userdata('branch_id');
$this->db->where('branch_id', $branch_id);
$details = $this->db->get('ewaybill_access_token_info')->row();

// $accessTokenInfo=array(    
//         'username' =>'testeway@mastersindia.co',
//         'password' =>'Test@1234',
//         'client_id' =>'fIXefFyxGNfDWOcCWn',
//         'client_secret' =>'QFd6dZvCGqckabKxTapfZgJc',
//         'grant_type'=>'password',
//     );

$accessTokenInfo=array(    
        'username' => isset($details->username) ? $details->username : '',
        'password' => isset($details->password) ? $details->password : '',
        'client_id' => isset($details->client_id) ? $details->client_id : '',
        'client_secret' => isset($details->client_secret) ? $details->client_id : '',
        'grant_type'=> isset($details->grant_type) ? $details->client_id : '',
    );
define ("requestUrl", serialize ($requestUrl));
define ("accessTokenInfo", serialize ($accessTokenInfo));
?>


