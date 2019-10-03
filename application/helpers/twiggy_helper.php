<?php
/**
 * By Riaz Laskar (you r saved :D i did it all )
 * functions defined here will be avilable in twig tempelates
 * part of tempelate management
 */
function theme_url(){
	echo base_url().'assets/themes/';
}

function get_theme_url(){
	return base_url().'assets/themes/';
}

function get_themes_folder_path()
{
	return APPPATH.'../assets/themes/';
}

function getActiveThemeInfo()
{
	$ci =& get_instance();

	$ci->load->model('theme/admin/thememodel','tm');

	return $ci->tm->getActiveTheme();
}

function getActiveTheme()
{
	$activetheme = getActiveThemeInfo();

	return $activetheme->theme_slug;
}

function getCurrentThemePath()
{
	$activetheme = getActiveThemeInfo();

	return APPPATH.'../assets/themes/'.$activetheme->theme_slug;
}

function getCurrentThemeUrl()
{
	$activetheme = getActiveThemeInfo();

	return  base_url().'assets/themes/'.$activetheme->theme_slug;
}

function doUrlEncode($str)
{
	return urlencode(base64_encode($str));
}

function doUrlDecode($str)
{
	return base64_decode(urldecode($str));
}

function get_settings($name=null)
{
	$ci = & get_instance();
	$ci->load->model('admin/settingsmodel','sm');
	$s = $ci->sm->getSettings();
	if($name)
	{
		return $s[$name];
	}

	return $s;
}
function get_setting_by_name($name)
{
	$ci = & get_instance();
	$ci->load->model('admin/settingsmodel','sm');
	$s = $ci->sm->getSettingByName($name);
	return ($s)?$s->value:'';
}

function get_country_state_city_name($id,$tableName)
{

	$ci = & get_instance();
	$ci->load->model('admin/settingsmodel','sm');
	$name = $ci->sm->getCountryStateCityName($id,$tableName);
	return $name;
}


function generateMenu($menu_name,$attr)
{
	$ci =& get_instance();
	$ci->load->model('headermenu/admin/headermenumodel','hmm');
	$menu_arr = $ci->hmm->getMenuByGroupName($menu_name);
	echo '<ul '.$attr.'>';
	array_map(function($menu){
		
		echo '<li>';
		echo '<a href="'.site_url($menu->slug).'" >'.$menu->label.'</a>';
		generateMenuHTML($menu->submenu[$menu->id],'class="dropdown-menu"'); 
		echo '</li>'; 
	},$menu_arr);
	echo '</ul>'; 
}

function getThisSettingsData($key_name)
{

	$ci = & get_instance();
	$ci->load->model('admin/settingsmodel','sm');
	$settings_name = $ci->sm->get_settings_data($key_name)->value;
	return $settings_name;
}

function generateMenuHTML($menu,$attr='')
{
	if(!empty($menu))
      {
        echo '<ul '.$attr.'>'; 
        array_map(function($m) use($attr){
          if(!empty($m))
              echo '<li><a href="'.site_url($m->slug).'">'.$m->label.'</a>';

          if(!empty($m->submenu[$m->id]))
          {
            generateMenuHTML($m->submenu[$m->id],$attr);
          }
          echo '</li>';
        },$menu);
        echo '</ul>';
      }
}

/**
 * [getFunctionList is an internal function always put this at the end of this file
 *  it generate functions lists defined here and these functions
 *  get auto registered in the the twig tempelete engine. 
 * ]
 */
function getFunctionList()
{
	# The Regular Expression for Function Declarations
	$functionFinder = '/function[\s\n]+(\S+)[\s\n]*\(/';
	# Init an Array to hold the Function Names
	$functionArray = array();
	# Load the Content of the PHP File
	$fileContents = file_get_contents(__FILE__);

	# Apply the Regular Expression to the PHP File Contents
	preg_match_all( $functionFinder , $fileContents , $functionArray );

	# If we have a Result, Tidy It Up
	if( count( $functionArray )>1 ){
	  # Grab Element 1, as it has the Matches
	  $functionArray = $functionArray[1];
	}
	array_pop($functionArray);
	return $functionArray;
}

function getStreetOptions()
{
    if(check_plugin_exsist('streetname'))
    {
        $CI = & get_instance();
        $db =& DB();
        $final_array['is_mandatory']   = get_setting_by_name('is_street_mandatory');
        $sql            = 'SELECT * FROM pb_street_name';
        $query          = $db->query($sql);
        $result         = $query->result();
        $final_array['street_list']   = $result;

        return $final_array;
    }
    else
        return 0;
}

function getBundleList($productid = '')
{
    $CI = & get_instance();
    $db =& DB();

    if(check_plugin_exsist('productbundle'))
    {
        $sql = 'SELECT PDPL.*,PP1.name as parent_name,PP1.slug as slug,PP1.price
        FROM pb_bundle_product_list as PDPL
        INNER JOIN pb_products AS PP ON PP.id = PDPL.product_id
        INNER JOIN pb_products AS PP1 ON PP1.id = PDPL.parent_id
        WHERE PDPL.product_id = '.$productid;
        $query = $db->query($sql);
        $result = $query->result();

        if(is_array($result) && COUNT($result)>0)
        {
            foreach ($result as $key => $value) {
                if($value->parent_id != $value->stock_id)
                {
                    $sql    = 'SELECT stockdet,price
                    FROM pb_product_stock WHERE id ='.$value->stock_id;
                    $query  = $db->query($sql);
                    $variant_details = $query->result();
                    $result[$key]->variant_name     = $variant_details[0]->stockdet;
                    $result[$key]->variant_price    = $variant_details[0]->price;
                }
                else
                {
                    $result[$key]->variant_name     = 'Genaral';
                    $result[$key]->variant_price    = $result[$key]->price;
                }
            }
        }
        //echo "<pre>";print_r($result);exit;
        if(is_array($result) and COUNT($result)>0)
            return $result;
        else
            return 0;
    }
    else
        return 0;
}

function check_plugin_exsist($slug)
{
    $CI = & get_instance();
    $CI->load->model('admin/pluginmodel');
    $plugin_exist=$CI->pluginmodel->checkplugin($slug);

    if(!empty($plugin_exist))
    {
      return 1;
    }
    else
    {
      return 0;
    }
}



function plugin_get_discountbyorder($user_id,$subtotal)
{
    $CI = & get_instance();
    $CI->load->model('admin/pluginmodel');

    if(check_plugin_exsist('initialorderdiscount'))
    {
        $arytotprice=$CI->pluginmodel->get_discountbyorder($user_id,$subtotal);
        return $arytotprice;
    }
    else
    {
        return "";
    }
}


function plugin_get_ad($slug)
{
    $CI = & get_instance();
    $CI->load->model('admin/pluginmodel');
    $plugin_exist=$CI->pluginmodel->checkplugin('advertisement');

    if(!empty($plugin_exist))
    {
      $addresult=$CI->pluginmodel->get_ad_dtls($slug);
      if(!empty($addresult))
      {
        return $addresult;
      }
      else
      {
        return "";
      }
    }
    else
    {
      return "";
    }
}


function showfeaturedcategory($slug)
{
    $CI = & get_instance();
    $CI->load->model('admin/pluginmodel');

    if(check_plugin_exsist('featuredcategory'))
    {
        $catresult=$CI->pluginmodel->get_fetured_cat_details($slug);
        if(!empty($catresult))
        {
            return $catresult;
        }
        else
        {
            return "";
        }
    }
    else
    {
        return "";
    }
}



