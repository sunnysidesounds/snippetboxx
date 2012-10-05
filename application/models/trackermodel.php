<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TrackerModel extends CI_Model {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function insert_tracker($array) {
		if(!empty($array['tracker_ip'])) {	
			$sql   = "INSERT INTO user_tracker VALUES (NULL,?,?,?,?,?,?,?,?,?);";
			$query = $this->db->query( $sql, array($array['user_id'], $array['tracker_ip'], $array['tracker_region'], $array['tracker_date_created'], $array['tracker_date_updated'], $array['tracker_clicks'], $array['username'], $array['agent'], $array['referer']));
			return $query != false;	
		} else {
			return false;
		}		
	} //insert_tracker

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_tracker($array) {
		$time = date('m-d-Y-g:ia');
		if(!empty($array['tracker_ip'])) {	
			$sql   = "UPDATE user_tracker SET tracker_clicks ='".$array['tracker_clicks']."', tracker_date_updated ='".$time."' WHERE tracker_ip = '".$array['tracker_ip']."' ;";
			echo $sql;
			$query = $this->db->query( $sql);
			return $query != false;	
		} else {
			return 'empty';
		}		
	} //insert_tracker

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function check_ip($address){
		$sql   = "SELECT * FROM user_tracker WHERE tracker_ip =?;";
		$query = $this->db->query( $sql, array($address) );
		return $query && $query->num_rows() > 0;	
	} //check_ip



//$updateParent = 'UPDATE event_to_location SET el_location_id = '.$parent_location.' WHERE el_id = '.$el_array[0].'';
//$queryParent = $this->db->query( $updateParent );

} //TrackerModel
