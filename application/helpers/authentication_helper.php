<?php
/* * **********************************************
 * This helper is used for Login Authentication *
 * ********************************************** */

function admin_authenticate() {
    $CI = & get_instance();
    $admin_uid = $CI->session->userdata('admin_uid');
    if (empty($admin_uid)) {
        $CI->session->set_flashdata('errormessage', 'Invalid Access in Admin Panel');

        $redirect = base_url('admin');
        redirect($redirect);
    }
}

function front_authenticate() {
    $CI = & get_instance();
    $front_uid = $CI->session->userdata('front_uid');
    if (empty($front_uid)) {
        $redirect = base_url();
        redirect($redirect);
    }
}

function admin_login($email, $password) {
    $CI = & get_instance();
    $CI->load->model('admin/authmodel');
    $fl = $CI->authmodel->checklogin($email, $password);
    if (!empty($fl)) {
        $CI->session->set_flashdata('successmessage', 'You have logged in successfully');
        $redirect = site_url('admin/branch');
        redirect($redirect);
    } else {
        $CI->session->set_flashdata('errormessage', 'Invalid Credentials');
        $redirect = base_url('admin');
        redirect($redirect);
    }
}

function admin_logout() {
    $CI = & get_instance();
    $CI->load->model('admin/authmodel');
    $admin_uid = $CI->session->userdata['admin_uid'];
    $fl = $CI->authmodel->checklogout($admin_uid);
    if (!empty($fl)) {
        $CI->session->set_flashdata('successmessage', 'You have logged out successfully');
        $redirect = base_url('admin');
        redirect($redirect);
    }
}

function front_logout() {
    $CI = & get_instance();
    $CI->load->model('front/authmodel');
    $front_uid = $CI->session->userdata['front_uid'];
    $fl = $CI->authmodel->checklogout($front_uid);
    if (!empty($fl)) {
        $CI->session->set_flashdata('successmessage', 'You have logged out successfully');
        $redirect = base_url();
        redirect($redirect);
    }
}

function cookie_authenticate() {
    $CI = & get_instance();
    $admin_uid = get_cookie('admin_uid');
    $admin_detail = get_cookie('admin_detail');
    if (!empty($admin_uid)) {
        $CI->session->set_userdata('admin_uid', $admin_uid);
        $CI->session->set_userdata('admin_detail', $admin_detail);
        $redirect = site_url('admin/dashboard');
        redirect($redirect);
    }
}

function get_from_session($key) {
    $CI = & get_instance();
    $data = $CI->session->userdata('admin_detail');
    $data = json_decode($data);
    if (!empty($data)) {
        if (!empty($data->$key)) {
            return $data->$key;
        }
    }

    return "";
}

function get_admin_email() {
    $CI = & get_instance();
    $CI->load->model('admin/authmodel');
    $details = $CI->authmodel->checkAdmin(1);
    if (!empty($details)) {
        $email = $details[0]->email;
        return $email;
    } else {
        return "not valid";
    }
}

function smartdate($timestamp) {
    $timestamp = strtotime("$timestamp");
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return grammar_date(floor($diff), ' second(s) ago');
    } else if ($diff < 60 * 60) {
        return grammar_date(floor($diff / 60), ' minute(s) ago');
    } else if ($diff < 60 * 60 * 24) {
        return grammar_date(floor($diff / (60 * 60)), ' hour(s) ago');
    } else if ($diff < 60 * 60 * 24 * 30) {
        return grammar_date(floor($diff / (60 * 60 * 24)), ' day(s) ago');
    } else if ($diff < 60 * 60 * 24 * 30 * 12) {
        return grammar_date(floor($diff / (60 * 60 * 24 * 30)), ' month(s) ago');
    } else {
        return grammar_date(floor($diff / (60 * 60 * 24 * 30 * 12)), ' year(s) ago');
    }
}

