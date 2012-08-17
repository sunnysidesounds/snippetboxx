<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AuthModel extends CI_Model {

	public function verify_login_status($dirty_user, $dirty_pass){ 

		//TODO: Make better and safer
		$clean_user = trim($dirty_user);
		$clean_pass = trim($dirty_pass);
		
		$sql = 'SELECT username, password FROM users WHERE username = "'. $clean_user .'" AND password = "'.$clean_pass.'" ';
		
		$query = $this->db->query( $sql );
		if($query->num_rows() == 1){
			return true;
	
		} else {
			return false;
		}
	} //verify_login_status
	
	
	public function verify_username($username){
		$clean_username = trim($username);
		
		$sql = 'SELECT username FROM users WHERE username = "'. $clean_username .'" ';
		$query = $this->db->query( $sql );
		if($query->num_rows() == 1){
			return true;	
		} else {
			return false;
		}	
	} //verify_username
	
	
	public function verify_user_email($email){
		$clean_email = trim($email);		
		$sql = 'SELECT email FROM users WHERE email = "'. $clean_email .'" ';
		$query = $this->db->query( $sql );
		if($query->num_rows() == 1){
			return true;	
		} else {
			return false;
		}		
	} //verify_user_email
	


} //AuthModel