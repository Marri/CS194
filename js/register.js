$(document).ready( function() {
	bindRefer();
	bindChange();
});

function bindRefer() {
	$("#referred").keyup( function() { 
		var entry = $("#referred").val();
		var data = { data: entry };
		if (entry.length > 0 && !isNaN(entry)) {
			showAutoComplete('userid', data);
		} else if(entry.length > 2) {
			showAutoComplete('username', data);
		} else {
			$("#autoComplete").addClass("hidden");
		}
	});
}

function bindChange() {
	$("#changeRefer").click( function() {
		$("#referCell").html("<input class=\"width200\" id=\"referred\" autocomplete=\"off\" name=\"referer\" type=\"text\"><div id=\"autoComplete\" class=\"autocomplete width300 hidden\">autocomplete</div>");
		bindRefer();
	});
}

function showAutoComplete(url, data) {
	$("#autoComplete").removeClass("hidden");
	$.getJSON(
		"ajax/"+url+".php", 
		data, 
		function(data) { 
			if(data.length < 1) {
				$("#autoComplete").html("<i>No users found.</i>");
			} else {
				$("#autoComplete").html("");
				for(id in data) {
					var user = data[id];
					if(id < 10) {
						$("#autoComplete").append("<a href=\"#\" class=\"autoResult\" userid=\"" + user.id + "\">" + user.name + "</a> (#" + user.id + ")<br />");
					} else {
						$("#autoComplete").append("<span class='small italic'>...more results not shown.</span>");
						break;
					}
				}
				$(".autoResult").click( function() {
					var userID = $(this).attr("userid");
					var username = $(this).text();
					$("#referCell").html("<b>"+username+"</b> (#" + userID + ") " +
					"<input type='hidden' name='referer' value='" + userID + "' />" + 
					" <a class='small' href='#' id='changeRefer'>Change</a>");
					bindChange();
					return false;
				});
			}
		}
	);
}