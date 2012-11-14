<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class User extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$this->load->model( 'UserModel' );
		//$user_tracker_info = $this->input->cookie('user_tracker_info', TRUE);
		$user_tracker_info = explode(", ", $this->input->cookie('user_tracker_info', TRUE));
		echo '<pre>';
		print_r($user_tracker_info);
		echo '</pre>';

/*
		if(is_array($user_tracker_info)){
			$user = $user_tracker_info[0];
			$tags = $this->UserModel->get_user_tags($this->UserModel->get_user_id($user));
			$sniplets = $this->UserModel->get_user_sniplets($this->UserModel->get_user_id($user));
			$email = $this->UserModel->get_user_email($user);
			
			$data = $this->set_site_assets();
			$data['tags_count'] = $this->UserModel->get_user_count_tags($this->UserModel->get_user_id($user));
			$data['sniplets_count'] = $this->UserModel->get_user_count_sniplets($this->UserModel->get_user_id($user));
			$data['user'] = $user;
			$data['user_tags'] = $this->display($tags, 'tags');
			$data['user_snips'] = $this->display($sniplets, 'sniplets');
			$data['gravatar'] = $this->build_gravatar($email);
			$data['user_year'] = $this->member_since($user);	

			$this->load->view('user/profile', $data);
		}
		
*/

	}//index

	

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function account(){
		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){

			$user = base64_decode($this->input->get('u'));
			$tags = $this->UserModel->get_user_tags($this->UserModel->get_user_id($user));
			$sniplets = $this->UserModel->get_user_sniplets($this->UserModel->get_user_id($user));
			$email = $this->UserModel->get_user_email($user);
			
			$data['tags_count'] = $this->UserModel->get_user_count_tags($this->UserModel->get_user_id($user));
			$data['sniplets_count'] = $this->UserModel->get_user_count_sniplets($this->UserModel->get_user_id($user));
			$data['user'] = $user;
			$data['user_tags'] = $this->display($tags, 'tags');
			$data['user_snips'] = $this->display($sniplets, 'sniplets');
			$data['gravatar'] = $this->build_gravatar($email);
			$data['gravatar_mini'] = $this->build_gravatar($email, 1);
			$data['user_year'] = $this->member_since($user);	

			$this->load->view('user/profile', $data);

		//If not session redirect to login
		} else {
			redirect('/login');
		}
		
	} //index
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function member_since($username){
		$this->load->model( 'UserModel' );
		$member_since = $this->UserModel->get_user_year($username);
		//Get year from date string
		$member_since = date('Y', strtotime($member_since));
		return $member_since;
	} //member_since

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function display($array, $set = ''){	
		$out = '';
		if($set == 'tags'){
			$out .= '<ul class="ul_user_list">';
			if(!empty($array)){
				foreach($array as $outer){
					$out .= '<li id="'.$outer[0].'" class="li_user_list li_user_tags">';
					$out .= '<a id="'.$outer[0].'" class="sniplet_tag_link sniplet_tag_link_'.$outer[0].'" href="#">'.$outer[1].'</a>';
					$out .= '<a id="'.$outer[0].'" class="sniplet_tag_edit sniplet_tag_edit_'.$outer[0].'" href="#">edit</a>';
					$out .= '</li>';					
				}
			} else {
			 	$out .= '<li class="li_user_list li_user_none">You don\'t have any '.$set.' yet!, <a href="#" id="'.$set.'_learn_more" class="user_learn_more" >Learn how...</a></li>';
			}
			$out .= '</ul>';
		
		} elseif($set == 'sniplets'){
			$out .= '<ul class="ul_user_list">';
			if(!empty($array)){
				foreach($array as $outer){
					$out .= '<li id="'.$outer[0].'" class="li_user_list li_user_sniplets">';
					$out .= '<a id="'.$outer[0].'" class="sniplet_link sniplet_link_'.$outer[0].'" href="#">'.$outer[1].'</a>';
					$out .= '<a id="'.$outer[0].'" class="sniplet_link_edit sniplet_link_edit_'.$outer[0].'" href="#">edit</a>';
					//$out .= '<a href="#">'.$outer[1].'</a>';
					//sniplet_link_edit_s_185

					$out .= '</li>';					
				}
			} else {
			 	$out .= '<li class="li_user_list li_user_none">You don\'t have any '.$set.' yet!, <a href="#" id="'.$set.'_learn_more" class="user_learn_more" >Learn how...</a></li>';
			}
			$out .= '</ul>';

		}
		//$out = '';
		//$out .= '<ul class="ul_user_list">';
		//if(!empty($array)){
		//	foreach($array as $outer){
		//		$out .= '<li id="'.$outer[0].'" class="li_user_list">';
		//		$out .= '<a href="#">'.$outer[1].'</a>';
		//		$out .= '</li>';					
		//	}
		//} else {
		// $out .= '<li class="li_user_list li_user_none">You don\'t have any '.$set.' yet!, <a href="#" id="'.$set.'_learn_more" class="user_learn_more" >Learn how...</a></li>';
		//}
		//$out .= '</ul>';
	
		return $out;
	
	}//display

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function build_gravatar($email, $set = 0){
		$this->load->model( 'ConfigModel' );	
		$default = $this->ConfigModel->get_config('gravatar_photo_default');
		if($set){
			$size = $this->ConfigModel->get_config('gravatar_mini_photo_size');
		} else {
			$size = $this->ConfigModel->get_config('gravatar_photo_size');		
		}
		
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( base_url() .'img/' . $default ) . "&s=" . $size;

		return $grav_url;
	} //build_gravatar

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function tags($user_id, $tag_id){
		echo $user_id . '---';
		echo $tag_id;

		return $grav_url;
	} //tags	

	
} //Login