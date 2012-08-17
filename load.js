//SNIPLET BOOKMARKLET LOADER

var CI_ROOT = 'http://dev.snippetboxx.com/' //Change for production
var jui = CI_ROOT + 'js/jquery.ui.min.js';
var jquery = 'http://code.jquery.com/jquery-latest.min.js';
var sniplet = CI_ROOT + 'sniplet.js';
var jqueryuicss = CI_ROOT + 'css/smoothness/jquery-ui.css';

/* -------------------------------------------------------------------------------------------------- */
function loadjscssfile(filename, filetype){
	if (filetype=="js"){ //if filename is a external JavaScript file
		var fileref=document.createElement('script');
		fileref.setAttribute("type","text/javascript");
		fileref.setAttribute("src", filename);
	}
	else if (filetype=="css"){ //if filename is an external CSS file
		var fileref=document.createElement("link");
		fileref.setAttribute("rel", "stylesheet");
		fileref.setAttribute("type", "text/css");
		fileref.setAttribute("href", filename);
	}
	if (typeof fileref!="undefined")
		document.getElementsByTagName("head")[0].appendChild(fileref);
}

//Old Way
//var script = document.createElement('SCRIPT'); //someChildObject
//script.type = 'text/javascript';
//script.src = jquery;
//document.getElementsByTagName('head')[0].appendChild(script);


loadjscssfile(jqueryuicss, 'css');
loadjscssfile(jquery, 'js');
loadjscssfile(jui, 'js');
loadjscssfile(sniplet, 'js');



