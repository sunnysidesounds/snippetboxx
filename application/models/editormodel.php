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
	public function get_tag_all(){
		$sql = "SELECT * FROM tags";
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				//return $row['count'];
				$array[$row['tag_id']] = $row['tag_keyword'];
			} //foreach

			return $array;	
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

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_sniplet_by_id($id){

		$sql = "SELECT sniplet_title FROM sniplets WHERE sniplet_id = '".$id."' ";
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['sniplet_title'];
			} //foreach	
		} //if 0	
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tags_by_sniplet($id){

		$sql = "SELECT tag_id FROM sniplets_to_tags WHERE sniplet_id = '".$id."' ";
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				 $array[] = $row['tag_id'];
			} //foreach
			return $array;	
		} //if 0	
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_sniplet_content_id($id){

		$sql = "SELECT sniplet_content FROM sniplets WHERE sniplet_id = '".$id."' ";
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['sniplet_content'];
			} //foreach	
		} //if 0	
	}


} //EditorModel