function grammar_date($val, $sentence) {
    if ($val > 1) {
        return $val . str_replace('(s)', 's', $sentence);
    } else {
        return $val . str_replace('(s)', '', $sentence);
    }
}

function front_auth($redirect = false) {
    $CI = & get_instance();
    $user = $CI->session->userdata('user_logged_in');
    if (!empty($user)) {
        return true;
    }
    if ($redirect) {
        redirect(base_url());
    }
    return false;
}

function front_auth_pages() {

    $CI = & get_instance();
    $CI->load->model('front/cms', 'cms');

    $curent = $CI->uri->segment(1);


    foreach ($CI->router->routes as $key => $value) {

        if ($key == $curent) {

            if (in_array($key, $CI->config->item('auth_pages'))) {
                if (!front_auth()) {
                    redirect(base_url());
                }
            }
        }
    }
}

function get_from_session_front($key) {
    $CI = & get_instance();
    $data = $CI->session->userdata('user_logged_in');
    //$data = json_decode($data);
    if (!empty($data)) {
        if (!empty($data[$key])) {
            return $data[$key];
        }
    }

    return "not valid";
}

function getHomeRss() {
    $path = "http://www.cnet.com/rss/news/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    curl_close($ch);
    $xml = simplexml_load_string($contents);
    $newsitem = $xml->channel->item;
    $item = array();
    $local = array();
    if (!empty($newsitem)) {
        $i = 1;
        foreach ($newsitem as $newsitemlist) {
            if ($i <= 5) {
                $thumbnail = (array) $newsitemlist->children('http://search.yahoo.com/mrss/')->thumbnail->attributes()->url;
                $thumbnail = $thumbnail[0];
                $title = (array) $newsitemlist->title;
                $link = (array) $newsitemlist->link;
                $description = (array) $newsitemlist->description;
                $pubDate = (array) $newsitemlist->pubDate;
                $local['title'] = $title[0];
                $local['link'] = $link[0];
                $local['description'] = strip_tags($description[0]);
                $local['pubDate'] = date('D M j', strtotime($pubDate[0]));
                $local['thumbnail'] = $thumbnail;

                $item[] = (object) $local;
            }
            $i++;
        }
    }
    return $item;
}

function getautoheight($px = NULL) {
    $CI = & get_instance();
    $CI->load->library('user_agent', 'agent');
    if ($CI->agent->is_mobile()) {
        $height = "auto";
    } else {
        if (!empty($px))
            $height = $px . "px";
        else
            $height = "70px";
    }
    return $height;
}

function pr($par) {
    echo "<pre>";
    print_r($par);
    echo "</pre>";
}

if (!function_exists('project_mail')) {

    function project_mail($data = array()) {
        $ci = & get_instance();

        $ci->load->library('parser');
        if (count($data) > 0) {


            $tdata['date'] = date('l F d, Y');
            $tdata['year'] = date("Y");
            $tdata['siteurl'] = $ci->config->item('base_url');
            $tdata['logo'] = $ci->config->item('base_url') . "assets/images/logo.jpg";
            $tdata['heading'] = $data['subject'];
            $tdata['message'] = $data['message'];
            $msg = $ci->parser->parse('mail/mail-template', $tdata, TRUE);


            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: <' . get_admin_email() . '>' . "\r\n";
            mail($data['email'], $data['subject'], $msg, $headers);
        }
    }

}

function sociallinks($mediatype) {
    $CI = & get_instance();
    $CI->load->model('admin/sitesettingsmodel', 'ss');
    $socialdet = $CI->ss->getsociallinks();
    if ($mediatype == "facebook") {
        return $socialdet->fb_link;
    }
    if ($mediatype == "twitter") {
        return $socialdet->tw_link;
    }
    if ($mediatype == "googleplus") {
        return $socialdet->gp_link;
    }
    if ($mediatype == "linkedin") {
        return $socialdet->li_link;
    }
    if ($mediatype == "instagram") {
        return $socialdet->ins_link;
    }
    if ($mediatype == "pininterest") {
        return $socialdet->pin_link;
    }
}