function plugin_get_similar_product($productid)
{
    $CI = & get_instance();
    $CI->load->model('admin/pluginmodel');
    $plugin_exist=$CI->pluginmodel->checkplugin('similarproduct');

    if(!empty($plugin_exist))
    {
      $smp_result=$CI->pluginmodel->get_similar_product_details($productid);
      if(!empty($smp_result))
      {
        return $smp_result;
      }
      else
      {
        return "";
      }
    }
    else
    {
      return "";
    }
}

function plugin_get_google_translator($productid)
{
    if (file_exists(FCPATH."modules/language/views/language.php") && check_plugin_exsist('language'))
    {
      echo langview();
    }

    if (file_exists(FCPATH."modules/language/views/script.php")  && check_plugin_exsist('language'))
    {
      echo scriptview(); 
    }
}

function decode_jeson_discount_json($string,$productquantity)
{

    $html="";
    $discount_array=json_decode($string,true);
    foreach ($discount_array as $key => $value) {

        $html.='<tr class="bg-disc">';
            $html.='<td>'.$value['discount_name'].'</td>';
            $html.='<td colspan="2"></td>
            <td>';
                $html.=number_format($value['discount_amount'],2);
                
                    if($value['discount_type']=="P")
                        $html.='(%)';
                    else if($value['discount_type']=="A")
                        $html.="(".get_base_currency_setting('symbol').")";

            $html.='</td>
            <td>=</td>';
            $html.='<td> - '.get_base_currency_setting('symbol').number_format($value['product_discount_amount']*$productquantity,2).'</td>
        </tr>';
    }

    return $html;

}

function decode_jeson_spl_disc($string)
{
    $ary_spl_discount_json=json_decode($string,true);
    $spl_discount_val=0;
    if(!empty($ary_spl_discount_json))
    {
        $spl_discount_val=$ary_spl_discount_json['spl_discount_val']; 
        if($spl_discount_val>0)
        {
            $spl_discount_val=$spl_discount_val;
        }
    }
    return number_format($spl_discount_val,2);
}

function check_product_exist_for_home()
{
    $ci = & get_instance();
    $ci->load->model('products/admin/productsmodel','pm');
    $product=$ci->pm->loadproductsforhome(NULL,NULL,1);

    $product_exist='N';
    if(count($product)>0)
    {
        $product_exist='Y';
    }

    return $product_exist;
}






function generateMegaMenu($menu_name)
{
	    $ci =& get_instance();
        $ci->load->model('headermenu/admin/headermenumodel','mm');
	   if(check_plugin_exsist('megamenu'))
	   {
			$menu_arr = $ci->mm->getMenuByGroupName($menu_name);
		 	
			if(isset($menu_arr))
			{
				return $menu_arr;
			}
	   }
}
function getsubMegamenu($id)
{
	$ci =& get_instance();
    $ci->load->model('headermenu/admin/headermenumodel','sub_mm');
	if(check_plugin_exsist('megamenu'))
	{
		$sub_menu = $ci->sub_mm->getsubMegamenu($id);
		if(isset($sub_menu))
		{
			return $sub_menu;
		}
	}

}
function getsubtosubMegamenu($id)
{
	$ci =& get_instance();
    $ci->load->model('headermenu/admin/headermenumodel','sub_mm');
	if(check_plugin_exsist('megamenu'))
	{
		$sub_menu = $ci->sub_mm->getsubtosubMegamenu($id);
		if(isset($sub_menu))
		{
			return $sub_menu;
		}
	}
}




function sendCartFaceEmail($email_to,$email_from,$email_subject="",$email_body="",$email_cc="")
{
    if(get_setting_by_name('sitename')=="Store Name" || trim(get_setting_by_name('sitename')==""))
    {
        $headerstore=STORE_NAME;
    }
    else
    {
        $headerstore=get_setting_by_name('sitename');
    }
   
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    $headers .= "From: ".$headerstore." <".$email_from."> \r\n";
    $headers .= "Reply-To: ".$email_from."\r\n";
    $headers .= "Return-Path: ".$email_from."\r\n";

    
    if($email_cc!="")
    {
        $headers .= "CC: ".$email_cc. "\r\n";
    }
    
    $headers .= "Reply-To: ".$email_from . "\r\n" .
    "X-Mailer: PHP/" . phpversion();


    if(mail($email_to,$email_subject,$email_body,$headers, "-fme@mydomain.com"))
    {
        return true;
    }
    else
    {
        return;
    }
}


function sendCartFaceAttachmentEmail($email_to,$email_from, $email_subject, $email_body, $output, $file,$email_cc="") {

    if(get_setting_by_name('sitename')=="Store Name" || trim(get_setting_by_name('sitename')==""))
    {
        $headerstore=STORE_NAME;
    }
    else
    {
        $headerstore=get_setting_by_name('sitename');
    }


    $headers = "From: ".$headerstore." <".$email_from. "> \n";
    if($email_cc!="")
    {
        $headers .= "CC: ".$email_cc. "\n";
    }

    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    $headers .= "MIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
    

    $email_body = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_body . "\n\n";
    $email_body .= "--{$mime_boundary}\n";


    $data = $output;
    $data = chunk_split(base64_encode($data));
    // $file="invoice".date("Y-m-d").".pdf";
    $email_body .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$file\"\n" .
            "Content-Disposition: attachment;\n" . " filename=\"$file\"\n" .
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $email_body .= "--{$mime_boundary}\n";
    // }
    if (mail($email_to, $email_subject, $email_body, $headers, "-fme@mydomain.com")) {
        return 1;
    } else {
        return;
    }
}


