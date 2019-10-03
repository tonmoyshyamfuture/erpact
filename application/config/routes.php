<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */

$route['site-offline'] = "admin/siteoffline";

$route['404_override'] = 'admin/force404';

$route['admin/forget-password'] = "admin/forgetpass";
$route['admin/site-settings'] = "admin/sitesettings";
$route['admin/new-password.aspx/(:any)'] = "admin/newpass/$1";
$route['admin/change-password'] = "admin/changePassword";

$route['admin/cms'] = "cms/admin/cms";
$route['admin/add-cms'] = "cms/admin/formcms";
$route['admin/edit-cms.aspx/(:any)'] = "cms/admin/formcms/$1";
$route['admin/delete-cms.aspx/(:any)'] = "cms/admin/deletecms/$1";
$route['admin/cms-status.aspx/(:any)'] = "cms/admin/statuscms/$1";

$route['admin/contact-us'] = "contactus/admin/contactus";
$route['admin/delete-contact.aspx/(:any)'] = "contactus/admin/deletecontact/$1";
$route['admin/reply-contact.aspx/(:any)'] = "contactus/admin/replycontact/$1";
$route['admin/reply-view.aspx/(:any)'] = "contactus/admin/replyviewcontact/$1";

$route['admin/add-members'] = "admin/formusers";
$route['admin/edit-members.aspx/(:any)'] = "admin/formusers/$1";
$route['admin/delete-members.aspx/(:any)'] = "admin/deleteusers/$1";
$route['admin/members-status.aspx/(:any)'] = "admin/statususers/$1";

$route['admin/add-menu'] = "admin/addMenu";
$route['admin/edit-menu.aspx/(:any)'] = "admin/editmenu/$1";
$route['admin/delete-menu.aspx/(:any)'] = "admin/deletemenu/$1";

$route['admin/plugin-manager'] = "admin/plugins";
$route['admin/menu-manager'] = "admin/listMenu";

$route['default_controller'] = 'admin/index';


require_once( BASEPATH . 'database/DB' . EXT );
$db = & DB();
$query = $db->get('pb_cms');
$result = $query->result();
if (count($result) > 0) {
    foreach ($result as $row) {

        //if ($row->alias !== "home") {

            $route[$row->alias] = "front/pages/$1";
        //}
    }
}



$route['products'] = "front/products";
$route['products.aspx/(:any)'] = "front/products/$1";

$route['cart'] = "front/cart";
$route['checkout']="front/checkout";
$route['confirm']="front/confirm";
$route['register.aspx/(:any)']="front/register/$1";
$route['login']="front/login";
$route['profile']="front/profile";
$route['logout']="front/logout";
$route['forget-password']="front/forgetpassword";
$route['new-password.aspx/(:any)'] = "front/newpass/$1";


$route['blogs'] = "front/blogs";
$route['blogs.aspx/(:any)'] = "front/blogs/$1";
$route['blogscomments.aspx'] = "front/postComments";

$route['search.aspx/(:any)'] = "front/search/$1";

$db->where("status", "1");
$queryblogtag = $db->get('pb_blog_tags');
$resultblogtag = $queryblogtag->result();
if (count($resultblogtag) > 0) {
    foreach ($resultblogtag as $row) {
        $alias = $row->alias;

        $route['tags.aspx/' . $alias] = "front/tags/" . $row->id;
        $route['tags.aspx/' . $alias . '/(:any)'] = "front/tags/" . $row->id . "/$2";
    }
}


$db->where("status", "1");
$queryblog = $db->get('pb_blog_post');
$resultblog = $queryblog->result();
if (count($resultblog) > 0) {
    foreach ($resultblog as $row) {
        $name = str_replace(" ", "-", strtolower($row->title));
        $route['blog.aspx/'.$name] = "front/blogDetails/".$row->id;
    }
}

$db->where("status", "1");
$queryblogcat = $db->get('pb_blog_category');
$resultblogcat = $queryblogcat->result();
if (count($resultblogcat) > 0) {
    foreach ($resultblogcat as $row) {
	
	$alias = $row->alias;
	$route['category.aspx/' . $alias] = "front/category/" . $row->id;
        $route['category.aspx/' . $alias . '/(:any)'] = "front/category/" . $row->id . "/$2";
    }
}


//-------HMVC MODULES --------//




$route['admin/testimonial'] = "testimonial/admin";
$route['admin/add-testimonial'] = "testimonial/admin/formtestimonial";
$route['admin/edit-testimonial.aspx/(:any)'] = "testimonial/admin/formtestimonial/$1";
$route['admin/newsletter'] = "newsletter/admin/newsletter";
$route['admin/add-newsletter'] = "newsletter/admin/formNewsletter";
$route['admin/add-newsletter-templete'] = "newsletter/admin/formNewsletterTemplete";
$route['admin/edit-newsletter.aspx/(:any)'] = "newsletter/admin/formNewsletter/$1";
$route['admin/edit-newsletter-templete.aspx/(:any)'] = "newsletter/admin/formNewsletterTempleteedit/$1";
$route['admin/view-newsletter.aspx/(:any)'] = "newsletter/admin/viewNewsletter/$1";
$route['admin/view-newsletter-templete.aspx/(:any)'] = "newsletter/admin/viewNewsletterTemplete/$1";
$route['admin/delete-newsletter.aspx/(:any)'] = "newsletter/admin/deleteNewsletter/$1";
$route['admin/delete-newsletter-templete.aspx/(:any)'] = "newsletter/admin/deleteNewsletterTemplete/$1";
$route['admin/newsletter-status.aspx/(:any)'] = "newsletter/admin/statusNewsletter/$1";
$route['admin/subscribers'] = "newsletter/admin/subscribers";
$route['admin/send-newsletter'] = "newsletter/admin/sendNewsletter";
$route['admin/send-subscription-newsletter'] = "newsletter/admin/sendCronNewsletter";
$route['admin/delete-subscriber.aspx/(:any)'] = "newsletter/admin/deleteSubscriber/$1";
$route['admin/faqs'] = "faqs/admin";
$route['admin/add-faqs'] = "faqs/admin/formfaqs";
$route['admin/edit-faqs.aspx/(:any)'] = "faqs/admin/formfaqs/$1";


