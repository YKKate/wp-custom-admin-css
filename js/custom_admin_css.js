
var editor = ace.edit("customCss");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/css");




jQuery(document).ready(function($){
	
	var code = editor.getValue();
	
	var updateCss = function(){
		$('#admin_css').val( editor.getSession().getValue() );
	}
	$('#save-custom-admin-css-form').submit(updateCss);

});