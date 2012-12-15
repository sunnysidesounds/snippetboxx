<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'basemodel.php' );

class UserModel extends BaseModel {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_id($username){	
		$sql = 'SELECT id FROM users WHERE username =?;';
		$query = $this->db->query( $sql , array($username));	
		if($query->num_rows()>0){	
			foreach ($query->result() as $row){													
				return $row->id;				
			}//foreach
		} else {
			log_message('error', 'get_user_id method failed for username: '+$username+' [usermodel/get_user_id] ');
			echo 'No Records';
		}		
	} //get_user_id

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_sniplets_by_tags($username, $id){	
		$sql = "SELECT snip.sniplet_id, snip.sniplet_title FROM sniplets snip
			LEFT JOIN sniplets_to_tags stt ON stt.sniplet_id = snip.sniplet_id WHERE stt.tag_id =? AND stt.user_id =?; ";
		$query = $this->db->query( $sql, array($id, $username));	
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
		$sql = 'SELECT * FROM tags WHERE user_id =? ORDER BY tag_keyword;';
		$query = $this->db->query( $sql, array($id));	
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
		$sql = 'SELECT * FROM sniplets WHERE user_id =? ORDER BY sniplet_title;';
		$query = $this->db->query( $sql , array($id));	
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
		$sql = 'SELECT email FROM users WHERE username =?;';
		$query = $this->db->query( $sql , array($username));			
		if($query->num_rows()>0){	
			foreach ($query->result() as $row){													
				return $row->email;				
			}	//foreach	
		} 

	} //get_user_email

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_link($username, $id){
		$sql = 'SELECT sniplet_url FROM sniplets WHERE user_id =? AND sniplet_id =?;';
		$query = $this->db->query( $sql , array($username, $id));			
		if($query->num_rows()>0){	
			foreach ($query->result() as $row){													
				return $row->sniplet_url;				
			}	//foreach	
		} 

	} //get_user_link

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_year($username){
		$sql = 'SELECT date_created FROM users WHERE username =?;';
		$query = $this->db->query( $sql , array($username));	
		if($query->num_rows()>0){				
			foreach ($query->result() as $row){													
				return $row->date_created;				
			}	//foreach	
		} 

	} //get_user_year

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_count_sniplets($user_id){
		$sql = 'SELECT COUNT(*) as count FROM sniplets WHERE user_id =?;';
		$array = array();				
		$query = $this->db->query( $sql , array($user_id));
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array[0]['count'];
		}
	} //get_user_count_tags
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_user_count_tags($user_id){
		$sql = 'SELECT COUNT(*) as count FROM tags WHERE user_id =?;';
		$array = array();				
		$query = $this->db->query( $sql , array($user_id));
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array[0]['count'];
		}
	} //get_user_count_tags

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function insert_user($array){
		$cleaned = $this->clean_db_array($array);
		$sql   = "INSERT INTO users VALUES (NULL,?,?,?,?,?,?,?,?,?,?);";
		$query = $this->db->query( $sql, $cleaned);	

		if($query){
			return $this->db->insert_id();
		} else {
			log_message('error', 'Insert Failed : [usermodel/insert_user]');
			return false;
		}

	} //insert_user

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_active($username){
		$sql   = "UPDATE users SET active = 1 WHERE username = ?;";
		$query = $this->db->query( $sql, array($username));	
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_active]');
			return false;
		}
	} //update_user_active

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_login_time($username, $time){
		$sql   = "UPDATE users SET date_last_login= ? WHERE username = ?;";
		$query = $this->db->query( $sql, array($time, $username));	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_login_time]');
			return false;
		}
	} //update_user_login_time

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_login_ip($username, $ip){
		$sql   = "UPDATE users SET last_ip_login=? WHERE username = ?;";
		$query = $this->db->query( $sql, array($ip, $username));	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_login_ip]');
			return false;
		}
	} //update_user_login_ip

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_tag($title, $user_id, $tag_id){
		$sql   = "UPDATE tags SET tag_keyword=? WHERE user_id =? AND tag_id = ?;";
		$query = $this->db->query( $sql, array($title, $user_id, $tag_id));	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_tag]');
			return false;
		}
	} //update_user_tag

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_sniplet($title, $text, $user_id, $sniplet_id){
		$text = mysql_real_escape_string($text);
		$sql   = "UPDATE sniplets SET sniplet_title='".$this->db->escape_str($title)."', sniplet_content='".$this->db->escape_str($text)."' WHERE user_id = '".$this->db->escape_str($user_id)."' AND sniplet_id = '".$this->db->escape_str($sniplet_id)."' ;";
		echo $sql;
		$query = $this->db->query( $sql);	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_sniplet]');
			return false;
		}
	} //update_user_sniplet

} //UserModal Class