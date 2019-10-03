<?php

class reminders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('bill_reminder_model');
    }

    public function index() {
//        if (!$this->input->is_cli_request()) {
//            echo "This script can only be accessed via the command line" . PHP_EOL;
//            return;
//        }
        //----before due datr----
        $tomorrow_timestamp = strtotime("+1 days");
        $next_days_bill = $this->bill_reminder_model->get_next_days_bill($tomorrow_timestamp);
        if (!empty($next_days_bill)) {
            foreach ($next_days_bill as $bill) {
                //mail
                $this->load->helper('email');
                $ledger_contact_details = $this->bill_reminder_model->getContactDetails($bill->ledger_id);
                if ($ledger_contact_details) {
                    //mail  
                    $payment_date=date("dS-M-Y",  strtotime($bill->credit_date));
                    $mail_data = array($ledger_contact_details->company_name, $bill->bill_name,$payment_date,$bill->bill_amount);
                    sendMail($template = '', $slug = 'bill_reminder_mail', $to=$ledger_contact_details->email, $mail_data);
                    //end mail 
                    $this->bill_reminder_model->mark_reminded($bill->id);
                }
            }
        }
        //----end before due datr----
        //----due date----
        $today_timestamp = strtotime("+0 days");
        $todays_bill = $this->bill_reminder_model->get_todays_bill($today_timestamp);
        if (!empty($todays_bill)) {
            foreach ($todays_bill as $bill) {
                //mail
                $this->load->helper('email');
                $ledger_contact_details = $this->bill_reminder_model->getContactDetails($bill->ledger_id);
                if ($ledger_contact_details) {
                    //mail  
                    $payment_date=date("dS-M-Y",  strtotime($bill->credit_date));
                    $mail_data = array($ledger_contact_details->company_name, $bill->bill_name,$payment_date,$bill->bill_amount);
                    sendMail($template = '', $slug = 'bill_reminder_mail', $to=$ledger_contact_details->email, $mail_data);
                    //end mail 
                    $this->bill_reminder_model->mark_reminded($bill->id);
                }
            }
        }
        //----end due date----
        //----after due date----
        $yesterday_timestamp = strtotime("-5 days");
        $timestamp = strtotime("+0 days");
        $previous_days_bill = $this->bill_reminder_model->get_previous_days_bill($yesterday_timestamp, $timestamp);
       if (!empty($previous_days_bill)) {
            foreach ($previous_days_bill as $bill) {
                //mail
                $this->load->helper('email');
                $ledger_contact_details = $this->bill_reminder_model->getContactDetails($bill->ledger_id);
                if ($ledger_contact_details) {
                    //mail  
                    $payment_date=date("dS-M-Y",  strtotime($bill->credit_date));
                    $mail_data = array($ledger_contact_details->company_name, $bill->bill_name,$payment_date,$bill->bill_amount);
                    sendMail($template = '', $slug = 'bill_reminder_mail', $to=$ledger_contact_details->email, $mail_data);
                    //end mail 
                    $this->bill_reminder_model->mark_reminded($bill->id);
                }
            }
        }
        //----end after due date-----
    }

}
