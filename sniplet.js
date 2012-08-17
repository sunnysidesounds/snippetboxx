var j2 = jQuery.noConflict();
var CI_ROOT = 'http://dev.snippetboxx.com/' //Change for production
var jui = CI_ROOT + 'js/jquery.ui.min.js';
var jquery = 'http://code.jquery.com/jquery-latest.min.js';
var sniplet = CI_ROOT + 'sniplet.js';
var load = CI_ROOT + 'load.js?';
var jqueryuicss = CI_ROOT + 'css/smoothness/jquery-ui.css';





/* -------------------------------------------------------------------------------------------------- */

function document_keywords(){
    var keywords = '';
    var metas = document.getElementsByTagName('meta');
    if (metas) {
        for (var x=0,y=metas.length; x<y; x++) {
            if (metas[x].name.toLowerCase() == "keywords") {
                keywords += metas[x].content;
            }
        }
    }
    return keywords != '' ? keywords : false;
}

/* -------------------------------------------------------------------------------------------------- */
function getSelText(){
    var txt = '';
     if (window.getSelection){
        txt = window.getSelection();
     } else if (document.getSelection){
        txt = document.getSelection();
     } else if (document.selection){
        txt = document.selection.createRange().text;
     }
    else return;

	return txt;
}

/* -------------------------------------------------------------------------------------------------- */
function toggleItem(id){
  var item = document.getElementById(id);
  if(item){
    if ( item.style.display == "none"){
      item.style.display = "";
    }
    else{
      item.style.display = "none";
    } 
  }
}

/* -------------------------------------------------------------------------------------------------- */
function removejscssfile(filename, filetype){
	 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none";
	 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none";
	 var allsuspects=document.getElementsByTagName(targetelement);
	 for (var i=allsuspects.length; i>=0; i--){ 
		  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1){
		   	allsuspects[i].parentNode.removeChild(allsuspects[i]);
		   }
	 }
}

	
j2(document).ready(function() {


	selectedText = getSelText();
	var encodeHighlightString = escape(selectedText);
	var pageTitle = document.title;
	var pageUrl = document.URL;
	


	
	j2('#snippetOuterContainer').remove();
	div = j2(document.createElement('div')).attr('id', 'snippetOuterContainer');
	

	j2('body').prepend(div);
	j2('#snippetOuterContainer').css({'border' : '4px solid #CCCCCC', 'background-color' : '#F6F6F6', 'position' : 'fixed', 'right' : '0', 'width' : '465px', 'margin' : '10px', 'z-index' : '10000000'});
	j2('#snippetOuterContainer').html('<div id="snippetInnerContainer" style="-moz-box-shadow: 0 0 25px 3px #999; -webkit-box-shadow: 0 0 25px 3px #999; height: 440px;"></div>');

	var iframe = "";	
	iframe += '<div id="snipletIframe" class="snipletTransision" style="position: absolute; display:none;">'
		iframe += '<div id="sniplet_drag" style="width: 20px; height: 20px; cursor: move;"><img width="20" height="20" src="'+CI_ROOT+'img/drag-icon.png" border="0" alt="Drag Sniplet" title="Drag Sniplet" /></div>';
		iframe += "<a href='javascript:void(0);' onClick='toggleItem(\"snippetOuterContainer\");' id='snippetClose' style='position: relative;font-size: 10px; text-decoration: none; border: none; float:right; padding:5px 10px; color: #666666; margin-top: -18px;'>Close</a>";
		iframe += "<iframe frameborder='0' scrolling='no' name='snipletIframe' id='snipletBookmarkletIframe' src='"+CI_ROOT+"snippetform.php?snippet=" + encodeHighlightString +"&title="+ pageTitle +"&url="+ pageUrl +" ' width='460px' height='400px'>Loading...</iframe>";	
	iframe += '</div>';	
	
	var loader = ''
	loader += '<div id="snipletLoader" class="snipletTransision" style="position: absolute;">'
		loader += '<div style="text-align:center; color: #445A7E; font-weight: bold; width: 440px; margin-left: 18px; padding-top: 150px; font-size: 11px;">'
			loaderMessage = unescape('Loading Sniplet, Please wait&#46;&#46;&#46;');
			loader +=  loaderMessage + '<br /><br /><img src="' + CI_ROOT + 'img/loader3.gif" border="0" alt="loading..."/>'
		loader += '</div>'
	loader += '</div>'
	
	data = loader + iframe
	
	j2('#snippetInnerContainer').html(data);
	
	setTimeout(function() {
		j2('#snipletIframe').fadeIn('fast')
		j2('#snipletLoader').fadeOut('fast')
								
	}, 2000); 	
		
	j2("#sniplet_drag").hover(function(){
		j2( "#snippetOuterContainer" ).draggable({ handle: "img#sniplet_drag" });
		j2( "#snippetInnerContainer" ).draggable({ cancel: "#snippetInnerContainer" });
	});
	
			
	
	j2("#snippetOuterContainer").click(function(){ return false; });
	j2(document).one("click", function() { 
		j2("#snippetOuterContainer").fadeOut(); 
		//Remove bookmarklet scripts from original body after we're done
		removejscssfile(sniplet, 'js');
		removejscssfile(jui, 'js');
		removejscssfile(jquery, 'js');
		removejscssfile(jqueryuicss, 'css');
		removejscssfile(load, 'js');
	});





}); //jQuery	
