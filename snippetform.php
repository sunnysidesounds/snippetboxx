	<?php
		//Get ci config base url dynamically, So we don't have to change later.
		ob_start();
		include('index.php');
		ob_end_clean();
		$CI =& get_instance();
		$CI->load->library('config');
		$baseUrl = $CI->config->base_url();
	
		$text = $_GET['snippet'];	
		$title = $_GET['title'];
		
		$defaultTitle = 'Sniplet Title Placeholder';
		$defaultUrl = $baseUrl;
		
		if(isset($_GET['url'])){
			$url = $_GET['url'];
		}
					
		//Remove special characters from page title, because it's easier that way
		$titleRmSC = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $title);
		
		$characterLimit = 50;
		//If title is greater than character limit display dots and limit display
		if(strlen($titleRmSC) > $characterLimit){
			$titleLmt = substr($titleRmSC,0,$characterLimit) . " ...";
		} else {
			$titleLmt = $titleRmSC;
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
				  } //$.fn.submitCategory	
		
		

		
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
				<form id="snippetForm" name="snippetForm">	
					<ul id="snippetLister">
						<li class="snippetListlet"><h2>[sniplet]</h2></li>
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
							<input type="hidden" name="snippetPageTitle" value="<?php echo $titleRmSC; ?>">
							<input type="hidden" name="snippetPageUrl" value="<?php echo $url; ?>">
							<input type="hidden" name="snippetCurrentTime" value="<?php echo date('m-d-Y-g:ia'); ?>">
							<input id="snippetSubmit" type="submit" value="SEND" />
							<span id="processor"></span>
						</li>
					</ul>
				</form>
			</div>
		</div>		
	</body>
</html>

