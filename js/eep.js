Event.observe(window, 'load', init, false);

function init(){
	makeEditable('desc1');
	makeEditable('desc2');
	makeEditable('desc3');
	makeEditable('desc4');
	makeEditable('desc5');
	makeEditable('desc6');
	makeEditable('desc7');
	makeEditable('desc8');
	makeEditable('desc9');
	makeEditable('desc10');
	makeEditable('desc11');
	makeEditable('desc12');
	makeEditable('desc13');
	makeEditable('desc14');
	makeEditable('desc15');
	makeEditable('desc16');
	makeEditable('desc17');
	makeEditable('desc18');
	makeEditable('desc19');
	makeEditable('desc20');
	makeEditable('desc21');
	makeEditable('desc22');
	makeEditable('desc23');
	makeEditable('desc24');
	makeEditable('desc25');
	makeEditable('desc26');
	makeEditable('desc27');
	makeEditable('desc28');
	makeEditable('desc29');
	makeEditable('desc30');
	makeEditable('desc31');
	makeEditable('desc32');
	makeEditable('desc33');
	makeEditable('desc34');
	makeEditable('desc35');
	makeEditable('desc36');
	makeEditable('desc37');
	makeEditable('desc38');
	makeEditable('desc39');
	makeEditable('desc40');
	makeEditable('desc41');
	makeEditable('desc42');
	makeEditable('desc43');
	makeEditable('desc44');
	makeEditable('desc45');
	makeEditable('desc46');
	makeEditable('desc47');
	makeEditable('desc48');
	makeEditable('desc49');
	makeEditable('desc50');
}

function makeEditable(id){
	Event.observe(id, 'click', function(){edit($(id))}, false);
	Event.observe(id, 'mouseover', function(){showAsEditable($(id))}, false);
	Event.observe(id, 'mouseout', function(){showAsEditable($(id), true)}, false);
}

function edit(obj){
	Element.hide(obj);

	var textarea = '<div id="'+obj.id+'_editor">
	<textarea id="'+obj.id+'_edit" name="'+obj.id+'" rows="4" cols="60">
	'+obj.innerHTML+'
	</textarea>';
	var button	 = '<div><input id="'+obj.id+'_save" type="button" value="SAVE" /> OR <input id="'+obj.id+'_cancel" type="button" value="CANCEL" /></div></div>';

	new Insertion.After(obj, textarea+button);

	Event.observe(obj.id+'_save', 'click', function(){saveChanges(obj)}, false);
	Event.observe(obj.id+'_cancel', 'click', function(){cleanUp(obj)}, false);

}

function showAsEditable(obj, clear){
	if (!clear){
		Element.addClassName(obj, 'editable');
	}else{
		Element.removeClassName(obj, 'editable');
	}
}

function saveChanges(obj){

	var new_content	=  escape($F(obj.id+'_edit'));

	obj.innerHTML	= "Saving...";
	cleanUp(obj, true);

	var success	= function(t){editComplete(t, obj);}
	var failure	= function(t){editFailed(t, obj);}

  	var url = 'edit.php';
	var pars = 'id='+obj.id+'&content='+new_content;
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});

}

function cleanUp(obj, keepEditable){
	Element.remove(obj.id+'_editor');
	Element.show(obj);
	if (!keepEditable) showAsEditable(obj, true);
}

function editComplete(t, obj){
	obj.innerHTML	= t.responseText;
	showAsEditable(obj, true);
}

function editFailed(t, obj){
	obj.innerHTML	= 'Sorry, the update failed.';
	cleanUp(obj);
}


