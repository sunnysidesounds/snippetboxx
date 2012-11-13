<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Login extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$data = $this->set_site_assets();
		$m = base64_decode($this->input->get('m'));
		$signup = base64_decode($this->input->get('signup'));	
		if($m){
			$data['login_error'] = $m;
		}
		if($signup){
			$data['signup_email'] = $signup;
		}
		if($this->session->userdata('login_state') == FALSE){							
			$this->dynView( 'globals/login', 'Sniplets - Login', $data);
		} else {					
			echo 'You are already logged in. You should never reach this point.';
			redirect("/");  		
		} //else
		
	} //index
	
} //Login

