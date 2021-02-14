function getNotify() {
	$.ajax({
		dataType:"json",
		type:"POST",
		url:api_url,
		global:false,
		data: {
			action:'getnotify',
		}
	}).done(function(data) {
		$.each( data.data, function(i, notify) {
			openNotify(notify.id, notify.message, notify.style, notify.time);
		});
	});
}

function checkNotify(status) {
	if (status=='vis_visible') {
		getNotify();
	}
}

function openNotify(id, message, style, time) {
	var container=$('#vis_notify');
	
	if (!style) {
		style='info';  
	}
	
	if (!time) {
		time=0;  
	}
	  
	var output=$('<div id="vis_notify_'+id+'" class="vis_notify vis_notify_'+style+'">'+message+'</div>');
	container.append(output);

	output.on('click', function () {
		closeNotify(id);
	});

	if (time!='0') {
		setTimeout(function () {
			closeNotify(id);
		}, time);
	}
}

function closeNotify(id) {
	$('#vis_notify_'+id).remove();
}

$(function() {
	var hidden = "hidden";

	// Standards:
	if (hidden in document) {
		document.addEventListener("visibilitychange", onchange);
	} else if ((hidden = "mozHidden") in document) {
		document.addEventListener("mozvisibilitychange", onchange);
	} else if ((hidden = "webkitHidden") in document) {
		document.addEventListener("webkitvisibilitychange", onchange);
	} else if ((hidden = "msHidden") in document) {
		document.addEventListener("msvisibilitychange", onchange);
	} else if ("onfocusin" in document) {
		// IE 9 and lower:
		document.onfocusin = document.onfocusout = onchange;
	} else {
		// All others:
		window.onpageshow = window.onpagehide = window.onfocus = window.onblur = onchange;
	}
	
	function onchange (evt) {
		var v = "vis_visible", h = "vis_hidden",
		evtMap = {
			focus:v, focusin:v, pageshow:v, blur:h, focusout:h, pagehide:h
		};

		evt = evt || window.event;
		if (evt.type in evtMap) {
			document.body.className = evtMap[evt.type];
		} else {
			document.body.className = this[hidden] ? "vis_hidden" : "vis_visible";
		}
	}

	// set the initial state (but only if browser supports the Page Visibility API)
	if( document[hidden] !== undefined ) {
		onchange({type: document[hidden] ? "blur" : "focus"});
	}
	
	setInterval(function(){checkNotify(document.body.className);}, 10000);
});