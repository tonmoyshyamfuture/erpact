<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('DBPREFIX','pb_');
define('PAGESUFFIX','aspx');

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


//if(ENVIRONMENT == 'production') {
//    define('SAAS_URL','https://login.act-erp.com/');
//    define('MAIN_DB','PRO_ACTERP_DB_MAIN');
//    define('SAAS_DB_NAME','PRO_ACTERP_DB_SAAS');
//    define('SUB_DB_PREFIX','PRO_ACTERP_DB_CUST_');
//    define('DB_HOSTNAME','192.168.3.229');
//    define('DB_USERNAME','pro_mysql-db');
//    define('DB_PASSWORD','B%$*VBc12()');
//    define('ACCOUNT_URL','https://access.myact-erp.com/');
//}else{
//    define('SAAS_URL','http://login.act-erp.local/');
//    define('MAIN_DB','PRO_ACTERP_DB_MAIN');
//    define('SAAS_DB_NAME','PRO_ACTERP_DB_SAAS');
//    define('SUB_DB_PREFIX','PRO_ACTERP_DB_CUST_');
//    define('DB_HOSTNAME','localhost');
//    define('DB_USERNAME','root');
//    define('DB_PASSWORD','');
//    define('ACCOUNT_URL','http://access.myact-erp.local/');
//
//}
    if (defined('ENVIRONMENT')){

        switch (ENVIRONMENT){


            case 'beta':
                define('SAAS_URL','https://login.beta-myact-erp.in/');
                define('MAIN_DB','betamyac_PRO_ACTERP_DB_MAIN');
                define('SAAS_DB_NAME','betamyac_PRO_ACTERP_DB_SAAS');
                define('SUB_DB_PREFIX','betamyac_PRO_ACTERP_DB_CUST_');
                define('DB_HOSTNAME','localhost');
                define('DB_USERNAME','betamyac_beta');
                define('DB_PASSWORD','Beta123^&*()');
                define('ACCOUNT_URL','https://access.beta-myact-erp.in/');
                break;

            default :
                define('SAAS_URL','http://localhost/act-react/admin/');
                define('MAIN_DB','LOCAL_MYACTERP_DB_SAAS_ACCESS');
                define('SAAS_DB_NAME','DEV_LAB6_DB_P008_CARTFACE');
                define('SUB_DB_PREFIX','PRO_ACTERP_DB_CUST_');
                define('DB_HOSTNAME','172.16.16.35');
                define('DB_USERNAME','web-db_dev-6');
                define('DB_PASSWORD','ACBU%$@CBN*()');
                define('ACCOUNT_URL','http://localhost/act-react/admin/');
                break;
        }
    }


/* End of file constants.php */
/* Location: ./application/config/constants.php */
