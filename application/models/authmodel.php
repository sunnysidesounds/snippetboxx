<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'basemodel.php' );

class AuthModel extends BaseModel {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function verify_login_status($dirty_user, $dirty_pass){ 

		//TODO: Make better and safer
		$clean_user = trim($dirty_user);
		$clean_pass = trim($dirty_pass);
		
		$sql = 'SELECT username, password FROM users WHERE username = "'. $clean_user .'" AND password = "'.$clean_pass.'" AND active = 1;'; 
		
		$query = $this->db->query( $sql );
		if($query->num_rows() == 1){
			return true;
	
		} else {
			return false;
		}
	} //verify_login_status
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
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
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
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
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function verify_confirmation($username, $email){
		$clean_email = trim($email);	
		$clean_username= trim($username);		
		$sql = 'SELECT id FROM users WHERE email = "'. $clean_email .'" AND username="'.$clean_username.'" ';
		$query = $this->db->query( $sql );
		if($query->num_rows() == 1){
			return true;	
		} else {
			return false;
		}		
	} //verify_confirmation



} //AuthModel