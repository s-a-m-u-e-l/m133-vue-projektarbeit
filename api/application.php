<?php
function person()
{
    switch (getValue('post')->func) {
        case 'save':
            $person = json_decode(json_encode(getValue('post')->person), true);
            $invalidPerson = isInvalidPerson($person); # if true returns a array of the invalid variables
            if (!$invalidPerson) {
                if (empty($person['pid'])) {
                    db_insert_person($person);
                    return formatMessage(true, 'saved', db_select_personen_order_id());
                } else {
                    db_update_person($person['pid'], $person);
                    return formatMessage(true, 'updated', db_select_personen_order_id());
                }
            } else {
                return formatMessage(false, $invalidPerson, $person);
            }
        case 'readList':
            return db_select_personen_order_id();
        case 'delete':
            $pid = getValue('post')->pid;
            db_delete_person($pid);
            return formatMessage(true, 'deleted', db_select_personen_order_id());
    }
    return null;
}


function isInvalidPerson($person)
{
    $invalidValues = [];
    if (!CheckName($person['name'])) {
        array_push($invalidValues, 'name');
    }
    if (!CheckName($person['vorname'])) {
        array_push($invalidValues, 'vorname');
    }
    if (!CheckEmpty($person['strasse'])) {
        array_push($invalidValues, 'strasse');
    }
    if (!CheckEmail($person['email'])) {
        array_push($invalidValues, 'email');
    }
    if (!CheckCleanNumberEmpty($person['tel_priv'], 10)) {
        array_push($invalidValues, 'tel_priv');
    }
    if (!CheckCleanNumberEmpty($person['tel_gesch'], 10)) {
        array_push($invalidValues, 'tel_gesch');
    }
    if (sizeof($invalidValues) === 0) {
        return false;
    } else {
        return $invalidValues;
    }
}

function isInvalidOrt($ort) {
    $invalidValues = [];
    if (!CheckPLZ($ort['plz'])) {
        array_push($invalidValues, 'plz');
    }
    if (!CheckOrt($ort['ort'])) {
        array_push($invalidValues, 'ort');
    }
    if (sizeof($invalidValues) === 0) {
        return false;
    } else {
        return $invalidValues;
    }
}

function ort()
{
    switch (getValue('post')->func) {
        case 'read':
            $ortList = db_select_ort();
            return $ortList;
        case 'delete':
            $oid = getValue('post')->oid;
            db_delete_ort($oid);
            return formatMessage(true, 'deleted', db_select_ort());
        case 'save':
            $ort = json_decode(json_encode(getValue('post')->ort), true);
            $invalidOrt = isInvalidOrt($ort);
            if (!$invalidOrt) {
                if (empty($ort['oid'])) {
                    db_insert_ort($ort);
                } else {
                    db_update_ort($ort);
                }
                return formatMessage(true, 'saved', db_select_ort());
            } else {
                return formatMessage(false, $invalidOrt, $ort);
            }
    }
    return null;
}

function land()
{
    switch (getValue('post')->func) {
        case 'readList':
            $landList = db_select_land();
            return $landList;
        case 'save':
            $land = json_decode(json_encode(getValue('post')->land), true);
            $invalidLand = isInvalidLand($land);
            if (!$invalidLand) {
                if (empty($land['lid'])) {
                    db_insert_land($land);
                } else {
                    db_update_land($land);
                }
                return formatMessage(true, 'saved', db_select_land());
            } else {
                return formatMessage(false, $invalidLand, $land);
            }
        case 'delete':
            $lid = getValue('post')->lid;
            db_delete_land($lid);
            return formatMessage(true, 'deleted', db_select_land());
    }
    return null;

}

function isInvalidLand($land)
{
    return false;
}

?>