function get_menu_from_setting($name) {
    $CI = & get_instance();
    $CI->load->model('admin/sitesettingsmodel', 'ss');
    $a = $socialdet = $CI->ss->getmenu($name);
    return json_decode($a[0]->menu);
}

function get_sitebar() {
    $CI = & get_instance();
    $CI->load->model('admin/menumodel', 'menus');
    $groups = $CI->menus->getAllGroup();
    $current_uri = $CI->uri->segment(2);

    $menu = [];
    if (!empty($groups)) {
        $menu[] = '<ul class="sidebar-menu">';
        foreach ($groups as $row) {
            $rootmenu = $CI->menus->loadAllMenuGroup(0, $row->id);
            if (!empty($rootmenu)) {

                if (count($rootmenu) > 1) {
                    $menu[] = '<li class="treeview">
                      <a href="javascript:void(0);"> <i class="fa ' . $row->icon . '"></i><span>' . $row->name . '
                      </span><i class="fa fa-angle-left pull-right"></i>
                      </a> 
                      <ul class="treeview-menu">';
                }

                foreach ($rootmenu as $item) {

                    $childmenu = $CI->menus->loadAllMenu($item->id);

                    $maintain_inventory = get_setting_by_name('maintain_inventory');


                    if (!empty($childmenu)) {

                        $menu[] = '<li class="treeview">
                                <a href="' . site_url($item->url) . '">
                                  <i class="fa ' . $item->icon . '"></i>
                                  <span>' . $item->label . '</span>
                                  <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">';
                        //$menu[]='<li><a href="'.site_url($item->url).'"><i class="fa '.$item->icon.'"></i> '.$item->label.'</a></li>';

                        foreach ($childmenu as $childitem) {
                            if ($childitem->url == 'admin/product-inventory-list' && $maintain_inventory == 'N') {
                                
                            } else {
                                $menu[] = '<li><a href="' . site_url($childitem->url) . '"><i class="fa ' . $childitem->icon . '"></i> ' . $childitem->label . '</a></li>';
                            }
                        }

                        $menu[] = '</ul></li>';
                    } else {
                        /* if($item->url=='admin/product-inventory-list' && $maintain_inventory=='N')
                          {
                          continue;
                          } */

                        $menu[] = '<li class="">
                     <a href="' . site_url($item->url) . '">
                       <i class="fa ' . $item->icon . '"></i> <span>' . $item->label . '</span>
                     </a>
                     </li>';
                    }
                }

                if (count($rootmenu) > 1) {
                    $menu[] = '</ul></li>';
                }
            }
        }

        $menu[] = '</ul>';
    }
    if (!empty($menu)) {

        echo implode("", $menu);
    }
}

