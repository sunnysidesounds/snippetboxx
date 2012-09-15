<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Confirmed extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$userid = $this->input->get('id');
		$username = $this->input->get('username');
		echo $userid;
		echo $username;
	
	}
} //Confirmed