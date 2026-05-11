<?php
require_once('config_statuses_inc.php');

$s_my_view_title_vv = 'Verzióváltók';
$s_my_view_title_owned = 'Saját ügyek';

$s_time_tracking = 'Időráfordítás';
$s_time_tracking_get_info_button = 'Időráfordítási információk megtekintése';
$s_time_tracking_billing_link = 'Időráfordítás';

if( SYS_COMPANY == 'unosoft' ) {
    $s_status_enum_string = '10:új,20:esedékes,50:folyamatban,80:nyugvó,90:lezárt';

    $s_resolution_enum_string = '10:nyitott';
    $s_severity_enum_string = '10:eseti,50:ismétlődő';
    $s_projection_enum_string = '';
} else {
    $s_status_enum_string = '10:új,25:ajánlat&nbsp;kérés,27:tisztázás,30:ajánlat,40:elfogadva,50:folyamatban,55:kérdés,60:teszt,70:teszt&nbsp;ok,80:átadva,90:lezárva';

    $s_access_levels_enum_string = '10:néző,25:bejelentő,40:frissítő,55:fejlesztő,60:szervező,70:menedzser,90:adminisztrátor';

  	if( SYS_COMPANY === 'aegon' || SYS_COMPANY === 'alfa' ) {
      $s_status_enum_string = str_replace(',30:', ',29:ajánlat&nbsp;adható,30:', $s_status_enum_string);
    } elseif ( SYS_COMPANY === 'kobe' ) {
      $s_status_enum_string = str_replace(',90:', ',85:élesre&nbsp;tehető,90:', $s_status_enum_string);
    }

    $s_resolution_enum_string = '10:nyitott,20:kijavítva,21:storno,29:végrehajtva,30:újranyitva,40:reprodukálhatatlan,50:javíthatatlan,60:másolat,70:nem&nbsp;kell változtatni,80:felfüggesztve,90:nem lesz kijavítva';
// $s_severity_enum_string = '10:kérés,20:triviális,30:szöveghiba,40:zavar,50:apró hiba,60:nagyobb hiba,70:összeomlás,80:akadály';
    // $s_severity_enum_string = '10:kérés,50:hiba';
    $s_projection = 'Besorolás';
    $s_projection_enum_string = '10:támogatás,20:hiba,50:megrendelés,51:SPL-Fejlesztés,52:Lekérdezés,53:adatmódosítás,54:oktatás,55:tanácsadás';
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
$s_assigned_feedback_bug_title = 'kérdés';
$s_assigned_feedback_bug_button = 'Kérdés';
$s_ask_proposal_bug_title = 'ajánlat kérés';
$s_ask_proposal_bug_button = 'Ajánlat kérés';
$s_proposal_feedback_bug_title = 'tisztázás kérés';
$s_proposal_feedback_bug_button = 'Tisztázás kérés';
$s_to_be_proposed_bug_title = 'ajánlat adható';
$s_to_be_proposed_bug_button = 'Ajánlat adható';
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
if( SYS_COMPANY == 'kobe' ) {
    $s_priority_enum_string	= '20:alacsony,30:normál,40:magas,60:azonnali';
}

?>
