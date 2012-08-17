(function( $ ){
	 
		 
 $.fn.scrollPagination = function(options) {
  	
		var opts = $.extend($.fn.scrollPagination.defaults, options);  
		var target = opts.scrollTarget;
		if (target == null){
			target = obj; 
	 	}
		opts.scrollTarget = target;
	 
		return this.each(function() {
		  $.fn.scrollPagination.init($(this), opts);
		});

  };
  
  $.fn.stopScrollPagination = function(){
	  return this.each(function() {
	 	$(this).attr('scrollPagination', 'disabled');
	  });
	  
  };
  
  $.fn.scrollPagination.loadContent = function(obj, opts){
	 var target = opts.scrollTarget;
	 var mayLoadContent = $(target).scrollTop()+opts.heightOffset >= $(document).height() - $(target).height();
		

		//console.log(mayLoadContent + ' mayLoadContent');
		
		 if (mayLoadContent){
			 
			 if (opts.beforeLoad != null){
				opts.beforeLoad(); 
			 }
			 $(obj).children().attr('rel', 'loaded');
	
			//Flag get request with a timestamp
			$.currentCallId = (new Date()).getTime();
			//console.log($.currentCallId + ' currentCallId');
				
				var request = $.ajax({
					  type: 'POST',
					  url: opts.contentPage,
					  data: opts.contentData,
					  beforeSend: function(xhr, settings) {
	                	this.callId = $.currentCallId;     
	                	//console.log(this.callId + ' this.callId');            	  
	            	  },
					  success: function(data){			  
							//request = null;
							if (this.callId === $.currentCallId) {								
								console.log("Request with ID: " + $.currentCallId + " being used")					
								$(obj).append(data); 
								var objectsRendered = $(obj).children('[rel!=loaded]');
								if (opts.afterLoad != null){
									opts.afterLoad(objectsRendered);	
								}
							
							} else {
								console.log("Request with ID: " + $.currentCallId + " not being used")
							}
						
						
					  			  
					  },
					  dataType: 'html',
					  
					  complete: function(){
						console.log('ajax completed');
               		  }

					  
					  
					  
					  
				 });
				 
				 				 
				 	
		 }
	 
	 
	 
  };
  
  $.fn.scrollPagination.init = function(obj, opts){
	 var target = opts.scrollTarget;
	 $(obj).attr('scrollPagination', 'enabled');
	 
	 var count = 0;
	 var timeout = null;


	 
	 $(target).scroll(function(event){	
		
		count = count +1;
		
		clearTimeout(timeout);
		timeout = setTimeout(function() {			
			
			if ($(obj).attr('scrollPagination') == 'enabled'){
				
				console.log(count);		 		
		 		$.fn.scrollPagination.loadContent(obj, opts);
		 		
		 		
			}
			else {
				event.stopPropagation();	
			}
			
			
		
		}, 300);
		
		
	 });
	 
	 $.fn.scrollPagination.loadContent(obj, opts);
	 
 };
	
 $.fn.scrollPagination.defaults = {
      	 'contentPage' : null,
     	 'contentData' : {},
		 'beforeLoad': null,
		 'afterLoad': null	,
		 'scrollTarget': null,
		 'heightOffset': 0		  
 };	
})( jQuery );