function IsInjected($str)
{
    $injections = array('(\n+)','(\r+)','(\t+)','(%0A+)','(%0D+)','(%08+)','(%09+)');
    $inject = join('|', $injections);
    $inject = "/$inject/i";
    if(preg_match($inject,$str))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function fetch_all_notification()
{
    
    $ci = & get_instance();
    $ci->load->model('admin/settingsmodel','sm');

    $counter=0;
    $html="";

    /* Fetch Newly Placed Order Customes*/
    $totorder = $ci->sm->getPlacedOrderCount();
    if($totorder>0)
    {
        $counter++;
        $html.='<li><a href="'.site_url('admin/orders').'"><i class="fa fa-shopping-basket text-aqua"></i>'.$totorder.'  new order has been placed.</a></li>';
    }
    /*  Fetch Newly Placed Order Customes*/

    /* Fetch Newly Joined Customes*/
    $ary_customer = $ci->sm->getJoinedCustomer();
    if(!empty($ary_customer))
    {
        foreach($ary_customer as $k=>$v)
        {
            $counter++;
            $html.='<li><a href="'.site_url('admin/edit-customer').'/'.urlencode(base64_encode($v->id)).'/view"><i class="fa fa-user text-aqua"></i> A new customer '.$v->fname.' '.$v->lname.' has Joined with us.</a></li>';
        }
    }
    
    if(get_setting_by_name('enable_low_stock_notification') == 1)
    {
        $ci->load->model('inventory/admin/inventorymodel');
        $productlist = $ci->inventorymodel->getProductList();
        $stockListarr = getLowStockList($productlist);
        if(!empty($stockListarr))
        {
            foreach($stockListarr as $k=>$v)
            {
                $counter++;
                $html.='<li><a href="'.site_url('admin/product-inventory-list').'"><i class="fa fa-user text-aqua"></i>'.'Product stock is low for '.$v['product_name'].'('.$v['balance'].')</a></li>';
            }
        }
    }
    /* Fetch Newly Joined Customes*/

    $ary_result['tot_notification']=$counter;
    $ary_result['html_notification']=$html;

    return $ary_result;
}


function fetch_exp_alert($page_idfire=NULL)
{
    
    $res = _httpPost(ADMIN_API_URL,array('store_name'=>STORE_NAME,'get_store_info'=>1));
    $res = json_decode($res);
    
    $exp_msg="";
    if($res->status == 1)
    {
        $us = $res->result;

        $now = strtotime(date("Y-m-d"));
        $your_date1 = date("Y-m-d",strtotime($us->expire_date));
        $your_date = strtotime($your_date1);

        $datediff = $your_date-$now;
        $dys = floor($datediff/(60*60*24));
        $dys=$dys+1;

        if($us->theme_approv_status!="2")
        {
            if($us->planid==1)
            {
                if($dys>0)
                {

                    $exp_msg="<span class='pull-left'>".$us->plan_name." <br><small>(".$dys." days left)</small></span> <span class='pull-left'><a href='/admin/payment-history.html' class='btn btn-sm btn-success'>Upgrade</a></span>";
                }
                else
                {
                    $exp_msg="<span class='pull-left'>".$us->plan_name." <br><small>(expired)</small></span> <span class='pull-left'><a href='/admin/payment-history.html' class='btn btn-sm btn-success'>Upgrade</a></span>";
                }

            }
            elseif($dys<=7)
            {
                if($dys>0)
                {

                    $exp_msg="<span class='pull-left'>".$us->plan_name." <br><small>(".$dys." days left)</small></span> <span class='pull-left'><a href='/admin/payment-history.html' class='btn btn-sm btn-success'>Upgrade/Renew</a></span>";
                }
                else{

                    $exp_msg="<span class='pull-left'>".$us->plan_name." <br><small>(expired)</small></span> <span class='pull-left'><a href='/admin/payment-history.html' class='btn btn-sm btn-success'>Upgrade/Renew</a></span>";
                }
            }
        }
    }
     
    return $exp_msg;
}

function fetch_all_return_item()
{   
    $ci = & get_instance();
    $ci->load->model('admin/productdetailsmodel','pm'); 

    $counter=0;
    $html="";
    /* Fetch Newly Joined Customes*/
    $ary_return_order = $ci->pm->getreturnitemorder();
    if(!empty($ary_return_order))
    {
        foreach($ary_return_order as $k=>$v)
        {
            $counter++;
            $html.='<li><a href="'.site_url('admin/order-details').'/'.urlencode(base64_encode($v->id)).'"><i class="fa fa-undo text-aqua"></i>A return request raised '.$v->order_number.'.</a></li>';
        }
    }
    /* Fetch Newly Joined Customes*/

    $ary_result['tot_return']=$counter;
    $ary_result['html_return']=$html;

    return $ary_result;
}

function get_email_template($key)
{
    $ci = & get_instance();
    $ci->load->model('admin/settingsmodel','sm');
    $res = $ci->sm->get_email_template_data($key);
    return $res;
}



function get_payment_mode_data()
{
    $ci = & get_instance();
    $ci->load->model('front/authmodel','am');
    $paymentmodedata = $ci->am->getpaymentmodedata();
    return $paymentmodedata;
}


function fetch_cms_option($alias)
{

    $ci = & get_instance();
    $ci->load->model('admin/settingsmodel','sm');
    $cms_link = $ci->sm->fetch_cms_option($alias);
    return $cms_link;
}

function get_base_currency_setting($type='string')
{
    $base_currency=get_setting_by_name('base_currency');

    $ary_curr_value['INR']=array('string'=>'INR','symbol'=>'<i class="fa fa-inr"></i>');
    $ary_curr_value['USD']=array('string'=>'USD','symbol'=>'<i class="fa fa-usd" aria-hidden="true"></i>');
    $ary_curr_value['EUR']=array('string'=>'EUR','symbol'=>'<i class="fa fa-eur" aria-hidden="true"></i>');
    $ary_curr_value['AUD']=array('string'=>'AUD','symbol'=>'<i class="fa fa-usd" aria-hidden="true"></i>');
    $ary_curr_value['CAD']=array('string'=>'CAD','symbol'=>'<i class="fa fa-usd" aria-hidden="true"></i>');
    $ary_curr_value['CNY']=array('string'=>'CNY','symbol'=>'<i class="fa fa-jpy" aria-hidden="true"></i>');

    if(!empty($ary_curr_value[$base_currency][$type]))
    {
        return $ary_curr_value[$base_currency][$type];
    }
    
    return 'INR';
}

function getmostpopularporduct()
{

    $CI=& get_instance();
    $CI->load->model('admin/pluginmodel');
    $fetch_mpp = array();
   if(check_plugin_exsist('mostpopularproduct'))
       $fetch_mpp = $CI->pluginmodel->fetchmostpopularproduct();

   return $fetch_mpp;
}


function GetPDFHtml($storeOwnerAddress='',$txnid='',$date='',$MAIN_WEBSITE_URL='',$plan_name='',$plan_day='',$plan_amount='',$amc_percent='',$currency_symbol='',$is_amc='',$amc_amt='',$tax_array='',$planBaseAmount='')
{

$pdf_notes = _httpPost(ADMIN_API_URL,array('str_upgrd_renw_invoive_note'=>'1'));
$pdf_notes = json_decode($pdf_notes);
$pdf_notes = $pdf_notes->result;
//echo $storeOwnerAddress.'--'.$txnid.'--'.$date.'--'.$MAIN_WEBSITE_URL.'--'.$plan_name.'--'.$plan_day.'--'.$plan_amount.'--'.$amc_percent.'--'.$currency_symbol.'--'.$is_amc.'--'.$amc_amt.'--'.$tax_array.'--'.$planBaseAmount;exit;
$onlyAmc = 0;
$tax_data = json_decode($tax_array);
$tax_amount = 0;
$b64image = base64_encode(file_get_contents(MAIN_WEBSITE_URL.'/wp-content/themes/cartface-anup/assets/images/logo-blue-orange.png'));

$paidimage = base64_encode(file_get_contents(MAIN_WEBSITE_URL.'/assets-common/stamp-paid.png'));

$currency = 'USD ';
if($currency_symbol == 'fa fa-inr')
{
    $currency = 'INR ';
}

$total_amt = floatval(str_replace(',','',$planBaseAmount));
$amc_html  = '';

if($amc_amt != '' && $amc_amt != 0 && $is_amc != 1)
{
    if($plan_amount == '' || $plan_amount == 0)
    {
        $amc_html = '<tr>
                        <td style="padding:10px; border: 1px solid #ccc">
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc">
                            AMC ('.$amc_percent.'%)
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: center">
                            365  Days
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                           '.number_format($amc_amt,2).'
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            '.number_format($amc_amt,2).'
                        </td>
                    </tr>';

        $total_amt      = $plan_amount + $amc_amt;
        $plan_amount    = number_format($plan_amount,2);
        $total_amt      = number_format($total_amt,2);
    }
    else
    {
        $plan_amount = $total_amt - $amc_amt;
        $amc_html = '<tr>
                        <td style="padding:10px; border: 1px solid #ccc">
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc">
                            AMC ('.$amc_percent.'%)
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: center">
                            365  Days
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                           '.number_format($amc_amt,2).'
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            '.number_format($amc_amt,2).'
                        </td>
                    </tr>';

        $total_amt      = $planBaseAmount + $amc_amt;
        $plan_amount    = number_format($plan_amount,2);
        $total_amt      = number_format($total_amt,2);
    }
}
elseif($amc_amt != '' && $amc_amt != 0 && $is_amc == 1 && $plan_amount!=0)
{
    $plan_amount = $total_amt;
    //$total_amt      = $plan_amount + $amc_amt;
    $plan_amount    = number_format($plan_amount,2);
    $total_amt      = number_format($total_amt,2);
}
else
{
    //$planBaseAmount = str_replace(',','',$plan_amount);
    $onlyAmc = 1;
}
//echo $total_amt.'--'.$planBaseAmount.'--'.$amc_amt;exit;
if($tax_data->tax_type == 'P')
{
    $baseAmount     = $planBaseAmount + $amc_amt;
    $tax_amount     = ($baseAmount *$tax_data->amount)/100;
}
else
{
    $tax_amount = $tax_data->amount;
}

/*if($onlyAmc == 1)
{
    $total_amt = number_format($total_amt + $tax_amount,2);
}*/
//$total_amt      = number_format(str_replace(',','',$total_amt),2);
$subtotal_amt   = number_format($planBaseAmount + $amc_amt,2);
if($tax_data->tax_type == 'P')
{
    $tax_html       =   '<tr>                        
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                           '.$tax_data->tax_name.' ('.$tax_data->amount.'%)
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            '.number_format($tax_amount,2).'
                        </td>
                    </tr>';
}
else
{
    $tax_html       =   '<tr>                        
                    <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                    <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                       '.$tax_data->amount.'
                    </td>
                    <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                        '.number_format($tax_amount,2).'
                    </td>
                </tr>';
}
//echo $tax_amount.'--'.$planBaseAmount.'--'.$subtotal_amt;
$planBaseAmount = number_format($planBaseAmount,2);
$subtotal_amt   = $subtotal_amt;
$total_amt      = number_format($total_amt + $tax_amount,2);
$pdfhtml = <<< HTML

<table style="width: 100%; border-collapse: collapse; font-family: Arial; font-size: 13px; color: #000; line-height: 1.5; background: #fff; border: 1px solid #000">
    <tbody>
        <tr>
            <td>
                <table style="width:100%; border-collapse: collapse; border:0;font-family: Arial; font-size: 13px; color: #000">
                    <tr>
                        <td style="padding: 10px "><img src="data:image/png;base64,$b64image" width="200" />                            
                        </td>
                        <td style="text-align:right; padding: 10px;">
                            <strong>Cartface Pvt. Ltd.</strong><br>
                            50, D.H. Road, Kolkata, India.<br>
                            M: 9830303030
                        </td>
                    </tr>                    
                    <tr> 
                        <td style="width:50%; padding:10px 20px">
                            To,
                            {$storeOwnerAddress}
                        </td>
                        <td style="padding:20px 11px;">
                            <table style="width:100%; border-collapse: collapse; border:0;">
                                <tr>
                                    <td style="padding:10px; border: 1px solid #ccc">
                                        Invoice No. :<br>
                                        <strong>{$txnid}</strong>
                                    </td>
                                    <td style="padding:10px; border: 1px solid #ccc">
                                        Invoice Date:<br>
                                        <strong>{$date}</strong>
                                    </td>
                                </tr>                                
                                <tr style="display:none">
                                    <td colspan="2" style="padding:10px; border: 1px solid #ccc; text-align: center">
                                        <img src="{$MAIN_WEBSITE_URL}/assets-common/barcode.png"/>
                                    </td>                                    
                                </tr>                                
                            </table>
                        </td>
                    </tr>
                </table>
                
            </td>            
        </tr>        
        
        <tr>
            <td style="padding:8px 12px">
                <table style="width:100%; border-collapse: collapse; border:0; text-align: left">
                    <thead>
                    <tr>
                        <th style="padding:10px; border: 1px solid #ccc; width:20px; background: #e5e5e5">
                            #
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5">
                            Plan Name
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5; width:50px; text-align: center">
                            Duration
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5; width:70px;  text-align: right">
                            Price ({$currency})
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5; width:100px; text-align: right">
                            Total Value ({$currency})
                        </th>
                    </tr>
                    </thead>
                    <tr>
                        <td style="padding:10px; border: 1px solid #ccc">
                            1
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc">
                            {$plan_name}
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: center">
                            {$plan_day} Days
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                           {$planBaseAmount}
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            {$planBaseAmount}
                        </td>
                    </tr>
                    {$amc_html}                   
                    <tr>                        
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                           Subtotal
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            {$subtotal_amt}
                        </td>
                    </tr> 
                    {$tax_html}
                    <tr  style="display:none;">                     
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                           Tax
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            0.00
                        </td>
                    </tr>                    
                    <tr>       
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right; font-weight: bold">
                           Total
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right;  font-weight: bold">
                            {$total_amt}
                        </td>
                    </tr>                                                            
                    <tr style="display:none;">                        
                        <td colspan="5" style="padding:10px; border: 1px solid #ccc; font-weight: bold">
                           Rupees forty seven thousand nine hundred eighty seven only.
                        </td>                        
                    </tr>
                    
                </table>
                
            </td>            
        </tr>
        
        <tr>
            <td style="padding:8px 12px">
                <table style="width:100%;border-collapse: collapse; border:0;">
                    <tr> 
                        <td style="padding:10px; font-size: 12px">
                            {$pdf_notes}
                        </td>
                        <td style="padding:10px; text-align: center">  
                            <img src="data:image/png;base64,$paidimage" /><br>                            
                        </td>
                    </tr>                                                    
                </table>
            </td>            
        </tr>        
        <tr>
            <td height="200"></td>
        </tr>
        <tr>
            <td style="padding:12px; border-top: 1px solid #ccc; font-size: 14px; text-align: center;">
                Please contact billing@cartface.com for any query.
            </td>            
        </tr>
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                This is a computer generated Invoice, hence signature is not required.
            </td>            
        </tr>
    </tbody>
</table>


HTML;

    return $pdfhtml;
}


function GetPDFHtmlPaypal($pdfParamArr=array())
{
    $pdf_notes  = _httpPost(ADMIN_API_URL,array('str_upgrd_renw_invoive_note'=>'1'));
    $pdf_notes  = json_decode($pdf_notes);
    $pdf_notes  = $pdf_notes->result;
    $onlyAmc    = 0;
    $tax_data   = json_decode($pdfParamArr['taxJson']);
    $tax_amount = 0;
    $b64image   = base64_encode(file_get_contents(MAIN_WEBSITE_URL.'/wp-content/themes/cartface-anup/assets/images/logo-blue-orange.png'));
    $paidimage  = base64_encode(file_get_contents(MAIN_WEBSITE_URL.'/assets-common/stamp-paid.png'));
    $currency   = 'USD ';
    if($pdfParamArr['symbol'] == 'fa fa-inr')
    {
        $currency = 'INR ';
    }

    $total_amt = $pdfParamArr['plan_details']['plan_amount'];
    $amc_html  = '';
    if($pdfParamArr['plan_details']['amc_amt'] != '' && $pdfParamArr['plan_details']['amc_amt'] != 0)
    {
        $amc_html = '<tr>
                        <td style="padding:10px; border: 1px solid #ccc">
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc">
                            AMC ('.$pdfParamArr['plan_details']['amc_percent'].'%)
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: center">
                            365  Days
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                           '.number_format($pdfParamArr['plan_details']['amc_amt'],2).'
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            '.number_format($pdfParamArr['plan_details']['amc_amt'],2).'
                        </td>
                    </tr>';
    }

    if($tax_data->tax_type == 'P')
    {
        $tax_amount     = (($total_amt+$pdfParamArr['plan_details']['amc_amt']) *$tax_data->amount)/100;
    }
    else
    {
        $tax_amount = $tax_data->amount;
    }

    $subtotal_amt   = number_format($total_amt+$pdfParamArr['plan_details']['amc_amt'],2);
    if($tax_data->tax_type == 'P')
    {
        $tax_html       =   '<tr>                        
                            <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                            <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                               '.$tax_data->tax_name.' ('.$tax_data->amount.'%)
                            </td>
                            <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                                '.number_format($tax_amount,2).'
                            </td>
                        </tr>';
    }
    else
    {
        $tax_html       =   '<tr>                        
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                           '.$tax_data->amount.'
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            '.number_format($tax_amount,2).'
                        </td>
                    </tr>';
    }


    $final_amt = number_format($total_amt + $tax_amount+$pdfParamArr['plan_details']['amc_amt'],2);
    $total_amt = number_format($total_amt,2);

$pdfhtml = <<< HTML

<table style="width: 100%; border-collapse: collapse; font-family: Arial; font-size: 13px; color: #000; line-height: 1.5; background: #fff; border: 1px solid #000">
    <tbody>
        <tr>
            <td>
                <table style="width:100%; border-collapse: collapse; border:0;font-family: Arial; font-size: 13px; color: #000">
                    <tr>
                        <td style="padding: 10px "><img src="data:image/png;base64,$b64image" width="200" />                            
                        </td>
                        <td style="text-align:right; padding: 10px;">
                            <strong>Cartface Pvt. Ltd.</strong><br>
                            50, D.H. Road, Kolkata, India.<br>
                            M: 9830303030
                        </td>
                    </tr>                    
                    <tr> 
                        <td style="width:50%; padding:10px 20px">
                            To,
                            {$pdfParamArr['storeOwnerAddress']}
                        </td>
                        <td style="padding:20px 11px;">
                            <table style="width:100%; border-collapse: collapse; border:0;">
                                <tr>
                                    <td style="padding:10px; border: 1px solid #ccc">
                                        Invoice No. :<br>
                                        <strong>{$pdfParamArr['txnid']}</strong>
                                    </td>
                                    <td style="padding:10px; border: 1px solid #ccc">
                                        Invoice Date:<br>
                                        <strong>{$pdfParamArr['date']}</strong>
                                    </td>
                                </tr>                                
                                <tr style="display:none">
                                    <td colspan="2" style="padding:10px; border: 1px solid #ccc; text-align: center">
                                        <img src="{$pdfParamArr['MAIN_WEBSITE_URL']}/assets-common/barcode.png"/>
                                    </td>                                    
                                </tr>                                
                            </table>
                        </td>
                    </tr>
                </table>
                
            </td>            
        </tr>        
        
        <tr>
            <td style="padding:8px 12px">
                <table style="width:100%; border-collapse: collapse; border:0; text-align: left">
                    <thead>
                    <tr>
                        <th style="padding:10px; border: 1px solid #ccc; width:20px; background: #e5e5e5">
                            #
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5">
                            Plan Name
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5; width:50px; text-align: center">
                            Duration
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5; width:70px;  text-align: right">
                            Price ({$currency})
                        </th>
                        <th style="padding:10px; border: 1px solid #ccc; background: #e5e5e5; width:100px; text-align: right">
                            Total Value ({$currency})
                        </th>
                    </tr>
                    </thead>
                    <tr>
                        <td style="padding:10px; border: 1px solid #ccc">
                            1
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc">
                            {$pdfParamArr['plan_details']['plan_name']}
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: center">
                            {$pdfParamArr['plan_details']['plan_day']} Days
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                           {$total_amt}
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            {$total_amt}
                        </td>
                    </tr>
                    {$amc_html}                   
                    <tr>                        
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                           Subtotal
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            {$subtotal_amt}
                        </td>
                    </tr> 
                    {$tax_html}
                    <tr  style="display:none;">                     
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right">
                           Tax
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right">
                            0.00
                        </td>
                    </tr>                    
                    <tr>       
                        <td colspan="2" style="padding:10px; border-left: 1px solid #ccc"></td>
                        <td colspan="2"  style="padding:10px; border: 1px solid #ccc; text-align: right; font-weight: bold">
                           Total
                        </td>
                        <td style="padding:10px; border: 1px solid #ccc; text-align: right;  font-weight: bold">
                            {$final_amt}
                        </td>
                    </tr>                                                            
                    <tr style="display:none;">                        
                        <td colspan="5" style="padding:10px; border: 1px solid #ccc; font-weight: bold">
                           Rupees forty seven thousand nine hundred eighty seven only.
                        </td>                        
                    </tr>
                    
                </table>
                
            </td>            
        </tr>
        
        <tr>
            <td style="padding:8px 12px">
                <table style="width:100%;border-collapse: collapse; border:0;">
                    <tr> 
                        <td style="padding:10px; font-size: 12px">
                            {$pdf_notes}
                        </td>
                        <td style="padding:10px; text-align: center">  
                            <img src="data:image/png;base64,$paidimage" /><br>                            
                        </td>
                    </tr>                                                    
                </table>
            </td>            
        </tr>        
        <tr>
            <td height="200"></td>
        </tr>
        <tr>
            <td style="padding:12px; border-top: 1px solid #ccc; font-size: 14px; text-align: center;">
                Please contact billing@cartface.com for any query.
            </td>            
        </tr>
        <tr>
            <td style="text-align: center; padding-bottom: 20px;">
                This is a computer generated Invoice, hence signature is not required.
            </td>            
        </tr>
    </tbody>
</table>


HTML;

    return $pdfhtml;
}

function emailBody($body_content,$fullname)
{
        $temp_main_website=MAIN_WEBSITE_URL;
        $temp_support_website_url=SUPPORT_WEBSITE_URL;
        $temp_themes_website_url=THEMES_WEBSITE_URL;
        $temp_blog_website_url=BLOG_WEBSITE_URL;
        $email_template = <<< MAILTEMPLATE
<!DOCTYPE html>
<html>
<head>
    <title>Store creation mail</title>
    <meta charset="windows-1252">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{padding: 0; margin: 0}
    </style>
</head>

<body bgcolor="#e3e7ea">
    
    <div style="display: block; width: 100%;background: #e3e7ea; text-align: center;">
        <div style="padding: 30px;">
            <a href="{$temp_main_website}" target="_blank"><img width="200" border="0" alt="logo" src="{$temp_main_website}/assets-common/logo-cartface.png" class="CToWUd"></a>
        </div>
        <div style="display: block; width: 100%;max-width: 600px; padding: 0; margin: 0 auto; font-family:'Ubuntu',Verdana,Geneva,sans-serif; font-size:14px; background-color:#f5f5f5; color:#333">            
            <div style="display: block; width: 100%; box-sizing:border-box;padding:30px 0; background: url('{$temp_main_website}/assets-common/bg-email2.png') center repeat; color: #fff; font-size: 24px; text-shadow: 0 1px 0 #000">
                Hello {$fullname}
            </div>
            <div style="display: block; width: 100%; box-sizing:border-box;padding:30px 0; line-height: 20px;">
                {$body_content}
            </div>            
            <div style="border-top: 1px solid #ddd; width:100%; height: 1px; display: block; margin: 15px 0"></div>                      
            
            <div style="display: block; width: 100%; box-sizing:border-box;padding-bottom: 10px; line-height: 20px">
                We are here to help you along the way our support line is open <br>24/7 to help you get started chat live with Cartface
            </div>
            <div style="display: block; width: 100%; box-sizing:border-box;padding:0 0 30px 0;">
                <img src="{$temp_main_website}/assets-common/email-chat4.png"><img src="{$temp_main_website}/assets-common/email-chat1.png"><img src="{$temp_main_website}/assets-common/email-chat2.png"><img src="{$temp_main_website}/assets-common/email-chat3.png">
            </div>            
            <div style="display: block; width: 100%; box-sizing:border-box;padding: 20px 0; font-size: 18px; background: #137fed; color: #fff; border: 1px solid #137fed">
                Cartface Mobile
                <p style='font-size: 15px; line-height: 1'>Manage your online store from your mobile device</p>
                <a href="https://play.google.com/store/apps?hl=en" target="_blank"><img src="{$temp_main_website}/assets-common/btn-app.png"></a> 
            </div>
            
            <div style="display: block; width: 100%; box-sizing:border-box;padding: 25px 0; line-height: 20px">
                Looking for a white-glove ecommerce solutions for emerging brands and <br>high-volume business? <a style="color: #137fed" href="{$temp_main_website}/contact-us/">Contact Us</a>
            </div>
            <div style="display: block; width: 100%; font-family:Arial; box-sizing:border-box;padding:10px 0; line-height: 20px; font-family: 'Ubuntu', Verdana; font-size: 14px; color: #ccc; width: 100%; box-sizing:border-box; margin: 0 auto; max-width: 600px; background: #888">
                <a style="color:#ddd; padding:5px 10px 5px 0; text-decoration: none;  outline: 0" href="{$temp_support_website_url}" target="_blank">Support</a> |  <a style="color:#ddd; padding:5px 10px; text-decoration: none; outline: 0" href="{$temp_themes_website_url}" target="_blank">Theme Store</a> |  
                  <a style="color:#ddd; padding:5px 10px; text-decoration: none; outline: 0" href="{$temp_blog_website_url}" target="_blank">Blog</a> 
            </div>           
        </div>
        <div style="display: block; width: 100%; max-width: 600px; padding: 0; margin: 0 auto; font-family:'Ubuntu',Verdana,Geneva,sans-serif; font-size:14px; ">
            <div style="display: block; width: 100%;  font-family:Arial; box-sizing:border-box;padding:30px 0 30px;  font-family: 'Ubuntu', Verdana; font-size: 12px; color: #888;">
                &copy;Catrface | 50, D.H. ROAD, KOLKATA, INDIA.
            </div>
        </div>
    </div>
</body>

</html>
MAILTEMPLATE;

    return $email_template;

}

function pushFiltering($values)
{
    $ci = & get_instance();
    $filtering_values = array();
    $filtering_values = $ci->session->userdata('filtering_values');
    if(is_array($values) && COUNT($values)>0)
    {
        foreach ($values as $key => $value) {
            $filtering_values[$key] = $value;
        }
    }
    //echo "<pre>";print_r($filtering_values);exit;
    $ci->session->set_userdata('filtering_values',$filtering_values);
    return true;
}

function doCeil($number)
{
    return ceil($number);
}

/**
 * [getFunctionList is an internal function always put this at the end of this file
 *  it generate functions lists defined here and these functions
 *  get auto registered in the the twig tempelete engine. 
 * ]
 */
function calculateCart()
{
    $data=array();
    $subtotal=0;
    $ci =& get_instance();    
    $data['grandtotal']=0;
    $data['cart']=$ci->cart->contents();
    $ci->load->model('admin/productdetailsmodel');
    $cart_item_count=0;

    $ary_pro_disc=array();
    if(!empty($data['cart']))
    {
        $totalWeight=0;
        foreach($data['cart'] as $item)
        {
            $cart_item_count++;
            $ary_attributes=$item['options'];
            $attriparam="";

            foreach($ary_attributes as $k=>$v)
            {
                if($attriparam=="")
                {
                    $attriparam= $v;
                }
                else
                {
                    $attriparam= $attriparam."-".$v;
                }
            }
            if($item['name']==$attriparam)
            {
                $attriparam="";
            }

            $applicabledata=$ci->productdetailsmodel->getProductDetails($item['id'],$attriparam);

            if((is_array($applicabledata['all_discount_dtls']) && COUNT($applicabledata['all_discount_dtls'])>0) ||  $applicabledata['special_price'] == 0)
                $original_price=$applicabledata['product_price'];
            else
                $original_price=$applicabledata['special_price'];

            $data['cart'][$item['rowid']]['price']=number_format($original_price,2);
            $line_subtotal=$line_price=$original_price*$data['cart'][$item['rowid']]['qty'];
            $data['cart'][$item['rowid']]['line_price']=number_format($line_price,2);

            $newresval= $ci->productdetailsmodel->getalldiscountforproduct($item['id'],$attriparam);

            foreach($newresval as $k=>$v)
            {
                if($v['discount_type']=="P")
                {
                    $line_discount=($v['discount_amount']/100)*$line_price;
                    $newresval[$k]['display_disc_amnt']=$v['discount_amount']."(%)";

                }
                else
                {
                    $line_discount=$v['discount_amount']*$data['cart'][$item['rowid']]['qty'];
                    $newresval[$k]['display_disc_amnt']=number_format($v['discount_amount'],2);
                }
                $newresval[$k]['line_discount']=number_format($line_discount,2);
                $line_subtotal=$line_subtotal-$line_discount;
            }
            $ary_pro_disc[$item['rowid']]['ary_discount']=$newresval;
            $data['cart'][$item['rowid']]['line_subtotal']=number_format($line_subtotal,2);
            $subtotal=$subtotal+$line_subtotal;
        }
        $data['subtotal']=number_format($subtotal,2);
    }

    return array('no_of_item' => $cart_item_count,'total_amount' => $data['subtotal']);
}

function twiggy_json_decode($encoded_str)
{
    return json_decode($encoded_str);
}

/*function send_sms($senderName,$to,$smsContent)
{
    if(check_plugin_exsist('sendsms'))
    {
        $sms_uname  = get_setting_by_name('sms_uname');
        $sms_pass   = get_setting_by_name('sms_pass');
        $send_date  = date('Y-m-d h:i:s');
        $curl_url   = 'http://sms11.infonetservices.in/new/api/api_http.php?username='.$sms_uname.'&password='.$sms_pass.'&senderid='.$senderName.'&to='.$to.'&text='.urlencode($smsContent).'&route=Informative&type=text&datetime='.$send_date;

        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$curl_url);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        return true;
    }
    else
        return false;
}*/

function send_sms($senderName,$to,$smsContent)
{
    if(check_plugin_exsist('sendsms'))
    {
        $sms_uname  = get_setting_by_name('sms_uname');
        $sms_pass   = get_setting_by_name('sms_pass');
        $send_date  = date('Y-m-d h:i:s');
        
        $url = 'http://sms11.infonetservices.in/new/api/api_http.php';

        $postData='username='.$sms_uname.'&password='.$sms_pass.'&senderid='.$senderName.'&to='.$to.'&text='.urlencode($smsContent).'&route=Informative&type=text&datetime='.$send_date;

        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false); 

        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output=curl_exec($ch);
        curl_close($ch);
        //return $output;
        return true;
    }
    else
        return false;
}

