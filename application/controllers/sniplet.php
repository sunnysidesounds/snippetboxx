<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Sniplet extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index($title){
		$this->page($title);
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function page($value){
		
		$this->load->model( 'SearchModel' );
		$snipletArray = $this->SearchModel->sniplet_sniplet_id($value);
		$data = $this->set_site_assets();

		$data['content_sniplet'] = $snipletArray;
		$this->dynView( 'globals/sniplet', 'Sniplets', $data);


	}


} //Sniplet