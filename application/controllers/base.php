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
		$data['environment'] = ENVIRONMENT;
		$this->load->view('globals/template', $data);
	} // dynView

	/* --------------------------------------------------------------------------------------------------------------------------*/		
	public function logo(){
		$site_logo = $this->ConfigModel->get_config('site_logo');
		return $site_logo;
	} //logo

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function version(){
		$version = $this->ConfigModel->get_config('version');
		return $version;	
	} //version

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function copyright(){
		$copyright = $this->ConfigModel->get_config('copyright');
		return $copyright;	
	} //copyright

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function github_api_url(){
		$token = $this->ConfigModel->get_config('github_token');
		$perPage = $this->ConfigModel->get_config('github_commits_per_page');
		$url = 'https://api.github.com/repos/sunnysidesounds/snippetboxx/commits?access_token='.$token.'&per_page=' . $perPage;
		return $url;
	} //github_api_url

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function github_format_commits($date, $name, $message){
		$date = date("M d, Y", strtotime($date));
		$output = '';
		$output .= '<ul class="changelog_github_ul">';
			$output .= '<li class="changelog_github_li changelog_github_date">' . $date . '</li>';
			$output .= '<li class="changelog_github_li changelog_github_name">' . $name . '</li>';
			$output .= '<li class="changelog_github_li changelog_github_message">' . $message . '</li>';
		$output .= '</ul>';
    
		return $output;
	} //github_format_commits
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function changelog(){	
		$displayLog = $this->ConfigModel->get_config('show_changelog');
		if($displayLog){
			//$data = $this->set_site_assets();
			$data['display_changelog'] = $this->get_changelog();
			$this->load->view('frontend/changelog', $data);
		}
	} //changelog
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function get_changelog(){
		$filePath = $this->ConfigModel->get_config('changelog');
		$output = $this->get_curl_json($this->github_api_url());

		$array = array();
		foreach ($output as $github_key => $github_value) {				
			//if we know that the github_value is not an object we know something is wrong with the api connection
			if(is_object($github_value)){
				$array[] = $this->github_format_commits($github_value->commit->author->date, $github_value->commit->author->name, strtolower($github_value->commit->message));
			//fall back to old changelog file if api fails
			} else {
				log_message('error', 'get_changelog failed : github_value not a object [base/get_changelog]');
				return $this->read_file_to_array($filePath);
			}		
		}
		return $array;
	} //get_changelog

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_curl_json($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$output = json_decode($output);
		return $output;
	} //curl_json

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function read_file_to_array($filePath){
		$handle = @fopen($filePath, "r");
		$array = array();
		if ($handle) {
		    while (($buffer = fgets($handle, 4096)) !== false) {
		        $array[] = nl2br($buffer);
		    }
		    if (!feof($handle)) {
		        $array[] = "Error: unexpected fgets() fail\n";
		    }
		    return $array;

		    fclose($handle);		    
		}
	} //read_file

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function prep_password($password){
	     return sha1($password.$this->config->item('encryption_key'));
	} //prep_password

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function set_site_assets(){
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
		
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function set_signup_errors($error){	
		$data['signup_error'] = $error;	
		$data['ip_address'] = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$data['date_created'] = $time = date('m-d-Y-g:ia');	
	
		return $data;
	} //set_signup_errors

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
	} //tags_items

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function bookmarklet($environment){
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
		$data['x_iframe_reponse'] = $this->ConfigModel->get_config('x_iframe_reponse');
		$this->load->view('user/editor_noload', $data);
	} //no_iframe_load

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function dbug($value){
		$this->load->library('firephp');
		$this->firephp->log($value);
	} //dbug

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }

	    return false;
	} //in_array_r
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function parse_object_to_array($object) {
		$array = array();
		if (is_object($object)) {
			$array = get_object_vars($object);
		}
		return $array;
	}//parse_object_to_array

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function the_alphabet() {
		$array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		return $array;
	} //the_alphabet

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function search_items($array, $fancybox = 0, $title_length = 50){
		$this->load->model( 'SnipletModel' ); //TODO: Break this down to a smaller method. 
		$this->load->library('geshilib');
		$highlight_languages = explode(", ", $this->ConfigModel->get_config('highlight_languages'));		
		$this->SnipletModel->get_tag_list($array['sniplet_id'], $raw_array = 1);
		$formatTitle = substr($array['sniplet_title'], 0, $title_length); 
		
		echo '<ul class="sniplet_data_ul">';
			echo '<li class="sniplet_data_li sniplet_title"><a id="sniplet_id_'.$array['sniplet_id'].'" href="'.$array['sniplet_url'].'" target="_blank" title="'.$array['sniplet_title'].' --> ('.$array['sniplet_url'].')"> ' . $formatTitle . '... </a></li>';
			
			//This outputs syntax highlights depending on tag list 
			$snipletTags = $this->SnipletModel->get_tag_list($array['sniplet_id'], $raw_array = 1);		

			foreach($snipletTags as $snipArr){
				$cleaned = strip_tags(strtolower(trim($snipArr)));				
				if (in_array($cleaned, $highlight_languages)) {
					//If only one item					
					if(count($snipletTags) == 1){
						$setLanguageType = $cleaned;
					} else {
						$setLanguageType = strip_tags(strtolower(trim($snipletTags[0])));
						break;
					}				
				}else{
					$setLanguageType = $this->ConfigModel->get_config('highlight_languages_default');
					break;
				}
								
			} //foreach
								
			echo '<li id="sniplet_content_'.$array['sniplet_id'].'" class="sniplet_data_li sniplet_contents">' . $this->geshilib->highlight(htmlspecialchars_decode($array['sniplet_content']), $setLanguageType) . '</li>';
			$snipletTags = $this->SnipletModel->get_tag_list($array['sniplet_id'], 1);
			if(!empty($snipletTags)){
			$tags = implode(", ", $snipletTags);
			} else {
				$tags = 'N/A';
			}
		//	echo '<li class="sniplet_data_li sniplet_zoom"><img class="sniplet_zoom_img" src="'.base_url().'img/zoom_mag.png" alt="Zoom Sniplet" border="0" /></li>';
			echo '<li class="sniplet_data_li sniplet_tags"><b>tags:</b> ' . $tags . '</li>';
			echo '<li class="sniplet_data_li sniplet_created sniplet_time"><b>created at:</b> ' . $array['create_sniplet_time'] . '</li>';
			if(!empty($array['score'])){
				$score = number_format($array['score'], 4, '.', '');
				echo '<li class="sniplet_data_li sniplet_score"><b>search score:</b> ' . $score . '</li>';
			}
			if($fancybox == 0){
				echo '<li class="sniplet_data_li copy_it">
					<input type="image" title="view sniplet closer" class="view_sniplet_button" id="sniplet_view_'.$array['sniplet_id'].'" alt="view" src="'.base_url().'img/zoom_mag.png" >
					<input type="image" title="highlights text to copy" class="copy_sniplet_button" id="'.$array['sniplet_id'].'" alt="copy" src="'.base_url().'img/icon_copy.png">
					<input type="image" title="send email of sniplet" class="email_sniplet_button" id="sniplet_email_'.$array['sniplet_id'].'" alt="email" src="'.base_url().'img/icon_mail.png">
					</li>';
			} else {
				echo '<li class="sniplet_data_li copy_it">
					<input type="image" title="highlights text to copy" class="copy_sniplet_button copy_sniplet_fancy" id="'.$array['sniplet_id'].'" alt="copy" src="'.base_url().'img/icon_copy.png">
					</li>';
			}
			echo '<li class="sniplet_data_li status_message" id="status_message_'.$array['sniplet_id'].'">';
			echo '';
			echo '</li>';
			

		echo '</ul>';
	} //search_items

} //BaseController