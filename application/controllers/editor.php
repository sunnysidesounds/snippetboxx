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

	//UPDATE
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_tag($id){
		$this->load->model( 'EditorModel' );
		$data['tag_content'] = $this->EditorModel->get_tag_by_id($id);
		$this->load->view('user/editor', $data);



	} //update_tag

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function update_sniplet($id){
		echo $id;


	} //update_sniplet











} //Crud