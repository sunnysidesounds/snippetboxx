
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Jason R Alexander <sunnysidesounds@gmail.com>
*  All rights reserved
*
* js/jscript.js
***************************************************************/

//TODO: Make this OOO

$(document).ready(function() {

	/* ############################################################################ */
	/* --------------------------------- Snippetboxx Jquery Functions/Methods ------------------------------- */
	/* ############################################################################ */

	//Hover Sniplet List On Click 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displaySnipletHover = function(id) {
		$.fancybox({
			'transitionIn': 'none',
			'width' : 700,
			'height' : 500,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onComplete' : function(){
				$('body').addClass("active_menuclick");
					$("div#fancybox-wrap div#fancybox-outer div#fancybox-content div ul.sniplet_data_ul li.sniplet_data_li input.copy_sniplet_button").live('click', function() {
						$('div#fancybox-wrap div#fancybox-outer div#fancybox-content div ul.sniplet_data_ul li#sniplet_content_' + this.id).attr('id','sniplet_fancy_'+this.id);
							var selectedText = 'sniplet_fancy_' + this.id;
							var select = $(selectedText).text();
							selectText(selectedText);
					});					
			},
			'type': 'ajax',
			'href': CI_SITE + "frontend/box/" + id
		}); //fancybox	
	} //displaySnipletHover


	//Display Sniplet List On Click 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displaySnipletClicked = function(id) {
		$.fancybox({
			'transitionIn': 'none',
			'width' : 700,
			'height' : 500,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onComplete' : function(){
				console.log('sniplet found with id: ' + id);	
				
				$("div#fancybox-wrap div#fancybox-outer div#fancybox-content div ul.sniplet_data_ul li.sniplet_data_li input.copy_sniplet_button").live('click', function() {
					$('div#fancybox-wrap div#fancybox-outer div#fancybox-content div ul.sniplet_data_ul li#sniplet_content_' + this.id).attr('id','sniplet_fancy_'+this.id);
					var selectedText = 'sniplet_fancy_' + this.id;
					var select = $(selectedText).text();
					selectText(selectedText);
							
					$('div#fancybox-wrap div#fancybox-outer div#fancybox-content div ul.sniplet_data_ul li.status_message').hide();
					$('div#fancybox-wrap div#fancybox-outer div#fancybox-content div ul.sniplet_data_ul li#status_message_' + this.id).html('Ctrl/Command C to copy this sniplet.').show();		
				});
			},
			'type': 'ajax',
			'href': CI_SITE + "frontend/box/" + id
		}); //fancybox	
	} //displaySnipletClicked

	//Display all tags 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayAllTags = function() {
		//build sort dropdown
		var sortData = $(this).getJson('frontend/sort_json');
		var tagCount = $(this).getJson('frontend/count_tags');
		$(this).buildDropdown(sortData, '#sniplet_messager', 'tag_sorter', 'remove_none', 'Sort ' + tagCount + ' tags'); //show dropdown
		
		var tagzAmount = 'all_tags_limit';
		
		var tagzString = 'get='+ tagzAmount;
		var tagzUrl = CI_ROOT + 'frontend/all_tags/';
		$.ajax({
			type: "GET",
			url: tagzUrl,
			data: tagzString,
			beforeSend:  function() {					
				$('#search_results').hide();
				$("html, body").animate({ scrollTop: 0 }, "slow");
				img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				$('#search_load').html(img).show();									
					//Check if message is visable, if not show it.
					var visable = $('#sniplet_messager').is(":visible");
					if(visable == false){
						$('#sniplet_messager').show();
					}					
			},
			success: function(server_response){
				$('#search_load').hide();	
				$('#search_results').html(server_response).show();
				
				//Dropdown sort acrion
				$("#tag_sorter").change(function(){
					$('#search_results').hide();
					var selectorValue = $(this).val();
					var selectValue = 'get='+ selectorValue;
					var selectorUrl = CI_ROOT + 'frontend/all_tags/';						
					$.ajax({
						type: "GET",
						url: selectorUrl,
						data: selectValue,
						beforeSend:  function() {					
							$("html, body").animate({ scrollTop: 0 }, "slow");
							img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
							$('#search_load').html(img).show();		
						},
						success: function(server_response){
							$('#search_load').hide();	
							$('#search_results').html(server_response).show();
						} //success		
					}); //ajax inner
				}); //change
				$.fn.scrollThatPage(tagzUrl, '?get=tags_limit');
			} //success		
		}); //ajax
	} //displayAllTags

	//Display Tags List On click. 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayTagsClicked = function(selectedId) {
		id = selectedId.split('_')[1];
		var dataString = 'taglet='+ id;
		//Display total results or when click results
		var resultsCount = $(this).getJson('frontend/count_results/' + id);
		$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + resultsCount + ')</a>');

		$("#tags_slider").slideUp(sliderUDTime);
		var theUrl = CI_ROOT + 'frontend/search/';
		
		$.ajax({
			type: "GET",
			url: theUrl,
			data: dataString,
			beforeSend:  function() {					
				$('#search_results').hide();
				$("html, body").animate({ scrollTop: 0 }, "slow");
				img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				$('#search_load').html(img).show();	
			},
			success: function(server_response){
				$('#search_load').hide();	
				$('#search_results').html(server_response).show();
				
				$.fn.scrollThatPage(theUrl, '?get=tag_limit&' +dataString);
	
			} //success		
		}); //ajax
	} //displayTagsClicked

	//Search As You Type - Display Tags. 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.searchAsTypeTag = function(dataString, tagsUrl) {
		//DISPLAY TAGS IN HEADER
		$.ajax({
			type: "GET",
			url: tagsUrl,
			data: dataString,
			beforeSend:  function() {					
			//	img = '<img src="' + CI_ROOT + 'img/loader2.gif" border="0" alt="loading..."/> '
			//	$('#search_messages').html(img).show();				
			},
			success: function(server_response){				
				$('#search_tags').html(server_response).show();

			} //success		
		}); //ajax
	} //searchAsTypeTag
	
	//Search As You Type - Display Sniplets. 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.searchAsTypeSniplet = function(dataString, theUrl) {
		//DISPLAY SNIPLETS
		delay(function(){ //delay keyup
			$.ajax({
				type: "GET",
				url: theUrl,
				data: dataString,
				beforeSend:  function() {					
					$('#search_results').hide();								
					img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
					$('#search_load').html(img).show();	
				},
				success: function(server_response){
					$('#search_load').hide();	
				 	
				 	if(server_response.length == 0){			 	
					 	$('#nomoreresults').fadeIn();				 	
					 	setTimeout(function() {
							$('#nomoreresults').fadeOut();									
						}, 1000); 
					} else {						
						$('#search_results').html(server_response).show();		
						$.fn.scrollThatPage(theUrl, '?get=search&' +dataString);					
					}
					
				} //success		
			}); //ajax		
		}, 1000 );
	} //searchAsTypeSniplet


	//Scroll To Top
	/* -------------------------------------------------------------------------------------*/	
	$.fn.topLink = function(settings) {
		settings = jQuery.extend({
			min: 1,
			fadeSpeed: 200,
			ieOffset: 50
		}, settings);
		return this.each(function() {
			//listen for scroll
			var el = $(this);
			el.css('display','none'); //in case the user forgot
			$(window).scroll(function() {
				if(!jQuery.support.hrefNormalized) {
					el.css({
						'position': 'absolute',
						'top': $(window).scrollTop() + $(window).height() - settings.ieOffset
					});
				}
				if($(window).scrollTop() >= settings.min)
				{
					el.fadeIn(settings.fadeSpeed);
				}
				else
				{
					el.fadeOut(settings.fadeSpeed);
				}
			});
		});
	}; //topLink

	//Display All Records
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayRecords = function(modeValue, abort){
		var displayString = 'set='+ modeValue;
		var displayUrl = CI_ROOT + 'frontend/display/';		
		if(!abort){
			$.ajax({
				type: "GET",
				url: displayUrl,
				data: displayString,
				beforeSend:  function() {					
					img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
					$('#search_load').html(img).show();				
				},
				success: function(server_response){					
				
					$('#search_load').hide();
					$('#search_results').html(server_response).show();					
						$.fn.scrollThatPage(displayUrl, '?get=all_limit');									
				

				} //success		
			}); //ajax
		}else {
			//console.log('aborted display all records');
			$(this).clog('aborted display all records');
		}		
	}; //displayRecords
		
	//Display User Profile
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUser = function(username){
		var usernameVal = 'u='+ username;
		var usernameUrl = CI_ROOT + 'user/account/';
		$.ajax({
					type: "GET",
					url: usernameUrl,
					data: usernameVal,
					beforeSend:  function() {					
						$('#search_results').hide();
						$("html, body").animate({ scrollTop: 0 }, "slow");
						img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '						
						$('#search_load').html(img).show();									
							//Check if message is visable, if not show it.
							var visable = $('#sniplet_messager').is(":visible");
							if(visable == false){
								$('#sniplet_messager').show();
							}			
										},
					success: function(server_response){
						$('#search_load').hide();							
						//Let's disable scroll pagination on this page.				
						$('#search_results').attr('scrollpagination', 'disabled');
						$('#search_results').html(server_response).show();

						//Hide header if hide click cookie is zero.
						var show_header = $.cookie('sniplet_show_header');
						if(show_header == '0'){
							$('#sniplet_profile_vcard').hide();
							$('#sniplet_mini_profiler').show();
							$('body').addClass("active_menuclick");

						} //show_header
					} //success		
			}); //ajax
	}; //displayUser

	//Display User Profile Sniplet RAW
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserSnipletRaw = function(username){
		var displayString = 'u='+ username;
		var displayUrl = CI_ROOT + 'user/user_sniplet_all/';		
		$.ajax({
			type: "GET",
			url: displayUrl,
			data: displayString,
			beforeSend:  function() {					
				img = '<img src="' + CI_ROOT + 'img/loader2.gif" border="0" alt="loading..."/> '
				$('span#your_loader_sniplet').html(img).show();			
			},
			success: function(server_response){
				$('span#your_loader_sniplet').hide();
				$('div.sniplet_profile_float div.sniplet_profile_sniplets').html(server_response);						
			} //success		
		}); //ajax


	}; //displayUserSnipletRaw

	//Display User Profile Tags RAW
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserTagsRaw = function(username){
		var displayString = 'u='+ username;
		var displayUrl = CI_ROOT + 'user/user_tags_all/';		
		$.ajax({
			type: "GET",
			url: displayUrl,
			data: displayString,
			beforeSend:  function() {					
				//img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				//$('#search_load').html(img).show();				
			},
			success: function(server_response){
				$('div.sniplet_profile_float div.sniplet_profile_tags').html(server_response);					
				//$('#search_load').hide();
				//$('#search_results').html(server_response).show();					
				//	$.fn.scrollThatPage(displayUrl, '?get=all_limit');									
			} //success		
		}); //ajax


	}; //displayUserTagsRaw

	//Display User Profile Tags
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserTagsById = function(username, tid){
		var displayString = 'u='+username+'&tid='+ tid;
		var displayUrl = CI_ROOT + 'user/user_tags_id/';		
		//Display total results or when click results
		var resultsCount = $(this).getJson('frontend/count_results/' + tid);
		$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + resultsCount + ')</a>');

		$.ajax({
			type: "GET",
			url: displayUrl,
			data: displayString,
			beforeSend:  function() {					
				img = '<img src="' + CI_ROOT + 'img/loader2.gif" border="0" alt="loading..."/> '
				$('span#your_loader_sniplet').html(img).show();				
			},
			success: function(server_response){					
				//$('span#your_loader_sniplet').hide();
				$('span#your_loader_sniplet').html('<a id="sniplet_secret_refresh_2" href="#">(show all)</a>');
				$('div.sniplet_profile_float div.sniplet_profile_sniplets').html(server_response);

								
			} //succest
		}); //ajax

	}; //displayUserTagsById

	//Display User Profile Tags Edit Form
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserTagsEdit = function(tid){
		$.fancybox({
			'transitionIn': 'none',
			'width' : 500,
			'height' : 150,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onComplete' : function(){
				$(this).clog('tag found with id: ' + tid)
				//Lock scrolling on fancyboc pop-up
				$('body').addClass("active_menuclick");
				//Highlight text on fancybox load.
				$('div#pop-up-snipletiter form#editor_tag_form div#edit_tag_container input#edit_tag.edit_tag_input').focus().select();

			},
			'type': 'ajax',
			'href': CI_SITE + "editor/tag_form/" + tid
		}); //fancybox	

	}; //displayUserTagsEdit

	//Display User Profile Sniplet Display Link
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserSnipletLink = function(username, tid){

		var page_json = $(this).getJson("user/user_sniplet_link?u= "+username+"&tid="  + tid);

		$.each(page_json, function(key, value) {
			var page_key = key;
			var page_url = decodeURI(value);

			if(page_key == 'no-link'){
				//TODO: Use the jquery cross-domain plugin here
				$.fancybox({
					'transitionIn': 'none',
					'width' : 500,
					'height' : 150,
					'autoDimensions': false,
					'transitionOut': 'none',
					'onComplete' : function(){
						$('body').addClass("active_menuclick");
						$('div#fancybox-content div#no_iframe_container').append('<br /><br /><a id="sniplet_open_new_page_noload" href="'+page_url+'" target="_blank">open in new page</a>');
					},
					'type': 'ajax',
					'href': CI_SITE + "user/no_iframe_load/"
				}); //fancybox
			} else {
				$.fancybox({
					'transitionIn': 'none',
					'width' : 1024,
					'height' : 768,
					'autoDimensions': true,
					'transitionOut': 'none',
					'onComplete' : function(){
						$('body').addClass("active_menuclick");
						//Let's prepend a url in case user doesn't want the page in fancybox.
						var prependContent = '<a id="sniplet_open_new_page" href="'+page_url+'" target="_blank">open in new page</a>';
						$('div#fancybox-content').prepend(prependContent);
						//This displays pre-loader until iframe has loaded. 
					            $.fancybox.showActivity();
					            $('#fancybox-frame').load(function(){
					                $.fancybox.hideActivity();
					            });
					},
					'type': 'iframe',
					'href': page_url
				}); //fancybox	
			}

		});

	}; //displayUserSnipletLink

	//Display User Profile Bookmmarklet
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayBookmarklet = function(environment){
		$.fancybox({
			'transitionIn': 'none',
			'width' : 500,
			'height' : 150,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onStart' : function(){
            			$(this).clog('OnStart creates for bookmarklet version: ' + environment);
        			},
			'onComplete' : function(content){	
				//Add class to click the scroller when in fancybox mode
				$('body').addClass("active_menuclick");
				$('div#fancybox-wrap div#fancybox-outer div#fancybox-content div div#bookmarklet_container').focus().select();
			},
			'type': 'ajax',
			'href': CI_SITE + "backend/bookmarklet/" + environment
		}); //fancybox	
	}; //displayUserSnipletCreate

	//Display User Profile Sniplet Create New Form
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserSnipletCreate = function(tid){
		$.fancybox({
			'transitionIn': 'none',
			'width' : 700,
			'height' : 600,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onStart' : function(){
            			console.log('OnStart Create  - sniplet found with id: ' + tid);
        			},
			'onComplete' : function(content){
				console.log('onComplete - sniplet found with id: ' + tid);	
				//Add class to click the scroller when in fancybox mode
				$('body').addClass("active_menuclick");

					//SNIPLET EDIT BOX TAGS - AUTOSUGGEST
					var tagUrl = CI_SITE + 'backend/taglet/';	
					$("div#fancybox-wrap div#fancybox-outer div#fancybox-content div div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_spot input#edit_tags_sniplet.edit_tags_sniplet_input").autoSuggest(tagUrl, {
						minChars: 2, 
						matchCase: true,
						asHtmlID: 'edit-tags-sniplet-input',
						//selectionLimit: 8,
						retrieveLimit: 2
						//preFill: resultsCount.items
					});					
				
			},
			'type': 'ajax',
			'href': CI_SITE + "editor/sniplet_form/" + tid
		}); //fancybox	
	}; //displayUserSnipletCreate

	//Display User Profile Sniplet Edit Form
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayUserSnipletEdit = function(tid){
		$.fancybox({
			'transitionIn': 'none',
			'width' : 700,
			'height' : 600,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onStart' : function(){
            			console.log('OnStart Edit  - sniplet found with id: ' + tid);
        			},
			'onComplete' : function(content){
				console.log('onComplete Edit - sniplet found with id: ' + tid);	
				//Add class to click the scroller when in fancybox mode
				$('body').addClass("active_menuclick");

					//SNIPLET EDIT BOX TAGS - AUTOSUGGEST
					var tagUrl = CI_SITE + 'backend/taglet/';	
					//var selectedData = {items: [{value: "55", name: "Rudy Hamilton"}, {value: "79", name: "Michael Jordan"}]};
					//Get prefilled tags
					var resultsCount = $(this).getJson('editor/get_prefill_categories/' + tid);

					$("div#fancybox-wrap div#fancybox-outer div#fancybox-content div div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_spot input#edit_tags_sniplet.edit_tags_sniplet_input").autoSuggest(tagUrl, {
						minChars: 2, 
						matchCase: true,
						asHtmlID: 'edit-tags-sniplet-input',
						//selectionLimit: 8,
						retrieveLimit: 2,
						preFill: resultsCount.items
					});					
				
			},
			'type': 'ajax',
			'href': CI_SITE + "editor/sniplet_form/" + tid
		}); //fancybox

	}; //displayUserSnipletEdit

	//Display About Page
	/* -------------------------------------------------------------------------------------*/	
	$.fn.displayAbout = function(tid){
		var aboutAmount = 'display';		
		var aboutString = 'about='+ aboutAmount;
		var aboutUrl = CI_ROOT + 'frontend/about/';
		$.ajax({
			type: "GET",
			url: aboutUrl,
			data: aboutString,
			beforeSend:  function() {					
				$('#search_results').hide();
				$("html, body").animate({ scrollTop: 0 }, "slow");
				img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '						
				$('#search_load').html(img).show();									
					//Check if message is visable, if not show it.
					var visable = $('#sniplet_messager').is(":visible");
					if(visable == false){
						$('#sniplet_messager').show();
					}			
			},
			success: function(server_response){
				$('#search_load').hide();							
				//Let's disable scroll pagination on this page.				
				$('#search_results').attr('scrollpagination', 'disabled');					
				$('#search_results').html(server_response).show();
				
			} //success		
		}); //ajax
	} //displayUserSnipletEdit


	//User Profile - Update User Sniplet
	/* -------------------------------------------------------------------------------------*/	
	 $.fn.updateUserSniplet = function(title, text, tags, username, update_time, sniplet_id) { 
	 	//title = $.base64.encode(title);
	 	//text = $.base64.encode(text);
	 	var theUrl = CI_ROOT + 'user/sniplet_update';	   
		var submit = $.ajax({
			type: "POST",
			url: theUrl,
			data: "title=" + title + "&text=" + text + "&tags=" + tags + "&username=" + username + "&update_time" + update_time + "&sniplet_id=" + sniplet_id,
			beforeSend:  function(server_response) {					
				$('#pop-up-snipletiter').hide();		
				$('body').addClass("active_menuclick");
				html = '<div id="tag_user_text_loader">Updating place wait...</div>';		
				html += '<img id="tag_user_img_loader" src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '						
				$('#pop-up-snipletiter').html(html).show();	
			},
			success: function(server_response){											
 				$(this).clog(server_response);
				//TODO: Look at maybe just refreshing divs and not the whole user profile. 
				username = $.base64.encode(username);
				//$(this).displayUser(username);
				$('body').addClass("active_menuclick");
				$(this).displayUserSnipletRaw(username);

				setTimeout(function() {

					$.fancybox.close();

					//TODO: if cookie is set we want to add this in --> $('body').addClass("active_menuclick"); to lock the screen.

				}, 1000);
									
			}, //success		
			
			
			error: function(server_response){
		
			} //error			
		}); //ajax 	
 
	} //$.fn.updateUserSniplet	


	//User Profile - Update User Tag Name
	/* -------------------------------------------------------------------------------------*/	
	$.fn.updateUserTagName = function(title, id, username) { 
		var theUrl = CI_ROOT + 'user/tag_update/';
		var cudder = $.ajax({
			type: "POST",
			url: theUrl,
			data: 'edit_tag='+ title + '&edit_tag_id=' + id + '&username=' + username,
			beforeSend:  function(server_response) {	
				$('#pop-up-snipletiter').hide();		
				$('body').addClass("active_menuclick");
				html = '<div id="tag_user_text_loader">Updating place wait...</div>';		
				html += '<img id="tag_user_img_loader" src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '						
				$('#pop-up-snipletiter').html(html).show();
				 	
			},
			success: function(server_response){
				$(this).clog(server_response);
				//TODO: Look at maybe just refreshing divs and not the whole user profile. 
				username = $.base64.encode(username);
				//$(this).displayUser(username);
				$('body').addClass("active_menuclick");
				$(this).displayUserTagsRaw(username);

				setTimeout(function() {

					$.fancybox.close();

					//TODO: if cookie is set we want to add this in --> $('body').addClass("active_menuclick"); to lock the screen.

				}, 1000);
			}, //success		
			error: function(server_response){
				$(this).clog("error: " + server_response);
		
			} //error			
		}); //ajax




	}

	//Display About Page
	/* -------------------------------------------------------------------------------------*/	
	$.fn.userVerify= function(user, pass){
		var theUrl = CI_ROOT + 'backend/verify';	
		var snipletLogin = $.ajax({
			type: "POST",
			url: theUrl,
			data: 'username='+ user + '&password='+pass,
			beforeSend:  function(server_response) {					
				img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				$('#search_load').html(img).show();	
				$('#login_form').hide();	
			},
			success: function(server_response){
				//If reponse starts with an error, let's redirect back to login with a error message
				if (server_response.substring(0, 5) == "error") {
					console.log("substring " + server_response.substring(6));
					error_message = $.base64.encode(server_response.substring(6));
					$('#search_load').hide();
					$('#search_results').load(CI_ROOT+'login/?m=' + error_message + ' #search_results').show();
				} else {
					//Set a logging in cookie and redirect this solves login being in the url after login. 
					$.cookie('sniplet_just_logged_in', server_response, { expires: 30, path: '/', domain: '.snippetboxx.com' });
					window.location = CI_ROOT;				
				}
	
			}, //success		
			error: function(server_response){
				console.log("error: " + server_response);
		
			} //error			
		}); //ajax
	} //userVerify

	/*Generic getJson Function  */
	/*--------------------------------------------------------------------------------*/	
	 $.fn.getJson = function(post_url) { 
	 	var theUrl = CI_ROOT + post_url	 
		var root = 
			$.ajax({
				url: theUrl,
				type: "GET",
				cache   : false,
				async: false,
				timeout : 10000,
				dataType : 'json',
		}).responseText;     // close of .ajax section
		// Converts the json string to a js object	

		return eval('(' + root + ')');      
	 } //getJson

	/*Scrolling Pagination */
	/* -------------------------------------------------------------------------------------*/	
	$.fn.scrollThatPage = function(displayUrl, getString){
		$('#search_results').scrollPagination({
			'contentPage':displayUrl + getString, // the page where you are searching for results
			'contentData': {}, // you can pass the children().size() to know where is the pagination
			'scrollTarget': $(window), // who gonna scroll? in this example, the full window
			'heightOffset': 1, // how many pixels before reaching end of the page would loading start? positives numbers only please
			'beforeLoad': function(){ // before load, some function, maybe display a preloader div							
				img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				$('#sniplet_loading').html(img).show();							
			},
			'afterLoad': function(elementsLoaded){ // after loading, some function to animate results and hide a preloader div							
				 if(elementsLoaded.length == ''){
				 	$('#sniplet_loading').hide();
				 	$('#nomoreresults').fadeIn();				 	
				 	setTimeout(function() {
						$('#nomoreresults').fadeOut();									
					}, 1000); 	
				 	$('#search_results').stopScrollPagination();
				 } else {
				 	$('#sniplet_loading').fadeOut();
				 	$('#sniplet_messager').show();
				 	//$('#sniplet_messager').html('<a href="#" id="to_top">scroll to top</a>');
				 	
				 	//<a href="#" id="to_top"><img src="' + CI_ROOT + 'img/to_top.png.gif" border="0" alt="loading..."/></a>
				 	
				 	$(elementsLoaded).fadeInWithDelay();
				 }				
				 if ($('#search_results').children().size() > 100){ // if more than 100 results loaded stop pagination (only for test)
				 	$('#nomoreresults').fadeIn();
					$('#search_results').stopScrollPagination();
				 }	
			}
		}); //scrollPagination

	}; //scrollThatPage

	// Special Fade elements in 
	/* -------------------------------------------------------------------------------------*/	
	$.fn.fadeInWithDelay = function(){
		var delay = 0;
		return this.each(function(){
			$(this).delay(delay).animate({opacity:1}, 200);
			delay += 100;
		});
	}; //fadeInWithDelay

	/*This builds all dropdowns */
	 /*--------------------------------------------------------------------------------*/
	$.fn.buildDropdown = function(data, id, name, none, custom) { 
		if(none != 'remove_none'){$(id).prepend('<option value="0">None</option>');}
		if (custom){			
			$(id).prepend('<option value="0">'+custom+'</option>');
		}
		$.each(data, function(i, val) {
			if(val == null){ 
				val = 'No Values Yet';
				i = 0;
			}
			$(id).append('<option value="'+i+'">'+val+'</option>');
		});
		
		$(id).wrapInner('<select name="'+name+'" id="'+name+'">');	
	} //buildDropdown

	/* Custom console logger function */
	 /*--------------------------------------------------------------------------------*/
	$.fn.clog = function(message) { 
		var time = new Date();
		var format_time = time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
		console.log(format_time + ' : ' + message);
	} //clog








	/* ############################################################################ */
	/* ---------------------------------------- Snippetboxx Logic -------------------------------------------------- */
	/* ############################################################################ */

	//Misc Vaues and Global Settings
	/* -------------------------------------------------------------------------------------*/		
	$("html, body").animate({ scrollTop: 0 }, "slow");
	
	//This is the slider time
	var sliderUDTime = 400;
	
	//Dynamically set boomarklet version master/development (values passed www and dev)
	var bookmarkletEnvironment = window.location.host.substr(0,3);

	//On Page Load
	//On load if root url matches current url. Let display all records. 
	/* -------------------------------------------------------------------------------------*/		
	if(CI_ROOT == window.location){	
		var just_logged_in = $.cookie('sniplet_just_logged_in');
		if(just_logged_in){

			$('#search_results').displayUser(just_logged_in);
			$.cookie('sniplet_just_logged_in', null, { expires: 30, path: '/', domain: '.snippetboxx.com' });

		} else {
			$.fn.displayRecords('all');

		}
	}

	//Login Page
	if('/login/' == window.location.pathname){
		//When user click on header login this locks the scroller
		$('body').addClass("active_menuclick");
	//Signup Page
	} else if ('/signup/' == window.location.pathname) {
		//When user click on header login this locks the scroller
		$('body').addClass("active_menuclick");
	}



	//Delete any old cookies on page load
	/* -------------------------------------------------------------------------------------*/		
	$.cookie('sniplet_tracker', null, { expires: 30, path: '/', domain: '.snippetboxx.com' });
	$.cookie('ajax_stopper', null, { expires: 30, path: '/', domain: '.snippetboxx.com' });



