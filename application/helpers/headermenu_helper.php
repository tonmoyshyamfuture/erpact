<?php

function create_front_header() {
    error_reporting(0);
    $ci = & get_instance();
    $ci->db->order_by("hierarchy", "ASC");
    $res = $ci->db->get(tablename("headermenu"))->result();
    $html_ = "";
    foreach ($res as $val) {
        $url = "";
        $submenu = $val->submenu_json_string;



        if ((int) $val->cms_page_id != -1) {
            $url = site_url(get_url_from_cms_page_id($val->cms_page_id));
        } else {
            $url = site_url($val->url);
        }

        /*
          Checking whether submenus available or not
         */
        if (!empty($submenu)) {
            $submenu = json_decode($submenu);
            $html_ .= get_submenus($submenu, $val->menu_name, $val->url);
        } else if ($submenu == "") {
            $html_ .= '<li><a href="' . $url . '">' . $val->menu_name . '</a></li>';
        }
    }

    return $html_;
}

function get_submenus($arr, $menu_name, $main_menu_url = null) {
    $htm_ = '<li class="dropdown">';
    $htm_.= '<a href="' . $main_menu_url . '" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $menu_name . '<span class="caret"></span></a>';
    $htm_.= '<ul class="dropdown-menu">';
    foreach ($arr as $res_val) {

        $submenu_url = get_url_from_cms_page_id($res_val->cms_page_id);
        if (empty(str_replace(" ", "", $submenu_url))) {
            $submenu_url = site_url($res_val->url);
        }
        $htm_.= '<li><a href="' . $submenu_url . '">' . $res_val->submenu_name . '</a></li>';
    }
    $htm_ .= '</ul></li>';

    return $htm_;
}

function get_url_from_cms_page_id($cms_page_id) {
    $ci = & get_instance();
    return $ci->db->get_where(tablename("cms"), array("id" => $cms_page_id))->row()->alias;
}

function getBaseCurrency()
{
    $ci = & get_instance('currency');
    $ci->db->select();
    $ci->db->from('currency as c');
    $ci->db->join('account_configuration as p', 'c.id = p.selected_currency', 'left');
    $base_currency = $ci->db->get()->row();
    return $base_currency->currency;
}

?>
