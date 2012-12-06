<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Editor extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		//$userid = $this->input->get('id');
		//$username = $this->input->get('username');
		//echo $userid;
		//echo $username;
	
	}

	// CREATE!
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function create_tag(){


	} //create_tag

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function create_sniplet(){


	} //create_sniplet

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_form($id){
		$this->load->model( 'EditorModel' );
		$this->load->model( 'ConfigModel' );
		$this->load->model('UserModel');
		
		$data['id'] = $id;		
		$snipelt_title = $this->EditorModel->get_sniplet_by_id($id);
		//Set title, if no title use default from config table.
		if(!empty($snipelt_title)){
			$data['sniplet_title'] = $snipelt_title;

		} else {
			$data['sniplet_title'] = $this->ConfigModel->get_config('sniplet_default_title');
		}

		$data['sniplet_content'] = $this->EditorModel->get_sniplet_content_id($id);
		$data['tag_url'] = $this->EditorModel->get_tag_url($id);

		$new_tags_array = array();
		$tags_array = $this->EditorModel->get_tags_by_sniplet($id);
		
		//TODO: Probably need to change this to view all by user. As this list could become huge when loading the sniplet eidtor if all tags from everyone.
		$all_tags_array = $this->EditorModel->get_tag_all();

		if(!empty($tags_array)){
			foreach ($tags_array as $tag) {
				$new_tags_array[$tag] = $this->EditorModel->get_tag_by_id($tag);

				if (array_key_exists($tag, $all_tags_array)) {
				    unset($all_tags_array[$tag]);
				}	
			} //foreach
		} //if not empty


		$user_cookie_array = explode(", ", $this->input->cookie('user_tracker_info', TRUE));
		$username = $user_cookie_array[0];
		//$username_id = $this->UserModel->get_user_id($username);

		$data['sniplet_multiple_tags'] = $new_tags_array;
		$data['sniplet_multiple_all_tags'] = $all_tags_array;
		//$data['sniplet_multiple_all_tags'] = $all_tags_array;
		$data['sniplet_username'] = $username;

		$this->load->view('user/editor_sniplet', $data);
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tag_form($id){
		$this->load->model( 'ConfigModel' );
		$this->load->model( 'EditorModel' );
		
		$date = $this->EditorModel->get_tag_date($id);
		if(empty($date)){
			$date = $this->ConfigModel->get_config('tag_default_date');
		} 

		$data['tag_id'] = $id;
		$data['tag_content'] = $this->EditorModel->get_tag_by_id($id);
		$data['tag_date_created'] = $date;
		$data['tag_total'] = $this->EditorModel->get_tag_count($id);
		$this->load->view('user/editor_tag', $data);

	} //update_tag

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_sniplet($id){
		echo $id;


	} //update_sniplet


	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function get_tags_for_form(){

	
	} //taglet

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function get_prefill_categories($id){
		$this->load->model( 'EditorModel' );
		$new_tags_array = array();
		$tags_array = $this->EditorModel->get_tags_by_sniplet($id);
		$tags_array_count = count($tags_array);

		$json = '{items: [';
		foreach ($tags_array as $tag) {
			//Note for some reason there is a possible bug in the autoSuggest jquery plugin. I have to pass the tag name as value to work properly.
			$json .= '{value: "'.$this->EditorModel->get_tag_by_id($tag).'", name: "'.$tag.'"}';
			if($tags_array_count != 1){
				$json .= ',';
			}

		}
		$json .= ']}';
		
		echo $json;
	} //get_prefill_categories










} //Crud