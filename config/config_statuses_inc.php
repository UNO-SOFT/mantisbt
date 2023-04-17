<?php
$g_status_enum_workflow = array();

if( SYS_COMPANY === 'unosoft' ) {
	define('U_NEW', 10);  // uj
	define('U_DUE', 20);  // esedékes
	define('U_ASSIGNED', 50);  // folyamatban
	define('U_SUSPENDED', 80); // nyugvó
	define('U_CLOSED', 90);  // lezarva

	require_once(dirname(__FILE__) . '/../config_defaults_inc.php');

	$g_status_enum_workflow = array(
		U_NEW => '20:due,50:assigned',
		U_DUE => '50:assigned',
		U_ASSIGNED => '80:suspended,20:due,50:assigned,90:closed',
		U_SUSPENDED => '50:assigned,20:due,90:closed',
		U_CLOSED => ''
	);

	$g_status_enum_string = '10:new,20:due,50:assigned,80:suspended,90:closed';

	$g_set_status_threshold = array (
		U_NEW => REPORTER,
		U_DUE => REPORTER,
		U_ASSIGNED => REPORTER,
		U_SUSPENDED => REPORTER,
		U_CLOSED => UPDATER
	);

	$g_status_colors = array(
		'new' => '#FF9999',
		'due' => '#FDA7FF',
		'assigned' => '#ABEDEC',
		'suspended' => '#CAFD8A',
		'closed' => '#FFFFFF',
	);

	$g_enable_projection = OFF;

	//$g_resolution_enum_string = '10:open,20:fixed,30:reopened,40:unable to duplicate,50:not fixable,60:duplicate,70:not a bug,80:suspended,90:wont fix';
	$g_resolution_enum_string = '10:open';

	//$g_severity_enum_string = '10:feature,20:trivial,30:text,40:tweak,50:minor,60:major,70:crash,80:block';
	$g_severity_enum_string	= '10:task,50:incident';

	//$g_priority_enum_string = '10:none,20:low,30:normal,40:high,50:urgent,60:immediate';
	$g_priority_enum_string	= '30:normal,40:high,60:immediate';


	$g_default_notify_flags = array(
		'reporter' => ON,
		'handler' => ON,
		'monitor' => ON,
		'bugnotes' => OFF,
		//'threshold_min' => REPORTER,
		//'threshold_max' => REPORTER,
	);
	if( !$g_notify_flags ) {
		$g_notify_flags = $g_default_notify_flags;
	}
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

} else {

	define('U_NEW', 10);  // uj
	define('U_FEEDBACK', 20);  // kerdes
	define('U_ASK_PROPOSAL', 25);  // ajanlat keres
	define('U_PROPOSAL_FEEDBACK', 27); // tisztazas
	define('U_PROPOSAL', 30);  // ajanlat
	define('U_ACKNOWLEDGED', 40);  // elfogadva
	define('U_ASSIGNED', 50);  // folyamatban
	define('U_ASSIGNED_FEEDBACK', 55);  // kerdes2
	define('U_TEST', 60);  // belso teszt
	define('U_TEST_OK', 70);  // belso teszt ok
	define('U_RESOLVED', 80);  // atadva
	define('U_CLOSED', 90);  // lezarva
	define('U_STORNO', 100);
	define('U_JOKER', 99);

	require_once(dirname(__FILE__) . '/../config_defaults_inc.php');

	$g_status_enum_workflow[U_NEW] = '20:feedback,25:ask_proposal,50:assigned,90:closed';
	$g_status_enum_workflow[U_FEEDBACK] = '25:ask_proposal,50:assigned,90:closed';
	$g_status_enum_workflow[U_ASK_PROPOSAL] = '30:proposal,27:proposal_feedback,90:closed';
	$g_status_enum_workflow[U_PROPOSAL_FEEDBACK] = '30:proposal,25:ask_proposal,90:closed,20:feedback';
	$g_status_enum_workflow[U_PROPOSAL] = '40:acknowledged,25:ask_proposal,90:closed';
	$g_status_enum_workflow[U_ACKNOWLEDGED] = '50:assigned,90:closed';
	$g_status_enum_workflow[U_ASSIGNED] = '55:assigned_feedback,60:test,80:resolved';
	$g_status_enum_workflow[U_ASSIGNED_FEEDBACK] = '50:assigned,60:test,80:resolved';
	$g_status_enum_workflow[U_TEST] = '50:assigned,70:test_ok';
	$g_status_enum_workflow[U_TEST_OK] = '80:resolved';
	$g_status_enum_workflow[U_RESOLVED] = '50:assigned,90:closed';
	$g_status_enum_workflow[U_CLOSED] = '99:joker';
	$g_status_enum_workflow[U_JOKER] = '10:new,20:feedback,25:ask_proposal,50:assigned,55:assigned_feedback,80:resolved,90:closed';

	$g_status_enum_string = '10:new,20:feedback,25:ask_proposal,27:proposal_feedback,30:proposal,40:acknowledged,50:assigned,55:assigned_feedback,60:test,70:test_ok,80:resolved,90:closed,99:joker';

	$g_set_status_threshold = array (
		U_NEW => REPORTER,
		U_FEEDBACK => UPDATER,
		U_ASK_PROPOSAL => REPORTER,
		U_PROPOSAL_FEEDBACK => UPDATER,
		U_PROPOSAL => MANAGER,
		U_ACKNOWLEDGED => REPORTER,
		U_ASSIGNED => REPORTER,
		U_ASSIGNED_FEEDBACK => UPDATER,
		U_TEST => DEVELOPER,
		U_TEST_OK => UPDATER,
		U_RESOLVED => DEVELOPER,
		U_CLOSED => DEVELOPER,
		U_JOKER => MANAGER
	);

	$g_status_colors = array(
		'new' => '#FF9999',
		'feedback' => '#FDA7FF',
		'ask_proposal' => '#FFED75',
		'proposal_feedback' => '#FDA7FF',
		'proposal' => '#F9FFA7',
		'acknowledged' => '#ABEDEC',
		'assigned' => '#ABEDEC',
		'assigned_feedback' => '#FDA7FF',
		'test' => '#CAFD8A',
		'test_ok' => '#9CE964',
		'resolved' => '#D9D9D9',
		'closed' => '#FFFFFF',
	);

	if( SYS_COMPANY === 'kobe' ) {
		define('U_SHIP', 85);
		$g_status_enum_workflow[U_RESOLVED] = '50:assigned,85:ship,90:closed';
		$g_status_enum_workflow[U_SHIP] = '90:closed,80:resolved';
		$g_status_enum_string = str_replace(',80:resolved,', ',80:resolved,85:ship,', $g_status_enum_string);
		$g_set_status_threshold[U_SHIP] = REPORTER;
		$g_status_colors['ship'] = '#00FF00';
	}

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
		U_PROPOSAL_FEEDBACK [label="tisztázás"];
		U_PROPOSAL [label="ajánlat" color="red" shape="rectangle"];
		U_ACCEPTED [label="elfogadva" color="green"];

		U_NEW -> U_ASK_PROPOSAL -> U_PROPOSAL -> U_ACCEPTED -> U_ASSIGNED;
		U_ASK_PROPOSAL -> U_PROPOSAL_FEEDBACK -> U_ASK_PROPOSAL;
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
	//$s_projection_enum_string = '10:támogatás,20:hiba,50:megrendelés,51:SPL-Fejlesztés,52:Lekérdezés,54:oktatás,55:konzultáció';
	if( SYS_COMPANY === 'aegon' ) {
		$g_projection_enum_string = '10:support,20:error,50:order,55:consulting';
	} elseif( SYS_COMPANY === 'cig' ) {
		$g_projection_enum_string = '10:support,20:error,50:order,53:data_modification,54:education,55:consulting';
	} elseif( SYS_COMPANY === 'kobe' ) {
		$g_projection_enum_string = '10:support,20:error,50:order,54:education';
	} else {
		$g_projection_enum_string = '10:support,20:error,50:order,54:education,55:consulting';
	}

	//$g_resolution_enum_string = '10:open,20:fixed,30:reopened,40:unable to duplicate,50:not fixable,60:duplicate,70:not a bug,80:suspended,90:wont fix';
	$g_resolution_enum_string = '10:open,20:fixed,21:storno,29:executed,30:reopened,40:unable to duplicate,50:not fixable,60:duplicate,70:not a bug,80:suspended,90:wont fix';

	//$g_severity_enum_string = '10:feature,20:trivial,30:text,40:tweak,50:minor,60:major,70:crash,80:block';
	$g_severity_enum_string	= '10:feature,50:error';

	//$g_priority_enum_string = '10:none,20:low,30:normal,40:high,50:urgent,60:immediate';
	$g_priority_enum_string	= '30:normal,40:high,60:immediate';


	$g_default_notify_flags = array(
		'reporter' => ON,
		'handler' => ON,
		'monitor' => ON,
		'bugnotes' => OFF,
		//'threshold_min' => REPORTER,
		//'threshold_max' => REPORTER,
	);
	if( !$g_notify_flags ) {
		$g_notify_flags = $g_default_notify_flags;
	}
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
}

$g_bug_reopen_status = U_ASSIGNED;

$g_bug_reopen_resolution = REOPENED;
$g_reopen_bug_threshold = DEVELOPER;
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

$g_bug_closed_status_threshold = U_CLOSED;

$g_bug_status_modulo = 1;
$g_bug_status_modulo_permanent = ON;

require_once(dirname(__FILE__) . '/custom_strings_inc.php');


//
// vim: set noet:
