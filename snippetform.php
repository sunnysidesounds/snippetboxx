	<?php
		//TODO: Move this file to a controller in ci, maybe??
		//Get ci config base url dynamically, So we don't have to change later.
		ob_start();
		include('index.php');
		ob_end_clean();
		$CI =& get_instance();
		$CI->load->library('config');
		$baseUrl = $CI->config->base_url();

		$defaultTitle = 'Sniplet Title Placeholder';
		$defaultSniplet = 'No content selected for this sniplet, try again!';
		$defaultUrl = $baseUrl;

		$login_error = $CI->input->get('m');

		//If not logged in, save on load data to reload after login
		if(!$CI->input->cookie('user_tracker_info', TRUE)){
			//Let's make sure all the values are set before we create a cookie
			if(isset($_GET['title']) && isset($_GET['sniplet']) && isset($_GET['url'])){
				$temp_bkm = $_GET['title'] . '///' . $_GET['snippet'] . '///' . $_GET['url'] ; //This needs some reworking, just getting basic cookie tracker stuff going. 
				$temp_data = array('name'   => 'sniplet_bkdata', 'value'  => $temp_bkm, 'expire' => '63072000', 'domain' => '.snippetboxx.com'); 
				set_cookie($temp_data);
			}

		} else {
			$temp_data = array('name'   => 'sniplet_bkdata', 'value'  => null, 'expire' => '63072000', 'domain' => '.snippetboxx.com'); 
			set_cookie($temp_data);
		}

		//Get set cookie and transform into an array
		$bkdata_cookie = $CI->input->cookie('sniplet_bkdata', TRUE);
		$bkdata_array = explode("///", $CI->input->cookie('sniplet_bkdata', TRUE));
	
		if(isset($_GET['snippet'])){
			$text = $_GET['snippet'];	
		} else {
			//If cookie value is not empty, use the value to display after login
			if(!empty($bkdata_array[1])){
				$text = $bkdata_array[1];
			} else {
				$text = $defaultSniplet;	
			}	
		}
		if(isset($_GET['url'])){
			$url = $_GET['url'];
		} else {
			//If cookie value is not empty, use the value to display after login
			if(!empty($bkdata_array[2])){
				$url = $bkdata_array[2];
			} else {
				$url = $baseUrl;
			}
		}
		//TODO: Fix this when moving to controller, make a method
		if(isset($_GET['title'])){
			
			$title = $_GET['title'];

			//Remove special characters from page title, because it's easier that way
			$titleRmSC = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $title);
			
			$characterLimit = 55;
			//If title is greater than character limit display dots and limit display
			if(strlen($titleRmSC) > $characterLimit){
				$titleLmt = substr($titleRmSC,0,$characterLimit) . " ...";
			} else {
				$titleLmt = $titleRmSC;
			}		


		} else {
			if(!empty($bkdata_array[0])){
				$title = $bkdata_array[0];
				$titleRmSC = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $title);
				
				$characterLimit = 55;
				//If title is greater than character limit display dots and limit display
				if(strlen($titleRmSC) > $characterLimit){
					$titleLmt = substr($titleRmSC,0,$characterLimit) . " ...";
				} else {
					$titleLmt = $titleRmSC;
				}
			} else{
				$titleLmt = $defaultTitle;
			}
		}

	
	?>

