
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
	$("html, body").animate({ scrollTop: 0 }, "slow");
	
	var sliderUDTime = 400;
	//Delete any old cookies on page load
	$.cookie('sniplet_tracker', null, { expires: 30, path: '/', domain: '.snippetboxx.com' });
	$.cookie('ajax_stopper', null, { expires: 30, path: '/', domain: '.snippetboxx.com' });
	
	//Resets scrolling from fancybox overflow-x: hidden
	$("div#fancybox-overlay").live('click', function(event) {	
		$('body').removeClass("active_menuclick");
	});
	
	//HIDE SLIDER
	/* -------------------------------------------------------------------------------------*/		
	$("#sniplet_hide_me").live('click', function(event) {	
		$("#tags_slider").slideUp(sliderUDTime);
		$('body').removeClass("active_menuclick");
				
	});

	//TOTAL TOP (Total records showing "sniplets: <total>")
	/* -------------------------------------------------------------------------------------*/	
	$("#total_top").live('click', function(event) {
		event.preventDefault();
	});

	//PROFILE TAGS
	/* -------------------------------------------------------------------------------------*/	
	$(".sniplet_tag_link").live('click', function(event) {
		event.preventDefault();
		var tid = this.id;
		alert('fuck yay! ' + tid);		
		var displayString = 'tid='+ tid;
		var displayUrl = CI_ROOT + 'user/tags/';		
		$.ajax({
			type: "GET",
			url: displayUrl,
			data: displayString,
			beforeSend:  function() {					
				//img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				//$('#search_load').html(img).show();				
			},
			success: function(server_response){					
				//$('#search_load').hide();
				//$('#search_results').html(server_response).show();					
				//	$.fn.scrollThatPage(displayUrl, '?get=all_limit');									
			} //success		
		}); //ajax

	});

	//PROFILE TAGS EDIT
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

	//PROFILE SNIPLETS EDIT
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
	
	//TAG EDIT BOX
	$(".sniplet_tag_edit").live('click', function(event) {
		event.preventDefault();
		var tid = this.id;

		$.fancybox({
			'transitionIn': 'none',
			'width' : 500,
			'height' : 150,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onComplete' : function(){
				console.log('tag found with id: ' + tid);	
				//Lock scrolling on fancyboc pop-up
				$('body').addClass("active_menuclick");
				//Highlight text on fancybox load.
				$('div#pop-up-snipletiter form#editor_tag_form div#edit_tag_container input#edit_tag.edit_tag_input').focus().select();

				
								
				
			},
			'type': 'ajax',
			'href': CI_SITE + "editor/tag_form/" + tid
		}); //fancybox	
	});

	//SNIPLET EDIT BOX
	$(".sniplet_link_edit").live('click', function(event) {
		event.preventDefault();
		var tid = this.id;

		$.fancybox({
			'transitionIn': 'none',
			'width' : 900,
			'height' : 500,
			'autoDimensions': false,
			'transitionOut': 'none',
			'onComplete' : function(){
				console.log('sniplet found with id: ' + tid);	
				$('body').addClass("active_menuclick");


				
								
				
			},
			'type': 'ajax',
			'href': CI_SITE + "editor/sniplet_form/" + tid
		}); //fancybox	
	});

	
	//SNIPLET EDIT BOX TAGS - AUTOSUGGEST
//	var tagUrl = CI_SITE + 'editor/get_tags_for_form';	
//	$("div#edit_sniplet_container_spot input#edit_sniplet_tags.edit_sniplet_input_tags").autoSuggest(tagUrl, {
//		minChars: 2, 
//		matchCase: true,
//		asHtmlID: 'edit_sniplet_input_tags',
//		selectionLimit: 8
//	});

