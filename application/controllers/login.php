<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Login extends Base {

	public function index(){
		

		
		$data = $this->set_site_assets();

		if($this->session->userdata('login_state') == FALSE){							
			$this->bkView( 'globals/login', 'Sniplets - Login', $data);
		} else {					
			echo 'You are already logged in. You should never reach this point.';
			redirect("/");  		
		} //else
		
	} //index
	
} //Login

