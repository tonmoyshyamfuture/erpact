<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
    
     function price_format($amount) {
        $CI = & get_instance();
         $CI->load->helper('money_format');
         $CI->load->model('admin/dashboardmodel');
       $data = $CI->dashboardmodel->getPriceFormat();
       if ($data->price_format == 0) {
           // $fmt = new NumberFormatter($locale = 'en_IN', NumberFormatter::DECIMAL);
           // return $fmt->format($amount);

           setlocale(LC_MONETARY, 'en_IN.UTF-8');
           // $amount = money_format_act('%!i', $amount);
           // $amount = money_format_act('%.2n', $amount);
           $amount = money_format_india($amount);
           return $amount;
       } else {
           // $fmt = new NumberFormatter($locale = 'en_US', NumberFormatter::DECIMAL);
           // $amount=$fmt->format($amount);
           // $amount_arr=explode(".",$amount);
           // $point='00';
           // if(!isset($amount_arr[1])){
           //  $point='00';   
           // }elseif (isset($amount_arr[1]) && strlen($amount_arr[1])==1) {
           //  $point=$amount_arr[1].'0';    
           // }elseif (isset($amount_arr[1]) && strlen($amount_arr[1])==2) {
           //  $point=$amount_arr[1];    
           // }
           // if(isset($amount_arr[0])){
           //  $decimal= $amount_arr[0];  
           // }else{
           //  $decimal='00';   
           // }
           // return $decimal.'.'.$point;
           setlocale(LC_MONETARY, 'en_US.UTF-8');
           $amount = money_format_act('%!i', $amount);
           // $amount = money_format_act('%.2n', $amount);

           return $amount;
       }
   }
    
    
    
    //company address
    function company_address() {
      $CI = & get_instance();
        $CI->load->model('admin/dashboardmodel');
        $data = $CI->dashboardmodel->getCompanyDetails();
        $html='';
        if($data){
        $html.='<div class="col-xs-12 text-center">';
        $html.='<h3>'.$data->company_name.'</h3>';
        $html.='<p>';
        $html.=(isset($data->appt_number) && $data->appt_number)?$data->appt_number.', ':'';
        $html.=(isset($data->street_address) && $data->street_address)?$data->street_address.', ':'';
        $html.=(isset($data->city_name) && $data->city_name)?$data->city_name.' - ':'';
        $html.=(isset($data->zip_code)&& $data->zip_code)?$data->zip_code.', ':'';
        $html.=(isset($data->state_name) && $data->state_name)?$data->state_name.', ':'';
        $html.=(isset($data->country_name) && $data->country_name)?$data->country_name:'';
        $html.='<br>';
        $html.=(isset($data->mobile) && $data->mobile)?'Mobile: '.$data->mobile.',':'';
        $html.=(isset($data->telephone) && $data->telephone)?'Phone: '.$data->telephone.',':'';
        $html.='<br>';
        $html.=(isset($data->email) && $data->email)?' Email: '.$data->email:'';
        $html.='</p>';
        $html.='</div>';
        }
        return $html;
    }
    
    
    
    
}