function getsocialdetails() 
{
    if(check_plugin_exsist('sociallogin'))
    {
        $ci = & get_instance();
        $sc_sql = "select * from ".tablename('social_settings')." where status = '1' and is_paid ='0'";
        $sc_query = $ci->db->query($sc_sql);
        $sc_row = $sc_query->result();
        
        if(!empty($sc_row))
        {
            return $sc_row;
            
        }
        else
        {
            return '';
        }
    }
}

function get_cms_cotent($slug,$length)
{
    $CI = & get_instance();
    $CI->load->model('cms/admin/cmsmodel');

    $cms_cotent=$CI->cmsmodel->get_cms_cotent($slug);
    if(!empty($cms_cotent))
    {
        return substr(html_entity_decode($cms_cotent->content),0,$length);
    }
    else
    {
        return "";

    }
}

function chat_code()
{
    if(check_plugin_exsist('livechat'))
    {
        $ci = & get_instance();
        $sql = "select value from ".tablename('settings')." where name = 'chat_code'";
        $sc_query = $ci->db->query($sql);
        $sc_row = $sc_query->row();
        if($sc_row)
        {
            return $sc_row;
        }
        else
        {
            return '';
        }
    }
}

function getLatestPost($no_of_post) 
{
    if(check_plugin_exsist('latestpost'))
    {
        if($no_of_post == 0 || $no_of_post == '')
            $no_of_post = 10;

        $ci         = & get_instance();
        $sc_sql     = "select * from pb_blog_post where archive_status = '0' AND status = '1' ORDER BY created_date LIMIT ".$no_of_post;
        $sc_query   = $ci->db->query($sc_sql);
        $sc_row     = $sc_query->result();
        
        if(!empty($sc_row))
            return $sc_row;        
        else
            return '';
    }
    else
        return '';
}