/*
	$('#sniplet_mini_profiler').show();
	var header_hideshow = $.cookie('user_tracker_info');
	header_hideshow = header_hideshow.split(',');

	header_hideshow = header_hideshow[5];

	console.log(header_hideshow);
	//#sniplet_profile_vcard
*/

	//Display Sniplet Counts in Header	
	/* -------------------------------------------------------------------------------------*/		
	var snipletCount = $(this).getJson('frontend/count_sniplets');
	$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + snipletCount + ')</a>');

	//Scroll to top actions
	/* -------------------------------------------------------------------------------------*/		
	$('#top-link').topLink({min: 400, fadeSpeed: 500});
	$('#top-link').click(function(e) {e.preventDefault(); $.scrollTo(0,300); });
	
	//Profile Scroll
	/* -------------------------------------------------------------------------------------*/		
	var $scrollingDiv = $("#profile_scroll");
	$(window).scroll(function(){			
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 30) + "px"}, "slow" );			
	});

	//Resets scrolling from fancybox overflow-x: hidden
	/* -------------------------------------------------------------------------------------*/		
	$("div#fancybox-overlay").live('click', function(event) {	
		$('body').removeClass("active_menuclick");
	});

	//Hide slider
	/* -------------------------------------------------------------------------------------*/		
	$("#sniplet_hide_me").live('click', function(event) {	
		$("#tags_slider").slideUp(sliderUDTime);
		$('body').removeClass("active_menuclick");
				
	});

	//Total Tops, disable default click
	/* -------------------------------------------------------------------------------------*/	
	$("#total_top").live('click', function(event) {
		event.preventDefault();
	});

	//Search Button, disable default click
	/* -------------------------------------------------------------------------------------*/
	$("#submit_search").click(function(){
		event.preventDefault();
	});

	//Display User Profile Tags
	/* -------------------------------------------------------------------------------------*/	
	$(".sniplet_tag_link").live('click', function(event) {
		event.preventDefault();
		var username = $.cookie('user_tracker_info');
		username = username.split(',');
		username = username[0];
		username = $.base64.encode(username);
		var tid = this.id;
		$(this).displayUserTagsById(username, tid);
	});

	//Display User Profile Tags Edit (This is the rollover pop-up edit functionality)
	/* -------------------------------------------------------------------------------------*/	
	$(".li_user_tags").live('mouseover', function(event) {
		event.preventDefault();
		var tid = this.id;
		$('.sniplet_tag_edit_'+ tid).css({visibility: 'visible'});
	});

	$(".li_user_tags").live('mouseout', function(event) {
		event.preventDefault();
		var tid = this.id;
		$('.sniplet_tag_edit_'+ tid).css({visibility: 'hidden'});
	});

	//Display User Profile Sniplet Edit (This is the rollover pop-up edit functionality)
	/* -------------------------------------------------------------------------------------*/	
	$(".li_user_sniplets").live('mouseover', function(event) {
		event.preventDefault();
		var tid = this.id;
		$('.sniplet_link_edit_'+ tid).css({visibility: 'visible'});
	});

	$(".li_user_sniplets").live('mouseout', function(event) {
		event.preventDefault();
		var tid = this.id;
		$('.sniplet_link_edit_'+ tid).css({visibility: 'hidden'});
	});

	//Display User Profile Display Link
	/* -------------------------------------------------------------------------------------*/	
	$(".sniplet_link").live('click', function(event) {
		event.preventDefault();
		var username = $.cookie('user_tracker_info');
		username = username.split(',');
		username = username[0];
		username = $.base64.encode(username);
		var tid = this.id;
		//$('div#fancybox-loading').show();
		//img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
		//$('div#fancybox-loading').html(img).show();
		 //	setTimeout(function() {		
		$(this).displayUserSnipletLink(username, tid);								
		//	}, 1500); 

		
	});


	//Display User Profile Tags Edit Form
	/* -------------------------------------------------------------------------------------*/	
	$(".sniplet_tag_edit").live('click', function(event) {
		event.preventDefault();
		var tid = this.id;
		$(this).displayUserTagsEdit(tid);
	});

	//Display User Profile Sniplet Create New Form
	/* -------------------------------------------------------------------------------------*/	
	$("#sniplet_create_button").live('click', function(event) {
		event.preventDefault();
		var tid = this.id;
		$(this).displayUserSnipletCreate(tid);
	});

	//Display User Profile Sniplet Create Edit Form
	/* -------------------------------------------------------------------------------------*/	
	$(".sniplet_link_edit").live('click', function(event) {
		event.preventDefault();
		var tid = this.id;
		$(this).displayUserSnipletEdit(tid);
	});

	//Display User Profile Sniplet Show Bookmarklet
	/* -------------------------------------------------------------------------------------*/	
	$("#sniplet_bookmarklet_button").live('click', function(event) {
		event.preventDefault();
		$(this).displayBookmarklet(bookmarkletEnvironment);
	});

	//Display User Profile Click to Hide Header
	/* -------------------------------------------------------------------------------------*/		
	$("#sniplet_button").live('click', function(event) {
		  event.preventDefault();
		  $('#sniplet_profile_vcard').animate({ height: 'toggle', opacity: 'toggle' }, 'fast', function() {
     	   		$(this).clog('profile header set to hide');
	   		$('#sniplet_mini_profiler').show();
	   		$('body').addClass("active_menuclick");
	   		$.cookie('sniplet_show_header', '0', { expires: 30, path: '/', domain: '.snippetboxx.com' });
    		});


	});

	//Display User Profile Page
	/* -------------------------------------------------------------------------------------*/		
	$(".header_username").live('click', function(event) {
		$('#search_results').hide();
		//$.fn.displayRecords('all', 'abort');
		event.preventDefault();
		$('body').removeClass("active_menuclick");	
		var username = this.id
		$(this).displayUser(username);


	});

	//Display About Page on click
	/* -------------------------------------------------------------------------------------*/		
	$(".header_about").live('click', function(event) {
		$('#search_results').hide();
		event.preventDefault();		
		$('body').removeClass("active_menuclick");
		$(this).displayAbout();					
	});

	//User verify login on click
	/* -------------------------------------------------------------------------------------*/	
	$("input#sniplet_login").live('click', function(event) {
		event.preventDefault();
		user = $('input#username').val();
		pass = $('input#password').val();
		$('#search_results').hide();
		$(this).userVerify(user, pass);
	}); 

	//Search as you type - Sniplets & Tags
	/* -------------------------------------------------------------------------------------*/
	$("#search_sniplets").val(' ');
	$("#search_sniplets").keyup(function(){
		$('#sniplet_messager').hide();
		
		$("html, body").animate({ scrollTop: 0 }, "slow");
		$('body').removeClass("active_menuclick");
		var search_sniplets = $(this).val();
		var dataString = 'sniplet='+ search_sniplets;
		$("#tags_slider").slideUp(sliderUDTime);
		
		if(search_sniplets.length>2){
			var snipletUrl = CI_ROOT + 'frontend/search/';
			var tagsUrl = CI_ROOT + 'frontend/tags/';
				
			$(this).searchAsTypeSniplet(dataString, snipletUrl);
			$(this).searchAsTypeTag(dataString, tagsUrl);		
		} //length
	
		return false;
	});

	//Tags In Header - on click
	/* -------------------------------------------------------------------------------------*/
	$(".top_ten_a").live('click', function(event) {		
		event.preventDefault();
		$('body').removeClass("active_menuclick");
		var selectedId = this.id;
		$(this).displayTagsClicked(selectedId);
	});	

	//Tags In Sniplets On click
	/* -------------------------------------------------------------------------------------*/
	$(".sniplet_click_tag").live('click', function(event) {
		event.preventDefault();
		$('body').removeClass("active_menuclick");
		var selectedId = this.id;
		$(this).displayTagsClicked(selectedId);	
	});

	//Tags On All Tags On Click
	/* -------------------------------------------------------------------------------------*/
	$(".at_tags").live('click', function(event) {
		event.preventDefault();
		$('body').removeClass("active_menuclick");
		var selectedId = this.id;
		$(this).displayTagsClicked(selectedId);
	});

	//Display All Tags On Click Header
	/* -------------------------------------------------------------------------------------*/		
	$(".header_tags").live('click', function(event) {						
		event.preventDefault();
		$('body').removeClass("active_menuclick");
		$(this).displayAllTags();					
	});

	//Hightlight / Copy Bookmarklet Button
	/* -------------------------------------------------------------------------------------*/
	$("div#sniplet_copy_container input.copy_sniplet_fancy").live('click', function() {
		$('div#fancybox-wrap div#fancybox-outer div#fancybox-content div div#bookmarklet_container div#bookmarklet_me').html();
		selectText('bookmarklet_me');
		$('#sniplet_copy_text').hide();
		$('#sniplet_copy_text').html('Ctrl/Command C to copy your bookmarklet.').show();
	});

	//Hightlight / Copy Button
	/* -------------------------------------------------------------------------------------*/
	//Had to rebind the copy buttons as the content is loaded dynamically.
	$("#search_results .sniplet_data_li .copy_sniplet_button").live('click', function() {
		var selectedText = 'sniplet_content_' + this.id;
		var select = $(selectedText).text();
		selectText(selectedText);
		$('.status_message').hide();
		$('#status_message_' + this.id).html('Ctrl/Command C to copy this sniplet.').show();
	});

	//Fixed Header that follows scroll
	/* -------------------------------------------------------------------------------------*/
  	if ( $('#header').length ){ //if set
	  var stickyHeaderTop = $('#header').offset().top;
	        $(window).scroll(function(){
	                if( $(window).scrollTop() > stickyHeaderTop ) {
	                        $('#header').css({position: 'fixed', top: '0px'});
	                } else {
	                        $('#header').css({position: 'static', top: '0px'});
	                }
	        });

	} //header length

	//Display Pop-view of sniplet
	/* -------------------------------------------------------------------------------------*/
	$("input.view_sniplet_button").live('click', function() {
		$("input.view_sniplet_button").removeClass("view_selected");
		$(this).addClass("view_selected");
		var id = this.id
		$(this).displaySnipletClicked(id);
	});

	//Display / Hover Content
	/* -------------------------------------------------------------------------------------*/
	$("#search_results ul.search_results_ul li.search_results_li ul.sniplet_data_ul li.sniplet_contents").live('hover', function(event) {
				
		if(event.type == 'mouseenter'){			
			$("#search_results ul.search_results_ul li.search_results_li ul.sniplet_data_ul li.sniplet_contents").removeClass("sniplet_content_selected");
			$('#' + this.id).addClass("sniplet_content_selected");
			
			//HOVER VIEW BUTTON
			/* -------------------------------------------------------------------------------------*/
			$(this).live('click', function() {
				$(this).removeClass("view_selected");
				$(this).addClass("view_selected");
				var id = this.id
				$(this).displaySnipletHover(id);
			});	
	
		} else if (event.type == 'mouseleave'){
			$("#search_results ul.search_results_ul li.search_results_li ul.sniplet_data_ul li.sniplet_contents").removeClass("sniplet_content_selected");
		}else {
			console.log('Error: in hover functionality');
		}
	}); //content hover/click


	//User profile submit edit sniplet
	/* -------------------------------------------------------------------------------------*/
	$("div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_submit input#edit_submit").live('click', function(event) {
		event.preventDefault();
		sniplet_title = $('div#edit_sniplet_container input#edit_sniplet.edit_sniplet_input').val();
		sniplet_text = $('div#edit_sniplet_container_area textarea#edit_sniplet_text').val();
		sniplet_tags = $('div#edit_sniplet_container_spot ul#as-selections-edit-tags-sniplet-input.as-selections li#as-original-edit-tags-sniplet-input.as-original input#as-values-edit-tags-sniplet-input.as-values').val();
		sniplet_user_id = $('div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_submit input#sniplet_username_id').val();
		sniplet_id = $('div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_submit input#sniplet_id').val();
		sniplet_update_time = $('div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_submit input#sniplet_update_time').val();

	

		console.log(sniplet_id);

		$(this).updateUserSniplet(sniplet_title, sniplet_text, sniplet_tags, sniplet_user_id, sniplet_update_time, sniplet_id);
		//$('body').removeClass("active_menuclick");
		//		alert(sniplet_title + sniplet_text + sniplet_tags + sniplet_user_id + sniplet_update_time);
	});

	//User profile submit edit tag
	/* -------------------------------------------------------------------------------------*/
	$("div#pop-up-snipletiter form#editor_tag_form input#submit_tag_edit").live('click', function(event) {
		event.preventDefault();
		var username = $.cookie('user_tracker_info');
		username = username.split(',');
		username = username[0];
		tag_title = $('div#edit_tag_container input#edit_tag.edit_tag_input').val();
		tag_id = $('div#pop-up-snipletiter form#editor_tag_form input#edit_tag_id').val();

		console.log(username);
		$(this).updateUserTagName(tag_title, tag_id, username); 
		$('body').removeClass("active_menuclick");
	});

	//User profile secret tag refresh link, used for other stuff too. 
	/* -------------------------------------------------------------------------------------*/
	$("a#tags_secret_refresh").live('click', function(event) {
		event.preventDefault();
		var username = $.cookie('user_tracker_info');
		username = username.split(',');
		username = username[0];
		username = $.base64.encode(username);
		$(this).displayUserTagsRaw(username);
	});

	//User profile secret sniplet refresh link, used for other stuff too. 
	/* -------------------------------------------------------------------------------------*/
	$("a#sniplet_secret_refresh").live('click', function(event) {
		event.preventDefault();
		var username = $.cookie('user_tracker_info');
		username = username.split(',');
		username = username[0];
		username = $.base64.encode(username);
		$(this).displayUserSnipletRaw(username);
	});

	//User profile show all sniplets link (show all) 
	/* -------------------------------------------------------------------------------------*/
	$("a#sniplet_secret_refresh_2").live('click', function(event) {
		event.preventDefault();
		var username = $.cookie('user_tracker_info');
		username = username.split(',');
		username = username[0];
		username = $.base64.encode(username);
		$(this).displayUserSnipletRaw(username);
	});

	//User profile open in new page, close fancy box, open new tab
	/* -------------------------------------------------------------------------------------*/
	$("a#sniplet_open_new_page").live('click', function(event) {
		$.fancybox.close();
		$('body').removeClass("active_menuclick");
	});

	//User profile open in new page no load, close fancy box, open new tab
	/* -------------------------------------------------------------------------------------*/
	$("a#sniplet_open_new_page_noload").live('click', function(event) {
		$.fancybox.close();
		$('body').removeClass("active_menuclick");
	});
	
	//User profile header - This is the "(show)" link in the mini user profile.
	/* -------------------------------------------------------------------------------------*/
	$("a#sniplet_profile_header").live('click', function(event) {
		event.preventDefault();
		$('#sniplet_mini_profiler').hide();
		 $('#sniplet_profile_vcard').animate({ height: 'toggle', opacity: 'toggle' }, 'fast', function() {
	   		$('body').removeClass("active_menuclick");
			$.cookie('sniplet_show_header', '1', { expires: 30, path: '/', domain: '.snippetboxx.com' });
		});
	});


}); //end of jQuery







/* ############################################################################ */
/* --------------------------------- Snippetboxx Javascript Functions/Methods --------------------------- */
/* ############################################################################ */
function selectText(element) { //NOTE: element --> don't use # or . for class or id just use it's name.
    var text = document.getElementById(element);
    if ($.browser.msie) {
        var range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if ($.browser.mozilla || $.browser.opera) {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    } else if ($.browser.safari) {
        var selection = window.getSelection();
        selection.setBaseAndExtent(text, 0, text, 1);
    }
}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

        