<?php
define('U_NEW', 10);  // uj
define('U_FEEDBACK', 20);  // kerdes
define('U_ASK_PROPOSAL', 25);  // ajanlat keres
define('U_PROPOSAL', 30);  // ajanlat
define('U_ACKNOWLEDGED', 40);  // elfogadva
define('U_ASSIGNED', 50);  // folyamatban
define('U_TEST', 60);  // belso teszt
define('U_TEST_OK', 70);  // belso teszt ok
define('U_RESOLVED', 80);  // atadva
define('U_CLOSED', 90);  // lezarva
define('U_STORNO', 100);

require_once(dirname(__FILE__) . '/../config_defaults_inc.php');

$g_status_enum_workflow[U_NEW] = '20:feedback,25:ask_proposal,50:assigned,90:closed';
$g_status_enum_workflow[U_FEEDBACK] = '25:ask_proposal,50:assigned,90:closed';
$g_status_enum_workflow[U_ASK_PROPOSAL] = '30:proposal,20:feedback,90:closed';
$g_status_enum_workflow[U_PROPOSAL] = '40:acknowledged,30:proposal,90:closed';
$g_status_enum_workflow[U_ACKNOWLEDGED] = '50:assigned,90:closed';
$g_status_enum_workflow[U_ASSIGNED] = '20:feedback,60:test,80:resolved';
$g_status_enum_workflow[U_TEST] = '50:assigned,70:test_ok';
$g_status_enum_workflow[U_TEST_OK] = '80:resolved';
$g_status_enum_workflow[U_RESOLVED] = '50:assigned,90:closed';
$g_status_enum_workflow[U_CLOSED] = '';

$g_status_enum_string = '10:new,20:feedback,25:ask_proposal,30:proposal,40:acknowledged,50:assigned,60:test,70:test_ok,80:resolved,90:closed';

$g_set_status_threshold = array (
	U_NEW => REPORTER,
	U_FEEDBACK => UPDATER,
	U_ASK_PROPOSAL => REPORTER,
	U_PROPOSAL => MANAGER,
	U_ACKNOWLEDGED => REPORTER,
	U_ASSIGNED => UPDATER,
	U_TEST => DEVELOPER,
	U_TEST_OK => UPDATER,
	U_RESOLVED => DEVELOPER,
	U_CLOSED => DEVELOPER	
);

$g_status_colors = array(
	'new' => '#FF9999',
	'feedback' => '#FDA7FF',
	'ask_proposal' => '#FFED75',
	'proposal' => '#F9FFA7',
	'acknowledged' => '#ABEDEC',
	'assigned' => '#ABEDEC',
	'test' => '#CAFD8A',
	'test_ok' => '#9CE964',
	'resolved' => '#D9D9D9',
	'closed' => '#FFFFFF',
);