//<input type="text" size="75" class="edit_sniplet_input_tags" id="edit_sniplet_tags" value=".addClass()  jQuery API" name="edit_sniplet_tags">
//html body.active_menuclick div#fancybox-wrap div#fancybox-outer div#fancybox-content div div#pop-up-snipletiter form#editor_sniplet_form div#edit_sniplet_container_spot input#edit_sniplet_tags.edit_sniplet_input_tags


	//DISPLAY ABOUT PAGE
	/* -------------------------------------------------------------------------------------*/		
	$(".header_about").live('click', function(event) {
		$('#search_results').hide();
		$.fn.displayRecords('all', 'abort');
		event.preventDefault();		
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
				
		});










	//METHODS/FUNCTION

	//SCROLL TO TOP FUNCTION
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
		};


		
	//DISPLAY RECORDS FUNCTION
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
			console.log('aborted display all records');
		}		
	}; //displayRecords
		
	/*Used to get and generate a all mysql records */
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
	 } //$.fn.getCategories

	/*SCROLL PAGINATION */
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
	
	// code for fade in element by element with delay
	/* -------------------------------------------------------------------------------------*/	
	$.fn.fadeInWithDelay = function(){
		var delay = 0;
		return this.each(function(){
			$(this).delay(delay).animate({opacity:1}, 200);
			delay += 100;
		});
	};
	
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
		
	} //$.fn.buildDropdown

	//CHANGELOG DISPLAY
	/* -------------------------------------------------------------------------------------*/	
