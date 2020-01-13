
<?php
function person() {
    switch ( getValue('post')->func ) {
        case 'speichern':
            $person = json_decode(json_encode(getValue('post')->person), true);
            $invalidPerson = validatePerson($person); # if true returns a array of the invalid variables
            if (!$invalidPerson) {
                db_insert_person($person);
                return formatMessage(true, 'saved', db_select_personen_order_id());
            } else {
                return formatMessage(false, $invalidPerson, $person);
            }
        case 'readList':
            return db_select_personen_order_id();
    }
    return null;
}

function validatePerson($person) {
    return false;
}

function ort() {
    switch ( getValue('post')->func ) {
        case 'read':
            $ortList = db_select_ort();
            return $ortList;
    }
    return null;
}

function land() {
    switch ( getValue('post')->func ) {
        case 'read':
            $landList = db_select_land();
            return $landList;
        case 'speichern':
            $land = json_decode(json_decode(getValue('post')->land),true);
            $invalidLand = validateLand($land);
            if(!$invalidLand){
                db_insert_land($land);
                return formatMessage(true, 'saved', db_select_land_lid());
            } else {
                return formatMessage(false, $invalidLand, $land);
            }
             case 'readList':
            return db_select_land_lid();
    }
    return null;

}
function validateLand($land) {
    return false;
}

function personAlt() {
    setValue('phpmodule', $_SERVER['SCRIPT_NAME']."?id=".__FUNCTION__);

    if ( isset($_REQUEST['suchen']) ) {
        session_save();
    } else if ( isset($_REQUEST['neu']) ) {
        session_clear();
        redirect(__FUNCTION__);
        exit();
    } else if ( isset($_REQUEST['speichern']) ) {
        if ( checkInputPerson() ) {
            if ( empty($_REQUEST['pid']) ) db_insert_person($_REQUEST);
            else {
                db_update_person( $_REQUEST['pid'], $_REQUEST );
            }
        } else {
            setValues($_REQUEST);
        }
    } else if ( isset($_REQUEST['loeschen']) ) {
        db_delete_person( $_REQUEST['pid'] );
    }

    if ( session_check() ) {
        $data = db_select_personen_search( $_SESSION );
        $sdata = $data[0];
        if ( count($data) ) {
            setValue('data', $data);
            if (isset($_REQUEST['next'])) {
                $sdata = getNextPerson($data,  $_REQUEST['pid']);
            } else if (isset($_REQUEST['previous'])) {
                $sdata = getPreviousPerson($data, $_REQUEST['pid']);
            }
            setValues($sdata);
        }
    }

    setValue('orte', db_select_ort());
    setValue('droport', dropOrt( $sdata['oid'] ));
    setValue('dropland', dropLand( $sdata['lid'] ));
    return runTemplate(getValue('cfg_template_path')."personen_verwaltung_1.htm.php");
}
?>