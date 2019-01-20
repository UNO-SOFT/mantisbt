<?php
// vim: set filetype=php noet:
	# $Id$
	# Debian default configuration file for mantis.

	# Attention: You should NOT remove the following line, if using
	# dbconfig-common/debconf to configure mantis database, nor should you
	# change the database configuration by hand.
	include("config_db.php");

	$g_hostname			= "$dbserver";
	$g_db_username		= "$dbuser";
	$g_db_password		= "$dbpass";
	$g_database_name	= "$dbname";
	$g_db_type			= "$dbtype";
if( isset( $salt ) ) {
	$g_crypto_master_salt = "$salt";
} else {
	$g_crypto_master_salt = 'fbnCVY7vY1ZRqFoHiFXj7YxR/o2gFLyTdhVFqTVB7YDW4czD2VXe4It7O5cV4I+MRKH0pj0tRJ/sZHZENFqbAA==';
}

# Hook to notify after aa issue has been created.
# In case of errors, this function should call trigger_error()
# p_issue_id is the issue number that can be used to get the existing state
//function custom_function_override_issue_create_notify( $p_issue_id ) {
//	require_once('core.php');
//	require_once('event_api.php');
//	event_hook( EVENT_REPORT_BUG, 'bug_reported', 'UnoCustomization' );
//}
	# E-Mail addresses
if ( substr(dirname(__FILE__), 0, 5) === '/home' ) {
	$t_chunks = explode('/', dirname(__FILE__));
	//echo '<!--t_chunks[2]='.print_r($t_chunks[2], true)."-->";
	//echo '<!--t_chunks='.print_r($t_chunks, true)."-->";
	// /home/kobe/prd/mantis -> "", "home", "kobe", "prd", "mantis"
	if ( $t_chunks[2] === 'tgulacsi' ) {
		define('SYS_COMPANY', 'unosoft');
		define('SYS_FLAVOR', 'dev');
	} else {
		define('SYS_COMPANY', $t_chunks[2]);
		define('SYS_FLAVOR', $t_chunks[3]);
	}
} else if ( substr(dirname(__FILE__), 0, 8) === '/var/www' ) {
	$t_chunks = array_slice(explode('/', dirname(__FILE__)), 3);
	//echo '<!--t_chunks[2]='.print_r($t_chunks[2], true)."-->";
	//echo '<!--t_chunks='.print_r($t_chunks, true)."-->";
///var/www/www.unosoft.hu/mantis/kobe/config/config_inc.php -> "www.unosoft.hu", "mantis", "kobe", "config"
	if ( substr_compare( $t_chunks[2], "_dev", -4 ) === 0 ) {
		define('SYS_COMPANY', substr($t_chunks[2], 0, -4) );
		define('SYS_FLAVOR', 'dev');
	} else {
		define('SYS_COMPANY', $t_chunks[2]);
		define('SYS_FLAVOR', 'prd');
	}
//echo "<!-- co=".SYS_COMPANY." fl=".SYS_FLAVOR. "-->";
}
$g_file_download_xsendfile_enabled = ON;
$g_file_download_xsendfile_header_name = 'X-Accel-Redirect';

$g_file_upload_method = DISK;
$g_log_destination = 'file://var/log/mantis/'.SYS_COMPANY.'-'.SYS_FLAVOR.'.log';
$g_absolute_path_default_upload_folder = '/home/' . SYS_COMPANY . '/' . SYS_FLAVOR . '/attachments/';

define('SYS_COMPANY_NAME',
	SYS_COMPANY == 'kobe' ? 'KÖBE'
	: (SYS_COMPANY == 'waberer' ? 'Wáberer'
	: strtoupper(SYS_COMPANY)));
//echo SYS_FLAVOR.'%'.SYS_COMPANY;

//** EMAIL **//
$g_administrator_email = 'T.Gulacsi@unosoft.hu';
$g_webmaster_email = $g_administrator_email;
#$g_from_email = 'mantis-'.SYS_COMPANY.'-'.SYS_FLAVOR.'@unosoft.hu';
$g_from_email = SYS_COMPANY.'@unosoft.hu';
$g_from_name = SYS_COMPANY_NAME.(SYS_FLAVOR == 'prd' ? '' : '-'.SYS_FLAVOR).' Mantis hibakövető';
#$g_return_path_email = str_replace('mantis-', 'noreply-', $g_from_email);
$g_return_path_email = $g_from_email;
$g_allow_blank_email = OFF;
$g_show_user_email_threshold = NOBODY;
if ( strcmp(SYS_FLAVOR, 'prd') != 0 ) { //nem PRD
  $g_limit_email_domain = 'unosoft.hu';
}
$g_phpMailer_method = 0; //0 - mail(), 1 - sendmail 2 - SMTP
//$g_email_send_using_cronjob = (strcmp(SYS_FLAVOR, 'prd') ? ON : OFF);
$g_email_send_using_cronjob = ON;

$g_bug_reminder_threshold = 20;


$g_max_file_size = 20000000;
$g_disallowed_files = trim($g_disallowed_files . ','
						   . 'msg,php,php3,phtml,html,class,java,exe,pl', ',');
$g_preview_attachments_inline_max_size = 10240;


$g_default_language = 'hungarian';

