<?php
function person()
{
    switch (getValue('post')->func) {
        case 'save':
            $person = json_decode(json_encode(getValue('post')->person), true);
            $invalidPerson = validatePerson($person); # if true returns a array of the invalid variables
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

function validatePerson($person)
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

function ort()
{
    switch (getValue('post')->func) {
        case 'read':
            $ortList = db_select_ort();
            return $ortList;
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
            $invalidLand = validateLand($land);
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

function validateLand($land)
{
    return false;
}

?>