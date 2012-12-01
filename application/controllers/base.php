<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors', 1);

class Base extends CI_Controller {

/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function view( $view_name, $title ) {
		$this->view( $view_name, $title, null );
	} //view
		
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
		$displaySignup = $this->ConfigModel->get_config('show_signup');
		$data['show_login'] = $displayLogin;
		$data['show_about'] = $displayAbout;
		$data['show_signup'] = $displaySignup;
		$data['show_log'] = $displayLog;
		$data['copyright'] = $this->copyright();
		$data['software_version'] = $this->version();
		$data['site_logo'] = $this->logo();
		$data['tag_top_ten'] = $this->top_ten_tags();		
	
		return $data;
	} //set_site_assets
	

/*
			$displayLog = $this->ConfigModel->get_config('show_changelog');
			$displayAbout = $this->ConfigModel->get_config('show_about');
			$displayLogin = $this->ConfigModel->get_config('show_login');
			$displaySignup = $this->ConfigModel->get_config('show_signup');
			
			$data['show_login'] = $displayLogin;
			$data['show_about'] = $displayAbout;
			$data['show_log'] = $displayLog;
			$data['show_signup'] = $displaySignup;
			$data['copyright'] = $this->copyright();
			$data['software_version'] = $this->version();
			$data['site_logo'] = $this->logo();
			$data['tag_top_ten'] = $this->top_ten_tags();
*/

	
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function set_signup_errors($error){	
		$this->load->model( 'ConfigModel' );
		$data['signup_error'] = $error;	
		$data['ip_address'] = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$data['date_created'] = $time = date('m-d-Y-g:ia');	
	
		return $data;
	}


	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function tags_items($array){
		$this->load->model( 'SearchModel' );
		$out = '';
		$out = '<ul class="tags_top_ten_ul">';
		$out .= '<li class="tags_top_ten_li"><h5>Popular Tags: </h5></li>';
		if(!empty($array)){
			foreach($array as $tTen){
				$tagName = $this->SearchModel->tag_name_by_id($tTen);
				if($tagName != ''){
					$out .= '<li class="tags_top_ten_li">';
					$out .= '<a href="#" id="tagid_'.$tTen.'" class="top_ten_a">' . strtolower($tagName) . '</a>';
					$out .= '</li>';
				}
			}
		}
		
		$out .= '</ul>';
		
		return $out;
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function bookmarklet($environment){
		$this->load->model( 'ConfigModel' );
		$bookmarklet = '';
		$bookmarklet .= '<div id="bookmarklet_container"> ';
			$bookmarklet .= '<div id="bookmarklet_your_mark">your bookmarklet</div>';
			$bookmarklet .= '<div id="bookmarklet_me">';
		switch ($environment) {
		    case "dev":
		        $bookmarklet .= $this->ConfigModel->get_config('bookmarklet_development_code');
		        break;
		    case "www":
		        $bookmarklet .= $this->ConfigModel->get_config('bookmarklet_master_code');
		        break;
		}
			$bookmarklet .= '</div>';
			$bookmarklet .= '<div id="sniplet_copy_container">';
				$bookmarklet .= '<input type="image" title="Highlights text to Copy!" class="copy_sniplet_button copy_sniplet_fancy" id="copy_bookmarklet" alt="Copy!" src="'.base_url().'img/icon_copy.png">';
				$bookmarklet .= '<div id="sniplet_copy_text">click to highlight</div>';
			$bookmarklet .= '</div>';
		$bookmarklet .= '</div> ';

		echo $bookmarklet;
	
	} //bookmarklet	
  
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function top_ten_tags(){
		$out = '';	
		$this->load->model( 'SearchModel' );
		$topTen = $this->SearchModel->tags_count_ids();		
		$out .= $this->tags_items($topTen);
		return $out;
	
	} //top_ten_tags	

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function check_x_frame($url){
		//$url = $this->input->get('url');
		$xframe = 'X-Frame-Options';
		$xframeOptions = array('DENY', 'SAMEORIGIN');

		$header = get_headers($url, 1);

		if (array_key_exists($xframe, $header)) {
    			$reponse = $header[$xframe];
    			if(in_array($reponse, $xframeOptions)){
    				return 1;
    				//TODO: Add logging here. 
    			}
		}else {
			return 0;
		}

	} //check_x_frame

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function check_x_frame_get(){ //Get request version
		$url = $this->input->get('url');
		$xframe = 'X-Frame-Options';
		$xframeOptions = array('DENY', 'SAMEORIGIN');

		$header = get_headers($url, 1);

		if (array_key_exists($xframe, $header)) {
    			$reponse = $header[$xframe];
    			if(in_array($reponse, $xframeOptions)){
    				return 1;
    				//TODO: Add logging here. 
    			}
		}else {
			return 0;
		}

	} //check_x_frame

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function no_iframe_load(){
		$this->load->model( 'ConfigModel' );
		$data['x_iframe_reponse'] = $this->ConfigModel->get_config('x_iframe_reponse');
		$this->load->view('user/editor_noload', $data);
	} //no_iframe_load

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function dbug($value){
		$this->load->library('firephp');
		$this->firephp->log($value);
	} //dbug



} //BaseController