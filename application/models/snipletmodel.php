<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'basemodel.php' );

class SnipletModel extends BaseModel {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_exists($title) {
		$sql = 'SELECT * FROM sniplets WHERE sniplet_title=?;';
		$query = $this->db->query( $sql, array($title) );
		return $query && $query->num_rows() > 0;
	} //sniplet_exists
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function insert_sniplet($title, $content, $url, $time, $username){
		if( !empty( $title ) && !empty( $content ) && !empty( $url ) && !empty( $time ) && !empty( $username ) ) {				
				$sql   = "INSERT INTO sniplets VALUES (NULL,?,?,?,?,?,?);";
				//Current time is update time.
				$update_time = $time;
				$query = $this->db->query( $sql, array($title , $content, $url, $time, $username, $update_time));
				return $query != false;	
		} else {
			log_message('error', 'insert_sniplet failed: [snipletmodel/insert_sniplet]');
			return false;
		}
	} //insert_sniplet
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_tag($tag, $username){
		//If doesn't exist insert
	/*	if($this->tag_exists($tag) != 1){
			$time = date('m-d-Y-g:ia');
			$sql   = "INSERT INTO tags VALUES (NULL,?, ?, ?);";
			$query = $this->db->query( $sql, array($tag, $username, $time));
			$lastInsert = $this->db->insert_id();
			return $lastInsert;
			//return $query != false;
		} else {
			//else return tag id
			$getTagId = $this->get_tag_id($tag);
			return $getTagId;
		}*/
	} //update_tag



	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function insert_tag($tag, $username){
		//If doesn't exist insert
		if($this->tag_exists($tag) != 1){
			$time = date('m-d-Y-g:ia');
			//Current time is update time. 
			$update_time = $time;
			$sql   = "INSERT INTO tags VALUES (NULL,?, ?, ?, ?);";
			$query = $this->db->query( $sql, array($tag, $username, $time, $update_time));
			$lastInsert = $this->db->insert_id();
			return $lastInsert;
			//return $query != false;
		} else {
			//else return tag id
			$getTagId = $this->get_tag_id($tag);
			return $getTagId;
		}
	} //insert_tag
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function insert_sniplet_to_tag($sniplet_id, $tag_id, $username){
		$sql   = "INSERT INTO sniplets_to_tags VALUES (?,?,?);";
		$query = $this->db->query( $sql, array($sniplet_id, $tag_id, $username));
		return $query != false;
	} //insert_tag
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tag_exists( $tag_keyword ) {
	   $sql      = 'SELECT * FROM tags WHERE tag_keyword=?;';
	   $query    = $this->db->query( $sql, array($tag_keyword) );
		return $query && $query->num_rows() > 0;
	} //tag_exists

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tag_id($tag) {
		$sql = 'SELECT tag_id FROM tags WHERE tag_keyword =?;';
		$query = $this->db->query( $sql , array($tag));	
		if($query->num_rows()>0){	
			foreach ($query->result() as $row){													
				return $row->tag_id;				
			}//foreach			
		} else {
			log_message('error', 'get_tag_id failed: [snipletmodel/get_tag_id]');
		}		
	} //get_tag_id
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tag_name($tag_id) {
		$sql = 'SELECT tag_keyword FROM tags WHERE tag_id =?;';
		$query = $this->db->query( $sql, array($tag_id));	
		if($query->num_rows()>0){	
			foreach ($query->result() as $row){													
				return $row->tag_keyword;				
			}//foreach			
		} 
	} //get_tag_id
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_tag_list($sniplet_id, $raw_array = 0) {
		$array = array();
		$sql = 'SELECT tag_id FROM sniplets_to_tags WHERE sniplet_id =?;';
		$query = $this->db->query( $sql , array($sniplet_id));	
		if($query->num_rows()>0){	
			foreach ($query->result() as $row){															
				if(!$raw_array == 0){
					$array[] = '<a href="#" id="sniplettagid_'.$row->tag_id.'" class="sniplet_click_tag">' . $this->get_tag_name($row->tag_id) . '</a>';
				} else{
					$array[] = $this->get_tag_name($row->tag_id);
				}
			}	//foreach
			return $array;	
		} 
	} //get_tag_list
} //SnipletModel