function number_format_twiggy($val)
{
    echo number_format($val,2);
}

function addslash_twiggy($val)
{
    echo addslashes($val);
}

function showhidedefaultreport()
{

    $ci = & get_instance();
    $ci->load->model('admin/settingsmodel','sm');;

    if(check_plugin_exsist('report'))
    {
        $update['value'] = 'N';
    }
    else
    {
        $update['value'] = 'Y';
    }
    $where['name'] = 'show_old_version_report_menu';
    $update['modified_date'] = date('Y-m-d H:i:s');
    $ci->sm->updateSetting($update,$where);
}

function sendAbandonMail()
{
    $ci = & get_instance();
    $sql = "SELECT PAL.id,PO.abondon_cart_no,PO.payment_method,PU.fname,PU.lname,PU.email
    FROM ".tablename('abondon_list')." AS PAL
    INNER JOIN ".tablename('orders')." AS PO ON PO.id = PAL.order_id
    INNER JOIN ".tablename('users')."  AS PU ON PU.id = PO.users_id
    WHERE mail_send = '0'";
    $sc_query   = $ci->db->query($sql);
    $sc_row     = $sc_query->result_array();
    //echo "<pre>";print_r($sc_row);exit;
    if(is_array($sc_row) && COUNT($sc_row)>0)
    {
        foreach ($sc_row as $value)
        {
            $template_data  = get_email_template('abandon_order');
            $email_from     = $template_data->email_from;
            $email_subject  = $template_data->email_subject;
            $email_body     = $template_data->content;
            $email_status   = $template_data->status;
            $srch_string    = array("[var.client_full_name]","[var.order_number]");
            $replace_string = array($value['fname'].' '.$value['lname'],$value['abondon_cart_no']);
            $email_body     = str_replace($srch_string, $replace_string, $email_body);
            $useremail      = $value['email'];           
            if(trim($useremail)!="" && $email_status==1)
            {
                $sendOrderEmail=sendCartFaceEmail($useremail,$email_from,$email_subject,$email_body);
                $abandonData      =   array(
                                    'mail_send'          => '1',
                                    'mail_send_date'     => date('Y-m-d H:i:s')
                                );

                $ci->db->where(array('id'=>$value['id']));
                $ci->db->update(tablename('abondon_list'),$abandonData);
            }
        }
    }
    //return "success";
    //echo "<pre>";print_r($sc_row);exit;
}