$route['admin/category-subcategory'] = "catsubcat/admin";
$route['admin/add-category-subcategory'] = "catsubcat/admin/formcatsubcat";
$route['admin/edit-category-subcategory.aspx/(:any)'] = "catsubcat/admin/formcatsubcat/$1";
//$queryprocat = $db->get('pb_category_subcategory');
//$resultprocat = $queryprocat->result();
//if (count($resultprocat) > 0) {
//    foreach ($resultprocat as $row) {
//	$procatname = str_replace(" ", "-", strtolower($row->alias));
//        $procatid=urlencode(base64_encode($row->id));
//        $route['admin/edit-category-subcategory.aspx/'.$procatname] = "catsubcat/admin/formcatsubcat/".$procatid;
//    }
//}



$route['admin/users'] = "users/admin";
$route['admin/add-user'] = "users/admin/add_user"; // somnath - for add user
$route['admin/add-users'] = "users/admin/formsubadmin";
$route['admin/edit-users.aspx/(:any)'] = "users/admin/formsubadmin/$1";
$route['admin/role'] = "users/admin/role";
$route['admin/add-role'] = "users/admin/addRole";
$route['admin/edit-role.aspx/(:any)'] = "users/admin/editRole/$1";
$route['admin/getLogByUserId'] = "users/admin/getLogByUserId";











$route['admin/product-inventory-list'] = "inventory/admin/listproduct";

$route['admin/editor'] = "editor/admin";

$route['admin/theme/upload'] = "theme/admin/upload";
$route['admin/theme'] = "theme/admin";
$route['admin/theme.aspx/(:any)'] = "theme/admin/index/$1";
$route['admin/theme.aspx/(:any)/(:any)'] = "theme/admin/index/$1/$2";
$route['admin/theme.aspx/(:any)/(:any)/(:any)'] = "theme/admin/index/$1/$2/$3";
$route['admin/theme.aspx/(:any)/(:any)/(:any)/(:any)'] = "theme/admin/index/$1/$2/$3/$4";
$route['admin/theme/activate.aspx/(:any)'] = "theme/admin/activate/$1";



$route['admin/reports'] = "reports/admin";
$route['admin/detail-report'] = "reports/admin/detailreport";









































$route['admin/productdiscount'] = "productdiscount/admin/formproductdiscount";










$route['admin/frontmenuedit/(:any)'] = "headermenu/admin/frontmenuedit/$1";
$route['admin/frontmenuupdate/(:any)'] = "headermenu/admin/frontmenuupdate/$1";
$route['admin/frontmenugroup'] = "headermenu/admin/frontmenugroup";
$route['admin/add-menu'] = "headermenu/admin/add_menu";
$route['admin/update-menu'] = "headermenu/admin/update_menu";



$route['admin/blog-category-subcategory'] = "blogs/admin";
$route['admin/add-blog-category-subcategory'] = "blogs/admin/formblogcatsubcat";
$route['admin/edit-blog-category-subcategory.aspx/(:any)'] = "blogs/admin/formblogcatsubcat/$1";
$route['admin/blog-tags'] = "blogs/admin/blogtagslist";
$route['admin/add-blog-tag'] = "blogs/admin/formblogtag";
$route['admin/edit-blog-tag.aspx/(:any)'] = "blogs/admin/formblogtag/$1";
$route['admin/blog-posts'] = "blogs/admin/blogpostslist";
$route['admin/add-blog-post'] = "blogs/admin/formblogpost";
$route['admin/edit-blog-post.aspx/(:any)'] = "blogs/admin/formblogpost/$1";


$route['admin/blog-category-subcategory'] = "blogs/admin";
$route['admin/add-blog-category-subcategory'] = "blogs/admin/formblogcatsubcat";
$route['admin/edit-blog-category-subcategory.aspx/(:any)'] = "blogs/admin/formblogcatsubcat/$1";
$route['admin/blog-tags'] = "blogs/admin/blogtagslist";
$route['admin/add-blog-tag'] = "blogs/admin/formblogtag";
$route['admin/edit-blog-tag.aspx/(:any)'] = "blogs/admin/formblogtag/$1";
$route['admin/blog-posts'] = "blogs/admin/blogpostslist";
$route['admin/add-blog-post'] = "blogs/admin/formblogpost";
$route['admin/edit-blog-post.aspx/(:any)'] = "blogs/admin/formblogpost/$1";















$route['admin/receipts'] = "accounts/entries/index/1";
$route['admin/payments'] = "accounts/entries/index/2";
$route['admin/contres'] = "accounts/entries/index/3";
$route['admin/jurnals'] = "accounts/entries/index/4";
//22082016
$route['admin/sales'] = "accounts/entries/index/5";
$route['admin/purchases'] = "accounts/entries/index/6";

$route['admin/post-dated-entry'] = "accounts/entries/post_dated_entry";
$route['admin/recurring-entry'] = "accounts/entries/recurring_entry";

$route['admin/sub-voucher'] = "accounts/entries/sub_voucher";
$route['admin/sub-vouchar-add-entry.aspx/(:any)/(:any)'] = "accounts/entries/sub_voucher_new_entry/$1/$2";

$route['admin/add-accounts-entry.aspx/(:any)'] = "accounts/entries/new_entry/$1";
$route['admin/edit-accounts-entry.aspx/(:any)/(:any)'] = "accounts/entries/edit_entry/$1/$2";
$route['admin/accounts-groups'] = "accounts/groups";
$route['admin/add-accounts-groups'] = "accounts/groups/add_group";
$route['admin/edit-accounts-groups.aspx/(:any)'] = "accounts/groups/add_group/$1";
$route['admin/accounts-group-ledgers.aspx/(:any)'] = "accounts/accounts/account_ledger/$1";
$route['admin/accounts-ledger'] = "accounts";
$route['admin/add-accounts-ledger'] = "accounts/add_ledger";
$route['admin/edit-accounts-ledger.aspx/(:any)'] = "accounts/add_ledger/$1";
$route['admin/company-details-ledger.aspx/(:any)'] = "accounts/ledger_company_details/$1";
$route['admin/accounts-ledger-statement'] = "accounts/reports/ledger_statements";
$route['admin/bill-wise-outstanding'] = "accounts/reports/bill_ledger_statements";
$route['admin/accounts-trial-balance'] = "accounts/reports/trial_balance";
$route['admin/accounts-balance-sheet'] = "accounts/reports/balance_sheet";
$route['admin/accounts-profit-loss'] = "accounts/reports/profit_loss";
$route['admin/vertical-profit-loss'] = "accounts/reports/profit_loss_vertical";
$route['admin/vertical-balance-sheet'] = "accounts/reports/balance_sheet_vertical";