<html>
	<head>	
				
		<!-- CSS -->
		<link rel='stylesheet' type='text/css' href='<? echo $baseUrl; ?>css/styles.css' />
		<link rel='stylesheet' type='text/css' href='<? echo $baseUrl; ?>js/autosuggest/autoSuggest.css' />
				<style type="text/css">
			/*Need to manually set the background color as this is an external script, technically */
			body {
				background-color: #F6F6F6;
			}
		</style>
		
		<!-- JS -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="<? echo $baseUrl; ?>js/autosuggest/jquery.autoSuggest.js"></script>
	<!--	<script type="text/javascript" src="<? echo $baseUrl; ?>js/validation.js"></script>-->
		<script type="text/javascript">
			var jobj = jQuery.noConflict();
							
			jobj(document).ready(function() {
				//Get baseurl from ci config			
				var CI_ROOT = '<?php echo $baseUrl;?>';		
				
				 /*--------------------------------------------------------------------------------*/
				/*Function submits the snippet form */
				 jobj.fn.submitSniplet = function() { 
				   var theUrl = CI_ROOT + 'backend/sniplet';	   
					var submit = jobj.ajax({
							type: "POST",
							url: theUrl,
							data: jobj(this).serialize(),
							beforeSend:  function() {					
								img = '<img src="' + CI_ROOT + 'img/loader2.gif" border="0" alt="loading..."/> '						
								jobj('#processor').html(img).show();						
					
							},
							success: function(message){											
								jobj('#processor').hide();	

								if(message == '1'){
									jobj('#as-selections-snippetTaglet').removeClass("empty-tags");
									jobj('#as-original-snippetTaglet').removeClass("empty-tags-text");				
														
									jobj('#snipletMessages').addClass("empty");
									jobj('#snippetLister').fadeOut('fast', function() {
    									jobj('#snippetFormContainer').append('<div id="sniplet_thankyou"><h2>Sniplet has been submitted, thank you!</h2><div id="sniplet_thanks">(Click outside to close window)</div></div>');
    									//window.parent.jobj('#snippetOuterContainer').css({'display' : 'none'});
    									//jobj('#snippetOuterContainer', parent.document).hide();
  									});									
								} else {
								
									for (var resp in message){
										console.log('empty: ' + resp);
										if(resp == 'tags'){
											jobj('#as-selections-snippetTaglet').addClass("empty-tags");
											jobj('#as-original-snippetTaglet').addClass("empty-tags-text");
										}
										if(resp == 'sniplet'){
											jobj('#snippetText').addClass("empty-sniplet");
										}
								
									}//for
								} //else
								
								
								
							/*	if (message == 'empty_tag'){
									jobj('#as-selections-snippetTaglet').addClass("empty-tags");
									jobj('#as-original-snippetTaglet').addClass("empty-tags-text");

								} 
								if (message == 'empty_sniplet'){
									jobj('#snippetText').addClass("empty-sniplet");
								//	jobj('#as-original-snippetTaglet').addClass("empty-tags-text");

								} 			*/					 
													
							}, //success		
							
							
							error: function(message){
							//	$('#category_creation_messages').addClass("error");
							//	$('#category_creation_messages').fadeIn(1000).append(errorAjaxMessage);
						
							} //error			
						}); //ajax	
					return submit;	 
				  } //$.fn.submitSniplet	
		
		

		
				jobj("form#snippetForm").submit(function(event) {	
					//Cancel default event action
					event.preventDefault();
					jobj(this).submitSniplet();			
				}); //form	 

	
		//Remove empty tag error if clicked on input.
		jobj("div#snippetFormContainer form#snippetForm ul#snippetLister li.snippetListlet input#snippetTag").focus(function(){
			jobj('#as-selections-snippetTaglet').removeClass("empty-tags");
			jobj('#as-original-snippetTaglet').removeClass("empty-tags-text");	
		});
		jobj("form#snippetForm ul#snippetLister li.snippetListlet textarea#snippetText").focus(function(){
			jobj('#snippetText').removeClass("empty-sniplet");
		});
	
	
			
		var tagUrl = CI_ROOT + 'backend/taglet';	
		jobj("div#snippetFormContainer form#snippetForm ul#snippetLister li.snippetListlet input#snippetTag").autoSuggest(tagUrl, {
			minChars: 2, 
			matchCase: true,
			asHtmlID: 'snippetTaglet',
			selectionLimit: 8
		});


		


	}); //jQuery			
		
	
		
		</script>
	
	</head>
	<body>
	<?php
	if(!isset($titleRmSC)){
		$titleRmSC = $defaultTitle;
	}
	if(!isset($url)){
		$url = $defaultUrl;
	}
		
	?>
		<div id="snipletLoad">
			<div id="snippetFormContainer">
				<?php
					if($CI->input->cookie('user_tracker_info', TRUE)){
						$user_cookie_array = explode(", ", $CI->input->cookie('user_tracker_info', TRUE));
				?>
				<form id="snippetForm" name="snippetForm">	
					<!--Bookmarklet Form -->
					<ul id="snippetLister">
						<li class="snippetListlet">
							<ul id="snippetListerSub">
								<li class="snippetListlet snippetListletSub"><h2>[sniplet]</h2></li>
								<li class="snippetListlet snippetListletSub"><?php echo " - Logged in as: <b>" .  $user_cookie_array[0]; ?></b></li>
							</ul>
						</li>						
						<li id="snipletMessages" class="snippetListlet"></li>
						<li class="snippetListlet">Title: <b><?php echo $titleLmt; ?></b></li>
						<li class="snippetListlet"></li>
						<li class="snippetListlet"><label for="snippetText">Your selected snippet:</label></li> <!--TODO: Fix the formatting in the textarea. Even though it doesn't effect the overall formatting -->
						<li class="snippetListlet"><textarea id="snippetText" name="snippetText" rows="8" cols="60"><?php echo $text; ?></textarea></li>
						<li class="snippetListlet"><label for="snippetTag">Keywords:</label></li>
						<li class="snippetListlet"><input id="snippetTag" type="text" name="snippetTag" value="" size="10" /></li>
						<li class="snippetListlet">Send to:</li>
						<li class="snippetListlet"><input type="radio" name="snippetSendTo" value="db" checked> Snippetboxx</li>
						<li class="snippetListlet"><input type="radio" name="snippetSendTo" value="email"> Email</li>
						<li class="snippetListlet"></li>
						<li class="snippetListlet">
							<input type="hidden" name="snippetUser" value="<?php echo $user_cookie_array[0]; ?>">
							<input type="hidden" name="snippetPageTitle" value="<?php echo $titleRmSC; ?>">
							<input type="hidden" name="snippetPageUrl" value="<?php echo $url; ?>">
							<input type="hidden" name="snippetCurrentTime" value="<?php echo date('m-d-Y-g:ia'); ?>">
							<input id="snippetSubmit" type="submit" value="SEND" />
							<span id="processor"></span>
						</li>
					</ul>
				</form>
				<?php } else { ?>
					<!--Login Form TODO: Move this block of code into the login controller/view, This way one block of code to edit. -->
					<ul id="snippetLister">
						<li class="snippetListlet">
							<div id="bk_content">
								<div id="sniplet_login">
									<div id="login_form">
										<?php 
										if(isset($login_error)){ ?>
											<div id="login_error"><?php echo $login_error; ?></div>
										<?php
										}
										echo form_open('backend/verify'); 	
										?>
										<div class="login_block">
											<label for="username">Username:</label>	
											<input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" /><?php echo form_error('username'); ?>
										</div>
										<div class="login_block">
											<label for="password">Password:</label>	
											<input type="password" name="password" value="<?php echo set_value('password'); ?>" id="password" /><?php echo form_error('password'); ?>
										</div>
										<div class="login_block login_submit">		
												<input type="hidden" value="1" name="bookmarklet" />
												<input type="submit" value="Login" name="login" />
										</div>
										<?php echo form_close(); ?>
									</div>
								</div>
							</div>
						 </li>
					</ul>
				<?php } ?>
			</div>
		</div>		
		
	</body>
</html>