/*

 digraph Mantis {
	U_NEW [label="új" color="green"];
	U_FEEDBACK [label="kérdés" color="red" shape="rectangle"];
	U_ASSIGNED [label="folyamatban"];
	U_RESOLVED [label="átadva" color="red" shape="rectangle"];
	U_CLOSED [label="lezárva"];
	U_TEST [label="belső teszt"];
	U_TEST_OK [label="belső teszt ok"];
	U_ASK_PROPOSAL [label="ajánlat kérés"];
	U_PROPOSAL [label="ajánlat" color="red" shape="rectangle"];
	U_ACCEPTED [label="elfogadva" color="green"];

	U_NEW -> U_ASK_PROPOSAL -> U_PROPOSAL -> U_ACCEPTED -> U_ASSIGNED;
	U_ASK_PROPOSAL -> U_FEEDBACK -> U_ASK_PROPOSAL;
    U_PROPOSAL -> U_ASK_PROPOSAL;
	U_FEEDBACK -> U_PROPOSAL -> U_CLOSED;

	U_NEW -> U_FEEDBACK -> U_ASSIGNED -> U_FEEDBACK -> U_CLOSED;
	U_NEW -> U_ASSIGNED -> U_RESOLVED -> U_CLOSED [weight=10];
	U_ASSIGNED -> U_TEST -> U_TEST_OK -> U_RESOLVED -> U_ASSIGNED;
	U_TEST -> U_ASSIGNED;
}

BEGIN;

CREATE TABLE mantis_bug_table_20210307 AS SELECT * FROM mantis_bug_table;

UPDATE mantis_bug_table A
    SET projection = (SELECT CASE C.value WHEN 'hibajavítás' THEN 20  WHEN 'Hibabejelentés' THEN 20
    WHEN 'megrendelés' THEN 50 WHEN 'Fejlesztés' THEN 50
    WHEN 'SPL-Fejlesztés' THEN 51 WHEN 'Lekérdezés' THEN 52 WHEN 'SPOOLSYS' THEN 51 WHEN 'oktatás' THEN 53 
    WHEN 'konzultáció' THEN 55 WHEN 'Konzultáció' THEN 55
	ELSE 10 END 
	FROM mantis_custom_field_string_table C, mantis_custom_field_table B 
	WHERE C.bug_id = A.id AND C.field_id = B.id AND B.name LIKE 't_pus')
	WHERE EXISTS (SELECT 1 FROM mantis_custom_field_string_table C, mantis_custom_field_table B 
          WHERE C.bug_id = A.id AND C.value <> '' AND C.field_id = B.id AND B.name LIKE 't_pus');

UPDATE mantis_bug_table
  SET projection = CASE MOD(status, 10) WHEN 1 THEN 20 WHEN 2 THEN 50 ELSE 10 END
  WHERE status >= 100 AND MOD(status, 10) BETWEEN 1 AND 3;

UPDATE mantis_bug_table A
 SET projection = CASE (SELECT MOD(MAX(TO_NUMBER(new_value,'999')), 10) FROM mantis_bug_history_table  B
WHERE field_name = 'status' AND B.bug_id = A.id AND TO_NUMBER(new_Value, '999')<999)
   WHEN 2 THEN 50 ELSE 10 END
  WHERE EXISTS (SELECT 1 FROM mantis_bug_history_table B
WHERE field_name = 'status' AND B.bug_id = A.id AND TO_NUMBER(new_Value, '999')<999) AND
        status >= 100 AND projection = 10;

UPDATE mantis_bug_table
 SET status = CASE status
 WHEN 666 THEN 50
 WHEN 101 THEN 10 WHEN 102 THEN 10 WHEN 103 THEN 10
 WHEN 201 THEN 50 WHEN 202 THEN 30 WHEN 203 THEN 50
 WHEN 301 THEN 50 WHEN 302 THEN 25 WHEN 303 THEN 50
 WHEN 401 THEN 50 WHEN 402 THEN 40 WHEN 403 THEN 50
 WHEN 501 THEN 50 WHEN 502 THEN 50 WHEN 503 THEN 50
 WHEN 701 THEN 60 WHEN 702 THEN 60 WHEN 703 THEN 60
 WHEN 801 THEN 70 WHEN 802 THEN 70 WHEN 803 THEN 70
 WHEN 901 THEN 60 WHEN 902 THEN 60 WHEN 903 THEN 60
 WHEN 981 THEN 50 WHEN 982 THEN 50 WHEN 983 THEN 50
 WHEN 991 THEN 80 WHEN 992 THEN 80 WHEN 993 THEN 80
 WHEN 999 THEN 90
 ELSE 50
 END
 WHERE status >= 100;


UPDATE mantis_bug_history_table
 SET old_value = CASE old_value
 WHEN '666' THEN '50'
 WHEN '101' THEN '10' WHEN '102' THEN '10' WHEN '103' THEN '10'
 WHEN '201' THEN '50' WHEN '202' THEN '30' WHEN '203' THEN '50'
 WHEN '301' THEN '50' WHEN '302' THEN '25' WHEN '303' THEN '50'
 WHEN '401' THEN '50' WHEN '402' THEN '40' WHEN '403' THEN '50'
 WHEN '501' THEN '50' WHEN '502' THEN '50' WHEN '503' THEN '50'
 WHEN '701' THEN '60' WHEN '702' THEN '60' WHEN '703' THEN '60'
 WHEN '801' THEN '70' WHEN '802' THEN '70' WHEN '803' THEN '70'
 WHEN '901' THEN '60' WHEN '902' THEN '60' WHEN '903' THEN '60'
 WHEN '981' THEN '50' WHEN '982' THEN '50' WHEN '983' THEN '50'
 WHEN '991' THEN '80' WHEN '992' THEN '80' WHEN '993' THEN '80'
 WHEN '999' THEN '90'
 ELSE old_value
 END
 WHERE field_name = 'status' AND old_value >= '100' AND old_value IS NOT NULL;

UPDATE mantis_bug_history_table
 SET new_value = CASE new_value
 WHEN '666' THEN '50'
 WHEN '101' THEN '10' WHEN '102' THEN '10' WHEN '103' THEN '10'
 WHEN '201' THEN '50' WHEN '202' THEN '30' WHEN '203' THEN '50'
 WHEN '301' THEN '50' WHEN '302' THEN '25' WHEN '303' THEN '50'
 WHEN '401' THEN '50' WHEN '402' THEN '40' WHEN '403' THEN '50'
 WHEN '501' THEN '50' WHEN '502' THEN '50' WHEN '503' THEN '50'
 WHEN '701' THEN '60' WHEN '702' THEN '60' WHEN '703' THEN '60'
 WHEN '801' THEN '70' WHEN '802' THEN '70' WHEN '803' THEN '70'
 WHEN '901' THEN '60' WHEN '902' THEN '60' WHEN '903' THEN '60'
 WHEN '981' THEN '50' WHEN '982' THEN '50' WHEN '983' THEN '50'
 WHEN '991' THEN '80' WHEN '992' THEN '80' WHEN '993' THEN '80'
 WHEN '999' THEN '90'
 ELSE old_value
 END
 WHERE field_name = 'status' AND new_value >= '100' AND new_value IS NOT NULL;


*/

