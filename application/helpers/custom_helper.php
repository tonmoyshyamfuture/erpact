<?php 
function despatchDetails($entry_id){
    $CI = & get_instance();
    $CI->load->model('account/custom');
    $status = $CI->custom->setDespatchDetails($entry_id);
    return true;
}

?>