/** Sagnik - Getting Sidebar data  */
function getSidebarData() {

    $CI = & get_instance();
    $CI->load->model('admin/menumodel', 'menus');
    $admin_uid = $CI->session->userdata('admin_uid');
    $branch_id = $CI->session->userdata('branch_id');
    $company_id = $CI->session->userdata('company_id');
    $CI->load->model('admin/authmodel');
    $user_branch_access = $CI->authmodel->userBranchAccess($admin_uid, $branch_id, $company_id);
    $all_module = unserialize($user_branch_access->module);
    $mainMenu = $CI->menus->getMainMenu();
    $mainMenuListArray = array();
    foreach ($mainMenu as $rows) {
        $subMenuExist = $CI->menus->checkSubMenuExist($rows);
        
        if ($subMenuExist) {
            $downArrow = '<i class="fa fa-chevron-right chevron-selector" aria-hidden="true"></i>';
            $anchorTag = '<a href="javascript:;" class="menu-btn main-menu-link">';
        }
        
        if ($rows->id == 1) {
            $downArrow = '';
            $anchorTag = '<a href="' . base_url() . 'admin/dashboard" class="menu-btn-dont main-menu-link">';
        } 
        
        if ($rows->id == 2 && isset($all_module[2]->list) && $all_module[2]->list==1) {
            $downArrow = '';
            $anchorTag = '<a href="' . base_url() . 'admin/customer-details" class="menu-btn-dont main-menu-link">';
        }
        
        if ($rows->id == 2 && isset($all_module[2]->list) && $all_module[2]->list==0) {
            continue;
        }
        
        if($rows->id == 3 && isset($all_module[4]->list) && $all_module[4]->list==1){
            $downArrow = '';
            $anchorTag = '<a href="' . base_url() . 'admin/brs" class="menu-btn-dont main-menu-link">';
        }
        
        if($rows->id == 3 && ((isset($all_module[4]->list) && $all_module[4]->list==0) || !isset($all_module[4]->list)) ){
             continue;
        }



        /* Get submenu and children  */
        $menuListArray = array();
        $subMenus = $CI->menus->getSubMenu($rows->id);
        foreach ($subMenus as $subMenu) {
            $subMenuChildren = $CI->menus->getSubMenuChildren($subMenu->id);
            if ((isset($all_module[$subMenu->id]->list) && $all_module[$subMenu->id]->list == 1) || count($subMenuChildren) > 0):
                if (empty($subMenuChildren)) {
                    $menuListArray[] = '<li><a href="' . base_url() . $subMenu->url . '" data-target="sideMenuChild' . $subMenu->id . '"> ' . $subMenu->label . '</a></li>';
                } else {
                    $subMenuChildrenList = array();
                    foreach ($subMenuChildren as $subMenuChild) {
                        if ((isset($all_module[$subMenuChild->id]->list) && $all_module[$subMenuChild->id]->list == 1)):
                            $subMenuChildrenList[] = '<li><a href="' . base_url() . $subMenuChild->url . '" data-url="' . base_url() . $subMenuChild->url . '" class="call_ajax" data-child="' . $subMenuChild->id . '">' . $subMenuChild->label . '</a></li>';
                        endif;
                    }

                    $menuListArray[] = '<li>
                        <a href="#" class="sideMenuBtn" data-target="sideMenuChild' . $subMenu->id . '"> ' . $subMenu->label . '&nbsp;&nbsp;<i class="fa fa-chevron-right chevron-selector-sideMenuChild" aria-hidden="true"></i></a>
                                <ul class="sidemenu-second-level" id="sideMenuChild' . $subMenu->id . '">
                                   ' . implode("", $subMenuChildrenList) . '
                                   
                                </ul>

                             </li>';
                }
            endif;
        }



        $mainMenuListArray[] = '<li class="' . $rows->id . '">
                ' . $anchorTag . '                    
                <i data-feather="'.$rows->icon. '"></i>
                <span class="menu-text" data-type="' . $rows->id . '"> ' . $rows->name . '</span> 
                ' . $downArrow . '</a>
              </li> 
              <div class="side-menu-child closed" data-type="' . $rows->id . '" id="sidemenu-first-level" style="display:none;"> 
                <header class="text-left">
                        <h5><span>' . $rows->name . '</span></h5>
                </header>
                <ul class="sidebar-nav">' . implode("", $menuListArray) . '</ul>
              </div> ';      
    }
    echo implode("", $mainMenuListArray);
    ?>

    <script>
        var baseUrl = "<?php echo base_url(); ?>";
        $('.sideMenuBtn').click(function(){
            var target = $(this).attr('data-target');
            if ($(this).find('.chevron-selector-sideMenuChild').hasClass('fa-chevron-right')) {
                $(this).find('.chevron-selector-sideMenuChild').removeClass('fa-chevron-right').addClass('fa-chevron-down');
                $('.sidemenu-second-level[id=' + target + ']').slideDown('fast');
            } else if ($(this).find('.chevron-selector-sideMenuChild').hasClass('fa-chevron-down')) {
                $(this).find('.chevron-selector-sideMenuChild').removeClass('fa-chevron-down').addClass('fa-chevron-right');
                $('.sidemenu-second-level[id=' + target + ']').slideUp('fast');
            }
        });


        /* Ajax load views */

        // $('.call_ajax').click(function(){
        //     var dataUrl = $(this).attr('data-url');
        //     // console.log("URL " + dataUrl);
        //     // var routes = dataUrl.substring(dataUrl.indexOf('admin'));
        //     // console.log("routes " + routes);
        //     // console.log(baseUrl + routes);

        //     console.log(dataUrl + '/page_load');
        //     $.ajax({
        //         type: 'GET',
        //         url: dataUrl + '/page_load',
        //         cache: true,
        //         success: function(result){

        //                 // console.log(result);

        //             $('section.content').html("");
        //             $('section.content').html(result);
        //             $('section.content .wrapper2').show();


        //         }
        //     })


        // })

    </script>

    <?php
}

