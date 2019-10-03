<?php

function sendMail($template, $slug, $to, $data) {
    $CI = & get_instance();
    $CI->load->helper('email');
    $db_data = getDbEmailData($slug);
    if ($db_data) {
        $page_data = [];
        if($template){
          $template_name=$template;  
        }else{
          $template_name='template'; 
        }
        $template = $CI->load->view('mail/'.$template_name, $page_data, TRUE);
        $variable_arr = explode(',', $db_data->variables);
        $content = str_replace($variable_arr, $data, $db_data->content);
        $subject = $db_data->email_subject;
        $message = str_replace('{{email_message}}', $content, $template);
        $headers = "From: Accounts <" . $db_data->email_from . ">\r\n";
        if ($db_data->email_cc && $db_data->email_cc != '') {
            $headers.= "CC:" . $db_data->email_cc . "\r\n";
        } else {
            $headers.= 'Reply-To: no-reply@access.myact-erp.com' . "\r\n";
        }
        $headers.= 'MIME-Version: 1.0' . "\r\n" .
                'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                "X-Priority: 1\n" .
                'X-Mailer: PHP/' . phpversion();
        
        return mail($to, $subject, $message, $headers);
    }
}

function getDbEmailData($slug) {
    $CI = & get_instance();
    $CI->load->model('admin/emailmodel');
    return $CI->emailmodel->getDBEmailData($slug);
}
