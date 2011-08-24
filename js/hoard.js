$(document).ready( function() {
	$("#filter").change(function() {
		filterPiles(this);
	});
	$("#filter").keyup(function () {
		filterPiles(this);
	});
});

function filterPiles(select) {
	var pile = $(select).val();
	if(pile == "") { 
		$(".hoard").removeClass("hidden");
	} else {
		$(".hoard").addClass("hidden");
		$(".pile"+pile).removeClass("hidden");
	}
}