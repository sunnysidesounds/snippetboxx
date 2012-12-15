<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'basemodel.php' );

class AuthModel extends BaseModel {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function verify_login_status($dirty_user, $dirty_pass){ 
		$clean_user = trim($dirty_user);
		$clean_pass = trim($dirty_pass);		
		$sql = 'SELECT username, password FROM users WHERE username = ? AND password = ? AND active = 1;'; 
		$query = $this->db->query( $sql,  array($clean_user, $clean_pass));
		if($query->num_rows() == 1){
			return true;
		} else {
			log_message('error', 'verify_login_status failed: [authmodel/verify_login_status]');
			return false;
		}
	} //verify_login_status
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function verify_username($username){
		$clean_username = trim($username);
		$sql = 'SELECT username FROM users WHERE username = ?;';
		$query = $this->db->query( $sql, array($clean_username));
		if($query->num_rows() == 1){
			return true;	
		} else {
			log_message('error', 'verify_login_status failed: [authmodel/verify_username]');
			return false;
		}	
	} //verify_username
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function verify_user_email($email){
		$clean_email = trim($email);		
		$sql = 'SELECT email FROM users WHERE email = ?;';
		$query = $this->db->query( $sql , array($clean_email));
		if($query->num_rows() == 1){
			return true;	
		} else {
			log_message('error', 'verify_user_email failed: [authmodel/verify_user_email]');
			return false;
		}		
	} //verify_user_email
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function verify_confirmation($username, $email){
		$clean_email = trim($email);	
		$clean_username= trim($username);		
		$sql = 'SELECT id FROM users WHERE email = ? AND username=?;';
		$query = $this->db->query( $sql, array($clean_email, $clean_username));
		if($query->num_rows() == 1){
			return true;	
		} else {
			log_message('error', 'verify_confirmation failed: [authmodel/verify_confirmation]');
			return false;
		}		
	} //verify_confirmation

} //AuthModel