$(document).ready( function() {
	displayTabMenu($("#menu a.active"));
	
	$("#nut-toggle").click(function() {
		$("#nut-holder").toggleClass('hidden');
		var src = $(this).attr("src");
		if(src == "./images/icons/add.png") { $(this).attr("src", "./images/icons/delete.png"); }
		else { $(this).attr("src", "./images/icons/add.png"); }
		return false;
	});
							
	//Create menu style
	$("#menu a").click( function () {
		if($(this).hasClass("active")) {
			$(this).removeClass("active");
			$('#submenu').css("visibility","hidden");
			$(".submenu-span").hide();
		} else {
			if($(this).text() == "Home") { return true; }
			displayTabMenu($(this));
		}
		return false;
	});
	
	//Kill the Firefox default drag
	for(var i in document.images) {
		img = document.images[i];
		img.onmousedown = function(event) {
			if(event) { event.preventDefault(); }
		}
	}
});

function displayTabMenu(elem) {
	var tab = elem.text().toLowerCase().replace(/ /g,"");
	if(tab == "home") {
		$('#submenu').css("visibility","hidden");
		$(".submenu-span").hide();
	} else {
		$('#submenu').css("visibility","visible");
		$(".submenu-span").hide();
		$("#" + tab).show();
	}	
	$("#menu a.active").removeClass("active");
	elem.addClass("active");
}