$route['admin/save-the-group'] = "accounts/groups/save_the_group"; // somnath 13/04/2018
$route['admin/save-the-ledger'] = "accounts/groups/save_the_ledger"; // somnath 13/04/2018
$route['admin/save-the-contact'] = "customer_details/admin/save_the_contact"; // somnath 14/04/2018
$route['admin/save-the-category'] = "catsubcat/admin/save_the_category"; // somnath 17/04/2018
$route['admin/save-the-unit'] = "unit/admin/save_the_unit"; // somnath 18/04/2018
$route['admin/save-the-gst'] = "gst/admin/save_the_gst"; // somnath 18/04/2018
$route['admin/save-the-product'] = "products/admin/save_the_product"; // somnath 18/04/2018
$route['admin/save-the-voucher'] = "accounts/entries/save_the_voucher"; // somnath 19/04/2018
$route['admin/save-the-service'] = "services/admin/save_the_service"; // somnath 20/04/2018
$route['admin/save-the-attribute'] = "products/admin/save_the_attribute"; // somnath 04/05/2018
$route['admin/save-the-brs'] = "brs/admin/save_the_brs"; // somnath 04/05/2018



//$route['admin/users'] = "subadmin/admin";
//$route['admin/add-users'] = "subadmin/admin/formsubadmin";
//$route['admin/edit-users.aspx/(:any)'] = "subadmin/admin/formsubadmin/$1";
//$route['admin/role'] = "subadmin/admin/role";
//$route['admin/add-role'] = "subadmin/admin/addRole";
//$route['admin/edit-role.aspx/(:any)'] = "subadmin/admin/editRole/$1";

//23062016
$route['admin/accounts-settings'] = "accounts_settings/admin";
//24062016
$route['admin/accounts-configuration'] = "accounts_configuration/admin";
//11072016
// $route['admin/sub-voucher-receipts'] = "accounts/entries/sub_voucher_add/1";
// $route['admin/sub-voucher-payments'] = "accounts/entries/sub_voucher_add/2";
// $route['admin/sub-voucher-contres'] = "accounts/entries/sub_voucher_add/3";
// $route['admin/sub-voucher-jurnals'] = "accounts/entries/sub_voucher_add/4";

$route['admin/sub-voucher-add'] = "accounts/entries/sub_voucher_add";

$route['admin/sub-voucher.aspx/(:any)'] = "accounts/entries/sub_voucher/$1";
$route['admin/edit-sub-voucher.aspx/(:any)/(:any)'] = "accounts/entries/edit_sub_vobcher/$1/$2";
$route['admin/entry-sub-voucher.aspx/(:any)'] = "accounts/entries/sub_voucher_entry/$1";

//$route['admin/standard-formaty'] = "accounts_settings/admin/standard_formaty";
$route['admin/standard-format'] = "accounts_settings/admin/standard_format";
$route['admin/vouchers'] = "accounts/entries/vouchers";
$route['admin/currency'] = "currency/admin";
$route['admin/currency-add'] = "currency/admin/currency_add";
$route['admin/currency-add.aspx/(:any)'] = "currency/admin/currency_add/$1";
// $route['admin/tracking'] = "accounts_settings/admin/tracking";
// $route['admin/tracking-details.aspx/(:any)'] = "accounts_settings/admin/tracking_details/$1";

$route['admin/tracking'] = "tracking/admin";
$route['admin/tracking-add'] = "tracking/admin/tracking_add";
$route['admin/tracking-edit.aspx/(:any)'] = "tracking/admin/tracking_add/$1";


//transaction asit
$route['admin/transaction-list/(:any)/(:num)/(:any)'] = "accounts/entries/index/$1/$2/$3";
$route['admin/inventory-transaction-list/(:any)/(:num)/(:any)'] = "transaction_inventory/inventory/index/$1/$2/$3";
$route['admin/loadmore'] = "transaction_inventory/inventory/loadMoreByAjax";
$route['admin/loadMoreRecords'] = "transaction_inventory/inventory/loadMoreRecords";

$route['admin/transaction'] = "transaction/admin";
$route['admin/transaction-add'] = "transaction/admin/transaction_add";
$route['admin/transaction/(:any)'] = "transaction/admin/index/$1";

//asit
$route['admin/transaction-update.aspx/(:any)/(:any)/(:any)'] = "transaction/entries/update/$1/$2/$3";
$route['admin/transaction-copy.aspx/(:any)/(:any)/(:any)'] = "transaction/entries/update/$1/$2/$3";
$route['api-get-date-by-finance-year']="transaction/entries/checkDate";
$route['api-get-ledger-details']="transaction/entries/getLedgerDelails";
$route['api-get-temp-tracking-data']="transaction/entries/getTempTrackingData";
$route['api-save-temp-tracking-data']="transaction/entries/saveTempTrackingData";
$route['api-save-temp-billing-data']="transaction/entries/saveTempBillingData";
$route['api-get-temp-billing-data']="transaction/entries/getTempBillingData";
$route['api-get-tracking-name']="transaction/entries/getTrackingName";
$route['api-get-temp-banking-data']="transaction/entries/getTempBankingData";
$route['api-save-temp-bank-data']="transaction/entries/saveTempBankData";
//asit
$route['accounts_inventory/transaction'] = "transaction/accounts_inventory";
$route['accounts_inventory/transaction/(:any)'] = "transaction/accounts_inventory/index/$1";
$route['accounts_inventory/transaction/'] = "transaction/accounts_inventory/index/$1";
$route['accounts_inventory/transaction-add'] = "transaction/accounts_inventory/transaction_add";
$route['accounts_inventory/transaction-edit.aspx/(:any)'] = "transaction/accounts_inventory/transaction_add/$1";

