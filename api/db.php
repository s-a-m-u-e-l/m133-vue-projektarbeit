<?php
/*
 *  @autor Daniel Mosimann
 *  @version Juni 2019
 *
 *  Dieses Modul beinhaltet s�mtliche Datenbankfunktionen.
 *  Die Funktionen formulieren die SQL-Anweisungen und rufen dann die Funktionen
 *  sqlQuery() und sqlSelect() aus dem Modul basic_functions.php auf.
 *
 */


/*
 * Datenbankfunktionen zur Tabelle land
 */
function db_select_land() {
	$sql = "select * from land order by land";
	return sqlSelect( $sql );
}

function db_select_land_search( $land ) {
	$sql = "select * from land where land like '%".$land."%' order by land";
	return sqlSelect( $sql );
}

function db_select_land_lid( $lid ) {
	$sql = "select * from land where lid='$lid'";
	return sqlSelect( $sql );
}

function db_delete_land( $lid ) {
	$sql = "delete from land where lid='$lid'";
        sqlQuery($sql);
}

function db_insert_land() {
	$sql = "insert into land (land)
		values ('".$_REQUEST['land']."')";
        sqlQuery($sql);
}

function db_update_land() {
	$sql = "update land set land='".$_REQUEST['land']."'
		where lid = '".$_REQUEST['lid']."'";
        sqlQuery($sql);
}

/*
 * Datenbankfunktionen zur Tabelle ort
 */
function db_select_ort() {
	$sql = "select * from ort order by ort, plz";
	return sqlSelect( $sql );
}

function db_select_ort_oid( $oid ) {
	$sql = "select * from ort where oid='$oid'";
	return sqlSelect( $sql );
}

function db_insert_ort() {
	$sql = "insert into ort (plz, ort)
		values ('".$_REQUEST['plz']."','".$_REQUEST['ort']."')";
        sqlQuery($sql);
}

function db_update_ort() {
	$sql = "update ort set plz='".$_REQUEST['plz']."', ort='".$_REQUEST['ort']."'
		where oid = '".$_REQUEST['oid']."'";
        sqlQuery($sql);
}

function db_delete_ort( $oid ) {
	$sql = "delete from ort where oid='$oid'";
        sqlQuery($sql);
}

/*
 * Datenbankfunktionen zur Tabelle personen
 */
function db_select_personen() {
	$sql = "select * from personen
		left join ort on personen.oid = ort.oid
		left join land on personen.lid = land.lid
		order by name, vorname";
	return sqlSelect( $sql );
}

/**
 * Nach den gew�nschten Personen suchen
 *
 * @param  $values     Associativer Array mit den Suchkriterien
 * @param  $pid        Identifikation der selektierten Person
 * @param  $index      Index der selektierten Person
 */
function db_select_personen_search( $values ) {
	$sql = "select * from personen
		left join ort on personen.oid = ort.oid
		left join land on personen.lid = land.lid
		where name like '".$values['name']."%' and vorname like '".$values['vorname']."%' order by name, vorname";
	return sqlSelect( $sql );
}

/**
 * Spezifische Person lesen
 *
 * @param   $pid    Identifikation der Person
 */
function db_select_person_pid( $pid ) {
	$sql = "select * from personen
	left join ort on personen.oid = ort.oid
	left join land on personen.lid = land.lid
	where pid = '$pid'";
	return sqlSelect( $sql );
}

/**
 * Person in DB einf�gen
 * @param  $values  Assoziativer Array mit den Werten
 */
function db_insert_person($values) {
	if ( empty($values['oid']) ) $oid = "Null";
	else $oid = "'".$values['oid']."'";
	if ( empty($values['lid']) ) $lid = "Null";
	else $lid = "'".$values['lid']."'";

	$sql = "insert into personen (name, vorname, strasse, oid, email, tel_priv, tel_gesch, lid)
		values ('".$values['name']."','".$values['vorname']."','".$values['strasse']."',".$oid.",'".
		$values['email']."','".$values['tel_priv']."','".$values['tel_gesch']."',".$lid.")";
        sqlQuery($sql);
}

/**
 * Eine Person in der DB aktualisieren
 * @param $pid      Identifikation der Person
 * @param $values   Assoziativer Array mit den Werten
 */
function db_update_person( $pid, $values ) {
	if ( empty($values['oid']) ) $oid = "Null";
	else $oid = "'".$values['oid']."'";
	if ( empty($values['lid']) ) $lid = "Null";
	else $lid = "'".$values['lid']."'";

	$sql = "update personen set name='".$values['name']."',
                                vorname='".$values['vorname']."',
				strasse='".$values['strasse']."',
				oid=".$oid.",
				email='".$values['email']."',
				tel_priv='".$values['tel_priv']."',
				tel_gesch='".$values['tel_gesch']."',
				lid=".$lid. "
			where pid = '".$pid."'";
        sqlQuery($sql);
}

/**
 * Eine Person in der DB l�schen
 * @param $pid  Identifikation der Person
 */
function db_delete_person( $pid ) {
	$sql = "delete from personen where pid='$pid'";
        sqlQuery($sql);
}

?>