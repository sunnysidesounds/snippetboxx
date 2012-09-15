<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class User extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function account(){
	
		$this->load->model( 'UserModel' );
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
		$data['user_year'] = $this->member_since($user);	

		$this->load->view('user/profile', $data);
		
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
	public function display($array, $none = ''){	

		$out = '';
		$out .= '<ul class="ul_user_list">';
		if(!empty($array)){
			foreach($array as $outer){
				$out .= '<li id="'.$outer[0].'" class="li_user_list">';
				$out .= '<a href="#">'.$outer[1].'</a>';
				$out .= '</li>';					
			}
		} else {
		 $out .= '<li class="li_user_list li_user_none">You don\'t have any '.$none.' yet!, <a href="#" id="'.$none.'_learn_more" class="user_learn_more" >Learn how...</a></li>';
		}
		$out .= '</ul>';
	
		return $out;
	
	}//display

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function build_gravatar($email){
		$this->load->model( 'ConfigModel' );	
		$default = $this->ConfigModel->get_config('gravatar_photo_default');
		$size = $this->ConfigModel->get_config('gravatar_photo_size');
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( base_url() .'img/' . $default ) . "&s=" . $size;

		return $grav_url;
	} //build_gravatar
	
} //Login