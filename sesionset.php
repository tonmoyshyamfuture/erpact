<?php
@session_start();



echo '123dd ::: ';
$dbname = ' DB not found ';
if($_REQUEST['compdb']){
    $dbname = $_REQUEST['compdb'];    
    $_SESSION['dbname'] = $dbname;
}
echo $dbname;

?>