//$g_reproducibility_enum_string = '10:always,30:sometimes,50:random,70:have not tried,90:unable to duplicate,100:N/A';
//$g_status_enum_string = '10:new,20:feedback,30:acknowledged,40:confirmed,50:assigned,80:resolved,90:closed';

$g_enable_projection = ON;
//$g_projection_enum_string = '10:none,30:tweak,50:minor fix,70:major rework,90:redesign';
$g_projection_enum_string = '10:support,20:error,50:order';

//$g_resolution_enum_string = '10:open,20:fixed,30:reopened,40:unable to duplicate,50:not fixable,60:duplicate,70:not a bug,80:suspended,90:wont fix';
$g_resolution_enum_string = '10:open,20:fixed,21:storno,29:executed,30:reopened,40:unable to duplicate,50:not fixable,60:duplicate,70:not a bug,80:suspended,90:wont fix';

//$g_severity_enum_string = '10:feature,20:trivial,30:text,40:tweak,50:minor,60:major,70:crash,80:block';
$g_severity_enum_string	= '10:feature,50:error';

//$g_priority_enum_string = '10:none,20:low,30:normal,40:high,50:urgent,60:immediate';
$g_priority_enum_string	= '30:normal,40:high,60:immediate';


$g_default_notify_flags = array(
	'reporter' => ON,
	'handler' => OFF,
	'monitor' => ON,
	'bugnotes' => OFF,
	//'threshold_min' => REPORTER,
	//'threshold_max' => REPORTER,
);
if (! $g_notify_flags ) $g_notify_flags = $g_default_notify_flags;
$g_notify_flags = array_merge_recursive( $g_notify_flags, array (
	'reopened' => array (
		'reporter' => ON,
		'handler' => ON,
		'monitor' => ON,
	),
	'deleted' => array (
		'reporter' => ON,
		'handler' => ON,
		'monitor' => ON,
	),
	'bugnote' => array (
		'reporter' => ON,
		'monitor' => ON,
		'handler' => ON,
		//'threshold_max' => REPORTER,
	),
) );

