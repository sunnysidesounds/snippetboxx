<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Logout extends Base {
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$data = $this->set_site_assets();
		$this->session->set_userdata('login_state', FALSE);
		$set_tracking_on_user = array('name'   => 'user_tracker_info', 'value'  => null, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
		set_cookie($set_tracking_on_user);	
		sleep(1);
		echo '<div id="logout_message">Logging out and redirecting....</div>';
		
		redirect("/"); 
	
	} //index	
}