function getPluginListWithLoader()
{
    $ci         = & get_instance();
    $sql        = "SELECT alias FROM pb_modules WHERE is_loader = '1'";
    $sc_query   = $ci->db->query($sql);
    $sc_row     = $sc_query->result_array();
    return $sc_row;
}

function get_emailmarketing_replace_ary($email)
{
    
    $emailmarketing_ary=array();

    $emailmarketing_srch = array("[var.Unsubscribe]");
    $emailmarketing_replace   = array('<a href="'.base_url('emailmarketing/admin/unsubscriber')."/".base64_encode($email).'">Click Here</a>');

    $emailmarketing_ary['srch']=$emailmarketing_srch;
    $emailmarketing_ary['replace']=$emailmarketing_replace;

    return $emailmarketing_ary;

}

function convert_number_to_words($number) 
{

    $hyphen      = '';
    $conjunction = ' ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' and ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty ',
        30                  => 'thirty ',
        40                  => 'fourty ',
        50                  => 'fifty ',
        60                  => 'sixty ',
        70                  => 'seventy ',
        80                  => 'eighty ',
        90                  => 'ninety ',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        /*$words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }*/
        if($fraction=="09")
        {
            $string .= "nine";
        }
        elseif($fraction=="08")
        {
            $string .= "eight";
        }
        elseif($fraction=="07")
        {
            $string .= "seven";
        }
        elseif($fraction=="06")
        {
            $string .= "six";
        }
        elseif($fraction=="05")
        {
            $string .= "five";
        }
        elseif($fraction=="04")
        {
            $string .= "four";
        }
        elseif($fraction=="03")
        {
            $string .= "three";
        }
        elseif($fraction=="02")
        {
            $string .= "two";
        }
        elseif($fraction=="01")
        {
            $string .= "one";
        }
        elseif($fraction=="00")
        {
            $string .= "zero zero";
        }
        else
        {
            $string .= convert_number_to_words($fraction);
        }
        
        //$string .= implode(' ', $words);
    }

    return $string;
}

