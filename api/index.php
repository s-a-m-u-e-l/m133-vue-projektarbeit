<?php
include("util.php");
include("config.php");
include("db.php");
include("application.php");

$status = connectDB(getValue('cfg_host'), getValue('cfg_user'), getValue('cfg_password'),getValue('cfg_db'));
if ( !$status['status'] ) {
    echo json_encode($status);
    exit();
}

setValue('post', json_decode(file_get_contents('php://input')));

$func = getValue('post')->id;
$flist = getValue('cfg_func_list');
if ( !in_array($func, $flist) ) $func = $flist[0];
echo json_encode( $func() );
?>