//asit
$route['transaction/sales'] = "transaction_inventory/inventory/index/5";
$route['transaction/sales-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/sales-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/invoice.aspx/(:any)/(:any)'] = "transaction_inventory/inventory/invoice/$1/$2";
$route['transaction/invoice.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/invoice/$1/$2/$3";

$route['transaction/entry-delete/(:any)'] = "transaction_inventory/inventory/entry_delete/$1"; // 18/07/2018

$route['transaction/purchase'] = "transaction_inventory/inventory/index/6";
$route['transaction/purchase-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/purchase-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";

//service 20032018 @sudip
$route['transaction/service-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/service-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";

//sales order
$route['transaction/sales-order'] = "transaction_inventory/inventory/index/7";
$route['transaction/sales-order-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/sales-order-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/sales-order-sales.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
//purchase order
$route['transaction/purchase-order'] = "transaction_inventory/inventory/index/8";
$route['transaction/purchase-order-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/purchase-order-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/purchase-order-purchase.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
//receive notes
$route['transaction/receive-note'] = "transaction_inventory/inventory/index/9";
$route['transaction/receive-note-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/receive-note-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/receive-note-purchase.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
//delivery notes
$route['transaction/delivery-note'] = "transaction_inventory/inventory/index/10";
$route['transaction/delivery-note-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/delivery-note-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/delivery-note-sales.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
//credit note
$route['transaction/credit-note'] = "transaction_inventory/inventory/index/14";
$route['transaction/credit-note-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/credit-note-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
//debit note
$route['transaction/debit-note'] = "transaction_inventory/inventory/index/12";
$route['transaction/debit-note-add.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
$route['transaction/debit-note-update.aspx/(:any)/(:any)/(:any)'] = "transaction_inventory/inventory/transaction_form/$1/$2/$3";
//04082016
$route['admin/accounts-groups-report'] = "reports/admin/group_list";
$route['admin/groups-report-details'] = "reports/admin/group_report";
$route['admin/ledger-billwish-report.aspx/(:any)'] = "reports/admin/ledger_billwish_report/$1";
$route['admin/ledger-billwish-report'] = "reports/admin/ledger_billwish_report";
//08082016
$route['admin/ledger-report.aspx/(:any)'] = "reports/admin/ledger_report/$1";
//0982016
$route['admin/monthly-report.aspx/(:any)'] = "reports/admin/monthly_report/$1";
$route['admin/monthly-report'] = "reports/admin/monthly_report";
$route['admin/monthly-ledger-report.aspx/(:any)/(:any)'] = "reports/admin/monthly_ledger_report/$1/$2";
//13082016
$route['admin/statistics-report'] = "reports/admin/statistics_report";
$route['admin/monthly-statistics-report.aspx/(:any)'] = "reports/admin/monthly_statistics_report/$1";
$route['admin/monthly-statistics-report.aspx/(:any)/(:any)'] = "reports/admin/monthly_statistics_report/$1/$2";
//18082016
$route['admin/receipt-payment-report'] = "reports/admin/receipt_payment_report";
//18082016
$route['admin/cash-flow-report'] = "reports/admin/cash_flow_report";
$route['admin/cash-flow-report/(:any)'] = "reports/admin/cash_flow_report/$1";
//24082016
$route['admin/cashflow-details-report.aspx/(:any)'] = "reports/admin/cashflow_details_report/$1";
//26082016 
$route['admin/ledger-statement'] = "accounts/reports/ledger_report";
//06092016
$route['admin/view-accounts-groups.aspx/(:any)'] = "accounts/groups/view_group/$1";
$route['admin/view-accounts-ledger.aspx/(:any)'] = "accounts/view_ledger/$1";
$route['admin/currency-view.aspx/(:any)'] = "currency/admin/currency_view/$1";
//07092016
$route['admin/accounts-trial-balance.aspx/(:any)'] = "accounts/reports/trial_balance/$1";
//18/12/2017 - somnath.
$route['admin/day-book'] = "accounts/reports/dayBook";

$route['admin/day-book-search'] = "accounts/reports/dayBookSearch"; // 09/01/2018 - somnath

$route['admin/edit-parent-voucher.aspx/(:any)'] = "accounts/entries/edit_parent_vobcher/$1";
//19092016
$route['admin/cash-bank-book'] = "reports/admin/cash_bank_book_report";
$route['admin/sales-register'] = "reports/admin/sales_register";
$route['admin/monthly-sales-register-report'] = "reports/admin/monthly_sales_register_report";
$route['admin/sales-register-menthly-details.aspx/(:any)'] = "reports/admin/sales_register_monthly_details/$1";
$route['admin/purchase-register'] = "reports/admin/purchase_register";
$route['admin/monthly-purchase-register-report'] = "reports/admin/monthly_purchase_register_report";
$route['admin/purchase-register-menthly-details.aspx/(:any)'] = "reports/admin/purchase_register_monthly_details/$1";
//20092016
$route['admin/journal-register'] = "reports/admin/journal_register";
$route['admin/monthly-journal-register-report'] = "reports/admin/monthly_journal_register_report";
$route['admin/journal-register-menthly-details.aspx/(:any)'] = "reports/admin/journal_register_monthly_details/$1";
//22092016
$route['admin/trasaction-details.aspx/(:any)'] = "reports/admin/trasaction_details/$1";

//26092016
$route['admin/tracking-report1'] = "reports/admin/tracking_report1";

//28092016
$route['admin/tracking-report'] = "reports/admin/tracking_report";
$route['admin/tracking-report.aspx/(:any)'] = "reports/admin/tracking_report/$1";
$route['admin/monthly-tracking-report'] = "reports/admin/monthly_tracking_report";
$route['admin/monthly-tracking-report.aspx/(:any)'] = "reports/admin/monthly_tracking_report/$1";
//30/09/2016
$route['admin/monthly-tracking-ledger-report.aspx/(:any)/(:any)'] = "reports/admin/monthly_tyracking_ledger_report/$1/$2";
//06102016
$route['admin/fund-flow-report'] = "reports/admin/fund_flow_report";
$route['admin/fund-flow-details-report.aspx/(:any)'] = "reports/admin/fund_flow_report_details/$1";
//17102016
$route['admin/receivable-group'] = "reports/admin/receivable_group";
$route['admin/invoice-details'] = "reports/admin/invoice_details";
$route['admin/ratio-analysis'] = "reports/admin/ratio_analysis";
$route['admin/receivable-aging-details'] = "reports/admin/receivable_aging_details";
$route['admin/payable-aging-details'] = "reports/admin/payable_aging_details";
//@asit
$route['admin/all-group'] = "accounts/groups/group_list";



//report
$route['admin/receivable-customer-balance'] = "reports/receivable/customer_balence";
$route['admin/customer-monthly-report.aspx/(:any)'] = "reports/receivable/monthly_report/$1";
$route['admin/customer-monthly-report'] = "reports/receivable/monthly_report";
$route['admin/receivable-customer-group'] = "reports/receivable/receivable_group";
$route['admin/customer-group-details'] = "reports/receivable/group_details";
$route['admin/receivable-aging-summary'] = "reports/receivable/receivable_aging_summary";
$route['admin/receivable-refund-history'] = "reports/receivable/refund_history";
$route['admin/receivable-refund-monthly-report'] = "reports/receivable/refund_monthly_report";
$route['admin/monthly-ledger-report-details.aspx/(:any)/(:any)'] = "reports/receivable/monthly_ledger_report/$1/$2";
$route['admin/receivable-overdue'] = "reports/receivable/overdue";
$route['admin/receivable-billwish-report'] = "reports/receivable/ledger_billwish_report/$1";
$route['admin/receivable-bill-details'] = "reports/receivable/bill_details/$1/$2";


$route['admin/payable-vendor-balance'] = "reports/payable/vendor_balence";
$route['admin/vendor-monthly-report.aspx/(:any)'] = "reports/payable/monthly_report/$1";
$route['admin/vendor-monthly-report'] = "reports/payable/monthly_report";
$route['admin/payable-vendor-group'] = "reports/payable/payable_group";
$route['admin/vendor-group-details'] = "reports/payable/group_details";
$route['admin/payable-aging-summary'] = "reports/payable/payable_aging_summary";
$route['admin/payable-refund-history'] = "reports/payable/refund_history";
$route['admin/payable-refund-monthly-report'] = "reports/payable/refund_monthly_report";
$route['admin/payable-monthly-ledger-report-details.aspx/(:any)/(:any)'] = "reports/payable/monthly_ledger_report/$1/$2";

$route['admin/payable-overdue'] = "reports/payable/overdue";
$route['admin/payable-billwish-report'] = "reports/payable/ledger_billwish_report/$1";
$route['admin/payable-bill-details'] = "reports/payable/bill_details/$1/$2";

//product
$route['admin/products'] = "products/admin";
$route['admin/products.aspx/(:any)'] = "products/admin/index/$1";

$route['admin/add-products'] = "products/admin/formproducts";
$route['admin/edit-products.aspx/(:any)'] = "products/admin/formproducts/$1";
$route['admin/edit-products.aspx/(:any)/(:any)'] = "products/admin/formproducts/$1/$2";

$route['admin/product-attributes'] = "products/admin/productattribute";
$route['admin/product-attributes.aspx/(:any)'] = "products/admin/productattribute/$1";
$route['admin/add-product-attributes'] = "products/admin/formproductattribute";
$route['admin/edit-product-attributes.aspx/(:any)'] = "products/admin/formproductattribute/$1";

$route['admin/products/sales-price-list'] = "products/admin/productSalesPriceList";

//inventry
$route['admin/product-inventory-list'] = "inventory/admin/listproduct";
$route['admin/product-inventory-list.aspx/(:any)'] = "inventory/admin/listproduct/$1";

$route['admin/product-stock-details.aspx/(:any)/(:any)'] = "inventory/admin/stockdetails/$1/$2";

$route['admin/product-inventory'] = "inventory/admin/liststocks";
$route['admin/product-inventory.aspx/(:any)'] = "inventory/admin/liststocks/$1";

$route['admin/product-inventory-list'] = "inventory/admin/listproduct";
$route['admin/product-inventory-list.aspx/(:any)'] = "inventory/admin/listproduct/$1";

$route['admin/product-stock-details.aspx/(:any)/(:any)'] = "inventory/admin/stockdetails/$1/$2";

//GST
$route['admin/goodgst'] = "gst/admin/goodgst";
$route['admin/add-gst'] = "gst/admin/formgoodgst";
$route['admin/edit-gst.aspx/(:any)'] = "gst/admin/formgoodgst/$1";
$route['admin/delete-goodgst.aspx/(:any)'] = "gst/admin/deletegoodgst/$1";
//order
$route['admin/orders'] = "orders/admin";
$route['admin/orders.aspx/(:any)'] = "orders/admin/index/$1";
$route['admin/credit-note'] = "orders/admin/list_credit_note";
$route['admin/debit-note'] = "orders/admin/list_debit_note";

$route['admin/change-delivery-status/(:any)/(:any)'] = "orders/admin/changedeliverystatus/$1/$2";
$route['admin/change-order-status/(:any)/(:any)'] = "orders/admin/changeorderstatus/$1/$2";
$route['admin/add-order'] = "orders/admin/addorder";
$route['admin/order-details.aspx/(:any)'] = "orders/admin/orderdetails/$1";
$route['admin/order-details.aspx/(:any)/(:any)'] = "orders/admin/orderdetails/$1/$2";
$route['admin/edit-order.aspx/(:any)'] = "orders/admin/addorder/$1";
$route['admin/view-trash-orders'] = "orders/admin/viewtrashorders";
$route['admin/order-edit.aspx/(:any)'] = "orders/admin/orderedit/$1";
$route['admin/customer-orders.aspx/(:any)'] = "orders/admin/index/$1";



//tax
$route['admin/taxclass']                            = "taxclass/admin";
$route['admin/taxclass.aspx/(:any)']                = "taxclass/admin/index/$1";
$route['admin/add-taxclass']                        = "taxclass/admin/addTaxclass";
$route['admin/edit-taxclass.aspx/(:any)']           = "taxclass/admin/editTaxclass/$1";
$route['admin/del-taxclass.aspx/(:any)']            = "taxclass/admin/delTaxclass/$1";
$route['admin/change-status-taxclass.aspx/(:any)']  = "taxclass/admin/statustaxclass/$1";
$route['admin/setup-class.aspx/(:any)']             = "taxclass/admin/setup_class/$1";
$route['admin/getNotSelectedCountry']               = "taxclass/admin/getNotSelectedCountry";
$route['admin/addCountryClass']                     = "taxclass/admin/addCountryClass";
$route['admin/remove_country_class']                = "taxclass/admin/remove_country_class";
$route['admin/update-config']                       = "taxclass/admin/update_config";

//purchase order
$route['admin/purchaseorder'] = "purchaseorder/admin";
$route['admin/purchaseorder.aspx/(:any)'] = "purchaseorder/admin/index/$1";

$route['admin/change-delivery-status/(:any)/(:any)'] = "purchaseorder/admin/changedeliverystatus/$1/$2";
$route['admin/change-purchaseorder-status/(:any)/(:any)'] = "purchaseorder/admin/changeorderstatus/$1/$2";
$route['admin/add-purchaseorder'] = "purchaseorder/admin/addorder";
$route['admin/purchaseorder-details.aspx/(:any)'] = "purchaseorder/admin/orderdetails/$1";
$route['admin/purchaseorder-details.aspx/(:any)/(:any)'] = "purchaseorder/admin/orderdetails/$1/$2";
$route['admin/edit-purchaseorder.aspx/(:any)'] = "purchaseorder/admin/addorder/$1";
$route['admin/view-trash-purchaseorder'] = "purchaseorder/admin/viewtrashorders";

$route['admin/purchaseorder-edit.aspx/(:any)'] = "purchaseorder/admin/orderedit/$1";
$route['admin/customer-purchaseorder.aspx/(:any)'] = "purchaseorder/admin/index/$1";


//inventory report

$route['admin/stock-summary'] = "reports/inventory/stock_summary";
$route['admin/stock-category-details'] = "reports/inventory/stock_category_details";
$route['admin/product-varient'] = "reports/inventory/liststocks";
$route['admin/product-varient.aspx/(:any)'] = "reports/inventory/liststocks/$1";
$route['admin/product-monthly-report'] = "reports/inventory/monthly_report";
$route['admin/product-monthly-report-details.aspx/(:any)/(:any)'] = "reports/inventory/monthly_product_report_details/$1/$2";
$route['admin/product-in-details.aspx/(:any)/(:any)'] = "reports/inventory/product_in_report_details/$1/$2";
$route['admin/product-out-details.aspx/(:any)/(:any)'] = "reports/inventory/product_out_report_details/$1/$2";
$route['admin/product-in-out-details.aspx/(:any)/(:any)'] = "reports/inventory/product_in_out_report_details/$1/$2";

$route['admin/stock-item'] = "reports/inventory/stock_item";
$route['admin/stock-group'] = "reports/inventory/stock_group";

$route['admin/aging-analysis'] = "reports/inventory/aging_analysis";
$route['admin/inventory-aging-summary'] = "reports/inventory/aging_summary";
$route['admin/products-aging-summary/(:any)'] = "reports/inventory/product_aging_summary/$1";
//sales
$route['admin/sales-by-item'] = "reports/inventory/sales_by_item";
//$route['admin/sales-product-monthly-report'] = "reports/inventory/sales_product_monthly_report";
$route['admin/sales-product-monthly-report'] = "reports/inventory/monthly_report/$1/out";
$route['admin/sales-product-monthly-report-details.aspx/(:any)/(:any)'] = "reports/inventory/sales_product_monthly_report_details/$1/$2";
$route['admin/sales-product-monthly-details.aspx/(:any)/(:any)'] = "reports/inventory/monthly_product_report_details/$1/$2/out";

//purchase
$route['admin/purchase-by-item'] = "reports/inventory/purchase_by_item";
$route['admin/purchase-product-monthly-report'] = "reports/inventory/monthly_report/$1/in";
$route['admin/purchase-product-monthly-details.aspx/(:any)/(:any)'] = "reports/inventory/monthly_product_report_details/$1/$2/in";
//$route['admin/purchase-product-monthly-report'] = "reports/inventory/purchase_product_monthly_report";
$route['admin/purchase-product-monthly-report-details.aspx/(:any)/(:any)'] = "reports/inventory/purchase_product_monthly_report_details/$1/$2";
$route['admin/finish-product-sales'] = "reports/inventory/finish_product_sales";

$route['admin/sales-by-customer'] = "reports/receivable/sales_by_customer";
$route['admin/purchase-by-vendor'] = "reports/payable/purchase_by_vendor";

//sales order request

$route['admin/request-sales-orders'] = "request_sales_order/admin";
$route['admin/new-sales-orders'] = "request_sales_order/admin/addorder";
$route['admin/sales-order-request-deliver.aspx/(:any)'] = "request_sales_order/admin/orderdeliver/$1";
$route['admin/sales-order-request-edit.aspx/(:any)'] = "request_sales_order/admin/orderedit/$1";

$route['admin/request-purchase-orders'] = "request_purchase_order/admin";
$route['admin/new-purchase-orders'] = "request_purchase_order/admin/addorder";
$route['admin/purchase-order-request-edit.aspx/(:any)'] = "request_purchase_order/admin/orderedit/$1";
$route['admin/purchase-order-request-deliver.aspx/(:any)'] = "request_purchase_order/admin/orderdeliver/$1";

$route['admin/invoice.aspx/(:any)'] = "orders/admin/invoice/$1";

//customer
$route['admin/customer-details'] = "customer_details/admin";
$route['admin/contact-person-details'] = "customer_details/admin/contact_person_details";
$route['admin/add-customer-details'] = "customer_details/admin/add_details";
$route['admin/view-customer-details.aspx/(:any)'] = "customer_details/admin/view_contact_details/$1";
$route['admin/edit-customer-details.aspx/(:any)'] = "customer_details/admin/edit_contact_details/$1";
$route['admin/delete-customer-details.aspx/(:any)'] = "customer_details/admin/delete_contact_details/$1"; // somnath - 15/01/2018
$route['admin/getCustomerBalanceUsingAjax'] = "customer_details/admin/getCustomerBalanceUsingAjax"; // somnath - 06/03/2018

//receive notes
$route['admin/receive-notes'] = "receive_notes/admin";
$route['admin/receive-notes-add'] = "receive_notes/admin/addorder";
$route['admin/receive-notes-edit.aspx/(:any)'] = "receive_notes/admin/orderedit/$1";
$route['admin/receive-notes-deliver.aspx/(:any)'] = "receive_notes/admin/orderdeliver/$1";

//delivery notes
$route['admin/delivery-notes'] = "delivery_notes/admin";
$route['admin/delivery-notes-add'] = "delivery_notes/admin/addorder";
$route['admin/delivery-notes-edit.aspx/(:any)'] = "delivery_notes/admin/orderedit/$1";
$route['admin/delivery-notes-deliver.aspx/(:any)'] = "delivery_notes/admin/orderdeliver/$1";

//stock journal
$route['admin/stock-journal'] = "stock_journal/admin";
$route['admin/journal/(:any)/(:num)/(:any)'] = "stock_journal/admin/index/$1/$2/$3";
$route['admin/stock-journal-add'] = "stock_journal/admin/addorder";
$route['admin/view-stock-journal.aspx/(:any)'] = "stock_journal/admin/view/$1";
$route['admin/bom-stock-journal-add'] = "stock_journal/admin/bom_stock_journal_add";
$route['admin/edit-stock-journal.aspx/(:any)'] = "stock_journal/admin/update/$1";


//credit note
$route['admin/credit-note-create.aspx/(:any)'] = "orders/admin/credit_note_create/$1";
$route['admin/purchase-credit-note-create.aspx/(:any)'] = "purchaseorder/admin/credit_note_create/$1";
$route['admin/credit-note-edit.aspx/(:any)/(:any)'] = "orders/admin/credit_note_edit/$1/$2";

//debit  note
$route['admin/debit-note-create.aspx/(:any)'] = "orders/admin/debit_note_create/$1";
$route['admin/purchase-debit-note-create.aspx/(:any)'] = "purchaseorder/admin/debit_note_create/$1";
$route['admin/debit-note-edit.aspx/(:any)'] = "orders/admin/debit_note_edit/$1";

//bill of material
$route['admin/bill-of-material'] = "bill_of_material/admin";
$route['admin/bill-of-material-add'] = "bill_of_material/admin/addbill";
$route['admin/edit-bill-of-material.aspx/(:any)'] = "bill_of_material/admin/edit_bom/$1";

//Unit module route
$route['admin/unit'] = "unit/admin";
$route['admin/unit-add'] = "unit/admin/unit_add";
$route['admin/unit-add.aspx/(:any)'] = "unit/admin/unit_add/$1";
$route['admin/unit-delete/(:any)'] = "unit/admin/unit_delete/$1";
//email template
$route['admin/email-template'] = "emailtemplate/admin/emailtemplate";
$route['admin/add-email-template'] = "emailtemplate/admin/addemailtemplate";
$route['admin/edit-email-template.aspx/(:any)'] = "emailtemplate/admin/addemailtemplate/$1";
$route['admin/goodgst'] = "gst/admin/goodgst";
$route['admin/add-gst'] = "gst/admin/formgoodgst";
$route['admin/edit-gst.aspx/(:any)'] = "gst/admin/formgoodgst/$1";
$route['admin/delete-goodgst.aspx/(:any)'] = "gst/admin/deletegoodgst/$1";

//TAX Report
$route['admin/gstr-one'] = "tax_report/admin/gstr_one";
$route['admin/gstr-two'] = "tax_report/admin/gstr_two";
$route['admin/gstr-three-b'] = "tax_report/admin/gstr_three_b";
$route['admin/sales-tax-report'] = "tax_report/admin/sales_tax_report";
$route['admin/purchase-tax-report'] = "tax_report/admin/purchase_tax_report";
$route['admin/computation-tax-report'] = "tax_report/admin/computation_tax_report";
$route['admin/itc-details'] = "tax_report/admin/itc_details";
$route['admin/reverse-entry-details'] = "tax_report/admin/reverse_entry_details";
$route['admin/inter-state-purchase-details'] = "tax_report/admin/inter_state_purchase_details";
$route['admin/intra-state-purchase-details'] = "tax_report/admin/intra_state_purchase_details";
$route['admin/unregister-person-purchase-details'] = "tax_report/admin/unregister_person_purchase_details";
$route['admin/inport-purchase-details'] = "tax_report/admin/inport_purchase_details";
$route['admin/credit-note-details'] = "tax_report/admin/credit_note_details";
$route['admin/nil-rated-tax-purchase-details'] = "tax_report/admin/nil_rated_tax_purchase_details";
$route['admin/inter-state-sales-details'] = "tax_report/admin/inter_state_sales_details";
$route['admin/intra-state-sales-details'] = "tax_report/admin/intra_state_sales_details";
$route['admin/local-consumer-transaction-details'] = "tax_report/admin/local_consumer_transaction_details";
$route['admin/central-consumer-less-2.5-transaction-details'] = "tax_report/admin/central_consumer_less_2_5_transaction_details";
$route['admin/central-consumer-grater-2.5-transaction-details'] = "tax_report/admin/central_consumer_grater_2_5_transaction_details";
$route['admin/nill-rated-sales-details'] = "tax_report/admin/nill_rated_sales_details";
$route['admin/debit-note-details'] = "tax_report/admin/debit_note_details";
$route['admin/export-sales-details'] = "tax_report/admin/export_sales_details";

//services
$route['admin/services'] = "services/admin";
$route['admin/services.aspx/(:any)'] = "services/admin/index/$1";

$route['admin/add-service'] = "services/admin/formservice";
$route['admin/edit-service.aspx/(:any)'] = "services/admin/formservice/$1";
$route['admin/edit-service.aspx/(:any)/(:any)'] = "services/admin/formservice/$1/$2";

//branch
$route['admin/add-branch'] = "admin/add_branch";

//API
$route['api-test'] = "api/admin/test";

//User Access
$route['admin/user-access'] = "role/admin/user_access";
$route['admin/add-watchlist'] = "admin/add_watchlist";
$route['admin/getAllGstUnits'] = "unit/admin/getAllGstUnits"; // somnath - 23/01/18 (autocomplete units)
$route['admin/export-gst-excel'] = "tax_report/admin/exportGstExcel"; // somnath - 25/01/18 (GST report export as excel)
$route['admin/export-gstr1'] = "tax_report/admin/exportGSTR1"; // somnath - 27/01/18 (GSTR1 report export as excel)
$route['admin/export-gstr2'] = "tax_report/admin/exportGSTR2"; // somnath - 29/01/18 (GSTR2 report export as excel)
$route['admin/export-gstr3'] = "tax_report/admin/exportGSTR3"; // somnath - 28/02/18 (GSTR3B report export as excel)


$route['admin/checkDuplicateEntryno'] = "transaction_inventory/inventory/checkDuplicateEntryno"; // somnath - 20/02/2018
$route['admin/checkDuplicateVoucherno'] = "transaction_inventory/inventory/checkDuplicateVoucherno"; // somnath - 20/02/2018

// $route['admin/ledgerReportForSalesAssets'] = "reports/admin/monthlyLedgerReportForGroup";
$route['admin/ledger-report-sales-assets/(:any)/(:any)/(:any)'] = "reports/admin/monthlyLedgerReportForGroup/$1/$2/$3";
$route['admin/ledger-report-purchase-assets/(:any)/(:any)/(:any)'] = "reports/admin/monthlyLedgerReportForGroup/$1/$2/$3";

$route['admin/getAllBankDetailsCompanyForInvoice'] = 'accounts_configuration/admin/getAllBankDetailsCompanyForInvoice'; // somnath - 25/04/2018
$route['admin/saveDefaultBank'] = 'accounts_configuration/admin/saveDefaultBank'; // somnath - 25/04/2018
$route['admin/showbankModalInvoice'] = 'accounts_configuration/admin/showbankModalInvoice'; // somnath - 25/04/2018
$route['admin/getBankDetailsStatus'] = 'accounts_configuration/admin/getBankDetailsStatus'; // somnath - 27/04/2018
$route['admin/showCourierModalInvoice'] = 'accounts_configuration/admin/showCourierModalInvoice'; 

$route['admin/getUserDetailsForEwayBill'] = 'accounts_configuration/admin/getUserDetailsForEwayBill'; // 04/07/2018
$route['admin/saveUserCredentialsForEwayBill'] = 'accounts_configuration/admin/saveUserCredentialsForEwayBill'; // 04/07/2018

//EWAY BILL
$route['admin/accesstoken'] = 'eway_bill/admin/generateAccessToken';
$route['admin/generated-eway-bill'] = 'eway_bill/admin/generatedEwayBill';
$route['admin/update-vehicle-number'] = 'eway_bill/admin/updateVehicleNumber';
$route['admin/eway-bill-cancel'] = 'eway_bill/admin/ewayBillCancel';
$route['admin/consolidated-eway-bills-generate'] = 'eway_bill/admin/consolidatedEwayBillsGenerate';
$route['admin/eway-bill-reject'] = 'eway_bill/admin/ewayBillReject';
$route['admin/get-distance'] = "eway_bill/admin/getDistance"; // 06/07/2018


$route['admin/columnar_receipt_payment_report'] = "reports/admin/columnar_receipt_payment_report"; // 20/06/2018 
// $route['admin/ledger-receivable-agingsummary-report.aspx/(:any)'] = "reports/admin/ledger_receivable_agingsummary_report/$1"; // 22/06/2018
$route['admin/ledger-agingsummary-report.aspx/(:any)'] = "reports/admin/ledger_receivable_agingsummary_report/$1"; // 23/07/2018


//Godown module route
$route['admin/godown'] = "godown/admin";
$route['admin/godown-add'] = "godown/admin/godown_add";
$route['admin/godown-add.aspx/(:any)'] = "godown/admin/godown_add/$1";
$route['admin/godown-delete/(:any)'] = "godown/admin/godown_delete/$1";
$route['admin/save-the-godown'] = "godown/admin/save_the_godown";

//BRS module route
$route['admin/brs'] = "brs/admin";
$route['admin/brs-statement/(:any)'] = "brs/admin/brs/$1";
$route['admin/bank-statement/(:any)'] = "brs/admin/bankStatement/$1";
$route['admin/brs-report/(:any)'] = "brs/admin/brsReport/$1";
$route['admin/reconciled-statement/(:any)'] = "brs/admin/reconciledStatement/$1";
$route['admin/reconcile/(:any)/(:any)'] = "brs/admin/reconcile/$1/$2";

$route['add-batch-for-product'] = 'products/admin/add_batch_for_product';

$route['admin/requisitions'] = 'requisition/admin/index';
$route['admin/requisition-add'] = 'requisition/admin/requisitionAdd';
// $route['admin/requisition-edit/(:any)'] = 'requisition/admin/requisitionAdd/$1';
$route['admin/requisition-edit/(:any)/(:any)'] = 'requisition/admin/requisitionAdd/$1/$2';
$route['admin/view-requisition/(:any)'] = 'requisition/admin/viewRequisition/$1';
$route['admin/requisition-delete/(:any)'] = 'requisition/admin/requisitionDelete/$1';

$route['admin/requisition/(:any)'] = 'requisition/admin/requisitionAdd/$1';
// $route['admin/inquiry-list'] = 'requisition/admin/index/15';
// $route['admin/indent-list'] = 'requisition/admin/index/16';
$route['admin/requisition-list/(:any)'] = 'requisition/admin/index/$1';

// quotation
$route['admin/quotation'] = 'transaction_inventory/inventory/quotation';
$route['admin/quotation/(:any)/(:any)/(:any)'] = 'transaction_inventory/inventory/quotation/$1/$2/$3';
$route['admin/quotation-list'] = 'transaction_inventory/inventory/quotationList';
$route['admin/view-quotation/(:any)'] = 'transaction_inventory/inventory/viewQuotation/$1';
$route['admin/edit-quotation/(:any)/(:any)/(:any)'] = 'transaction_inventory/inventory/quotation/$1/$2/$3';



// stock-in-out
$route['admin/stock-in-out-list/(:any)'] = 'stock_in_out/admin/index/$1';
$route['admin/stock-in-out-add/(:any)'] = 'stock_in_out/admin/addForm/$1/a';
$route['admin/save-stock-in-out'] = 'stock_in_out/admin/saveData';
$route['admin/stock-in-out-edit/(:any)/(:any)/(:any)'] = 'stock_in_out/admin/addForm/$1/e/$3';
$route['admin/stock-in-out-view/(:any)/(:any)'] = 'stock_in_out/admin/viewStock/$1/$2';


// manufacturing journal
$route['admin/manufacturing-journal'] = 'manufacturing_journal/admin/index';
$route['admin/add-manufacturing-journal'] = 'manufacturing_journal/admin/addManufacturingJounal';
$route['admin/manufacturing-journal-delete/(:any)'] = 'manufacturing_journal/admin/journalDelete/$1';
$route['admin/manufacturing-journal-edit/(:any)'] = 'manufacturing_journal/admin/addManufacturingJounal/$1';
$route['admin/view-manufacturing-journal/(:any)'] = 'manufacturing_journal/admin/viewJournal/$1';

// production master
$route['admin/production-master'] = 'production_master/admin/index';
$route['admin/add-production-master'] = 'production_master/admin/add_production_master';
$route['admin/edit-production-master/(:any)'] = 'production_master/admin/add_production_master/$1';
$route['admin/production-master-delete/(:any)'] = 'production_master/admin/delete/$1';

// batch master
$route['admin/batch-master'] = 'batch_master/admin/index';
$route['admin/add-batch-master'] = 'batch_master/admin/add_batch_master';
$route['admin/edit-batch-master/(:any)'] = 'batch_master/admin/add_batch_master/$1';
$route['admin/batch-master-delete/(:any)'] = 'batch_master/admin/delete/$1';


$route['admin/eway-bill-details'] = "eway_bill/admin/setUserDetails";

$route['admin/dashboard-setting'] = 'accounts_settings/admin/dashboard_setting';

$route['admin/add-complex-unit'] = 'unit/admin/add_complex_unit';
$route['admin/edit-complex-unit/(:any)'] = 'unit/admin/add_complex_unit/$1';