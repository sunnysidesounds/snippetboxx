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
	public function get_user_tags_not_own($id){	
		$sql = 'SELECT t.tag_id, t.tag_keyword FROM tags t
			LEFT JOIN sniplets_to_tags stt ON stt.tag_id = t.tag_id
			WHERE stt.user_id = '.$this->db->escape_str($id).' and t.user_id != '.$this->db->escape_str($id).' GROUP BY t.tag_id;';
		$query = $this->db->query( $sql);	
		$parent = array();
		if($query->num_rows()>0){
			foreach ($query->result() as $row){													
				$parent[] = array($row->tag_id, $row->tag_keyword);				
			}	//foreach	
			
			return $parent;		
		}		
	} //get_user_tags_not_own

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
	public function get_user_sniplets($id, $limit = ''){
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}		
		$sql = 'SELECT * FROM sniplets WHERE user_id =? ORDER BY sniplet_title '.$limit.';';
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
	public function update_user_sniplet_time($username, $sniplet_id, $time){
		$sql   = "UPDATE sniplets SET update_sniplet_time= ? WHERE user_id = ? AND sniplet_id = ?;";
		$query = $this->db->query( $sql, array($time, $username, $sniplet_id));	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_sniplet_time]');
			return false;
		}
	} //update_user_sniplet_time

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_user_tag_time($username, $tag_id, $time){
		$sql   = "UPDATE tags SET update_tag_time= ? WHERE user_id = ? AND tag_id = ?;";
		$query = $this->db->query( $sql, array($time, $username, $tag_id));	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_tag_time]');
			return false;
		}
	} //update_user_tag_time



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
		$sql   = "UPDATE sniplets SET sniplet_title='".$this->db->escape_str($title)."', sniplet_content='".$text."' WHERE user_id = '".$this->db->escape_str($user_id)."' AND sniplet_id = '".$this->db->escape_str($sniplet_id)."' ;";
		$query = $this->db->query( $sql);	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/update_user_sniplet]');
			return false;
		}
	} //update_user_sniplet

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function replace_user_tags($sniplet_id, $tag_id, $user_id){
		$sql = 'REPLACE INTO sniplets_to_tags (sniplet_id, tag_id, user_id) VALUES ("'.$this->db->escape_str($sniplet_id).'", "'.$this->db->escape_str($tag_id).'", "'.$this->db->escape_str($user_id).'")';
		//echo $sql . '<br />';
		$query = $this->db->query( $sql);	
		
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/replace_user_tags]');
			return false;
		}
	} //replace_user_tags

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function delete_user_tag($sniplet_id, $tag_id, $user_id){
		$sql = 'DELETE FROM sniplets_to_tags WHERE sniplet_id = ? AND tag_id = ? AND user_id = ?;';
		$query = $this->db->query($sql, array($sniplet_id, $tag_id, $user_id));	
		if($query){
			return true;
		} else {
			log_message('error', 'Update Failed : [usermodel/delete_user_tag]');
			return false;
		}
	} //delete_user_tag

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function delete_sniplet_tags($sniplet_id, $user_id){
		$sql = 'DELETE FROM sniplets_to_tags WHERE sniplet_id = ? AND user_id = ?;';
		$query = $this->db->query($sql, array($sniplet_id, $user_id));	
		if($query){
			log_message('info', 'Delete Success: [usermodel/delete_user_sniplet] (user_id: '.$user_id.' - sniplet_id:' . $sniplet_id . ')');
			return true;
		} else {
			log_message('error', 'Delete Failed : [usermodel/delete_user_sniplet]');
			return false;
		}

	} //delete_sniplet_tags	

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function delete_sniplet($sniplet_id, $user_id){
		$sql = 'DELETE FROM sniplets WHERE sniplet_id = ? AND user_id = ?;';
		$query = $this->db->query($sql, array($sniplet_id, $user_id));	
		if($query){
			log_message('info', 'Delete Success: [usermodel/delete_user_sniplet] (user_id: '.$user_id.' - sniplet_id:' . $sniplet_id . ')');
			return true;
		} else {
			log_message('error', 'Delete Failed : [usermodel/delete_sniplet]');
			return false;
		}
		
	} //delete_sniplet	



} //UserModal Class