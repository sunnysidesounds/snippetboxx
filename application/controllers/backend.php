<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );
ini_set('display_errors', 1);

class Backend extends Base {

	private $errorPleaseLogin = 'You must log in to see this page.';

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function sniplet(){
		$snipTagArray = array();
		$snipletCompleteArray = array();
		$errorsArray = array();
		
		//Load models
		$this->load->model( 'SnipletModel' );
		$this->load->model('UserModel');

		//Setup form values
		$snippet = $this->input->post('snippetText');			
		$tags = $this->input->post('as_values_snippetTaglet');
		$username_id = $this->UserModel->get_user_id($this->input->post('snippetUser'));
		$sendTo = $this->input->post('snippetSendTo');
		$pageTitle = $this->input->post('snippetPageTitle');
		$pageUrl = $this->input->post('snippetPageUrl');
		$currentTime = $this->input->post('snippetCurrentTime');
				
		$tagsArray = array_map('trim',explode(",",$tags)); //This trims white space from the values as the ajax send us a funky string
		$tagsArray = array_filter($tagsArray); //Filtering out all empty values due to the ending , coma in what ajax sends us.

		//Let's do some tag formatting
		$tagsArray = str_replace("_", "-", $tagsArray);
		$tagsArray = str_replace(" ", "-", $tagsArray);
										
		if($sendTo == 'db'){
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
		}elseif($sendTo == 'email'){
			if(!empty($tags)){
				$subject = $this->ConfigModel->get_config('subject_email');						
				$buildMessage = "TITLE: \n" . $pageTitle . "\n\n";
				$buildMessage .= "URL: \n" . $pageUrl . "\n\n";
				$buildMessage .= "SNIPLET: \n " . $snippet . "\n\n";		
				$buildMessage .= "TAGS: \n" . $tags . "\n\n";						
				$this->mailSniplet($subject, $buildMessage);
			} //empty tags
		} //send email	
	} //sniplet
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function mailConfirmation($to, $subject, $message){		
		$toEmail = $to;
		$fromEmail = $this->ConfigModel->get_config('from_email');
		$fullName = $this->ConfigModel->get_config('full_name');
		
		$this->load->library('email');
		
		$this->email->set_newline("\r\n");
		$this->email->from($fromEmail, $fullName);
		$this->email->to($toEmail);		
		$this->email->subject($subject);		
		
		$today_date = date("F j, Y");
		$today_date_formatted = $today_date."<br /><br />";
		
		//Set up message
		$email_message = $today_date_formatted .$message;
		
		$this->email->message($email_message);
		if($this->email->send()){
			return $this->ConfigModel->get_config('signup_success_message');
		} else {
			return $this->ConfigModel->get_config('signup_failure_message');
		}		
	} //mailConfirmation
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function mailSniplet($subject, $message){
		//Load model		
		$toEmail = $this->ConfigModel->get_config('to_email');
		$fromEmail = $this->ConfigModel->get_config('from_email');
		$fullName = $this->ConfigModel->get_config('full_name');
		
		$this->load->library('email');	
		$this->email->set_newline("\r\n");
		$this->email->from($fromEmail, $fullName);
		$this->email->to($toEmail);		
		$this->email->subject($subject);		
		
		$today_date = date("F j, Y");
		$today_date_formatted = $today_date."\n\n";
		
		//Set up message
		$email_message = $today_date_formatted .$message;
		
		$this->email->message($email_message);
		if($this->email->send()){
			echo 1;
		} else {
			show_error($this->email->print_debugger());
		}
	
	} //mailSniplet
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function taglet(){
		$this->load->model( 'SearchModel');		
		//Setup form values
		$term = $this->input->get('q');	
		$return = $this->SearchModel->tags_search_with_like($term);
		header("Content-type: application/json");
		echo json_encode($return);
	
	} //taglet
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function signup(){
		$this->load->model( 'AuthModel');
		$this->load->model( 'UserModel');

		//Set logo, top menu, version, copyright		
		$data = $this->set_site_assets();
		
		$username = $this->input->post('username', TRUE);
		$email = $this->input->post('email', TRUE);
		$password = $this->input->post('password', TRUE);
		$re_password = $this->input->post('re_password', TRUE);
		$default_group = $this->input->post('default_group', TRUE);
		$default_token = $default_group;
		$date_created = $this->input->post('date_created', TRUE);
		$ip_address = $this->input->post('ip_address', TRUE);
		
		//Verified username and email doesn't already exist
		$username_verified = $this->AuthModel->verify_username($username);
		$email_verified = $this->AuthModel->verify_user_email($email);
				
		//Check if username exsits using username and email
		if(!$username_verified && !$email_verified){
			//Check to make sure username is a-zA-Z0-9 and no whitespaces. 
			if(ctype_alnum($username) && !ctype_space($username)){

				//Check if passwords match
				if($password == $re_password){
					//Check that default group is zero, admin is 1
					if($default_group == 0){
						if (valid_email($email)){
							$user_ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];	
							$user_array = array($username, $email, $this->prep_password($password), $default_group, $default_token, $username, date('m-d-Y-g:ia'), date('m-d-Y-g:ia'), $user_ip,0);
							//Insert record
							$insert_user_data = $this->UserModel->insert_user($user_array);
							
							//If user data return numerical insert id continue
							if(is_numeric($insert_user_data)){
														
								//Send email get confirmation so we can change active from 0 to 1
								$emailConfirmationLink = '<a href="'.base_url().'confirmed?u='.base64_encode($username).'&e='.base64_encode($email).'&verify=1">HERE!</a>';
																
								$buildMessage = $this->ConfigModel->get_config('email_confirmation_message') . "<br /><br />";
								$buildMessage .= "<b>Your Username: </b>" . $username . "<br />";
								$buildMessage .= "<b>Your Email: </b>" . $email . "<br /><br />";	
								$buildMessage .= "To verify your account click this link " . $emailConfirmationLink;							
								$message = $buildMessage;
								
								$confirmMessage= $this->mailConfirmation($email, $this->ConfigModel->get_config('email_confirmation_subject'), $message);		
								//TODO: Add "Are you sure you want to leave this page javascript"
								redirect('login?m=' . base64_encode($confirmMessage) . '&signup=' . base64_encode($email));
							
							} else {
								$data = array_merge($data, $this->set_signup_errors($this->ConfigModel->get_config('error_user_invalid_insert_return')));
								$this->dynView( 'globals/signup', 'Sniplets - Sign-up', $data);							
							}				
						} else {
							$data = array_merge($data, $this->set_signup_errors($this->ConfigModel->get_config('error_user_invalid_email')));
							$this->dynView( 'globals/signup', 'Sniplets - Sign-up', $data);	
						}
					} else {
						//If default group is not zero. You should never reach this error unless your spoofing hidden values.
						$data = array_merge($data, $this->set_signup_errors($this->ConfigModel->get_config('error_user_default_group')));
						$this->dynView( 'globals/signup', 'Sniplets - Sign-up', $data);	
					}//default_group
				} else {
					//Merge errors data into data array for view
					$data = array_merge($data, $this->set_signup_errors($this->ConfigModel->get_config('error_user_password_match')));
					$this->dynView( 'globals/signup', 'Sniplets', $data);			
				}
				// password match
			//If bad character in username
			} else {
				//Merge errors data into data array for view
				$data = array_merge($data, $this->set_signup_errors($this->ConfigModel->get_config('error_user_username_error')));
				$this->dynView( 'globals/signup', 'Sniplets', $data);	
			}				
		} else {
			//Already an account (email/username match) direct to login page
			$data['login_error'] = $this->ConfigModel->get_config('error_user_exists_message');			
			$this->dynView( 'globals/login', 'Sniplets', $data);				
		}
		
	} //signup
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function verify(){
		$this->load->model( 'AuthModel');
		$this->load->model( 'TrackerModel' );
		$this->load->model( 'UserModel' );
		
		//Set logo, top menu, version, copyright	
		$data = $this->set_site_assets();
	
		$uname = $this->input->post('username');

		$from_bookmarklet = $this->input->post('bookmarklet');
		
		
		$user_ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];	
		$time = date('m-d-Y-g:ia');
		$pwd = $this->input->post('password');
		//Let's ci encrypt it
		$pwdpass = $this->prep_password($pwd);
		$login_status = $this->AuthModel->verify_login_status($uname, $pwdpass);
		
		if($login_status){
			$update_login_time = $this->UserModel->update_user_login_time($uname, $time);
			$update_login_ip = $this->UserModel->update_user_login_ip($uname, $user_ip);

			$this->session->set_userdata('login_state', TRUE);
			$time = date('m-d-Y-g:ia');
			$session_id = $this->session->userdata('session_id');
			$referer = $_SERVER['HTTP_REFERER'];
			$agent = $_SERVER['HTTP_USER_AGENT'];
			
			//Set user tracking
			$tracker_data = $uname . ', ' . $user_ip . ', ' . $time . ', ' . $agent . ', ' . $referer; //This needs some reworking, just getting basic cookie tracker stuff going. 
			
			$set_tracking_on_user = array('name'   => 'user_tracker_info', 'value'  => $tracker_data, 'expire' => '63072000', 'domain' => '.snippetboxx.com');	 //Expires in two years
			set_cookie($set_tracking_on_user);		

			

			
			//Insert user tracking into db
			//$tracker_insert_array = array('user_id' => $uname, 'tracker_ip' => $user_ip, 'tracker_region' => '', 'tracker_date_created' => $time, 'tracker_date_updated' => $time, 'tracker_clicks' => 'login-temp', 'username' => $uname, 'agent' => $agent, 'referer' => $referer);
	
			//$checking = $this->TrackerModel->check_ip($user_ip);
			//if($checking){
			//	$updating = $this->TrackerModel->update_tracker($tracker_insert_array);
			//} else {
			//	$inserting = $this->TrackerModel->insert_tracker($tracker_insert_array);
			//}
		
		} else {
			$this->session->set_userdata('login_state', FALSE);
			$set_tracking_on_user = array('name'   => 'user_tracker_info', 'value'  => null, 'expire' => '63072000', 'domain' => '.snippetboxx.com');					
			set_cookie($set_tracking_on_user);					

		}
		
		$session_status = $this->session->userdata('login_state');
		
		 if($session_status){
			//If you're on the main website
			//Main Site
			if(!$from_bookmarklet){
				echo base64_encode($uname);
			//Bookmarklet
			} else {
				redirect("snippetform.php");
			}
									
		 } else {
			$data['login_error'] = $this->ConfigModel->get_config('error_login_message');	
		 	
		 	if(!$from_bookmarklet){
					
				echo 'error:' . $data['login_error'];		 
		 	} else {
		 		redirect("snippetform.php?m=".$data['login_error']);
		 	}
		 }
		
	} //verify

	
	//Remove are move to a debugger controller
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function mysession(){
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
	}

	
	

} //Backend