<?php


$c_hidden_reporters = false;
function _hidden_reporters_where() {
       global $c_hidden_reporters;
       if( $c_hidden_reporters === false ) {
               $t_hidden_arr = array();
               foreach(config_get('hidden_reporters') as $t_name) {
                       array_push($t_hidden_arr, is_numeric($t_name)
                               ? (int)$t_name : user_get_id_by_name($t_name));
               }
               $c_hidden_reporters = (
                       count($t_hidden_arr) == 0 ? ''
                               : (count($t_hidden_arr) == 1
                                       ? 'reporter_id <> '.((int)$t_hidden_arr[0])
                                       : 'reporter_id NOT IN ('.implode(', ', $t_hidden_arr).')'
                                       ) . ' AND ');
       }
       return $c_hidden_reporters;
}


/**
 * return the reporter (user_id) for the most recent time at which a bugnote
 *  associated with the bug was modified
 * @param int p_bug_id integer representing bug id
 * @return bool|int false or user id in integer format representing last bugnot
 * @access public
 * @uses database_api.php
 */
function bug_get_last_bugnote_reporter( $p_bug_id ) {
       $c_bug_id = db_prepare_int( $p_bug_id );
       $t_bugnote_table = db_get_table( 'bugnote' );

       $query = "SELECT reporter_id, date_submitted
                                 FROM $t_bugnote_table
                                 WHERE " . _hidden_reporters_where() . "
                                       bug_id = " . db_param() . "
				   ORDER BY date_submitted DESC
				   LIMIT 1";
       $result = db_query( $query, Array( $c_bug_id ) );
       return db_result( $result );
}
