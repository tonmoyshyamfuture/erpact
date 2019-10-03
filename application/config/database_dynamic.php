<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 @session_start();
// if (isset($_GET['accountcallback']) && isset($_GET['value'])) {
//     $callback = $_GET['accountcallback'];
// //    $conn = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, "PRO_ACTERP_DB_SAAS");
//     $conn = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, SAAS_DB_NAME);
//
// // Check connection
//     if (mysqli_connect_errno()) {
//         header('Location: ' . SAAS_URL);
//     }
//     $value=  trim($_GET['value']);
//     $val_arr=  explode('_', $value);
//     $company_id=$val_arr[0];
//     $user_id=$val_arr[1];
//     $sql = "SELECT comp_db_name FROM saas_company_details WHERE id = " . $company_id;
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         // output data of each row
//         while ($row = $result->fetch_assoc()) {
            // $_SESSION['dbname'] = $row['comp_db_name'];
            $_SESSION['dbname'] = 'DEV_LAB6_DB_ACCOUNT';
    //     }
    // } else {
    //    $data_msg['res'] = 'error';
    //    $data_msg['message'] = 'You have not access this company' ;
    //    echo $callback . '(' . json_encode($data_msg) . ')';die();
    // }
    // $conn->close();

// }


/* End of file database_dynamic.php */
/* Location: ./application/config/database_dynamic.php */
