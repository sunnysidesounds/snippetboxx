<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_id($username){	
		$sql = 'SELECT id FROM users WHERE username ="'.$username.'";';
		$query = $this->db->query( $sql );	
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				return $row->id;				
			
			}	//foreach			
		
		} else {
			//TODO: Change this
			echo 'No Records';
		}		
	
	
	} //get_user_id

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_sniplets_by_tags($username, $id){	
		$sql = "SELECT snip.sniplet_id, snip.sniplet_title FROM sniplets snip
			LEFT JOIN sniplets_to_tags stt ON stt.sniplet_id = snip.sniplet_id WHERE stt.tag_id = '".$id."' AND stt.user_id = '".$username."'; ";
		$query = $this->db->query( $sql );	
		$parent = array();
		
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				$parent[] = array($row->sniplet_id, $row->sniplet_title);				
			}	//foreach	
			
			return $parent;		
		}		
	} //get_user_sniplets_by_tags

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_tags($id){	
		$sql = 'SELECT * FROM tags WHERE user_id ="'.$id.'" ORDER BY tag_keyword;';
		$query = $this->db->query( $sql );	
		$parent = array();
		
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				$parent[] = array($row->tag_id, $row->tag_keyword);				
			}	//foreach	
			
			return $parent;		
		}		
	} //get_user_tags

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_sniplets($id){	
		$sql = 'SELECT * FROM sniplets WHERE user_id ="'.$id.'" ORDER BY sniplet_title;';
		$query = $this->db->query( $sql );	
		$parent = array();
		
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				$parent[] = array($row->sniplet_id, $row->sniplet_title);				
			}	//foreach	
			
			return $parent;		
		} 	
	} //get_user_sniplets

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_email($username){
		$sql = 'SELECT email FROM users WHERE username ="'.$username.'";';
		$query = $this->db->query( $sql );			
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				return $row->email;				
			}	//foreach	
		} 

	} //get_user_email

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_link($username, $id){
		$sql = 'SELECT sniplet_url FROM sniplets WHERE user_id ="'.$username.'" AND sniplet_id = "'.$id.'";';
		$query = $this->db->query( $sql );			
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				return $row->sniplet_url;				
			}	//foreach	
		} 

	} //get_user_link

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_year($username){
		$sql = 'SELECT date_created FROM users WHERE username ="'.$username.'";';
		$query = $this->db->query( $sql );	
		if($query->num_rows()>0){				
			foreach ($query->result() as $row){													
				return $row->date_created;				
			}	//foreach	
					
		
		} 

	} //get_user_year

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_count_sniplets($user_id){
		$sql = 'SELECT COUNT(*) as count FROM sniplets WHERE user_id ="'.$user_id.'";';
		$array = array();				
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array[0]['count'];
		}
	} //get_user_count_tags
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_count_tags($user_id){
		$sql = 'SELECT COUNT(*) as count FROM tags WHERE user_id ="'.$user_id.'";';
		$array = array();				
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array[0]['count'];
		}
	} //get_user_count_tags

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function insert_user($array){
		$sql   = "INSERT INTO users VALUES (NULL,?,?,?,?,?,?,?,?,?,?);";
		$query = $this->db->query( $sql, $array);	
		
		return $this->db->insert_id();
	} //insert_user

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_active($username){
		$sql   = "UPDATE users SET active = 1 WHERE username = '".$username."'";
		$query = $this->db->query( $sql);	
		if($query){
			//TODO: Add logging
			return true;
		} else {
			return false;
		}
	} //update_user_active

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_login_time($username, $time){
		$sql   = "UPDATE users SET date_last_login='".$time."' WHERE username = '".$username."';";
		$query = $this->db->query( $sql);	
		
		if($query){
			//TODO: Add logging
			return true;
		} else {
			return false;
		}
	} //update_user_login_time

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_login_ip($username, $ip){
		$sql   = "UPDATE users SET last_ip_login='".$ip."' WHERE username = '".$username."';";
		$query = $this->db->query( $sql);	
		
		if($query){
			//TODO: Add logging
			return true;
		} else {
			return false;
		}
	} //update_user_login_ip

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_tag($title, $user_id, $tag_id){
		$sql   = "UPDATE tags SET tag_keyword='".$title."' WHERE user_id = '".$user_id."' AND tag_id = '".$tag_id."' ;";
		$query = $this->db->query( $sql);	
		
		if($query){
			//TODO: Add logging
			return true;
		} else {
			return false;
		}
	} //update_user_tag





} //UserModal Class