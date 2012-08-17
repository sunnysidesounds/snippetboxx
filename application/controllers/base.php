<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors', 1);

class Base extends CI_Controller {

/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function view( $view_name, $title ) {
		$this->view( $view_name, $title, null );
	} //view

/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function bkView( $view_name, $title, $data) {       
		$data['main_content'] = $view_name;
		$data['title'] = $title;
		$this->load->view('globals/bk_template', $data);
	} // dynView

		
/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function dynView( $view_name, $title, $data) {       
		$data['main_content'] = $view_name;
		$data['title'] = $title;
		$this->load->view('globals/template', $data);
	} // dynView


/* --------------------------------------------------------------------------------------------------------------------------*/		
	public function logo(){
		$this->load->model( 'ConfigModel' );
		$site_logo = $this->ConfigModel->get_config('site_logo');
		return $site_logo;
	} //logo


/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function version(){
		$this->load->model( 'ConfigModel' );
		$version = $this->ConfigModel->get_config('version');
		return $version;	
	} //version


/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function copyright(){
		$this->load->model( 'ConfigModel' );
		$copyright = $this->ConfigModel->get_config('copyright');
		return $copyright;	
	} //copyright
	

/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function changelog(){	
		$this->load->model( 'ConfigModel' );
		$filePath = $this->ConfigModel->get_config('changelog');
		$displayLog = $this->ConfigModel->get_config('show_changelog');
		if($displayLog){
			echo '<div id="sniplet_page" class="sniplet_min_height">';
			echo '<span id="backtohome"><a href="'.base_url().'" >back to home</a></span>';
			echo '<h3>Snippetboxx.com: (a.k.a Sniplet) ' . $this->version() . '</h3><br />';
			$this->get_changelog();
			echo '<br />File Source: ' . $filePath;		
			echo '</div>';
		}
	} //changelog
	
	
/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function get_changelog(){
		$this->load->model( 'ConfigModel' );
		$filePath = $this->ConfigModel->get_config('changelog');	
			$handle = @fopen($filePath, "r");
		if ($handle) {
		    while (($buffer = fgets($handle, 4096)) !== false) {
		        echo nl2br($buffer);
		    }
		    if (!feof($handle)) {
		        echo "Error: unexpected fgets() fail\n";
		    }
		    fclose($handle);		    
		}	
	} //get_changelog

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function prep_password($password){
	     return sha1($password.$this->config->item('encryption_key'));
	}	

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function set_site_assets(){
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
	
		return $data;
	} //set_site_assets
	
	
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function set_signup_errors($error){	
		$this->load->model( 'ConfigModel' );
		$data['signup_error'] = $error;	
		$data['ip_address'] = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$data['date_created'] = $time = date('m-d-Y-g:ia');	
	
		return $data;
	}
	
  	
} //BaseController