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
	public function account_settings(){
		$this->load->model( 'UserModel' );
		$username = base64_decode($this->input->get('u'));
		$session_status = $this->session->userdata('login_state');

		if($session_status){
			$user_settings = $this->UserModel->get_user_settings($this->UserModel->get_user_id($username));
			$user_groups = $this->UserModel->get_user_groups($this->UserModel->get_user_id($username));
			$this->ConfigModel->get_config_all();
			
			//echo serialize(array('username', 'email'));

			//$data['username'] = $username;
			//$data['email'] = $user_settings->email;
			//$data['date_created'] = $user_settings->date_created;
			//$data['date_last_login'] = $user_settings->date_last_login;

			$user_settings = $this->parse_object_to_array($user_settings);


			$field_set_array = array();
			$set_values_array = array();
			$set_describe_array = array();

			//Merge all field+sets
			foreach ($user_groups as $group_id) {
				$field_set= explode(", ", $group_id['field_set']);
				foreach ($field_set as $set) {
					$field_set_array[] = $set;
				}
			}

			//Builds our valued pair list to display editable fields. 
			if(in_array('all', $field_set_array)){			
				$configuration = $this->ConfigModel->get_config_all();
				foreach ($field_set_array as $vals) {
					if(array_key_exists($vals, $user_settings)){
						$set_values_array[$vals] = $user_settings[$vals];
					}
				}				



				foreach ($configuration as $k => $v) {
					$v = $this->parse_object_to_array($v);
					$set_values_array[$v['config_title']] = $v['config_value'];
					$set_describe_array[$v['config_title']] = $v['config_description'];
					//echo $v['config_description'];
				}
		
			} else {
				foreach ($field_set_array as $vals) {
					if(array_key_exists($vals, $user_settings)){
						$set_values_array[$vals] = $user_settings[$vals];
					}
				}
			}

			$data['field_describe'] = $set_describe_array;
			$data['field_sets'] = $set_values_array;
			$this->load->view('user/settings', $data);

		}


	} //account_settings

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function account(){
		$this->load->model( 'UserModel' );
		$limit = $this->ConfigModel->get_config('user_profile_sniplet_record_default');
		$session_status = $this->session->userdata('login_state');

		//Check if user has a session
		if($session_status){

			$user = base64_decode($this->input->get('u'));
			$tags = $this->UserModel->get_user_tags($this->UserModel->get_user_id($user));
			$sniplets = $this->UserModel->get_user_sniplets($this->UserModel->get_user_id($user), $limit);
			$email = $this->UserModel->get_user_email($user);

			$data['alphabet_list'] = $this->the_alphabet();
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
		$this->load->model( 'UserModel' );
		$sniplet_length = $this->ConfigModel->get_config('user_sniplet_string_length');	
		//TODO: Split this out into other methods/functions
		$out = '';
		if($set == 'tags'){
			$out .= '<ul class="ul_user_list">';
			if(!empty($array)){
				foreach($array as $outer){
					$out .= '<li id="'.$outer[0].'" class="li_user_list li_user_tags">';
					$out .= '<a title="'.$outer[1].'" id="'.$outer[0].'" class="sniplet_tag_link sniplet_tag_link_'.$outer[0].'" href="#">'.$outer[1].'</a>';
					$out .= '<a title="edit your tag" id="'.$outer[0].'" class="sniplet_tag_edit sniplet_tag_edit_'.$outer[0].'" href="#">edit</a>';
					$out .= '</li>';					
				}
			} else {
			 	$out .= '<li class="li_user_list li_user_none">You don\'t have any '.$set.' yet!, <a href="#" id="'.$set.'_learn_more" class="user_learn_more" >Learn how...</a></li>';
			}
			$out .= '</ul>';

		} elseif($set == 'tags_not_owned'){
			$out .= '<ul class="ul_user_list">';
			if(!empty($array)){
				foreach($array as $outer){
					$tag_owner = $this->UserModel->get_user_tag($outer[0]);
					$out .= '<li id="'.$outer[0].'" class="li_user_list li_user_tags">';
					$out .= '<a title="'.$outer[1].'" id="'.$outer[0].'" class="sniplet_tag_link sniplet_tag_link_'.$outer[0].'" href="#">'.$outer[1].'</a>';
					$out .= '<a title="" id="'.$outer[0].'" class="sniplet_tag_linkz sniplet_tag_linkz_'.$outer[0].'" href="#">owner: <b>'.$tag_owner.'</b></a>';
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
					//Limit our user sniplet string length
					if (strlen($outer[1]) > $sniplet_length){
   						$sniplet_title = substr($outer[1], 0, $sniplet_length) . '...';
   					} else {
   						$sniplet_title = $outer[1];
   					}

					$out .= '<li id="'.$outer[0].'" class="li_user_list li_user_sniplets">';
					
				//	$out .= '<a title="'.$outer[1].'" id="'.$outer[0].'" class="sniplet_link sniplet_link_'.$outer[0].'" href="#">'.$sniplet_title.'</a>';

					$out .= '<a title="'.$outer[1].'" id="'.$outer[0].'" class="sniplet_link sniplet_link_'.$outer[0].'" href="#">'.$sniplet_title.'</a>';
					$out .= '<a title="delete your sniplet" id="'.$outer[0].'" class="sniplet_link_delete sniplet_link_delete_'.$outer[0].'" href="#">delete</a>';
					$out .= '<a title="edit your sniplet" id="'.$outer[0].'" class="sniplet_link_edit sniplet_link_edit_'.$outer[0].'" href="#">edit</a>';
					$out .= '<a title="view url of your sniplet" id="'.$outer[0].'" class="sniplet_link_url sniplet_link_url_'.$outer[0].'" href="#">url</a>';
					$out .= '<a title="view your sniplet" id="'.$outer[0].'" class="sniplet_link sniplet_link_view sniplet_link_view_'.$outer[0].'" href="#">view</a>';
					$out .= '</li>';					
				}
			} else {
			 	$out .= '<li class="li_user_list li_user_none">You don\'t have any '.$set.' yet!, <a href="#" id="'.$set.'_learn_more" class="user_learn_more" >Learn how...</a></li>';
			}
			$out .= '</ul>';

		}
	
		return $out;
	
	}//display

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function build_gravatar($email, $set = 0){
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

		//Let's do some tag formatting
		$edit_tag_title = str_replace("_", "-", $edit_tag_title);
		$edit_tag_title = str_replace(" ", "-", $edit_tag_title);
		$update = $this->UserModel->update_user_tag(strtolower($edit_tag_title), $user_id, $edit_tag_id);
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
	public function sniplet_delete(){
		$this->load->model( 'UserModel' );
		$this->load->model( 'EditorModel' );
		$sniplet_id = $this->input->get('tid');
		$sniplet_title_raw = $this->EditorModel->get_sniplet_by_id($sniplet_id);
		//TODO: Turn string length into it's own method. 
		$sniplet_length = $this->ConfigModel->get_config('user_sniplet_string_length');
		if (strlen($sniplet_title_raw) > $sniplet_length){
				$sniplet_title = substr($sniplet_title_raw, 0, $sniplet_length) . '...';
			} else {
				$sniplet_title = $sniplet_title_raw;
			}

		$out = '';	
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$out .= '<div id="delete_sniplet_container">';
			$out .= '<div id="delete_sniplet_text">Are you sure you want to delete? <br /> <div>' . $sniplet_title . '</span></div>';
			$out .= '<a title="yes delete sniplet!" id="'.$sniplet_id.'" class="sniplet_delete_yes" href="#">yes</a> ';
			$out .= '<a title="no don\'t delete sniplet!" class="sniplet_delete_no" href="#">no</a>';
			$out .= '</div>';
		}
		echo $out;

	} //sniplet_delete

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function sniplet_delete_confirm(){
		$this->load->model( 'UserModel' );
		$username = base64_decode($this->input->post('u'));
		$user_id = $this->UserModel->get_user_id($username);
		$sniplet_id = $this->input->post('tid');
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$deleteSet1 = $this->UserModel->delete_sniplet($sniplet_id, $user_id);
			$deleteSet2 = $this->UserModel->delete_sniplet_tags($sniplet_id, $user_id);
			if($deleteSet1 && $deleteSet2){
				echo $sniplet_id;
			}
		}
	} //sniplet_delete_confirm

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function sniplet_create(){
		$this->load->model( 'UserModel' );
		$this->load->model( 'SnipletModel' );
		$pageTitle = $this->input->post('title');
		$snippet = $this->input->post('text');
		$tags = $this->input->post('tags');
		$username = $this->input->post('username_id');
		$username_id = $this->UserModel->get_user_id($username);
		$pageUrl = $this->input->post('sniplet_url');
		$currentTime = $this->input->post('sniplet_create_time');
 
		$tagsArray = array_map('trim',explode(",",$tags)); //This trims white space from the values as the ajax send us a funky string
		$tagsArray = array_filter($tagsArray); //Filtering out all empty values due to the ending , coma in what ajax sends us.
		$tagsArray = str_replace("_", "-", $tagsArray);
		$tagsArray = str_replace(" ", "-", $tagsArray);

		if(!empty($tags) && !empty($snippet) && !empty($username_id)){
							
			//Insert sniplet
			$addSniplet = $this->SnipletModel->insert_sniplet($pageTitle, $snippet, $pageUrl, $currentTime, $username_id);				
			
			//if insert of sniplet did not fail
			if($addSniplet){
				//Get last insert ID
				$lastInsert = $this->db->insert_id();
				//Insert only when we have the last insert id
				if(isset($lastInsert)){

					//Insert tags
					foreach($tagsArray as $tag){
						//insert and return tag ip
						$addTags = $this->SnipletModel->insert_tag(strtolower($tag), $username_id);
						//Build sniplet tag pairs to insert
						if(!empty($addTags)){
							//sniplet id, tag id
							$snipTagPairs = $lastInsert . ', ' . $addTags;												
							$snipTagArray[] = $snipTagPairs;
						}
					}
				
					//Insert sniplet to tags
					foreach($snipTagArray as $pairs){
						$pairsArray = explode(", ", $pairs);
						$sniplet_id = $pairsArray[0];
						$tag_id = $pairsArray[1];						
						$addSnipletToTag = $this->SnipletModel->insert_sniplet_to_tag($sniplet_id, $tag_id, $username_id);
						$snipletCompleteArray[] = $addSnipletToTag;
					}
					
					//Make sure all complete values are one
					$return = count(array_unique($snipletCompleteArray)) == 1;
					if($return == 1){
						echo $return;
					} else {
						echo 'error';
						 log_message('error', '$return is not 1 [backend/sniplet]');
					}								
				} //lastinsert								
				
			} else {
				//insert failed
				echo 'insert failed somehow';
				log_message('error', 'insert failed somehow [backend/sniplet]');
			}
		
		} else{
			if(empty($tags)){
				$errorsArray['tags'] = 'empty';
			}			
			if(empty($snippet)){
				$errorsArray['sniplet'] = 'empty';
			}
			header("Content-type: application/json");
			echo json_encode($errorsArray);
		
		} //tags empty

	} //sniplet_create

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

		//Let's do some tag formatting (Probably not need this as it's in the model.)
		$tagsArray = str_replace("_", "-", $tagsArray);
		$tagsArray = str_replace(" ", "-", $tagsArray);

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
						$addTags = $this->SnipletModel->insert_tag(strtolower($tag), $user_id);
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
	public function user_tags_not_owned(){
		$user = base64_decode($this->input->get('u'));
		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){	
			$tags = $this->UserModel->get_user_tags_not_own($this->UserModel->get_user_id($user));
			$tags_html = $this->display($tags, 'tags_not_owned');
			echo $tags_html;
		} 
		//TODO: Add logic for failture ELSE clause
	} //user_tags_not_owned

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
		$limit = $this->ConfigModel->get_config('user_profile_sniplet_record_default');
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$sniplets = $this->UserModel->get_user_sniplets($this->UserModel->get_user_id($user), $limit);
			$sniplets_html = $this->display($sniplets, 'sniplets');
			echo $sniplets_html;
		} 
		//TODO: Add logic for failture ELSE clause
	} //user_sniplet_raw

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function user_alphabet(){
		$user = base64_decode($this->input->get('u'));
		$letter = $this->input->get('letter');

		$this->load->model( 'UserModel' );
		$session_status = $this->session->userdata('login_state');
		//Check if user has a session
		if($session_status){
			$sniplets = $this->UserModel->get_user_sniplets_by_alphabet($this->UserModel->get_user_id($user), $letter);			
			$sniplets_html = $this->display($sniplets, 'sniplets');
			echo $sniplets_html;
		} 
	} //user_alphabet	

	//sniplet_count_alpha

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function user_alphabet_count(){
		$user = base64_decode($this->input->get('u'));
		$letter = $this->input->get('letter');
		$this->load->model( 'UserModel' );
		$this->load->model( 'SearchModel' );
	    	$count = $this->SearchModel->sniplet_count_alpha($letter, $this->UserModel->get_user_id($user));
		$jsonEncodeTypes = json_encode($count);
		echo $jsonEncodeTypes;

	} //user_alphabet	

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