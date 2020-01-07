<?php
function db_insert_kontakt( $kontakt ) {
    $sql = "insert into kontakte (name, vorname, strasse, plz, ort, email, tpriv, tgesch)
            values ('".addslashes($kontakt->name)."','".addslashes($kontakt->vorname)."','".addslashes($kontakt->strasse)."','".
            $kontakt->plz."','".addslashes($kontakt->ort)."','".
                       addslashes($kontakt->email)."','".addslashes($kontakt->tpriv)."','".addslashes($kontakt->tgesch)."')";
    sqlQuery($sql);
}

function db_select_kontakt() {
    return sqlSelect("select * from kontakte order by name, vorname");
}

function db_delete_kontakt( $kid ) {
    if ( isCleanNumber($kid) ) sqlQuery("delete from kontakte where kid='$kid'");
}

?>