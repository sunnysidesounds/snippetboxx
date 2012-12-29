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
	public function display($array, $set = ''){	//TODO: Maybe move into base controller
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
	public function tag_update(){
		$this->load->model( 'UserModel' );
		$edit_tag_title = $this->input->post('edit_tag');
		$edit_tag_id = $this->input->post('edit_tag_id');
		$username = $this->input->post('username');
		$user_id = $this->UserModel->get_user_id($username);
		$update = $this->UserModel->update_user_tag($edit_tag_title, $user_id, $edit_tag_id);
		//Update tag update date
		$time = date('m-d-Y-g:ia');
		$this->UserModel->update_user_tag_time($user_id, $edit_tag_id, $time);

		if($update){
			echo 'Success in updating';
		} else {
			echo 'Error in updateing'; //TODO: Add logging
		}

	} //user_tag_update

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function sniplet_update(){
		//TODO: This method is to big, reduce it into smaller methods. 
		$this->load->model( 'UserModel' );
		$this->load->model( 'SnipletModel' );
		$title = $this->input->post('title');
		$text = $this->input->post('text');

		$post_array = array();
		$db_array = array();
		$delete_array = array();

		$tags = $this->input->post('tags');	
		$sniplet_id = $this->input->post('sniplet_id');		
		$username = $this->input->post('username');
		$user_id = $this->UserModel->get_user_id($username);	
		$update_time = $this->input->post('update_time');	

		$tagsArray = array_map('trim',explode(",",$tags)); //This trims white space from the values as the ajax send us a funky string
		$tagsArray = array_filter($tagsArray); //Filtering out all empty values due to the ending , coma in what ajax sends us.

		if(!empty($tags) && !empty($title) && !empty($user_id) && !empty($text)){
				//This updates title and text
				$update = $this->UserModel->update_user_sniplet($title, $text, $user_id, $sniplet_id);
				if($update){
					//$lastInsert = $this->db->insert_id();
					//Slap value in a aray
					$tags_array = explode(",", $tags);
					//Remove all empties from the exploded array
					$tags_array = array_filter($tags_array);

					//insert tags or return id that already exsist. 
					foreach($tags_array as $tag){
						$addTags = $this->SnipletModel->insert_tag($tag, $user_id);
						if(!empty($addTags)){
							$post_array[] = array('sniplet_id' => $sniplet_id, 'tag_id' => $addTags, 'user_id' => $user_id);
						}
					}

					//Get our current data set of tags - sniplet
					$db_arr = $this->SnipletModel->get_tag_list($sniplet_id, 0);
					foreach($db_arr as $tag_name){
						$db_array[] = array('sniplet_id' => $sniplet_id, 'tag_id' => $this->SnipletModel->get_tag_id($tag_name), 'user_id' => $user_id);
					}
					//Get counts
					$db_array_count = count($db_array);
					$post_array_count = count($post_array);

					//REPLACE: Update user tags. if tags posted are greater or equal replace and insert the new data. 
					if($post_array_count >= $db_array_count){
						echo ' post is equal or greater than db';
						foreach($post_array as $post_values){
							echo $this->UserModel->replace_user_tags($post_values['sniplet_id'], $post_values['tag_id'], $post_values['user_id']);
						}		

					//DELETE: If Update user tags, if tags posted are less than db. Then delete tag from db
					} elseif($post_array_count < $db_array_count){
						echo 'post is less than db';
						foreach ($db_array as $db_values) {
							//See if tag_id is present in a multipe dimentional array
							$value_set = $this->in_array_r($db_values['tag_id'], $post_array);
							//If it is not present in the post array, referencing the db values, let's remove it. 
							if($value_set != 1){
								echo 'deleting ' . $db_values['tag_id'];
								$this->UserModel->delete_user_tag($db_values['sniplet_id'], $db_values['tag_id'], $db_values['user_id']);
							}

						} //foreach
					} else {
						echo 'error';
					}
					$time = date('m-d-Y-g:ia');
					$this->UserModel->update_user_sniplet_time($user_id, $sniplet_id, $time);

				}
		}

	} //sniplet_update

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function user_tags_all(){
		$user = base64_decode($this->input->get('u'));
		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$tags = $this->UserModel->get_user_tags($this->UserModel->get_user_id($user));
			$tags_html = $this->display($tags, 'tags');
			echo $tags_html;

		} 
		//TODO: Add logic for failture ELSE clause
	} //user_tags_raw

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function user_sniplet_all(){
		$user = base64_decode($this->input->get('u'));
		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$sniplets = $this->UserModel->get_user_sniplets($this->UserModel->get_user_id($user));
			$sniplets_html = $this->display($sniplets, 'sniplets');
			echo $sniplets_html;
		} 
		//TODO: Add logic for failture ELSE clause
	} //user_sniplet_raw

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function user_tags_id(){
		$user = base64_decode($this->input->get('u'));
		$tag_id = $this->input->get('tid');

		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$sniplets = $this->UserModel->get_user_sniplets_by_tags($this->UserModel->get_user_id($user), $tag_id);			
			$sniplets_html = $this->display($sniplets, 'sniplets');
			echo $sniplets_html;
		} 

	} //user_tags_id	

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function user_sniplet_link(){
		$user = base64_decode($this->input->get('u'));
		$sniplet_id = $this->input->get('tid');
		$response_array = array();

		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$sniplets_link = $this->UserModel->get_user_link($this->UserModel->get_user_id($user), $sniplet_id);			
			//We need to check header reponse as some sites like Google have a DENY X-Frame-Options
			$check_header_reponse = $this->check_x_frame($sniplets_link);
			if(!$check_header_reponse){
				echo json_encode(array('link' => $sniplets_link));
			} else {
				echo json_encode(array('no-link' => $sniplets_link));
			}
		} 

	} //user_sniplet_link
	
} //User