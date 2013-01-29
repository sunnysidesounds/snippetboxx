<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Confirmed extends Base {

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){
		$this->load->model( 'AuthModel');
		$this->load->model( 'UserModel');
		$email = base64_decode($this->input->get('e'));
		$username = base64_decode($this->input->get('u'));
		$verify = $this->input->get('verify');
		$verified = $this->AuthModel->verify_confirmation($username, $email);
		if($verified){
			$update_status = $this->UserModel->update_user_active($username);
			if($update_status){
				if($verify == 1){	
				redirect('login?m=' . base64_encode($this->ConfigModel->get_config('email_confirm_verified_success')));
				}
			} else {
				//TODO: Please log error here
			}

		} else {
			//TODO: Maybe redirect to something other then login page. 
			redirect('login?m=' . base64_encode($this->ConfigModel->get_config('email_confirm_verified_failure')));
		}
	}
} //Confirmed