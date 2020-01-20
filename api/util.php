<?php
$params = array();

function setValue( $key, $value ) {
    global $params;
    $params[$key] = $value;
}

function setValues( $list ) {
    global $params;
    if ( count($list )) {
        foreach ($list as $k => $v ) {
            $params[$k] = $v;
        }
    }
}

function getValue( $key ) {
    global $params;
    return $params[$key];
}

function getHtmlValue( $key ) {
    global $params;
    return htmlentities($params[$key], ENT_QUOTES | ENT_HTML5 | ENT_DISALLOWED | ENT_SUBSTITUTE, 'ISO-8859-1');
}

function formatMessage($status=true, $text='success', $data=null) {
    $message = array();
    $message['status'] = $status;
    $message['message'] = $text;
    $message['data'] = $data;
    return $message;
}

function connectDB($host, $user, $password, $db ) {
    @$db = mysqli_connect($host, $user, $password, $db);
    if ( mysqli_connect_errno($db) ) {
        return formatMessage( false, 'connection to MySQL failed' );
    }
    setValue('cfg_db', $db);
    return formatMessage();
}

function sqlSelect($sql) {
 	$result = mysqli_query(getValue('cfg_db'), $sql);
 	if ( !$result ) die("Fehler: ".mysqli_error(getValue(cfg_db)));
    $data = array();
 	while ( $row=mysqli_fetch_assoc($result) ) $data[]=$row;
    return $data;
}

function sqlQuery($sql) {
 	$result = mysqli_query(getValue('cfg_db'), $sql);
 	if ( !$result ) die(mysqli_error(getValue(cfg_db))."<pre>".$sql."</pre>");
}


function redirect( $id="" ) {
    if (!empty($id)) $id="?id=$id";
    header("Location: ".$_SERVER['PHP_SELF'].$id);
    exit();
}

function CheckEmpty( $value, $minlength=Null ) {
    if (empty($value)) return false;
    if ( $minlength != Null && strlen($value) < $minlength ) return false;
    else return true;
}

function CheckEmail( $value, $empty='N' ) {
    $pattern_email = '/^[^@\s<&>]+@([-a-z0-9]+\.)+[a-z]{2,}$/i';
    if ($empty=='Y' && empty($value)) return true;
    if ( preg_match($pattern_email, $value) ) return true;
    else return false;
}

function CheckName( $value, $empty='N' ) {
    $pattern_name = '/^[a-zA-Z \-]{2,}$/';
    if ($empty=='Y' && empty($value)) return true;
    if ( preg_match($pattern_name, $value) ) return true;
    else return false;
}

function CheckOrt( $value, $empty='N' ) {
    $pattern_ort = '/^[a-zA-Z \-\.]{2,}$/';
    if ($empty=='Y' && empty($value)) return true;
    if (empty($value)) return false;
    if ( preg_match($pattern_ort, $value) ) return true;
    else return false;
}

function CheckPLZ($value, $empty='N') {
    $pattern_plz = '/^[0-9]{4,}$/';
    if ($empty=='Y' && empty($value)) return true;
    if (empty($value)) return false;
    if ( preg_match($pattern_plz, $value) ) return true;
    else return false;
}

function isNumber( $value ) {
    if ( !is_numeric($value) ) return false;
    return true;
}

function CheckNumber( $value ) {
    if ( !isNumber($value) ) return false;
    else return true;
}

function isCleanNumber( $value ) {
    if ( !is_numeric($value) ) return false;
    $pattern_number = '/\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})/';
    if ( preg_match($pattern_number, $value) ) return true;
    else return false;
    return true;
}

function CheckCleanNumberEmpty( $value, $minlength=0) {
    if ( empty($value) ) return true;
    if ( !isCleanNumber($value) || strlen($value) < $minlength ) return false;
    else return true;
}

?>