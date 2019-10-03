<?php
/************************************************
 * This helper is used for Site visit *
 ************************************************/
 
function users_site_visit()
{
    $CI = & get_instance();
    $db =& DB();
    
    $current_date=date('dmY');
    //$current_date='09022016';
    if(isset($_COOKIE['site_visitors']) && ($current_date==$_COOKIE['site_visitors']))
    {
        
    }
    else {
        setcookie("site_visitors", $current_date, time() + (86400 * 30 * 7)); // 86400 = 1 day
        
        $ipaddress=$_SERVER['REMOTE_ADDR'];
        $visiting_date=date('Y-m-d H:i:s');
        $visitors_type='1';
        $sql="insert into ".tablename('site_visitors')." set ip_address='".$ipaddress."',visiting_date='".$visiting_date."',visitor_type='".$visitors_type."'";
        $query=$db->query($sql);
    }
}

function page_visits()
{
    $CI = & get_instance();
    $db =& DB();
    
    $ipaddress=$_SERVER['REMOTE_ADDR'];
    $visiting_date=date('Y-m-d H:i:s');
    $visitors_type='0';
    $sql="insert into ".tablename('site_visitors')." set ip_address='".$ipaddress."',visiting_date='".$visiting_date."',visitor_type='".$visitors_type."'";
    $query=$db->query($sql);
}