$g_window_title = SYS_COMPANY_NAME
	. (strcmp(SYS_FLAVOR, 'dev') == 0 ? ' DEV' : '')
	. ' UNO-SOFT hibakövető';
$g_page_title = 'UNO-SOFT ' . SYS_COMPANY_NAME
	. (strcmp(SYS_FLAVOR, 'dev') == 0 ? ' DEV' : '')
	. ' hibakövető';

// Top/bottom //
// $g_bottom_include_page = '%absolute_path%';
$g_top_include_page = 'top_page_inc.php';


$g_bugnote_order = 'DESC';

if ( strcmp(SYS_FLAVOR, 'prd') != 0 ) { //nem PRD
  $g_limit_email_domain = 'unosoft.hu';
  $g_show_detailed_errors = ON;
}

$g_log_level = LOG_NONE | LOG_EMAIL | LOG_EMAIL_RECIPIENT | LOG_AJAX | LOG_FILTERING;
//$g_log_destination = 'page:';
$g_show_timer = ON;

define( 'MAX_EVENTS', 5 );
$g_my_view_bug_count = 30;

$g_my_view_boxes = array(
	'assigned'      => '1',
	'unassigned'    => '2',
	'reported'      => '3',
	'resolved'      => '8',
	'recent_mod'    => '4',
	'monitored'     => '7',
	'feedback'      => '6',
	'verify'        => '0',
	'my_comments'   => '0'
);

# Toggle whether 'My View' boxes are shown in a fixed position (i.e. adjacent boxes start at the same vertical position)
$g_my_view_boxes_fixed_position = OFF;

# bejelentőnek is lehessen ügye
$g_handle_bug_threshold = REPORTER;
# de csak priv. rendelgethessen
$g_update_bug_assign_threshold = UPDATER;
$g_update_bug_status_threshold = REPORTER;
$g_update_bug_threshold = REPORTER;
$g_roadmap_update_threshold = REPORTER;

$g_delete_attachments_threshold = UPDATER;
$g_delete_bug_threshold = MANAGER;
$g_bugnote_user_delete_threshold = DEVELOPER;

$g_show_version = ON;

$g_show_product_version = 3;
$g_show_version_dates_threshold = REPORTER;
$g_view_issues_page_columns[] = 'target_version';
$g_view_issues_page_columns[] = 'fixed_in_version';
$g_print_issues_page_columns[] = 'target_version';
$g_print_issues_page_columns[] = 'fixed_in_version';
$g_show_product_version = ON;

$g_plugin_FileDistribution_url = '/static';
$g_plugin_FileDistribution_path = '/var/local/mantis/'.SYS_FLAVOR
	.'/'.SYS_COMPANY.'/static';

$g_allow_no_category = ON;

$g_accel_redirect = ON;
$g_show_detailed_errors = OFF;

$g_relationship_graph_enable = ON;

if( SYS_COMPANY == 'tir' ) {
  $g_update_bug_relationship_threshold = REPORTER;
}

$g_session_key = 'MantisBT-'.SYS_COMPANY.'-'.SYS_FLAVOR;
$g_cookie_prefix = $g_session_key;

$g_monitor_add_others_bug_threshold = REPORTER;
$g_monitor_bug_threshold = REPORTER;
$g_bug_reminder_threshold = REPORTER;
$g_show_monitor_list_threshold = REPORTER;
$g_reminder_receive_threshold = REPORTER;
$g_view_summary_threshold = UPDATER;
$g_filter_remember_last = false;

$g_hidden_reporters = array(); //array( 'mail_watcher' );
$g_create_permalink_threshold = REPORTER;
$g_allow_delete_own_attachments = ON;
$g_bugnote_allow_user_edit_delete = OFF;
$g_set_bug_sticky_threshold = DEVELOPER;

$g_attachment_print_uploader = ON;
$g_filter_use_last = OFF;
$g_email_from_is_last_updater = ON;
$g_default_email_bugnote_limit = 3;

$g_backward_year_count = 1;
$g_forward_year_count = 1;

if( SYS_FLAVOR.'/'.SYS_COMPANY != 'dev/unosoft' )
  $g_path = preg_replace('/^http:/', 'https:', $g_path);

$g_min_refresh_delay	= 1;
$g_default_refresh_delay				= 10;
$g_default_redirect_delay			   = 1;

$g_logo_image = 'config/mantis_logo.png';

$g_allow_signup = OFF;
$g_max_failed_login_count = 5;

#
$g_default_show_changed = 24;

$g_use_persistent_connections = ON;
$g_compress_html = OFF;

$g_show_avatar = OFF;
$g_show_avatar_threshold = 1000;
$g_time_tracking_enabled = ON;
$g_time_tracking_stopwatch = ON;

$g_create_permalink_threshold = REPORTER;
$g_stored_query_create_threshold = REPORTER;
$g_timeline_view_threshold = NOBODY;
//$g_timeline_view_threshold = 1000;

$g_css_include_file = "unosoft.css";
$g_cdn_enabled = OFF;

$g_impersonate_user_threshold = NOBODY;

$g_filter_views = SIMPLE_DEFAULT;

//if(file_exists('config_statuses_inc.php'))
	require_once(dirname(__FILE__) . '/config_statuses_inc.php');
if(file_exists('config_local.php')) {
	include("config_local.php");
}
