<?php
require_once('config_statuses_inc.php');

$s_my_view_title_vv = 'Verzióváltók';

//$s_status_enum_string = '10:új,101:új hiba,102:új megrendelés,103:új támogatás,301:hiba javítás,201:hiba kérdés,701:hiba belső teszten,801:hiba belső teszt ok,901:hibajavítás teszten,981:hibajavítás élesre tehető,991:hibajavítás élesen,202:ajánlat,302:ajánlat kérés,402:ajánlat elfogadva,502:megrendelés végrehajtása,602:megrendelés kérdés,702:megrendelés belső teszten,802:megrendelés belső teszt ok,902:megrendelés tesztre átadva,982:megrendelés élesre tehető,992:megrendelés élesen,203:támogatás kérdés,303:támogatás folyamatban,903:támogatás tesztre átadva,703:támogatás belső teszten,803:támogatás belső teszt ok,983:támogatás élesre tehető,993:támogatás élesen,999:lezárva,'.JOKER.':joker,996:zombi';

$s_status_enum_string = '10:új,20:kérdés,25:ajánlat kérés,30:ajánlat,40:elfogadva,50:folyamatban,60:teszt,70:teszt&nbsp;ok,80:átadva,90:lezárva';

if ( SYS_COMPANY === 'kobe' ) {
    $s_status_enum_string = '10:új,20:kérdés,25:ajánlat kérés,30:ajánlat,40:elfogadva,50:folyamatban,60:teszt,70:teszt&nbsp;ok,80:átadva,85:élesre tehető,90:lezárva,99:joker';
}


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

$s_resolution_enum_string = '10:nyitott,20:kijavítva,21:storno,29:végrehajtva,30:újranyitva,40:reprodukálhatatlan,50:javíthatatlan,60:másolat,70:nem&nbsp;kell változtatni,80:felfüggesztve,90:nem lesz kijavítva';

$s_severity_enum_string = '10:kérés,50:hiba';

$s_priority_enum_string	= '30:normál,40:magas,60:azonnali';

$s_projection_enum_string = '10:támogatás,20:hiba,50:megrendelés';
$s_projection = 'Besorolás';

//$g_status_enum_string = '10:new,101:new error,102:new order,103:new support,201:error feedback,301:error execute,701:error inner test,801:error inner test ok,901:error test,991:fixed,202:proposal,302:ask proposal,402:accept proposal,502:execute order,602:order feedback,702:order inner test,802:order inner test ok,902:order test,992:order executed,203:support feedback,303:support execute,903:support test,993:support executed,999:closed';

/*
$s_new_error_bug_title = 'új hiba';
$s_new_error_bug_button = 'hiba bejelentése';
$s_error_feedback_bug_title = 'hiba kérdés';
$s_error_feedback_bug_button = 'kérdezz';
$s_error_execute_bug_title = 'hiba javítás';
$s_error_execute_bug_button = 'hiba javítása';
$s_error_inner_test_bug_title = 'belső teszt';
$s_error_inner_test_bug_button = 'átadás belső tesztre';
$s_error_inner_test_ok_bug_title = 'belső teszt ok';
$s_error_inner_test_ok_bug_button = 'belső teszt ok';
$s_error_test_bug_title = 'tesztre átadva';
$s_error_test_bug_button = 'tesztre átadás';
$s_error_confirmed_bug_title = 'élesre tehető';
$s_error_confirmed_bug_button = 'élesre tehető';
$s_fixed_bug_title = 'élesen végrehajtva';
$s_fixed_bug_button = 'élesen végrehajtás';

$s_ask_proposal_bug_title = 'ajánlat kérés';
$s_ask_proposal_bug_button = 'ajánlat kérése';
$s_new_order_bug_title = 'új megrendelés';
$s_new_order_bug_button = 'ajánlat kérése';
$s_proposal_bug_title = 'ajánlat';
$s_proposal_bug_button = 'ajánlat adása';
$s_order_feedback_bug_title = 'megrendelés kérdés';
$s_order_feedback_bug_button = 'kérdezz';
$s_accept_proposal_bug_title = 'ajánlat elfogadása';
$s_accept_proposal_bug_button = 'ajánlat elfogadása';
$s_execute_order_bug_title = 'megrendelés végrehajtás';
$s_execute_order_bug_button = 'megrendelés végrehajtása';
$s_order_inner_test_bug_title = 'belső teszt';
$s_order_inner_test_bug_button = 'átadás belső tesztre';
$s_order_inner_test_ok_bug_title = 'belső teszt ok';
$s_order_inner_test_ok_bug_button = 'belső teszt ok';
$s_order_test_bug_title = 'tesztre átadva';
$s_order_test_bug_button = 'tesztre átadás';
$s_order_confirmed_bug_title = 'élesre tehető';
$s_order_confirmed_bug_button = 'élesre tehető';
$s_order_executed_bug_title = 'megrendelés élesen';
$s_order_executed_bug_button = 'megrendelés élesre';

$s_new_support_bug_title = 'új támogatás';
$s_new_support_bug_button = 'támogatás kérése';
$s_support_feedback_bug_title = 'támogatás kérdés';
$s_support_feedback_bug_button = 'kérdezz';
$s_support_execute_bug_title = 'támogatás végrehajtás';
$s_support_execute_bug_button = 'támogatás végrehajtása';
$s_support_inner_test_bug_title = 'belső teszt';
$s_support_inner_test_bug_button = 'átadás belső tesztre';
$s_support_inner_test_ok_bug_title = 'belső teszt ok';
$s_support_inner_test_ok_bug_button = 'belső teszt ok';
$s_support_test_bug_title = 'tesztre átadva';
$s_support_test_bug_button = 'tesztre átadás';
$s_support_confirmed_bug_title = 'élesre tehető';
$s_support_confirmed_bug_button = 'élesre tehető';
$s_support_executed_bug_title = 'támogatás élesen';
$s_support_executed_bug_button = 'támogatás élesre';

$s_joker_bug_title = 'Joker';
$s_joker_bug_button = 'Joker';

$s_resolution_enum_string = '10:nyitott,20:kijavítva,21:storno,29:végrehajtva,30:újranyitva,40:reprodukálhatatlan,50:javíthatatlan,60:másolat,70:nem&nbsp;kell változtatni,80:felfüggesztve,90:nem lesz kijavítv';

if ( SYS_COMPANY === 'waberer' ) {
    $s_severity_enum_string = '10:kérés,50:hiba';
}

//$MANTIS_ERROR[ERROR_BUG_VALIDATE_FAILURE] = 'A bejelentés hibás (minden kötelező mező kitöltött?)';
*/

?>
