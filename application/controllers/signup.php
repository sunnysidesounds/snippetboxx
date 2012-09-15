<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Signup extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$data = $this->set_site_assets();
		$data['ip_address'] = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$data['date_created'] = $time = date('m-d-Y-g:ia');
		if($this->session->userdata('login_state') == FALSE){							
			$this->bkView( 'globals/signup', 'Sniplets -Signup', $data);
		} else {					
			echo 'You are already signed up. You should never reach this point.';
			redirect("/");  		
		} //else
		
	} //index
	
} //Signup



