<?php
function langview()
{
	$data=array();
	$CI = & get_instance();
	$CI->load->model('language/languagemodel');
	$data['langstat']=$CI->languagemodel->loadlanguage();
	return $CI->load->view('language/language',$data,TRUE);
}

function scriptview()
{
	$data=array();
	$CI = & get_instance();
	$CI->load->model('language/languagemodel');
	$data['langstat']=$CI->languagemodel->loadlanguage();
	return $CI->load->view('language/script',$data,TRUE);
}
?>