$g_auto_set_status_to_assigned = OFF;

$g_allow_reporter_reopen = OFF;
$g_allow_reporter_close = ON;

$g_use_persistent_connections = ON;

$g_relationship_graph_enable = ON;

$g_default_email_on_new = OFF;
$g_default_email_on_assigned = OFF;
$g_default_email_on_feedback = OFF;
$g_default_email_on_resolved = OFF;
$g_default_email_on_closed = OFF;
$g_default_email_on_reopened = OFF;
$g_default_email_on_bugnote = ON;

$g_hide_status_default = CLOSED;
$g_hide_status = CLOSED;
$g_bug_resolved_status_threshold = U_CLOSED;
$g_bug_readonly_status_threshold = U_CLOSED;
$g_update_readonly_bug_threshold = DEVELOPER;
$g_private_bug_threshold = UPDATER;
$g_private_bugnote_threshold = UPDATER;
$g_update_bug_status_threshold = REPORTER;
$g_set_view_status_threshold = UPDATER;
$g_change_view_status_threshold = UPDATER;

$g_bug_reopen_status = U_ASSIGNED;
$g_bug_closed_status_threshold = U_CLOSED;

$g_bug_status_modulo = 1;
$g_bug_status_modulo_permanent = ON;

require_once(dirname(__FILE__) . '/custom_strings_inc.php');


