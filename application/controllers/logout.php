<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Logout extends Base {
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$data = $this->set_site_assets();
		$this->session->set_userdata('login_state', FALSE);
		//Delete Tracker Cookie
		$set_tracking_on_user = array('name'   => 'user_tracker_info', 'value'  => null, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
		set_cookie($set_tracking_on_user);	
		//Delete Profile Header Cookie
		$profile_header = array('name'   => 'sniplet_show_header', 'value'  => null, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
		set_cookie($profile_header);	

		sleep(1);
		echo '<div id="logout_message">Logging out and redirecting....</div>';
		
		redirect("/"); 
	
	} //index	
}
