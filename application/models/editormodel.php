<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class EditorModel extends CI_Model {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tag_by_id($id){

		$sql = "SELECT tag_keyword FROM tags WHERE tag_id = '".$id."' ";
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['tag_keyword'];
			} //foreach	
		} //if 0	
	}
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tag_count($tag_id){
		$sql = "SELECT COUNT(*) as count FROM sniplets_to_tags WHERE tag_id = '".$tag_id."' ";
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['count'];
			} //foreach	
		} //if 0			


	} //get_tag_count

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tag_date($tag_id){
		$sql = "SELECT create_tag_time as date FROM tags WHERE tag_id = '".$tag_id."' ";
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['date'];
			} //foreach	
		} //if 0			


	} //get_tag_date


} //EditorModel