/*
define('JOKER', 666);

define('X_ERR', 101);
define('X_ORD', 102);
define('X_SUP', 103);
$g_status_enum_workflow[NEW_] =
	'101:new error,102:new order,103:support,999:closed,666:joker';

define('E_FEEDBACK', 201);
define('E_EXECUTE', 301);
define('E_INNER_TEST', 701);
define('E_INNER_TEST_OK', 801);
define('E_TEST', 901);
define('E_CONFIRMED', 981);
define('E_EXECUTED', 991);
$g_status_enum_workflow[X_ERR] = '201:feedback,301:execute,999:closed,10:new,666:joker';
$g_status_enum_workflow[E_FEEDBACK] = '301:execute,999:closed,666:joker';
$g_status_enum_workflow[E_EXECUTE] = '701:inner test,201:feedback,999:closed,666:joker';
$g_status_enum_workflow[E_INNER_TEST] = '801:inner test ok,201:feedback,301:execute,666:joker';
$g_status_enum_workflow[E_INNER_TEST_OK] = '901:test,201:feedback,301:execute,666:joker';
$g_status_enum_workflow[E_TEST] = '981:confirmed,301:execute,999:closed,666:joker';
$g_status_enum_workflow[E_CONFIRMED] = '991:executed,999:closed,666:joker';
$g_status_enum_workflow[E_EXECUTED] = '999:closed,666:joker';

define('O_PROPOSAL', 202);
define('O_ASK', 302);
define('O_ACCEPTED', 402);
define('O_EXECUTE', 502);
define('O_FEEDBACK', 602);
define('O_INNER_TEST', 702);
define('O_INNER_TEST_OK', 802);
define('O_TEST', 902);
define('O_CONFIRMED', 982);
define('O_EXECUTED', 992);
$g_status_enum_workflow[X_ORD] = '302:ask proposal,10:new,666:joker';
$g_status_enum_workflow[O_ASK] = '202:propose,10:new,666:joker';
$g_status_enum_workflow[O_PROPOSAL] = '402:accepted,302:ask proposal,666:joker';
$g_status_enum_workflow[O_ACCEPTED] = '502:execute,666:joker';
$g_status_enum_workflow[O_FEEDBACK] = '502:execute,999:closed';
$g_status_enum_workflow[O_EXECUTE] = '702:inner test,602:feedback,999:closed,666:joker';
$g_status_enum_workflow[O_INNER_TEST] = '802:inner test ok,602:feedback,502:execute,666:joker';
$g_status_enum_workflow[O_INNER_TEST_OK] = '902:test,602:feedback,502:execute,666:joker';
$g_status_enum_workflow[O_TEST] = '982:confirmed,502:execute,999:closed,666:joker';
$g_status_enum_workflow[O_CONFIRMED] = '992:fixed,999:closed,666:joker';
$g_status_enum_workflow[O_EXECUTED] = '999:closed,666:joker';

define('S_FEEDBACK', 203);
define('S_EXECUTE', 303);
define('S_INNER_TEST', 703);
define('S_INNER_TEST_OK', 803);
define('S_TEST', 903);
define('S_CONFIRMED', 983);
define('S_EXECUTED', 993);
$g_status_enum_workflow[X_SUP] = '303:execute,10:new,666:joker';
$g_status_enum_workflow[S_EXECUTE] = '703:inner test,203:feedback,666:joker';
$g_status_enum_workflow[S_FEEDBACK] = '303:execute,666:joker';
$g_status_enum_workflow[S_INNER_TEST] = '803:inner test ok,303:execute,666:joker';
$g_status_enum_workflow[S_INNER_TEST_OK] = '903:test,203:feedback,303:execute,666:joker';
$g_status_enum_workflow[S_TEST] = '983:confirmed,303:execute,999:closed,666:joker';
$g_status_enum_workflow[S_CONFIRMED] = '993:executed,999:closed,666:joker';
$g_status_enum_workflow[S_EXECUTED] = '999:closed,666:joker';

$g_status_enum_workflow[JOKER] = '10:new,101:new error,102:new order,103:new support,201:error feedback,301:error execute,701:error inner test,801:error inner test ok,901:error test,981:error confirmed,991:fixed,202:proposal,302:ask proposal,402:accept proposal,502:execute order,602:order feedback,702:order inner test,802:order inner test ok,902:order test,982:order confirmed,992:order executed,203:support feedback,303:support execute,703:support inner test,803:support inner test ok,903:support test,983:support confirmed,993:support executed,999:closed';
$g_status_enum_workflow[999] = '666:joker';

$g_status_enum_string = '10:new,101:new error,102:new order,103:new support,201:error feedback,301:error execute,701:error inner test,801:error inner test ok,901:error test,981:error confirmed,991:fixed,202:proposal,302:ask proposal,402:accept proposal,502:execute order,602:order feedback,702:order inner test,802:order inner test ok,902:order test,982:order confirmed,992:order executed,203:support feedback,303:support execute,703:support inner test,803:support inner test ok,903:support test,983:support confirmed,993:support executed,999:closed,666:joker,996:zombie';

$g_set_status_threshold = array (
	101 => 40,
	102 => 25,
	103 => 40,
	201 => 40,
	301 => 25,
	701 => 40,
	801 => 40,
	901 => 40,
	981 => 25,
	991 => 40,
	202 => 70,
	302 => 25,
	402 => 25,
	502 => 25,
	602 => 40,
	702 => 40,
	703 => 40,
	802 => 40,
	803 => 40,
	902 => 40,
	982 => 25,
	992 => 40,
	203 => 40,
	303 => 25,
	903 => 40,
	983 => 25,
	993 => 40,
	996 => 70,
	999 => 40,
	666 => 70,
);

$g_default_notify_flags = array(
	'reporter' => ON,
	'handler' => OFF,
	'monitor' => ON,
	'bugnotes' => OFF,
	//'threshold_min' => REPORTER,
	//'threshold_max' => REPORTER,
);
if (! $g_notify_flags ) $g_notify_flags = $g_default_notify_flags;
$g_notify_flags = array_merge_recursive( $g_notify_flags, array (
	'reopened' =>
	array (
		'reporter' => ON,
		'handler' => ON,
		'monitor' => ON,
	),
	'deleted' =>
	array (
		'reporter' => ON,
		'handler' => ON,
		'monitor' => ON,
	),
	'bugnote' =>
	array (
		'reporter' => ON,
		'monitor' => ON,
		'handler' => ON,
		//'threshold_max' => REPORTER,
	),
) );

$g_auto_set_status_to_assigned = OFF;
$g_bug_reopen_resolution = 101;	//new error

$g_allow_reporter_reopen = OFF;
$g_allow_reporter_close = ON;

$g_use_persistent_connections = OFF;

$g_relationship_graph_enable = ON;

$g_default_email_on_new = OFF;
$g_default_email_on_assigned = OFF;
$g_default_email_on_feedback = OFF;
$g_default_email_on_resolved = OFF;
$g_default_email_on_closed = OFF;
$g_default_email_on_reopened = OFF;
$g_default_email_on_bugnote = ON;

if(!defined('RESOLVED')) define('RESOLVED', 900);
$g_hide_status_default = 990; //RESOLVED;
$g_hide_status = 999; //EXECUTED;
$g_bug_resolved_status_threshold = 990;
$g_bug_readonly_status_threshold = 990; //élesen van - nem módosítható
$g_bug_closed_status_threshold = 999;
$g_update_readonly_bug_threshold = DEVELOPER;
$g_private_bug_threshold = UPDATER;
$g_private_bugnote_threshold = UPDATER;
$g_update_bug_status_threshold = REPORTER;
$g_set_view_status_threshold = UPDATER;
$g_change_view_status_threshold = UPDATER;
$g_bug_reopen_status = 999;

$g_bug_status_modulo = 10;
$g_bug_status_modulo_permanent = ON;

$g_status_colors = array(
	'new' => '#FF9999',
	'new error' => '#FFBBBB',
	'new order' => '#FFBBBB',
	'new support' => '#FFBBBB',
	'error feedback' => '#FDA7FF',
	'error execute' => '#ABEDEC',
	'error inner test' => '#CAFD8A',
	'error inner test ok' => '#9CE964',
	'error test' => '#F9FFA7',
	'error confirmed' => '#7CC944',
	'fixed' => '#D9D9D9',
	'proposal' => '#F9FFA7',
	'ask proposal' => '#FFED75',
	'accept proposal' => '#ABEDEC',
	'execute order' => '#ABEDEC',
	'order feedback' => '#FDA7FF',
	'order inner test' => '#CAFD8A',
	'order inner test ok' => '#9CE964',
	'order test' => '#F9FFA7',
	'order confirmed' => '#7CC944',
	'order executed' => '#D9D9D9',
	'support feedback' => '#FDA7FF',
	'support execute' => '#ABEDEC',
	'support inner test' => '#CAFD8A',
	'support inner test ok' => '#9CE964',
	'support test' => '#F9FFA7',
	'support confirmed' => '#7CC944',
	'support executed' => '#D9D9D9',
	'closed' => '#FFFFFF',
	'joker' => '#B9B9B9'
);

define('EXECUTED', 99);
define('STORNO', 100);
$g_resolution_enum_string = '10:open,20:fixed,21:storno,29:executed,30:reopened,40:unable to duplicate,50:not fixable,60:duplicate,70:not a bug,80:suspended,90:wont fix';

if ( SYS_COMPANY === 'waberer' ) {
	$g_severity_enum_string	= '10:feature,50:error';
	$g_priority_enum_string	= '30:normal,40:high,60:immediate';
}
 */

//
// vim: set noet:
