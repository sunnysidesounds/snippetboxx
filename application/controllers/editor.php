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

		$data['id'] = $id;

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

		$data['tag_content'] = $this->EditorModel->get_tag_by_id($id);
		$data['tag_date_created'] = $date;
		$data['tag_total'] = $this->EditorModel->get_tag_count($id);
		$this->load->view('user/editor_tag', $data);

	} //update_tag

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_sniplet($id){
		echo $id;


	} //update_sniplet











} //Crud