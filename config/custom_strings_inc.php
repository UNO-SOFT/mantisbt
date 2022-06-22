<?php
require_once('config_statuses_inc.php');

$s_my_view_title_vv = 'Verzióváltók';

if( SYS_COMPANY == 'unosoft' ) {
    $s_status_enum_string = '10:új,20:esedékes,50:folyamatban,80:nyugvó,90:lezárt';

    $s_resolution_enum_string = '10:nyitott';
    $s_severity = 'Besorolás';
    $s_severity_enum_string = '10:eseti,50:ismétlődő';
    $s_projection_enum_string = '';
} else {
    //$s_status_enum_string = '10:új,101:új hiba,102:új megrendelés,103:új támogatás,301:hiba javítás,201:hiba kérdés,701:hiba belső teszten,801:hiba belső teszt ok,901:hibajavítás teszten,981:hibajavítás élesre tehető,991:hibajavítás élesen,202:ajánlat,302:ajánlat kérés,402:ajánlat elfogadva,502:megrendelés végrehajtása,602:megrendelés kérdés,702:megrendelés belső teszten,802:megrendelés belső teszt ok,902:megrendelés tesztre átadva,982:megrendelés élesre tehető,992:megrendelés élesen,203:támogatás kérdés,303:támogatás folyamatban,903:támogatás tesztre átadva,703:támogatás belső teszten,803:támogatás belső teszt ok,983:támogatás élesre tehető,993:támogatás élesen,999:lezárva,'.JOKER.':joker,996:zombi';

    $s_status_enum_string = '10:új,20:kérdés,25:ajánlat kérés,27:tisztázás,30:ajánlat,40:elfogadva,50:folyamatban,60:teszt,70:teszt&nbsp;ok,80:átadva,90:lezárva';

    if ( SYS_COMPANY === 'kobe' ) {
        $s_status_enum_string = '10:új,20:kérdés,25:ajánlat kérés,27:tisztázás,30:ajánlat,40:elfogadva,50:folyamatban,60:teszt,70:teszt&nbsp;ok,80:átadva,85:élesre tehető,90:lezárva,99:joker';
    }
    $s_resolution_enum_string = '10:nyitott,20:kijavítva,21:storno,29:végrehajtva,30:újranyitva,40:reprodukálhatatlan,50:javíthatatlan,60:másolat,70:nem&nbsp;kell változtatni,80:felfüggesztve,90:nem lesz kijavítva';
    $s_severity_enum_string = '10:kérés,50:hiba';
    $s_projection = 'Besorolás';
    $s_projection_enum_string = '10:támogatás,20:hiba,50:megrendelés,52:konzultáció,54:oktatás';
}

$s_steps_to_reproduce = 'Feladat';
$s_steps_to_reproduce_updated = 'Feladat frissítve';
$s_email_steps_to_reproduce = 'Feladat';

$s_new_bug_title = 'új';
$s_new_bug_button = 'Új';
$s_feedback_bug_bug_title = 'kérdés';
$s_feedback_bug_button = 'Kérdés';
$s_acknowledged_bug_title = 'elfogadva';
$s_acknowledged_bug_button = 'Elfogadás';
$s_assigned_bug_title = 'folyamatban';
$s_assigned_bug_button = 'Hozzárendelés';
$s_ask_proposal_bug_title = 'ajánlat kérés';
$s_ask_proposal_bug_button = 'Ajánlat kérés';
$s_proposal_feedback_bug_title = 'tisztázás kérés';
$s_proposal_feedback_bug_button = 'Tisztázás kérés';
$s_proposal_bug_title = 'ajánlat';
$s_proposal_bug_button = 'Ajánlat adása';
$s_test_bug_title = 'teszten';
$s_test_bug_button = 'Tesztre átadás';
$s_test_ok_bug_title = 'teszt OK';
$s_test_ok_bug_button = 'Megfelelelt';
$s_resolved_bug_title = 'átadva';
$s_resolved_bug_button = 'Átadás';
$s_ship_bug_title = 'élesre tehető';
$s_ship_bug_button = 'Élesre!';
$s_closed_bug_title = 'lezárva';
$s_closed_bug_button = 'Lezárás';
$s_joker_bug_title = 'joker';
$s_joker_bug_button = 'Joker';

$s_priority_enum_string	= '30:normál,40:magas,60:azonnali';

?>