function getDealProduct()
{
    $final_array = array();
    if(check_plugin_exsist("deals"))
    {
        $ci         = & get_instance();
        $ci->load->model('admin/productdetailsmodel');    
        $sql        = "SELECT PD.*
        FROM pb_discount AS PD
        WHERE PD.is_active = 'Y' AND PD.archive_status = '0' AND PD.is_dealtheday = 'yes' AND CURDATE() BETWEEN PD.from_date AND PD.to_date";
        $sc_query   = $ci->db->query($sql);
        $sc_row     = $sc_query->result_array();
        if(is_array($sc_row) && COUNT($sc_row)>0)
        {        
            foreach ($sc_row as $k=>$value)
            {
                $sql        = "SELECT * FROM pb_discount_dtls AS PDD
                INNER JOIN pb_products AS PP ON PP.id = PDD.pid AND PP.archive_status = '0' AND PP.status = '1'
                WHERE PDD.discount_id = ".$value['id'];
                $query      = $ci->db->query($sql);
                $result     = $query->result_array();
                if(is_array($result) && COUNT($result)>0)
                {
                    $newresult = array();
                    foreach ($result as $v)
                    {
                        $productArr = array();
                        $ary_product_details=$ci->productdetailsmodel->getProductDetails($v['pid'],$v['attr_combination']);
                        $sql        = "SELECT pic_name FROM pb_product_attriblute_image WHERE product_id = ".$v['pid']." AND stock_id = ".$v['stock_id']." AND default_status='1'";
                        $img_query   = $ci->db->query($sql);
                        $img_row     = $img_query->result_array();

                        if(!isset($img_row[0]['pic_name']) || $img_row[0]['pic_name'] =='' )
                        {
                            $sql        = "SELECT name as pic_name FROM pb_product_images WHERE pid = ".$v['pid']." AND default_status = '1' AND status='1'";
                            $img_query   = $ci->db->query($sql);
                            $img_row     = $img_query->result_array();
                        }
                        $productArr['img_name']             = $img_row[0]['pic_name'];                   
                        if(is_array($ary_product_details['all_discount_dtls']) && COUNT($ary_product_details['all_discount_dtls'])>0)
                        {
                            $total_discount_amt = 0;
                            foreach ($ary_product_details['all_discount_dtls'] as $discount)
                            {
                                if($discount['discount_type'] == 'P')
                                {
                                    $total_discount_amt = $total_discount_amt + (($ary_product_details['product_price']*$discount['discount_amount'])/100);
                                }
                                else
                                {
                                    $total_discount_amt = $total_discount_amt + $discount['discount_amount'];
                                }
                            }
                            $productArr['dicount_per']   = number_format(($total_discount_amt/$ary_product_details['product_price'])*100,1);
                            $productArr['price']         = $ary_product_details['product_price'];
                            $productArr['final_price']   = $ary_product_details['product_price'] - $total_discount_amt;
                        }
                        if($v['attr_combination'] != $ary_product_details['name'])
                        {
                            $productArr['name']             = $ary_product_details['name'].' '.$v['attr_combination'];
                        }
                        else
                        {
                            $productArr['name']             = $ary_product_details['name'];
                        }
                        $productArr['slug']             = $ary_product_details['slug'];
                        $newresult[]                    = $productArr;
                    }                
                }    
                $final_array[$k]['product_list'] = $newresult;
                $final_array[$k]['discount_name']= $value['discount_name'];
                $final_array[$k]['deals_display_type'] = $value['deals_display_type'];        
            }       
        }
    }    
    return $final_array;
}
function getLowStockList($productlist=array())
{
    $ci =& get_instance();
    $ci->load->model('inventory/admin/inventorymodel');
    $return_arr  = array();
    foreach ($productlist as $v)
    {
        $prodstocklist  = $ci->inventorymodel->getProductStockById($v['id']);
        $stocks         = array();
        foreach ($prodstocklist as $list)
        {
            $stockout       = $ci->inventorymodel->showoutstock($list['pid'], $list['stockdet']);
            $stockbalance   = (($list['quantity_beforestock']+$list['quantity']) - $stockout);
            if($stockbalance<0)
            {
                $stockbalance=0;
            }
            if($list['lowstock_value'] == null || $list['lowstock_value'] == 0)
            {
                $list['lowstock_value'] = get_setting_by_name('global_lowstock_value');
            }

            if($list['lowstock_value'] >= $stockbalance)
            {
                if($list['stockdet'] == $list['productname'] || $list['stockdet'] == '')
                {
                    $return_arr[] = array('product_name'=>$list['productname'],'balance'=>$stockbalance);
                }
                else
                {
                    $return_arr[] = array('product_name'=>$list['productname'].'-'.$list['stockdet'],'balance'=>$stockbalance);
                }
            }
        }
    }
    return $return_arr;
}