function getTransactionMenu() {

    $CI = & get_instance();
    $CI->load->model('admin/menumodel', 'menus');
    $all_tran_menu = $CI->menus->getTranMenu();
     $admin_uid = $CI->session->userdata('admin_uid');
    $branch_id = $CI->session->userdata('branch_id');
    $company_id = $CI->session->userdata('company_id');
    $CI->load->model('admin/authmodel');
    $user_branch_access = $CI->authmodel->userBranchAccess($admin_uid, $branch_id, $company_id);
    $all_module = unserialize($user_branch_access->module);
    foreach ($all_tran_menu as $row) {
        if(isset($all_module[$row->id]->add) && $all_module[$row->id]->add){
        echo '<li><a href="' . site_url($row->new_entry_url) . '?breadcrumbs=false">' . $row->label . '</a></li>';
        }  
        }
}

function getTransactionListMenu() {
    $CI = & get_instance();
    $CI->load->model('admin/menumodel', 'menus');
    $all_tran_menu = $CI->menus->getTranMenu();
    foreach ($all_tran_menu as $row) {
        echo '<li><a href="' . site_url($row->url) . '">' . $row->label . '</a></li>';
    }
}

/** /Sagnik - Getting Sidebar data */

/** Date Format * */
function get_date_format($date) {
    $CI = & get_instance();
    $CI->load->model('accounts_configuration/admin/accountconfigurationmodel');
    $format = $CI->accountconfigurationmodel->selected_date_format();
    $formatted_date = date($format->date_format_sign, strtotime($date));
    return $formatted_date;
}

function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}

function user_permission($module, $action) {
   
    $CI = & get_instance();
    $admin_uid = $CI->session->userdata('admin_uid');
    $branch_id = $CI->session->userdata('branch_id');
    $company_id = $CI->session->userdata('company_id');
    $CI->load->model('admin/authmodel');
    $user_branch_access = $CI->authmodel->userBranchAccess($admin_uid, $branch_id, $company_id);
    $all_module = unserialize($user_branch_access->module);
   
    if (isset($all_module[$module]->$action) && $all_module[$module]->$action) {
        return TRUE;
    } else {
        $CI->session->set_flashdata('errormessage', 'Invalid Access for this Module.');
        $redirect = base_url('admin/error');
        redirect($redirect);
    }
}

function ua($module, $action) {
    $CI = & get_instance();
    $admin_uid = $CI->session->userdata('admin_uid');
    $branch_id = $CI->session->userdata('branch_id');
    $company_id = $CI->session->userdata('company_id');
    $CI->load->model('admin/authmodel');
    $user_branch_access = $CI->authmodel->userBranchAccess($admin_uid, $branch_id, $company_id);
    $all_module = unserialize($user_branch_access->module);
    if (isset($all_module[$module]->$action) && $all_module[$module]->$action) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/** Date Format **/