/*	$(".header_changelog").live('click', function(event) {
		event.preventDefault();
		$('#search_results').hide();
		var changeAmount = 10;
		var changeString = 'changelog='+ changeAmount;
		var changeUrl = CI_ROOT + 'frontend/changelog/';
		$.ajax({
					type: "GET",
					url: changeUrl,
					data: changeString,
					beforeSend:  function() {					
			//			img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
			//			$('#search_load').html(img).show();				
					},
					success: function(server_response){
						//Slow down loading gif
					//	setTimeout(function() {
					//		$('#search_messages').hide();
					//	}, 1000); 
			//			$('#search_load').hide();
						$("#tags_slider").slideDown(sliderUDTime);
						$('#tags_slider').html(server_response).show();
						$('body').addClass("active_menuclick");
			
					} //success		
			}); //ajax		
	});	 */
		
	//SEARCH AS YOU TYPE
	/* -------------------------------------------------------------------------------------*/
	//lets clear form
	$("#search_sniplets").val(' ');
	$("#search_sniplets").keyup(function(){
		$('#sniplet_messager').hide();
		
		//TODO: Display count results from search
		$("html, body").animate({ scrollTop: 0 }, "slow");
		$('body').removeClass("active_menuclick");
		var search_sniplets = $(this).val();
		var dataString = 'sniplet='+ search_sniplets;
		$("#tags_slider").slideUp(sliderUDTime);
			
		//Removed as it slows down the query typing
		//var resultsCount = $(this).getJson('frontend/count_search_results/' + search_sniplets);
		//$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + resultsCount + ')</a>');
		
		
		if(search_sniplets.length>2){
			var theUrl = CI_ROOT + 'frontend/search/';
			var tagsUrl = CI_ROOT + 'frontend/tags/';
			
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
		
		
		//DISPLAY TAGS IN HEADER
		$.ajax({
			type: "GET",
			url: tagsUrl,
			data: dataString,
			beforeSend:  function() {					
		//		img = '<img src="' + CI_ROOT + 'img/loader2.gif" border="0" alt="loading..."/> '
		//		$('#search_messages').html(img).show();				
			},
			success: function(server_response){
				//Slow down loading gif
			//	setTimeout(function() {
			//		$('#search_messages').hide();
			//	}, 1000); 
				
				$('#search_tags').html(server_response).show();
	
			} //success		
	}); //ajax		
		
		
		
		
		} //length
	
	return false;
	});
	
	//TAG TOP TEN -HEADER
	/* -------------------------------------------------------------------------------------*/
	$(".top_ten_a").live('click', function(event) {
		//$('#sniplet_messager').hide();

		
		event.preventDefault();
		$.fn.displayRecords('all', 'abort');
		
		$('body').removeClass("active_menuclick");
		var selectedId = this.id;
		id = selectedId.split('_')[1];
		var dataString = 'taglet='+ id;
		
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

//var snipletCount = $(this).getJson('frontend/count_sniplets');
	});	

	//TAGS IN SNIPLETS
	/* -------------------------------------------------------------------------------------*/
	$(".sniplet_click_tag").live('click', function(event) {
		//$('#sniplet_messager').hide();
		event.preventDefault();
		$.fn.displayRecords('all', 'abort');
		var selectedId = this.id;
		id = selectedId.split('_')[1];
		var resultsCount = $(this).getJson('frontend/count_results/' + id);
		$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + resultsCount + ')</a>');
		
		var dataString = 'taglet='+ id;
		var theUrl = CI_ROOT + 'frontend/search/';		
		$.ajax({
			type: "GET",
			url: theUrl,
			data: dataString,
			beforeSend:  function() {					
				$('#search_results').hide();
				//Scroll to top
				$("html, body").animate({ scrollTop: 0 }, "slow");
				img = '<img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/> '
				$('#search_load').html(img).show();			
			},
			success: function(server_response){
				$('#search_load').hide();	
				$('#search_results').html(server_response).show();
				//$.fn.scrollThatPage(theUrl, '?get=tag_limit'); Old way without get param
				$.fn.scrollThatPage(theUrl, '?get=tag_limit&' +dataString);

	
			} //success		
		}); //ajax	
	});	
	
	//TAGS CLICK ON ALL TAGS PAGE (On Page)
	/* -------------------------------------------------------------------------------------*/
	$(".at_tags").live('click', function(event) {
		//$('#sniplet_messager').hide();
		event.preventDefault();
		$.fn.displayRecords('all', 'abort');
		$('body').removeClass("active_menuclick");
		var selectedId = this.id;
		id = selectedId.split('_')[1];
		var resultsCount = $(this).getJson('frontend/count_results/' + id);
		$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + resultsCount + ')</a>');
		var dataString = 'taglet='+ id;
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
				//$.fn.scrollThatPage(theUrl, '?get=tag_limit'); //#Old way without get param	
				$.fn.scrollThatPage(theUrl, '?get=tag_limit&' +dataString);
		
			} //success		
		}); //ajax	
	});	


	//DISPLAY USER PAGE
	/* -------------------------------------------------------------------------------------*/		
	$(".header_username").live('click', function(event) {
		$('#search_results').hide();
		$.fn.displayRecords('all', 'abort');
		event.preventDefault();		
		
		var username = this.id
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
						
					} //success		
			}); //ajax		
			
			
		
				
		});


	//DISPLAY ALL TAGS (On Header)
	/* -------------------------------------------------------------------------------------*/		
	$(".header_tags").live('click', function(event) {						
		event.preventDefault();
		$.fn.displayRecords('all', 'abort');

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
		}); //ajax outer

	//SEARCH BUTTON
	/* -------------------------------------------------------------------------------------*/
	$("#submit_search").click(function(){
		event.preventDefault();
	});

	//COPY BUTTON
	/* -------------------------------------------------------------------------------------*/
	//Had to rebind the copy buttons as the content is loaded dynamically.
	$("#search_results .sniplet_data_li .copy_sniplet_button").live('click', function() {
		//alert(this.id);
		var selectedText = 'sniplet_content_' + this.id;
		var select = $(selectedText).text();
		selectText(selectedText);
		$('.status_message').hide();
		$('#status_message_' + this.id).html('Ctrl/Command C to copy this sniplet.').show();

	});



	//SCROLLING HEADER
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

	} //length

	//VIEW BUTTON
	/* -------------------------------------------------------------------------------------*/
	$("input.view_sniplet_button").live('click', function() {
		$("input.view_sniplet_button").removeClass("view_selected");
		$(this).addClass("view_selected");
		var id = this.id
	
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
	});
	
	
	//CONTENT HOVER/CLICK
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
			});	
	
		} else if (event.type == 'mouseleave'){
			$("#search_results ul.search_results_ul li.search_results_li ul.sniplet_data_ul li.sniplet_contents").removeClass("sniplet_content_selected");
		}else {
			console.log('Error: in hover functionality');
		}
	
	
	}); //content hover/click
	




/* LOGIC*/
/* -------------------------------------------------------------------------------------*/
/* -------------------------------------------------------------------------------------*/
var snipletCount = $(this).getJson('frontend/count_sniplets'); //TODO: Get json request for complete user account panel. Display username, ..etc
$('#sniplet_messager').html('<a href="#" id="total_top">sniplets (' + snipletCount + ')</a>');

$.fn.displayRecords('all');

	$('#top-link').topLink({
    	min: 400,
    	fadeSpeed: 500
  	});
  		//smoothscroll
  	$('#top-link').click(function(e) {
    	e.preventDefault();
    	$.scrollTo(0,300);
  	});


var $scrollingDiv = $("#profile_scroll");

$(window).scroll(function(){			
	$scrollingDiv
		.stop()
		.animate({"marginTop": ($(window).scrollTop() + 30) + "px"}, "slow" );			
});



}); //end of jQuery



/* -------------------------------------------------------------------------------------*/
function selectText(element) {
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

        