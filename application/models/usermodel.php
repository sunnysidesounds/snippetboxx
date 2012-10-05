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


} //UserModal Class