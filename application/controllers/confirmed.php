<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Confirmed extends Base {

	public function index(){
		$userid = $this->input->get('id');
		$username = $this->input->get('username');
		echo $userid;
		echo $username;
	
	}
	
	
/*	
	public function account(){
	
		$this->load->model( 'UserModel' );
		$user = $this->input->get('u');
		
		$data['user'] = $user;
		
		$user_tags = $this->UserModel->get_user_tags($this->UserModel->get_user_id($user));
		
		echo '<pre>';
		print_r($user_tags);
		echo '</pre>';
		
		
		$this->load->view('user/profile', $data);
*/
/*
		$out = '';
		$out .= '<div id="sniplet_user" class="sniplet_min_height">';
		$out .= '<ul class="sniplet_user_ul">';
			
			//$out .= '<li class="snplet_user_li">';			
			//$out .= '<h4>Your Profile</h4>';			
			//$out .= '</li>';

			$out .= '<li class="snplet_user_li sniplet_user_tags">';			
			$out .= '<h4>'.$user.'\'s tags</h4>';		
			$out .= '</li>';

			$out .= '<li class="snplet_user_li sniplet_user_sniplets">';			
			$out .= '<h4>'.$user.'\'s sniplets</h4>';		
			$out .= '</li>';
				
		$out .= '</ul>';
		$out .= '</div>';
		
		echo $out; 

*/




/*

$data['site_logo'] = $this->logo();
			$data['tag_top_ten'] = $this->top_ten_tags();
			$this->dynView( 'frontend/main', 'Sniplets', $data);


		$this->load->model( 'ConfigModel' );	
			$displayLog = $this->ConfigModel->get_config('show_changelog');
		$displayAbout = $this->ConfigModel->get_config('show_about');
		$displayLogin = $this->ConfigModel->get_config('show_login');
		
		$data['show_login'] = $displayLogin;
		$data['show_about'] = $displayAbout;
		$data['show_log'] = $displayLog;
		$data['copyright'] = $this->copyright();
		$data['software_version'] = $this->version();
		$data['site_logo'] = $this->logo();

		if($this->session->userdata('login_state') == FALSE){							
			$this->bkView( 'globals/login', 'Sniplets', $data);
		} else {					
			echo 'You are already logged in. You should never reach this point.';
			redirect("/");  		
		} //else
		
		*/
		
	} //index
	
} //Login