function getQtnLimit($productID='',$stockID='')
{
    $CI = & get_instance();
    $db =& DB();

    if($productID == '')
    {
        $sqlstock   = "select purchase_limit from ".tablename('product_stock')." where id= ".$stockID;
    }
    else
    {
        $sqlstock   = "select purchase_limit from ".tablename('product_stock')." where pid= ".$productID." AND stockdet = '' AND is_deleted = '0'";
    }
    $querystock = $db->query($sqlstock);
    $rowstock   = $querystock->result();    
    if($rowstock[0]->purchase_limit == 0)
    {
        $rowstock[0]->purchase_limit = get_setting_by_name('global_qty_limit');
    }
    return $rowstock[0]->purchase_limit;
}

function sendInStockNotification()
{
    $ci         = & get_instance();
    $ci->load->model('inventory/admin/inventorymodel');

    $sql        = "SELECT PP.name,PP.slug,PNS.id,PNS.email,PNS.stock_id,PPS.stockdet
    FROM ".tablename('notify_instock')." AS PNS 
    INNER JOIN ".tablename('product_stock')." AS PPS ON PPS.id = PNS.stock_id
    INNER JOIN ".tablename('products')." AS PP ON PP.id = PPS.pid
    WHERE is_email_send = '0'";

    $sc_query   = $ci->db->query($sql);
    $sc_row     = $sc_query->result_array();
    
    if(is_array($sc_row) && COUNT($sc_row)>0)
    {
        foreach ($sc_row as $value)
        {
            $stockattriburedata = $ci->inventorymodel->getstockattriburedata($value['stock_id']);
            $stockout           = $ci->inventorymodel->showproductoutstock($value['stock_id']);

            $showoutstock = 0;
            if (!empty($stockout))
            {
               $showoutstock = $stockout;
            }
            $stockbalance       = ($stockattriburedata[0]->quantity+$stockattriburedata[0]->quantity_beforestock) - $showoutstock;

            if($stockbalance > 0)
            {
                $useremail      = $value['email'];
                $template_data  = get_email_template('back_in_stock');
                $email_from     = $template_data->email_from;
                $email_subject  = str_replace('[var.product_name]',$value['name'].' '.$value['stockdet'],$template_data->email_subject);
                $email_body     = $template_data->content;
                $email_status   = $template_data->status;

                $srch_string    = array('[var.product_name]','[var.product_link]','[var.store_name]');
                $replace_string = array($value['name'].' '.$value['stockdet'],base_url().'products/'.$value['slug'],get_setting_by_name("sitename"));

                $email_body     = str_replace($srch_string, $replace_string, $email_body);
                
                if(trim($useremail)!="" && $email_status==1)
                {
                    $sendOrderEmail=sendCartFaceEmail($useremail,$email_from,$email_subject,$email_body);
                    $updateData     =   array(
                                        'is_email_send'          => '1'
                                    );
                    $ci->db->where(array('id'=>$value['id']));
                    $ci->db->update(tablename('notify_instock'),$updateData);
                }
            }
        }
    }
    return 1;
}

function getInvoiceFileName()
{
    $CI = & get_instance();
    $db =& DB();
    $sqlstock   = "SELECT template_file_name FROM ".tablename('invoice_sample')." WHERE status = '1'";
    $querystock = $db->query($sqlstock);
    $rowstock   = $querystock->row();
    return $rowstock->template_file_name;
}

/* End of file twiggy_helper.php */
