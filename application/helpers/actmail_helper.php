<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once './assets/PHPMailer/src/Exception.php';
require_once './assets/PHPMailer/src/PHPMailer.php';
require_once './assets/PHPMailer/src/SMTP.php';

function sendActMail($template, $slug, $to, $data, $attachment = '', $company_name = '') {
    $CI = & get_instance();
    $CI->load->helper('email');
    $db_data = getDbEmailData($slug);
    $message = "";
    $subject = "Subject";
    $from = 'info@act-erp.com';

    if ($db_data) {
        $page_data = [];
        $page_data['company_name'] = ($company_name) ? $company_name : "";
        if($template){
          $template_name=$template;  
        }else{
          $template_name='template'; 
        }       

        $template = $CI->load->view('mail/'.$template_name, $page_data, TRUE);
        $variable_arr = explode(',', $db_data->variables);
        $content = str_replace($variable_arr, $data, $db_data->content);
        $subject = $db_data->email_subject;
        $message .= str_replace('{{email_message}}', $content, $template);
        $headers = "From: Accounts <" . $db_data->email_from . ">\r\n";
        if ($db_data->email_cc && $db_data->email_cc != '') {
            $headers.= "CC:" . $db_data->email_cc . "\r\n";
        } else {
            $headers.= 'Reply-To: no-reply@act-erp.com' . "\r\n";
        }
        $headers.= 'MIME-Version: 1.0' . "\r\n" .
                'Content-type: text/html; charset=utf-8' . "\r\n" .
                "X-Priority: 1\r\n" .
                'X-Mailer: PHP/' . phpversion();
        $from = $db_data->email_from;

         // mail($to, $subject, $message, $headers);
    }



         /* ================== test email ======================= */

         $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
         try {
            

//             $mail->SMTPDebug = 2;                                 // Enable verbose debug output
//             $mail->isSMTP();                                      // Set mailer to use SMTP
//             $mail->Host = 'ssl://smtp.googlemail.com';  // Specify main and backup SMTP servers
//             $mail->SMTPAuth = true;                               // Enable SMTP authentication
//             $mail->Username = 'cartface.acterp@gmail.com';                 // SMTP username
//             $mail->Password = 'acterp@1234';                           // SMTP password
//             $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//             $mail->Port = 465;                                    // TCP port to connect to


             //Recipients
             $mail->setFrom($from, 'ACT-ERP');
             $mail->addAddress($to);     // Add a recipient
             // $mail->addCC('sketch.dev24@gmail.com');
             if ($attachment) {
	             $mail->addAttachment(FCPATH.'assets/pdf_for_mail_uploads/'.$attachment);
             }

             //Content
             $mail->isHTML(true);                                  // Set email format to HTML
             $mail->Subject = $subject;
             $mail->Body    = ($message) ? $message : "Demo";

             $mail->send();
             // echo 'Message has been sent';
         } catch (Exception $e) {
             // echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
         }

         if ($attachment != "" && file_exists(FCPATH.'assets/pdf_for_mail_uploads/'.$attachment)) {
         	unlink(FCPATH.'assets/pdf_for_mail_uploads/'.$attachment);
         }

         /* ================== test email ======================= */

}