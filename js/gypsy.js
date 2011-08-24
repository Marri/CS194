$(document).ready( function() {
	bindOwner();
	for(var i = 1; i < 9; i++) {
		bindSlider(i);
	}
});

function bindSlider(i) {
	$( "#c" + i + "slider" ).slider({
		range: true,
		min: 0,
		max: 100,
		values: [0, 100],
		slide: function( event, ui ) {
			$("#c"+i+"min").html(ui.values[0]);
			$("#c"+i+"max").html(ui.values[1]);
			$("#minc"+i).val(ui.values[0]);
			$("#maxc"+i).val(ui.values[1]);
		}
	});
}

function bindOwner() {
	$("#owner").keyup( function() { 
		var entry = $("#owner").val();
		var data = { data: entry };
		if (entry.length > 0 && !isNaN(entry)) {
			showAutoComplete('userid', data, 'owner');
		} else if(entry.length > 2) {
			showAutoComplete('username', data, 'owner');
		} else {
			$("#autoComplete-owner").addClass("hidden");
		}
	});
	$("#squffy").keyup( function() { 
		var entry = $("#squffy").val();
		var data = { data: entry };
		if (entry.length > 0 && !isNaN(entry)) {
			showAutoComplete('squffyid', data, 'squffy');
		} else if(entry.length > 2) {
			showAutoComplete('squffyname', data, 'squffy');
		} else {
			$("#autoComplete-squffy").addClass("hidden");
		}
	});
}

function showAutoComplete(url, data, complete) {
	$("#autoComplete-"+complete).removeClass("hidden");
	$.getJSON(
		"ajax/"+url+".php", 
		data, 
		function(data) { 
			if(data.length < 1) {
				$("#autoComplete-"+complete).html("<i>No matches found.</i>");
			} else {
				$("#autoComplete-"+complete).html("");
				for(id in data) {
					var user = data[id];
					if(id < 10) {
						$("#autoComplete-"+complete).append("<a href=\"#\" class=\"autoResult\" userid=\"" + user.id + "\">" + user.name + "</a> (#" + user.id + ")<br />");
					} else {
						$("#autoComplete-"+complete).append("<span class='small italic'>...more results not shown.</span>");
						break;
					}
				}
				$(".autoResult").click( function() {
					var username = $(this).text();
					$("#"+complete).val(username);
					$("#autoComplete-"+complete).addClass("hidden");
					return false;
				});
			}
		}
	);
}