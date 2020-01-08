
<?php
function person() {
    switch ( getValue('post')->func ) {
        case 'speichern':

            db_insert_person( $values );
            return 'appiii workss!!!';
    }
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