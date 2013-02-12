<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Sniplet extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index($title){
		$this->page($title);
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function page($id){
		//$id = substr( $id, strrpos( $id, '_' )+1 );	
		
		$this->load->model( 'SearchModel' );
		$snipletArray = $this->SearchModel->sniplet_sniplet_id($id);
		echo '<pre>';
		//print_r($snipletArray);
		echo '</pre>';
		$data['sniplet_content'] = $snipletArray;
		$data = $this->set_site_assets();

		$data['this'] = 'test';
		$this->dynView( 'globals/sniplet', 'Sniplets', $data);

		 ///
		///$data = $this->set_site_assets();
		//$this->dynView( 'globals/sniplet', 'Sniplets', $data);

	}


} //Sniplet