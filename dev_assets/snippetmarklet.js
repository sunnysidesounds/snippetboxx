var jout = jQuery.noConflict();


//Original ProtoType Bookmarklet Version Non Jquery
//TEst
	
jout(document).ready(function() {
	//jout('#preload-img').hide();
	jout("#snippetOuterContainer").click(function(){ return false; });
	jout(document).one("click", function() { jout("#snippetOuterContainer").fadeOut(); });
	
//snippetOuterContainer


}); //jQuery	

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

//Get highlighted text
selectedText = getSelText();




var div = document.createElement("div");
div.id = "snippetOuterContainer";
div.style.border = "4px solid #CCCCCC";
div.style.backgroundColor = '#F6F6F6';
div.style.position = 'fixed';
div.style.right = '0';
div.style.width = '465px';
div.style.margin = '10px';
div.style.zIndex = '10000000';

//div.style.display = 'none';

var encodeHighlightString = escape(selectedText);
var pageTitle = document.title;
var pageUrl = document.URL;


//alert(document_keywords());

var str = "";
str += '<div id="snippetInnerContainer" style="-moz-box-shadow: 0 0 25px 3px #999; -webkit-box-shadow: 0 0 25px 3px #999;">';
str += "<a href='javascript:void(0);' onClick='toggleItem(\"snippetOuterContainer\");' id='snippetClose' style='position: relative;font-size: 10px; text-decoration: none; border: none; float:right; padding:5px 10px; color: #666666;'>Close</a>";
str += "<iframe frameborder='0' scrolling='no' name='snipletIframe' id='snipletBookmarkletIframe' src='http://dev.snippetboxx.com/snippetform.php?snippet=" + encodeHighlightString +"&title="+ pageTitle +"&url="+ pageUrl +" ' width='460px' height='400px'>Loading...</iframe>";
str += '</div>';







//Insert this div at the top
div.innerHTML = str;
document.body.insertBefore(div, document.body.firstChild);


//alert(selectedText);



//  str += "<iframe frameborder='0' scrolling='no' name='instacalc_bookmarklet_iframe' id='instacalc_bookmarklet_iframe' src='" + iframe_url + "' width='550px' height='75px' style='textalign:right; backgroundColor: white;'></iframe>";
