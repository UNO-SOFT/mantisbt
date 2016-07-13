<?php
if(!defined('NEW_')) define('NEW_', 10);
if(!defined('CLOSED')) define('CLOSED', 999);

require_once(dirname(__FILE__) . '/../config_defaults_inc.php');

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
$g_bug_reopen_resolution = 101;  //new error

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
  'error feedback' => '#F9FFA7',
  'error execute' => '#ABEDEC',
  'error inner test' => '#BCE984',
  'error inner test ok' => '#9CE964',
  'error test' => '#F9FFA7',
  'error confirmed' => '#7CC944',
  'fixed' => '#D9D9D9',
  'proposal' => '#F9FFA7',
  'ask proposal' => '#FFED75',
  'accept proposal' => '#ABEDEC',
  'execute order' => '#ABEDEC',
  'order feedback' => '#F9FFA7',
  'order inner test' => '#BCE984',
  'order inner test ok' => '#9CE964',
  'order test' => '#F9FFA7',
  'order confirmed' => '#7CC944',
  'order executed' => '#D9D9D9',
  'support feedback' => '#F9FFA7',
  'support execute' => '#ABEDEC',
  'support inner test' => '#BCE984',
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
